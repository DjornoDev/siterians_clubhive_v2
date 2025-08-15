@extends('layouts.dashboard')

@section('after_topbar')
    @if ($club instanceof \App\Models\Club)
        <!-- Club Navigation placed right after the topbar -->
        <div class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <div class="relative" x-data="{ mobileMenuOpen: false }">
                    <!-- Desktop Navigation -->
                    <nav class="hidden md:flex justify-between items-center">
                        <!-- Left side navigation links -->
                        <div class="flex space-x-6">
                            <a href="{{ route('clubs.show', $club) }}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ request()->routeIs('clubs.show') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Home
                            </a>
                            <a href="{{ route('clubs.events.index', $club) }}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ request()->routeIs('clubs.events.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Events
                            </a>
                            <a href="{{ route('clubs.people.index', $club) }}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center gap-2
                                {{ request()->routeIs('clubs.people.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                People
                                @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                        {{ $pendingRequestsCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('clubs.about.index', $club) }}"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                {{ request()->routeIs('clubs.about.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                About
                            </a>
                            @if (auth()->user()->user_id == $club->club_adviser)
                                <a href="{{ route('clubs.questions.index', $club) }}"
                                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200
                                    {{ request()->routeIs('clubs.questions.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                    Questions
                                </a>
                            @endif
                        </div>

                        <!-- Right side controls -->
                        <div class="flex items-center space-x-6">
                            @if ($club->club_id == 1 && auth()->user()->user_id == $club->club_adviser)
                                <!-- Club Hunting Day Toggle -->
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600">Club Hunting Day</span>
                                    <form id="toggleHuntingDayForm" class="m-0">
                                        @csrf
                                        <button type="button" onclick="toggleHuntingDay()"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 {{ $club->is_club_hunting_day ? 'bg-blue-600' : 'bg-gray-200' }}">
                                            <span class="sr-only">Toggle Club Hunting Day</span>
                                            <span
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $club->is_club_hunting_day ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if (auth()->user()->user_id == $club->club_adviser)
                                <!-- Settings Gear -->
                                <button onclick="openClubSettingsModal()" class="text-gray-600 hover:text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </nav>

                    <!-- Mobile Navigation -->
                    <div class="flex justify-between items-center py-3 md:hidden">
                        <!-- Club Name/Logo for Mobile -->
                        <div>
                            <h2 class="text-lg font-semibold text-blue-600">{{ $club->club_name }}</h2>
                        </div>

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="text-gray-600 hover:text-blue-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6" x-show="!mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6" x-show="mobileMenuOpen" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Menu -->
                    <div x-show="mobileMenuOpen" class="md:hidden" style="display: none;"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

                        <div class="pt-2 pb-4 border-t border-gray-200">
                            <!-- Navigation Links -->
                            <a href="{{ route('clubs.show', $club) }}"
                                class="block py-2 px-4 text-base font-medium {{ request()->routeIs('clubs.show') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-md">
                                Home
                            </a>
                            <a href="{{ route('clubs.events.index', $club) }}"
                                class="block py-2 px-4 text-base font-medium {{ request()->routeIs('clubs.events.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-md">
                                Events
                            </a>
                            <a href="{{ route('clubs.people.index', $club) }}"
                                class="flex items-center justify-between py-2 px-4 text-base font-medium {{ request()->routeIs('clubs.people.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-md">
                                <span>People</span>
                                @if (isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                                    <span
                                        class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $pendingRequestsCount }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('clubs.about.index', $club) }}"
                                class="block py-2 px-4 text-base font-medium {{ request()->routeIs('clubs.about.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-md">
                                About
                            </a>
                            @if (auth()->user()->user_id == $club->club_adviser)
                                <a href="{{ route('clubs.questions.index', $club) }}"
                                    class="block py-2 px-4 text-base font-medium {{ request()->routeIs('clubs.questions.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-500 hover:bg-gray-50' }} rounded-md">
                                    Questions
                                </a>
                            @endif

                            <!-- Controls for Mobile -->
                            @if ($club->club_id == 1 && auth()->user()->user_id == $club->club_adviser)
                                <div class="flex items-center justify-between py-2 px-4 border-t border-gray-100 mt-2">
                                    <span class="text-sm text-gray-600">Club Hunting Day</span>
                                    <form id="mobileToggleHuntingDayForm" class="m-0">
                                        @csrf
                                        <button type="button" onclick="toggleHuntingDay()"
                                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 {{ $club->is_club_hunting_day ? 'bg-blue-600' : 'bg-gray-200' }}">
                                            <span class="sr-only">Toggle Club Hunting Day</span>
                                            <span
                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $club->is_club_hunting_day ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if (auth()->user()->user_id == $club->club_adviser)
                                <button onclick="openClubSettingsModal()"
                                    class="flex items-center w-full py-2 px-4 text-base font-medium text-gray-500 hover:bg-gray-50 rounded-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Club Settings
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <div class="container mx-auto px-4">
        <!-- Content Section -->
        <div class="pb-6">
            @yield('club_content')
            @include('clubs.settings.club-settings-modal')
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Existing toggleHuntingDay script -->
    <script>
        async function toggleHuntingDay() {
            try {
                const response = await fetch("{{ route('clubs.toggle-hunting-day', $club) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });
                if (response.ok) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
    @stack('club-settings-scripts')
@endpush
