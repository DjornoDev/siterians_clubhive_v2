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
            </div>
            </nav>
        </div>
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
