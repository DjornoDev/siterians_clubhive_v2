<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/school_logo.png') }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#2563eb">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="ClubHive">
    <meta name="description" content="Official club management system for Siterians">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ asset('images/icons/apple-icon.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('images/icons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('images/icons/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('images/icons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/icons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('images/icons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('images/icons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('images/icons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('images/icons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icons/apple-icon-180x180.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('PWA: Service Worker registered successfully');
                        
                        // Check for updates
                        registration.addEventListener('updatefound', () => {
                            const newWorker = registration.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    // New content available, show update notification
                                    if (confirm('New version available! Reload to update?')) {
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    })
                    .catch((error) => {
                        console.log('PWA: Service Worker registration failed');
                    });
            });
        }

        // PWA Install Prompt
        let deferredPrompt;
        let installButton = null;

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent Chrome 67 and earlier from automatically showing the prompt
            e.preventDefault();
            deferredPrompt = e;
            
            // Show custom install button
            showInstallButton();
        });

        function showInstallButton() {
            // Create install button if it doesn't exist
            if (!installButton) {
                installButton = document.createElement('button');
                installButton.innerHTML = '📱 Install App';
                installButton.style.cssText = `
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #2563eb;
                    color: white;
                    border: none;
                    padding: 12px 16px;
                    border-radius: 25px;
                    font-size: 14px;
                    font-weight: 500;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
                    z-index: 1000;
                    transition: all 0.3s ease;
                `;
                
                installButton.addEventListener('mouseenter', () => {
                    installButton.style.transform = 'translateY(-2px)';
                    installButton.style.boxShadow = '0 6px 16px rgba(37, 99, 235, 0.4)';
                });
                
                installButton.addEventListener('mouseleave', () => {
                    installButton.style.transform = 'translateY(0)';
                    installButton.style.boxShadow = '0 4px 12px rgba(37, 99, 235, 0.3)';
                });
                
                installButton.addEventListener('click', installPWA);
                document.body.appendChild(installButton);
            }
        }

        function installPWA() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('PWA: User accepted the install prompt');
                        hideInstallButton();
                    }
                    deferredPrompt = null;
                });
            }
        }

        function hideInstallButton() {
            if (installButton) {
                installButton.style.display = 'none';
            }
        }

        // Hide install button if already installed
        window.addEventListener('appinstalled', () => {
            console.log('PWA: App was installed');
            hideInstallButton();
        });

        // Check if running as PWA
        if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
            console.log('PWA: Running as installed app');
            hideInstallButton();
        }
    </script>
</body>

</html>
