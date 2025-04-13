@extends('layouts.dashboard')

@section('after_topbar')
    @if ($club instanceof \App\Models\Club)
        <!-- Club Navigation placed right after the topbar -->
        <div class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <nav class="flex justify-between items-center">
                    <!-- Left side navigation links -->
                    <div class="flex space-x-8">
                        <a href="{{ route('clubs.show', $club) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm 
                          {{ request()->routeIs('clubs.show') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Home
                        </a>
                        <a href="{{ route('clubs.events.index', $club) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm 
                          {{ request()->routeIs('clubs.events.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Events
                        </a>
                        <a href="{{ route('clubs.people.index', $club) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm 
                          {{ request()->routeIs('clubs.people.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            People
                        </a>
                        <a href="{{ route('clubs.voting.index', $club) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm 
                        {{ request()->routeIs('clubs.voting.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Voting
                        </a>
                        <a href="{{ route('clubs.about.index', $club) }}"
                            class="py-4 px-1 border-b-2 font-medium text-sm 
                          {{ request()->routeIs('clubs.about.*') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            About
                        </a>
                    </div>

                    <!-- Right side controls -->
                    <div class="flex items-center space-x-6">
                        @if ($club->club_id == 1 && auth()->user()->user_id == $club->club_adviser)
                            <!-- Club Hunting Day Toggle -->
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Club Hunting Day</span>
                                <form id="toggleHuntingDayForm">
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
