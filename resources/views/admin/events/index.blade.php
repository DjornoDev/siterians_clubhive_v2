@extends('layouts.dashboard')

@section('title', 'Manage Events | ClubHive Admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manage Events</h1>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <form action="{{ route('admin.events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="club" class="block text-sm font-medium text-gray-700 mb-1">Filter by Club</label>
                    <select name="club" id="club"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">All Clubs</option>
                        @foreach (App\Models\Club::pluck('club_name', 'club_id') as $id => $name)
                            <option value="{{ $id }}" {{ request('club') == $id ? 'selected' : '' }}>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="visibility" class="block text-sm font-medium text-gray-700 mb-1">Filter by
                        Visibility</label>
                    <select name="visibility" id="visibility"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">All Visibility</option>
                        <option value="PUBLIC" {{ request('visibility') == 'PUBLIC' ? 'selected' : '' }}>Public</option>
                        <option value="CLUB_ONLY" {{ request('visibility') == 'CLUB_ONLY' ? 'selected' : '' }}>Club Only
                        </option>
                    </select>
                </div>
                <div>
                    <label for="date_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
                    <select name="date_filter" id="date_filter"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">All Events</option>
                        <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming
                            Events</option>
                        <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Past Events</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Today's Events
                        </option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                    @if (request()->hasAny(['club', 'visibility', 'date_filter']))
                        <a href="{{ route('admin.events.index') }}"
                            class="border border-gray-300 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-100 flex items-center">
                            <i class="fas fa-times mr-2"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Events Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event
                                ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Club
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event
                                Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                & Time</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Visibility</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created By</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($events as $event)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $event->event_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="{{ route('clubs.show', $event->club) }}"
                                        class="text-blue-600 hover:text-blue-800" target="_blank">
                                        {{ $event->club->club_name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    <div class="truncate max-w-xs">{{ $event->event_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $event->event_date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $event->event_time }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $event->event_location }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if ($event->event_visibility === 'PUBLIC')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Public
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Club Only
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full overflow-hidden mr-2 bg-gray-200">
                                            @if ($event->organizer->profile_picture)
                                                <img src="{{ asset('storage/profile_pictures/' . $event->organizer->profile_picture) }}"
                                                    alt="{{ $event->organizer->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div
                                                    class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600">
                                                    {{ strtoupper(substr($event->organizer->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <span>{{ $event->organizer->name }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">No events found</p>
                                        <p class="text-sm mt-1">Try adjusting your filters or check back later</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50">
                {{ $events->links() }}
            </div>
        </div>
    </div>
@endsection
