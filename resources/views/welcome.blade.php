<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitero Francisco Memorial NHS - Siterians Clubhive</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('images/school_logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600" rel="stylesheet" />

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="font-sans bg-[#F5F5F5] text-[#333333]">
    <!-- Header -->
    <header class="bg-[#2B34C9] shadow-sm animate-fadeIn">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/school_logo.png') }}" alt="School Logo"
                        class="h-10 w-auto animate-pulse">
                    <div class="ml-3">
                        <h1 class="text-lg font-semibold text-white">Sitero Francisco Memorial NHS</h1>
                        <p class="text-xs text-gray-200">Siterians Clubhive</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('dashboard') }}"
                                class="text-white bg-[#4A6CF7] px-4 py-2 rounded-md text-sm font-medium transition-all duration-300 hover:bg-opacity-90">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-white hover:text-[#4A6CF7] px-3 py-2 text-sm font-medium">Login</a>
                        @endauth
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left block px-4 py-2 text-sm text-[#4A6CF7]">
                            <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative bg-[#2B34C9]">
        <div class="absolute inset-0">
            <img src="{{ asset('images/bg.png') }}" alt="School Background"
                class="w-full h-full object-cover animate-fade-in-slow">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 lg:py-24">
            <div class="md:flex md:items-center md:space-x-8 lg:space-x-16">
                <div class="md:w-1/2" style="animation: fadeIn 1s ease-out;">
                    <h2
                        class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight animate-slide-in-left">
                        Empowering Student Clubs
                    </h2>
                    <p class="mt-4 text-lg text-gray-200 animate-slide-in-right">
                        Transform your school experience through collaborative learning, skill development, and
                        meaningful connections.
                    </p>
                    <div class="mt-8 flex space-x-4" style="animation: fadeIn 1.5s ease-out;">
                        <a href="{{ route('login') }}"
                            class="inline-block bg-[#F9A825] text-white px-5 py-3 rounded-lg font-medium shadow-md hover:bg-opacity-90 transition duration-150 transform hover:scale-105 hover:shadow-xl">
                            Join a Club
                        </a>
                        <a href="{{ route('login') }}"
                            class="inline-block bg-transparent text-white border border-white px-5 py-3 rounded-lg font-medium shadow-md hover:bg-white hover:text-[#2B34C9] transition duration-150 transform hover:scale-105 hover:shadow-xl">
                            Explore Clubs
                        </a>
                    </div>
                </div>

                <!-- Club Highlights Card -->
                <div class="mt-10 md:mt-0 md:w-1/2" style="animation: fadeIn 2s ease-out;">
                    <div
                        class="bg-white rounded-lg shadow-xl overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                        <div class="p-5 bg-[#2B34C9] text-white">
                            <h3 class="font-semibold text-xl">Club Highlights</h3>
                            <p class="text-gray-200 text-sm mt-1">Academic Year 2025</p>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                <div class="flex items-center transform transition duration-300 hover:translate-x-2">
                                    <div class="w-2 h-2 rounded-full bg-[#4A6CF7] mr-3"></div>
                                    <div class="flex-grow flex justify-between items-center">
                                        <span class="text-gray-700">Total Active Clubs</span>
                                        <span class="font-medium">50+</span>
                                    </div>
                                </div>

                                <div class="flex items-center transform transition duration-300 hover:translate-x-2">
                                    <div class="w-2 h-2 rounded-full bg-[#4A6CF7] mr-3"></div>
                                    <div class="flex-grow flex justify-between items-center">
                                        <span class="text-gray-700">Club Membership</span>
                                        <span class="font-medium">1000+ Students</span>
                                    </div>
                                </div>

                                <div class="flex items-center transform transition duration-300 hover:translate-x-2">
                                    <div class="w-2 h-2 rounded-full bg-[#4A6CF7] mr-3"></div>
                                    <div class="flex-grow flex justify-between items-center">
                                        <span class="text-gray-700">Upcoming Events</span>
                                        <span class="font-medium">15 This Semester</span>
                                    </div>
                                </div>

                                <div class="flex items-center transform transition duration-300 hover:translate-x-2">
                                    <div class="w-2 h-2 rounded-full bg-[#4A6CF7] mr-3"></div>
                                    <div class="flex-grow flex justify-between items-center">
                                        <span class="text-gray-700">Club Fair</span>
                                        <span class="font-medium">August 20, 2025</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="#"
                                    class="block w-full text-center bg-[#F9A825] hover:bg-opacity-90 text-white py-2 rounded font-medium transition-all duration-300">
                                    View All Clubs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Clubs Section -->
    <div class="py-12 bg-[#F5F5F5]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-[#2B34C9] text-center">Featured Clubs</h2>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="h-40 bg-[#4A6CF7] bg-opacity-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#2B34C9] animate-pulse"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-lg text-[#2B34C9]">Science Club</h3>
                        <p class="text-gray-600 text-sm mt-1">Research and Innovation</p>
                        <a href="#"
                            class="mt-3 inline-block text-[#4A6CF7] hover:text-opacity-80 text-sm font-medium transition-colors duration-300">Learn
                            more →</a>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="h-40 bg-[#4A6CF7] bg-opacity-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#2B34C9] animate-pulse"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-lg text-[#2B34C9]">Community Service</h3>
                        <p class="text-gray-600 text-sm mt-1">Social Impact Program</p>
                        <a href="#"
                            class="mt-3 inline-block text-[#4A6CF7] hover:text-opacity-80 text-sm font-medium transition-colors duration-300">Learn
                            more →</a>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="h-40 bg-[#4A6CF7] bg-opacity-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#2B34C9] animate-pulse"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-lg text-[#2B34C9]">Debate Society</h3>
                        <p class="text-gray-600 text-sm mt-1">Critical Thinking & Communication</p>
                        <a href="#"
                            class="mt-3 inline-block text-[#4A6CF7] hover:text-opacity-80 text-sm font-medium transition-colors duration-300">Learn
                            more →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-[#4A6CF7] bg-opacity-10 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="bg-[#2B34C9] rounded-lg shadow-xl overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="px-6 py-8 md:p-10 md:flex md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white md:text-2xl animate-bounce-slow">Ready to Join a Club?
                        </h2>
                        <p class="mt-2 text-gray-200">Discover your passion, develop new skills, and make lasting
                            connections.</p>
                    </div>
                    {{-- <div class="mt-6 md:mt-0 md:ml-10 flex">
                        <a href=""
                            class="inline-block bg-[#F9A825] text-white font-medium px-5 py-3 rounded-lg shadow-md hover:bg-opacity-90 transition duration-150 transform hover:scale-105 hover:shadow-xl">
                            Get Started
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2B34C9] text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('images/school_logo.png') }}" alt="School Logo"
                        class="h-10 w-auto animate-pulse">
                    <div class="ml-3">
                        <p class="font-medium">Sitero Francisco Memorial NHS</p>
                        <p class="text-xs text-gray-200">&copy; 2025 Siterians Clubhive</p>
                    </div>
                </div>

                <div class="mt-6 md:mt-0">
                    <div class="flex space-x-6">
                        <a href="#"
                            class="text-gray-200 hover:text-[#F9A825] transition-colors duration-300 transform hover:scale-125">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#"
                            class="text-gray-200 hover:text-[#F9A825] transition-colors duration-300 transform hover:scale-125">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#"
                            class="text-gray-200 hover:text-[#F9A825] transition-colors duration-300 transform hover:scale-125">
                            <span class="sr-only">Contact</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Custom Tailwind Animations -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideinLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideinRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes bounceSlowly {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</body>

</html>
