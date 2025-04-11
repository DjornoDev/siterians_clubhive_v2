<!-- resources/views/errors/403.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Unauthorized</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6 sm:p-8">
                <div class="text-center">
                    <h1 class="text-red-500 text-7xl font-bold">403</h1>

                    <div class="text-5xl my-6">😾</div>

                    <h2 class="text-2xl font-semibold text-gray-800 mt-4">Unauthorized Access</h2>

                    <p class="mt-2 text-lg italic text-gray-600">
                        {{ $exception->getMessage() ?? 'Bawal ka dito! 😼' }}
                    </p>

                    <p class="mt-4 text-gray-600">
                        You don't have permission to access this resource.
                    </p>

                    <div class="mt-8">
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
