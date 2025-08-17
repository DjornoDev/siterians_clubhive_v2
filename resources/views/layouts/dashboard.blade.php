<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClubHive Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/school_logo.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Base styles */
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Sidebar responsive styles */
        .sidebar-icon-only .nav-text {
            display: none;
        }

        .sidebar-icon-only {
            width: 5rem !important;
        }

        .sidebar-icon-only .sidebar-logo-text {
            display: none;
        }

        /* Enhanced sidebar dropdown styles */
        .sidebar-icon-only .dropdown-content {
            position: absolute;
            left: 5rem;
            top: 0;
            min-width: 220px;
            @apply bg-blue-800 rounded-r-lg shadow-lg z-40;
            display: flex !important;
            /* Force display */
            flex-direction: column;
            overflow: visible;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-left: 3px solid #2563eb;
        }

        /* Fix for dropdown positioning */
        .sidebar-icon-only .relative:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Hover state styling */
        .sidebar-icon-only .relative.hovered button {
            @apply bg-blue-600;
        }

        /* Make sure all links in the dropdown are visible */
        .sidebar-icon-only .dropdown-content a {
            white-space: normal;
            word-break: break-word;
            width: 100%;
            display: block;
        }

        .sidebar-icon-only .dropdown-title {
            @apply bg-blue-700 px-4 py-2 font-medium text-white rounded-tr-lg;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Improved hover states for sidebar icons */
        .sidebar-icon-only a:hover i,
        .sidebar-icon-only button:hover i {
            @apply transform scale-110 text-white;
            transition: all 0.2s ease;
        }

        /* Enhanced icon styles when sidebar is collapsed */
        .sidebar-icon-only i {
            @apply mx-auto text-xl;
            transition: all 0.2s ease;
        }

        .transition-width {
            transition: width 0.3s ease-in-out;
        }

        /* Animation styles */
        .dropdown-animation {
            transition: all 0.3s ease-out;
            transform-origin: top right;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        /* Responsive utilities */
        @media (max-width: 640px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            #sidebar:not(.sidebar-icon-only) {
                width: 14rem !important;
            }
        }

        .fixed {
            position: fixed;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-['Poppins'] bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar - Hidden on mobile by default, translucent overlay when open -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden md:hidden"> </div>
        <div id="sidebar"
            class="bg-gradient-to-b from-blue-900 to-blue-700 text-white w-64 md:w-64 lg:w-64 transition-all duration-300 space-y-6 py-7 px-2 absolute inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 z-30 shadow-xl h-full overflow-y-auto custom-scrollbar transition-width">
            <!-- Logo -->
            <div class="flex items-center space-x-3 pr-4 pl-1 mb-8 justify-center">
                <div class="bg-white p-2 rounded-lg shadow flex-shrink-0">
                    <img src="{{ asset('images/school_logo.png') }}" alt="Logo" class="h-10 w-10 object-cover">
                </div>
                <div class="sidebar-logo-text">
                    <h2 class="text-xl font-bold truncate">Siterians</h2>
                    <p class="text-sm font-medium text-blue-200 truncate">ClubHive</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav>
                @if (auth()->user()->role === 'ADMIN')
                    <a href="{{ route('admin.dashboard') }}"
                        class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                        <i class="fas fa-tachometer-alt w-6 text-center text-lg"></i>
                        <span class="nav-text ml-3 truncate">Dashboard</span>
                    </a>

                    <a href="{{ route('admin.clubs.index') }}"
                        class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('admin.clubs.*') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                        <i class="fas fa-users w-6 text-center text-lg"></i>
                        <span class="nav-text ml-3 truncate">Manage Clubs</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                        <i class="fas fa-user-cog w-6 text-center text-lg"></i>
                        <span class="nav-text ml-3 truncate">Manage Users</span>
                    </a>
                @else
                    {{-- Shared Navigation for TEACHER and STUDENT --}}
                    <a href="{{ route('home.index') }}"
                        class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('home.index') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                        <i class="fas fa-home w-6 text-center text-lg"></i>
                        <span class="nav-text ml-3 truncate">Home</span>
                    </a>

                    <a href="{{ route('events.index') }}"
                        class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('events.index') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                        <i class="fas fa-calendar-alt w-6 text-center text-lg"></i>
                        <span class="nav-text ml-3 truncate">Events</span>
                    </a>
                    @if (auth()->user()->role === 'TEACHER') {{-- Clubs Dropdown for TEACHER --}} <div
                            x-data="{
                                isClubDropdownOpen: false,
                                sidebarTimer: null,
                                wasCollapsed: false,
                                isSidebarCollapsed() { return document.getElementById('sidebar').classList.contains('sidebar-icon-only'); },
                                expandSidebar() {
                                    if (this.isSidebarCollapsed() && window.innerWidth >= 768) {
                                        this.wasCollapsed = true;
                                        document.getElementById('sidebar').classList.remove('sidebar-icon-only');
                                    }
                                },
                                collapseSidebar() {
                                    if (this.wasCollapsed && window.innerWidth >= 768) {
                                        clearTimeout(this.sidebarTimer);
                                        this.sidebarTimer = setTimeout(() => {
                                            document.getElementById('sidebar').classList.add('sidebar-icon-only');
                                            localStorage.setItem('sidebarCollapsed', 'true');
                                            this.wasCollapsed = false;
                                        }, 500);
                                    }
                                }
                            }" @mouseleave="isClubDropdownOpen = false; collapseSidebar();"
                            class="mb-1 relative">
                            <button @click="isClubDropdownOpen = !isClubDropdownOpen"
                                @mouseenter="isSidebarCollapsed() && (isClubDropdownOpen = true); expandSidebar();"
                                class="w-full flex items-center justify-between py-3 px-4 text-white hover:bg-blue-600 rounded-lg transition duration-200">
                                <div class="flex items-center">
                                    <i class="fas fa-users w-6 text-center text-lg"></i>
                                    <span class="nav-text ml-3">Advised Clubs</span>
                                </div>
                                <svg class="w-4 h-4 transform transition-transform duration-300"
                                    :class="{ 'rotate-180': isClubDropdownOpen }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="isClubDropdownOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                :class="{
                                    'pl-8 bg-blue-800 rounded-b-lg overflow-hidden': !isSidebarCollapsed(),
                                    'dropdown-content': isSidebarCollapsed()
                                }"
                                class="z-50">
                                <div class="dropdown-title" x-show="isSidebarCollapsed()">Advised Clubs</div>
                                <div class="p-1">
                                    @foreach (auth()->user()->advisedClubs as $club)
                                        <a href="{{ route('clubs.show', $club) }}" @click="collapseSidebar()"
                                            class="block py-2 px-3 text-white hover:bg-blue-600 transition duration-200 text-sm rounded-lg my-1">
                                            {{ $club->club_name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Check if user is adviser of Club ID 1 (SSLG) --}}
                        @php
                            $isClubOneAdviser = auth()->user()->advisedClubs->contains('club_id', 1);
                        @endphp

                        @if ($isClubOneAdviser)
                            {{-- Voting link for Club ID 1 Adviser --}}
                            <a href="{{ route('voting.index') }}"
                                class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('voting.*') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                                <i class="fas fa-poll w-6 text-center text-lg"></i>
                                <span class="nav-text ml-3 truncate">Voting</span>
                            </a>

                            {{-- Pending Events link for SSLG Adviser --}}
                            <a href="{{ route('events.pending') }}"
                                class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('events.pending') || request()->routeIs('events.approval.*') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                                <i class="fas fa-calendar-check w-6 text-center text-lg"></i>
                                <span class="nav-text ml-3 truncate">Event Approvals</span>
                            </a>
                        @endif
                    @else
                        @if (auth()->user()->role === 'STUDENT')
                            {{-- Direct link to Clubs for STUDENT --}}
                            <a href="{{ route('clubs.index') }}"
                                class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('clubs.index') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                                <i class="fas fa-users w-6 text-center text-lg"></i>
                                <span class="nav-text ml-3 truncate">Clubs</span>
                            </a> {{-- Joined Clubs Dropdown for STUDENT --}} <div x-data="{
                                isJoinedClubsOpen: false,
                                sidebarTimer: null,
                                wasCollapsed: false,
                                isSidebarCollapsed() { return document.getElementById('sidebar').classList.contains('sidebar-icon-only'); },
                                expandSidebar() {
                                    if (this.isSidebarCollapsed() && window.innerWidth >= 768) {
                                        this.wasCollapsed = true;
                                        document.getElementById('sidebar').classList.remove('sidebar-icon-only');
                                    }
                                },
                                collapseSidebar() {
                                    if (this.wasCollapsed && window.innerWidth >= 768) {
                                        clearTimeout(this.sidebarTimer);
                                        this.sidebarTimer = setTimeout(() => {
                                            document.getElementById('sidebar').classList.add('sidebar-icon-only');
                                            localStorage.setItem('sidebarCollapsed', 'true');
                                            this.wasCollapsed = false;
                                        }, 500);
                                    }
                                }
                            }"
                                @mouseleave="isJoinedClubsOpen = false; collapseSidebar();" class="mb-1 relative">
                                <button @click="isJoinedClubsOpen = !isJoinedClubsOpen"
                                    @mouseenter="isSidebarCollapsed() && (isJoinedClubsOpen = true); expandSidebar();"
                                    class="w-full flex items-center justify-between py-3 px-4 text-white hover:bg-blue-600 rounded-lg transition duration-200">
                                    <div class="flex items-center">
                                        <i class="fas fa-clipboard-list w-6 text-center text-lg"></i>
                                        <span class="nav-text ml-3">My Clubs</span>
                                    </div>
                                    <svg class="w-4 h-4 transform transition-transform duration-300"
                                        :class="{ 'rotate-180': isJoinedClubsOpen }" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="isJoinedClubsOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                    :class="{
                                        'pl-8 bg-blue-800 rounded-b-lg overflow-hidden': !isSidebarCollapsed(),
                                        'dropdown-content': isSidebarCollapsed()
                                    }"
                                    class="z-50">
                                    <div class="dropdown-title" x-show="isSidebarCollapsed()">My Clubs</div>
                                    <div class="p-1">
                                        @foreach (auth()->user()->joinedClubs as $club)
                                            <a href="{{ route('clubs.show', $club) }}" @click="collapseSidebar()"
                                                class="block py-2 px-3 text-white hover:bg-blue-600 transition duration-200 text-sm rounded-lg my-1">
                                                {{ $club->club_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            {{-- Voting link - visible to all STUDENTS --}}
                            <a href="{{ route('voting.index') }}"
                                class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('voting.*') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                                <i class="fas fa-poll w-6 text-center text-lg"></i>
                                <span class="nav-text ml-3 truncate">Voting</span>
                            </a>
                        @else
                            {{-- Direct link to Clubs for STUDENT --}}
                            <a href="{{ route('clubs.index') }}"
                                class="block py-3 px-4 rounded-lg transition duration-200 text-white mb-1 flex items-center {{ request()->routeIs('clubs.index') ? 'bg-blue-600 hover:bg-blue-700' : 'hover:bg-blue-600' }}">
                                <i class="fas fa-users w-6 text-center text-lg"></i>
                                <span class="nav-text ml-3 truncate">Clubs</span>
                            </a>
                        @endif
                    @endif
                @endif
            </nav>

            <!-- Sidebar Footer -->
            <div class="px-4 mt-12 sidebar-logo-text">
                <div class="bg-blue-500 rounded-lg p-4">
                    <p class="text-sm font-medium text-white mb-2">Need help?</p>
                    <p class="text-xs text-white">Contact the admin: <br>siteroadmin@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="bg-white border-b border-gray-200 shadow-md">
                <div class="flex items-center justify-between px-3 sm:px-6 py-3">
                    <!-- Left side: Toggle button and page title -->
                    <div class="flex items-center">
                        <button id="sidebarToggle" class="text-gray-600 hover:text-blue-600 focus:outline-none p-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-lg sm:text-xl font-semibold text-blue-900 ml-2 sm:ml-4 truncate">
                            {{ explode(' | ', $__env->yieldContent('title', 'ClubHive Dashboard'))[0] }}
                        </h1>
                    </div>

                    <!-- Right side: User profile -->
                    <div class="flex items-center">

                        <div class="relative">
                            <button id="userMenuButton"
                                class="flex items-center space-x-1 sm:space-x-3 focus:outline-none p-1 sm:p-2 rounded-lg hover:bg-gray-100">
                                <div class="hidden sm:flex sm:flex-col sm:items-end">
                                    <span class="text-sm font-medium text-blue-800 truncate">
                                        {{ auth()->user()->name }} {{-- Dynamic user name --}}
                                    </span>
                                    <span class="text-xs text-blue-600 truncate">
                                        {{ Str::title(auth()->user()->role) }} {{-- Formatted role --}}
                                    </span>
                                </div>
                                <div
                                    class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-100 overflow-hidden border-2 border-blue-300 shadow-md flex-shrink-0">
                                    @if (auth()->user()->profile_picture)
                                        <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}"
                                            alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                    @else
                                        <img src="{{ asset('images/default_profile.jpg') }}"
                                            alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <i class="fas fa-chevron-down text-sm text-gray-500 hidden sm:block"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="userDropdown"
                                class="absolute right-0 mt-2 bg-white rounded-md shadow-xl py-2 z-50 hidden dropdown-animation scale-95 opacity-0">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-700">Signed in as</p>
                                    <p class="text-sm text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                    <i class="fas fa-user mr-2 text-blue-500"></i> Your Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700">
                                        <i class="fas fa-sign-out-alt mr-2 text-red-500"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Navigation Bar - Added this section -->
            @yield('after_topbar')

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-3 sm:p-4 md:p-6 custom-scrollbar">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        // Initialize sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Apply saved state on desktop only
            if (window.innerWidth >= 768 && isSidebarCollapsed) {
                sidebar.classList.add('sidebar-icon-only');

                // Add event listeners for better hover effects on sidebar items
                setupSidebarHoverEffects();
            }
        });

        // Function to enhance sidebar hover effects
        function setupSidebarHoverEffects() {
            const menuItems = document.querySelectorAll('#sidebar .relative');

            menuItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    if (document.getElementById('sidebar').classList.contains('sidebar-icon-only')) {
                        this.classList.add('hovered');
                    }
                });

                item.addEventListener('mouseleave', function() {
                    this.classList.remove('hovered');
                });
            });
        } // Toggle sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar visibility
            sidebar.classList.toggle('-translate-x-full');

            // For desktop: toggle between full sidebar and icon-only sidebar
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('sidebar-icon-only');

                // Save state to localStorage
                const isCollapsed = sidebar.classList.contains('sidebar-icon-only');
                localStorage.setItem('sidebarCollapsed', isCollapsed);

                // If sidebar is collapsed, setup hover effects
                if (isCollapsed) {
                    setupSidebarHoverEffects();
                }
            }

            // Toggle overlay on mobile
            if (window.innerWidth < 768) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    sidebarOverlay.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebarOverlay.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }
            }
        });

        // Close sidebar when clicking on overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        });

        // Toggle user dropdown
        document.getElementById('userMenuButton').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('userDropdown');

            if (dropdown.classList.contains('hidden')) {
                // Show dropdown with animation
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('scale-95', 'opacity-0');
                    dropdown.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                // Hide dropdown with animation
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = document.getElementById('userMenuButton');

            if (!dropdown.contains(event.target) && !button.contains(event.target) && !dropdown.classList.contains(
                    'hidden')) {
                // Hide dropdown with animation
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }); // Handle window resize events for responsive design
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

            // Reset sidebar state on screen size change
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

                // Apply saved collapsed state
                if (isSidebarCollapsed) {
                    sidebar.classList.add('sidebar-icon-only');
                } else {
                    sidebar.classList.remove('sidebar-icon-only');
                }
            } else {
                // On mobile, respect the sidebar visibility
                if (!sidebar.classList.contains('-translate-x-full')) {
                    sidebarOverlay.classList.remove('hidden');
                }
                // Always remove icon-only on mobile
                sidebar.classList.remove('sidebar-icon-only');
            }
        });
    </script>

    @stack('modals')
    @stack('scripts')
</body>

</html>
