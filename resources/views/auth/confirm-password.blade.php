<x-guest-layout>
    <div class="flex min-h-screen bg-gray-50">
        <!-- Left Side with Background and Overlay -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Animated Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-white animate-gradient-x opacity-75">
                <!-- Subtle Geometric Shapes -->
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-blue-200 opacity-20 rounded-full mix-blend-multiply animate-blob">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-72 h-72 bg-blue-300 opacity-15 rounded-full mix-blend-multiply animate-blob animation-delay-2000">
                </div>
            </div>

            <!-- Content Overlay -->
            <div class="relative z-10 p-12 flex flex-col justify-center h-full">
                <div class="flex items-center transform transition-all duration-500 hover:scale-105">
                    <div class="bg-white p-4 rounded-xl shadow-lg">
                        <img src="{{ asset('images/school_logo.png') }}" alt="Logo" class="h-24 w-24 object-contain">
                    </div>
                    <div class="ml-6">
                        <h2 class="text-blue-800 font-bold text-4xl tracking-tight">Siterians</h2>
                        <p class="text-blue-600 text-xl font-medium">ClubHive</p>
                    </div>
                </div>

                <div class="mt-12 space-y-4">
                    <h1 class="text-4xl font-bold text-blue-900 leading-tight mb-4 animate-fade-in">
                        Empowering Students <br>Through Community</h1>
                    <p class="text-blue-700 text-lg max-w-md leading-relaxed animate-fade-in animation-delay-500">
                        Connect, collaborate, and grow together at Sitero Francisco Memorial National High School.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side with Confirm Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div
                class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 transform transition-all duration-300 hover:shadow-3xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('images/school_logo.png') }}" alt="Siterians ClubHive"
                        class="h-32 w-32 object-contain">
                </div>

                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-blue-900">Confirm Password</h1>
                    <p class="text-blue-600 mt-2">Secure area verification</p>
                </div>

                <!-- Description -->
                <div class="mb-6 text-blue-700 text-center">
                    <p>This is a secure area of the application. Please confirm your password before continuing.</p>
                </div>

                <!-- Confirm Password Form -->
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-blue-800 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required
                                autocomplete="current-password"
                                class="form-input pl-10 pr-3 py-3 block w-full rounded-lg border-blue-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                placeholder="Enter your password">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 active:scale-95">
                        Confirm Password
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="text-center mt-8">
                    <p class="text-sm text-blue-600">
                        Need to go back?
                        <a href="{{ route('login') }}"
                            class="text-blue-700 hover:text-blue-800 font-medium transition-colors">Return to login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Tailwind Animations -->
    <style>
        @keyframes gradient-x {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-gradient-x {
            background-size: 200% 200%;
            animation: gradient-x 10s ease infinite;
        }

        .animate-blob {
            animation: blob 15s infinite;
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }

        .animation-delay-500 {
            animation-delay: 0.5s;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-guest-layout>
