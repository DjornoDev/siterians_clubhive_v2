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

        <!-- Right Side with Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div
                class="w-full max-w-md bg-white rounded-xl shadow-2xl p-8 transform transition-all duration-300 hover:shadow-3xl">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <img src="{{ asset('images/school_logo.png') }}" alt="Siterians ClubHive"
                        class="h-32 w-32 object-contain">
                </div>

                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-blue-900">Welcome Back</h1>
                    <p class="text-blue-600 mt-2">Sign in to access your account</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-blue-800 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required autofocus
                                class="form-input pl-10 pr-3 py-3 block w-full rounded-lg border-blue-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                placeholder="Enter your email" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-medium text-blue-800">Password</label>
                        </div>
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
                                class="form-input pl-10 pr-10 py-3 block w-full rounded-lg border-blue-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                placeholder="••••••••">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror

                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-600 hover:text-blue-800 cursor-pointer transition-colors"
                                onclick="togglePassword()">
                                <svg class="h-5 w-5" id="showIcon" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd"
                                        d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg class="h-5 w-5 hidden" id="hideIcon" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3.28 2.22a.75.75 0 00-1.06 1.06l14.5 14.5a.75.75 0 101.06-1.06l-1.745-1.745a10.029 10.029 0 003.3-4.38 1.651 1.651 0 000-1.185A10.004 10.004 0 009.999 3a9.956 9.956 0 00-4.744 1.194L3.28 2.22zM7.752 6.69l1.092 1.092a2.5 2.5 0 013.374 3.373l1.091 1.092a4 4 0 00-5.557-5.557z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M10.748 13.93l2.523 2.523a9.987 9.987 0 01-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 010-1.186A10.007 10.007 0 012.839 6.02L6.07 9.252a4 4 0 004.678 4.678z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me and Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-blue-300 rounded cursor-pointer">
                            <label for="remember" class="ml-2 block text-sm text-blue-700">Remember me</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-blue-600 hover:text-blue-500 transition-colors">Forgot password?</a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-105 active:scale-95">
                        Sign in
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
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

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const showIcon = document.getElementById('showIcon');
            const hideIcon = document.getElementById('hideIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>
