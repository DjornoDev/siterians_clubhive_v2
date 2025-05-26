@section('title', 'About ' . $club->club_name)
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto">
        <div class="relative mb-8">
            <!-- Club Banner -->
            <div class="h-64 w-full overflow-hidden rounded-lg">
                <img src="{{ asset('storage/' . $club->club_banner) }}" alt="{{ $club->club_name }} Banner"
                    class="w-full h-full object-cover">
            </div>

            <!-- Club Logo Overlay -->
            <div
                class="absolute -bottom-8 left-8 w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white">
                <img src="{{ asset('storage/' . $club->club_logo) }}" alt="{{ $club->club_name }} Logo"
                    class="w-full h-full object-cover">
            </div>
        </div>

        <div class="pt-8">
            <!-- Club Name and Basic Info -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $club->club_name }}</h1>
                    @if ($club->adviser)
                        <p class="text-gray-600 mt-1">Adviser: {{ $club->adviser->name }}</p>
                    @endif
                </div>

                <!-- Club Quick Stats -->
                <div class="bg-blue-50 rounded-lg px-5 py-3 mt-4 md:mt-0 flex items-center space-x-6">
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-blue-700">{{ $club->members->count() }}</span>
                        <span class="text-sm text-blue-600">Members</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-blue-700">{{ $club->events->count() }}</span>
                        <span class="text-sm text-blue-600">Events</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-2xl font-bold text-blue-700">{{ $club->posts->count() }}</span>
                        <span class="text-sm text-blue-600">Posts</span>
                    </div>
                </div>
            </div>

            <!-- Club Description -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">About Us</h2>

                <div class="prose max-w-none">
                    @if ($club->club_description)
                        {!! nl2br(e($club->club_description)) !!}
                    @else
                        <p class="text-gray-500 italic">No description available.</p>
                    @endif
                </div>
            </div>

            <!-- Club Leadership -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Club Leadership</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Adviser -->
                    <div class="bg-gray-50 rounded-lg p-4 flex items-center">
                        <div class="flex-shrink-0 mr-4">
                            <div class="h-12 w-12 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $club->adviser->name }}</h3>
                            <p class="text-sm text-gray-600">Club Adviser</p>
                        </div>
                    </div>

                    <!-- Officers -->
                    @php
                        $officers = $club->members()->whereNotNull('club_position')->orderBy('club_position')->get();
                    @endphp

                    @forelse ($officers as $officer)
                        <div class="bg-gray-50 rounded-lg p-4 flex items-center">
                            <div class="flex-shrink-0 mr-4">
                                <div
                                    class="h-12 w-12 bg-purple-100 text-purple-700 rounded-full flex items-center justify-center">
                                    <span class="font-medium text-lg">{{ substr($officer->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $officer->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $officer->pivot->club_position }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-4 text-center text-gray-500">
                            No officers assigned yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Club Activity Stats -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Club Activity</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Activity -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-medium text-gray-800 mb-3">Recent Activity</h3>
                        <div class="space-y-3">
                            @php
                                $recentPosts = $club->posts()->latest()->take(3)->get();
                                $recentEvents = $club
                                    ->events()
                                    ->where('event_date', '>=', now())
                                    ->orderBy('event_date')
                                    ->take(2)
                                    ->get();
                            @endphp

                            @forelse ($recentPosts as $post)
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div class="text-sm">
                                        <p class="text-gray-800">New post by {{ $post->author->name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $post->post_date->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No recent posts</p>
                            @endforelse

                            @foreach ($recentEvents as $event)
                                <div class="flex items-center">
                                    <div
                                        class="h-8 w-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="text-sm">
                                        <p class="text-gray-800">{{ $event->event_name }}</p>
                                        <p class="text-gray-500 text-xs">{{ $event->event_date->format('M d, Y') }} at
                                            {{ $event->event_location }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Membership Information -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-medium text-gray-800 mb-3">Membership Information</h3>

                        @if ($club->is_club_hunting_day)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">Club Hunting Day is ACTIVE!</p>
                                        <p class="text-xs text-green-700 mt-1">You can now join other club/s from the
                                            Explore Clubs page.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Total Members:</span>
                                <span class="font-medium">{{ $club->members->count() }}</span>
                            </div>

                            @php
                                $membersByRole = $club->members->groupBy('pivot.club_role');
                            @endphp

                            @foreach ($membersByRole as $role => $members)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 text-sm">{{ $role }}s:</span>
                                    <span class="font-medium">{{ $members->count() }}</span>
                                </div>
                            @endforeach

                            <div class="mt-4">
                                <a href="{{ route('clubs.people.index', $club) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                    View all members
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->user_id === $club->club_adviser)
                <!-- Club Management (Only Visible to Adviser) -->
                <div class="bg-white rounded-lg shadow p-6 mb-8 border-l-4 border-purple-500">
                    <h2 class="text-xl font-semibold text-purple-800 mb-4">Club Management</h2>

                    <p class="text-gray-600 mb-4">As the club adviser, you can manage club details, membership, and
                        activities through this platform.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"> <button type="button"
                            onclick="openClubSettingsModal()"
                            class="bg-purple-50 hover:bg-purple-100 transition-colors rounded-lg p-4 flex items-center w-full text-left">
                            <div class="rounded-full bg-purple-200 p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Club Settings</h3>
                                <p class="text-sm text-gray-600">Update club details</p>
                            </div>
                        </button>

                        <a href="{{ route('clubs.people.index', $club) }}"
                            class="bg-blue-50 hover:bg-blue-100 transition-colors rounded-lg p-4 flex items-center">
                            <div class="rounded-full bg-blue-200 p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Manage Members</h3>
                                <p class="text-sm text-gray-600">Add/edit members & roles</p>
                            </div>
                        </a>

                        <a href="{{ route('clubs.events.index', $club) }}"
                            class="bg-green-50 hover:bg-green-100 transition-colors rounded-lg p-4 flex items-center">
                            <div class="rounded-full bg-green-200 p-2 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-700" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Club Events</h3>
                                <p class="text-sm text-gray-600">Schedule & manage events</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
