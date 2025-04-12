@extends('layouts.dashboard')

@section('after_topbar')
    @if ($club instanceof \App\Models\Club)
        <!-- Club Navigation placed right after the topbar -->
        <div class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <nav>
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
                    @if (auth()->user()->user_id === $club->club_adviser)
                        <div class="flex items-center space-x-4">
                            <!-- Club Hunting Day Toggle -->
                            <div x-data="{ huntingDay: false }" class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700">Club Hunting Day</span>
                                <button @click="huntingDay = !huntingDay" type="button"
                                    :class="huntingDay ? 'bg-blue-600' : 'bg-gray-200'"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <span aria-hidden="true" :class="huntingDay ? 'translate-x-5' : 'translate-x-0'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>

                            <!-- Settings Gear Icon -->
                            <button @click="$dispatch('open-club-settings')"
                                class="text-gray-400 hover:text-gray-500 p-2 rounded-full hover:bg-gray-100">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColoFr">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </nav>
            </div>
        </div>
        @include('clubs.settings.club-settings-modal')
    @endif
@endsection

@section('content')
    <div class="container mx-auto px-4">
        <!-- Content Section -->
        <div class="pb-6">
            @yield('club_content')
        </div>
    </div>
@endsection
