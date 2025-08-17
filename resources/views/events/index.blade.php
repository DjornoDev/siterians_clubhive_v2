@extends('layouts.dashboard')

@section('title', 'Events | ClubHive')

@section('content')
    <div tabindex="-1" x-data="{
        lastChecksum: '{{ md5(json_encode($events->pluck('event_id')->merge($events->pluck('updated_at')))) }}',
        modalOpen: false,
    
        checkForEventChanges() {
            // Skip refresh check if a modal is open
            if (this.modalOpen) return;
    
            // Check if any modals are visible (if added in the future)
            const anyModalOpen = document.querySelector('.modal:not(.hidden)');
            if (anyModalOpen) return;
    
            fetch('{{ route('events.check-changes') }}?checksum=' + this.lastChecksum)
                .then(response => response.json())
                .then(data => {
                    if (data.hasChanges) {
                        window.location.reload();
                    }
                });
        },
    
        init() {
            // Check for event changes every 10 seconds
            setInterval(() => this.checkForEventChanges(), 10000);
        }
    }" class="space-y-6 sm:space-y-8">
        <!-- Enhanced Header Section -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header Background -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 sm:px-8 py-6 sm:py-8 border-b border-gray-100">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Upcoming Events</h1>
                    <p class="text-gray-600 text-base sm:text-lg flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Discover exciting events hosted by your favorite clubs
                    </p>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="px-6 sm:px-8 py-4 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center justify-center sm:justify-start text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Total Events: <span class="font-semibold text-gray-900 ml-1">{{ $events->total() }}</span>
                    </div>
                    <div class="text-xs text-gray-500 text-center sm:text-right">
                        Last updated: {{ now()->format('F j, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Clean Filter Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col gap-4 sm:gap-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-blue-500 mr-2"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                            clip-rule="evenodd" />
                    </svg>
                    Filter Events
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Club Filter (Dropdown) -->
                    <div>
                        <label for="club-filter-trigger"
                            class="block text-sm font-semibold text-gray-700 mb-3">Clubs</label>
                        <div x-data="clubFilter({
                            clubs: {{ $clubs->toJson() }},
                            initialSelected: {{ $clubs->pluck('club_id')->toJson() }}
                        })" x-id="['club-filter']" class="relative" @click.away="isOpen = false">
                            <!-- Hidden input to store selected club IDs -->
                            <input type="hidden" name="club[]" :value="selectedClubs.join(',')" id="selected-clubs">

                            <!-- Dropdown Trigger -->
                            <div id="club-filter-trigger"
                                class="cursor-pointer rounded-lg border border-gray-300 bg-gray-50 p-2 sm:p-3 flex items-center justify-between hover:border-gray-400 transition-colors"
                                @click="isOpen = !isOpen">
                                <div class="flex flex-wrap gap-1 sm:gap-2 flex-1">
                                    <template x-for="(clubId, index) in selectedClubs" :key="clubId">
                                        <template x-if="index < 3">
                                            <div
                                                class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs sm:text-sm flex items-center">
                                                <span x-text="getClubName(clubId)"></span>
                                                <button @click.stop="toggleClub(clubId)" type="button"
                                                    class="ml-1 hover:text-blue-900 focus:outline-none">
                                                    ×
                                                </button>
                                            </div>
                                        </template>
                                    </template>
                                    <span x-show="selectedClubs.length > 3" class="text-gray-500 text-xs sm:text-sm">
                                        +<span x-text="selectedClubs.length - 3"></span>
                                    </span>
                                    <span x-show="selectedClubs.length === 0" class="text-gray-500 text-xs sm:text-sm">
                                        Select clubs...
                                    </span>
                                </div>
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400 transition-transform duration-200"
                                    :class="{ 'rotate-180': isOpen }" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>

                            <!-- Dropdown Content -->
                            <div x-show="isOpen"
                                class="absolute mt-1 w-full rounded-lg bg-white shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95">
                                <!-- Search Input -->
                                <div class="p-2 border-b border-gray-200">
                                    <input type="text" x-model="searchQuery" placeholder="Search clubs..."
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-transparent text-sm">
                                </div>

                                <!-- Select All -->
                                <div class="flex items-center p-2 hover:bg-gray-50 cursor-pointer border-b border-gray-200">
                                    <input type="checkbox" :checked="selectAll" :indeterminate="selectAllIndeterminate"
                                        @change="toggleAll" id="select-all-clubs"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="select-all-clubs" class="ml-2 text-sm text-gray-700 cursor-pointer">Select
                                        All</label>
                                </div>

                                <!-- Club List -->
                                <template x-if="filteredClubs.length === 0">
                                    <div class="p-3 text-gray-500 text-sm">
                                        No clubs match your search
                                    </div>
                                </template>
                                <template x-for="club in filteredClubs" :key="club.club_id">
                                    <div class="flex items-center p-2 hover:bg-gray-50 cursor-pointer"
                                        @click.stop="toggleClub(club.club_id)">
                                        <input type="checkbox" :checked="selectedClubs.includes(club.club_id)"
                                            :id="'club-' + club.club_id"
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                        <label :for="'club-' + club.club_id"
                                            class="ml-2 text-sm text-gray-700 cursor-pointer"
                                            x-text="club.club_name"></label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date-filter" class="block text-sm font-semibold text-gray-700 mb-3">Date Range</label>
                        <select id="date-filter"
                            class="w-full rounded-lg border-gray-300 bg-gray-50 pl-3 sm:pl-4 pr-8 sm:pr-10 py-2 sm:py-3 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 appearance-none text-sm sm:text-base">
                            <option value="">All Dates</option>
                            <option value="upcoming">Upcoming Events</option>
                            <option value="this-month">This Month</option>
                            <option value="next-month">Next Month</option>
                            <option value="custom">Custom Date Range</option>
                        </select>

                        <!-- Custom Date Range (initially hidden) -->
                        <div id="custom-date-range" class="mt-3 hidden">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="date-from"
                                        class="block text-xs font-medium text-gray-700 mb-1">From</label>
                                    <input type="date" id="date-from"
                                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-3 py-2 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm">
                                </div>
                                <div>
                                    <label for="date-to" class="block text-xs font-medium text-gray-700 mb-1">To</label>
                                    <input type="date" id="date-to"
                                        class="w-full rounded-lg border-gray-300 bg-gray-50 px-3 py-2 text-gray-700 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-between gap-3 sm:gap-4 pt-3 border-t border-gray-200">
                    <span id="events-count"
                        class="inline-flex items-center rounded-md bg-blue-50 px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-blue-800 border border-blue-200">
                        {{ $events->total() }} event{{ $events->total() !== 1 ? 's' : '' }} found
                    </span>

                    <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                        <button id="clear-filters"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center justify-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </button>
                        <button id="apply-filters"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events List - Single Column Layout -->
        <div class="grid gap-4 sm:gap-6">
            @foreach ($events as $event)
                <div class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 overflow-hidden event-item"
                    data-club-id="{{ $event->club->club_id }}"
                    data-event-date="{{ $event->event_date->format('Y-m-d') }}">

                    <!-- Event Header -->
                    <div class="p-4 sm:p-6 border-b border-gray-100">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-3">
                                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">
                                        {{ $event->event_name }}
                                    </h2>
                                    <div class="flex items-center gap-2">
                                        @include('clubs.partials.event-visibility-badge', [
                                            'event' => $event,
                                        ])
                                    </div>
                                </div>

                                <div
                                    class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 text-xs sm:text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <div
                                            class="bg-blue-100 text-blue-600 rounded-full h-6 w-6 flex items-center justify-center mr-2 text-xs font-semibold">
                                            {{ strtoupper(substr($event->club->club_name, 0, 1)) }}
                                        </div>
                                        <span
                                            class="font-medium text-gray-700 truncate">{{ $event->club->club_name }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Action Button -->
                            <div class="w-full lg:w-auto lg:ml-6">
                                <a href="{{ route('clubs.events.show', [$event->club, $event]) }}"
                                    class="inline-flex items-center justify-center w-full lg:w-auto px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <span class="hidden sm:inline">View Details</span>
                                    <span class="sm:hidden">View</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-4 sm:p-6">
                        <!-- Event Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4">
                            <div class="space-y-2 sm:space-y-3">
                                <!-- Date -->
                                <div class="flex items-center text-xs sm:text-sm">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-600 font-medium">
                                        {{ $event->event_date->format('F j, Y') }}
                                        @if ($event->event_time)
                                            <span class="hidden sm:inline">at</span> {{ $event->event_time }}
                                        @endif
                                    </span>
                                </div>

                                <!-- Location -->
                                @if ($event->location)
                                    <div class="flex items-center text-xs sm:text-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-gray-600 truncate">{{ $event->location }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Additional Info -->
                            <div class="space-y-2 sm:space-y-3">
                                @if ($event->max_attendees)
                                    <div class="flex items-center text-xs sm:text-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600">Max Attendees:
                                            {{ number_format($event->max_attendees) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Event Description -->
                        @if ($event->event_description)
                            <div class="bg-gray-50 rounded-lg p-3 sm:p-4 mb-4">
                                <p class="text-xs sm:text-sm text-gray-700 leading-relaxed">
                                    {{ Str::limit($event->event_description, 200) }}
                                </p>
                            </div>
                        @endif

                        <!-- Event Documents -->
                        @if ($event->documents && $event->documents->count() > 0)
                            <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <span class="text-xs sm:text-sm font-medium text-blue-700">
                                        Event Documents ({{ $event->documents->count() }})
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    @foreach ($event->documents as $document)
                                        <div
                                            class="flex items-center justify-between bg-white rounded-md p-2 border border-blue-200">
                                            <div class="flex items-center min-w-0 flex-1">
                                                <div class="bg-blue-100 rounded p-1 mr-2">
                                                    <svg class="w-3 h-3 text-blue-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs font-medium text-gray-900 truncate">
                                                        {{ $document->original_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $document->formatted_file_size }}
                                                        • {{ strtoupper($document->file_extension) }}</p>
                                                </div>
                                            </div>
                                            <a href="{{ route('events.documents.download', $document) }}"
                                                class="ml-2 text-blue-600 hover:text-blue-700 p-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-4-4m4 4l4-4m6 8a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($event->supporting_documents)
                            <!-- Legacy single document support -->
                            <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span class="text-xs sm:text-sm font-medium text-blue-700">Supporting
                                            Document</span>
                                    </div>
                                    <a href="{{ Storage::url($event->supporting_documents) }}"
                                        class="text-blue-600 hover:text-blue-700 text-xs">
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Simple Pagination -->
        @if ($events->hasPages())
            <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                        Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }} results
                    </div>
                    <div class="flex justify-center sm:justify-end">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Clean Empty State -->
        <div id="empty-state" class="hidden bg-white rounded-lg border border-gray-200 p-8 sm:p-16 text-center">
            <div class="max-w-md mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-16 w-16 sm:h-20 sm:w-20 mx-auto text-gray-300 mb-4 sm:mb-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-3">No events found</h3>
                <p class="text-gray-600 mb-6 sm:mb-8 text-sm sm:text-base">There are no upcoming events matching your
                    selected filters. Try adjusting
                    your filters to see more events.</p>
                <button id="reset-filters"
                    class="px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-300 inline-flex items-center text-sm sm:text-base">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter elements
            const dateFilter = document.getElementById('date-filter');
            const customDateRange = document.getElementById('custom-date-range');
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');
            const applyButton = document.getElementById('apply-filters');
            const clearButton = document.getElementById('clear-filters');
            const resetButton = document.getElementById('reset-filters');

            const eventItems = document.querySelectorAll('.event-item');
            const emptyState = document.getElementById('empty-state');
            const eventsCount = document.getElementById('events-count');

            // Toggle custom date range visibility
            dateFilter.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateRange.classList.remove('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                }
            });

            // Function to apply filters
            function applyFilters() {
                // Get selected clubs from hidden input
                const selectedClubs = document.getElementById('selected-clubs').value
                    .split(',')
                    .filter(id => id !== '');

                // Get date filter value
                const selectedDateOption = dateFilter.value;

                // Get current date for date filtering
                const currentDate = new Date();
                const today = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());

                let visibleEvents = 0;

                eventItems.forEach(item => {
                    let showEvent = true;

                    // Club filter
                    const eventClubId = item.dataset.clubId;
                    if (!selectedClubs.includes(eventClubId)) {
                        showEvent = false;
                    }

                    // Date filter
                    if (showEvent && selectedDateOption) {
                        const eventDate = new Date(item.dataset.eventDate);

                        if (selectedDateOption === 'upcoming') {
                            if (eventDate < today) {
                                showEvent = false;
                            }
                        } else if (selectedDateOption === 'this-month') {
                            if (eventDate.getMonth() !== today.getMonth() ||
                                eventDate.getFullYear() !== today.getFullYear()) {
                                showEvent = false;
                            }
                        } else if (selectedDateOption === 'next-month') {
                            const nextMonth = new Date(today);
                            nextMonth.setMonth(today.getMonth() + 1);
                            if (eventDate.getMonth() !== nextMonth.getMonth() ||
                                eventDate.getFullYear() !== nextMonth.getFullYear()) {
                                showEvent = false;
                            }
                        } else if (selectedDateOption === 'custom') {
                            const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
                            const toDate = dateTo.value ? new Date(dateTo.value) : null;

                            if (fromDate && eventDate < fromDate) {
                                showEvent = false;
                            }

                            if (toDate) {
                                const toDateEnd = new Date(toDate);
                                toDateEnd.setHours(23, 59, 59, 999);
                                if (eventDate > toDateEnd) {
                                    showEvent = false;
                                }
                            }
                        }
                    }

                    // Show/hide event
                    item.style.display = showEvent ? 'block' : 'none';
                    if (showEvent) visibleEvents++;
                });

                // Update counter
                eventsCount.textContent = `${visibleEvents} event${visibleEvents !== 1 ? 's' : ''} found`;

                // Toggle empty state
                emptyState.classList.toggle('hidden', visibleEvents > 0);
            }

            // Clear all filters
            function clearFilters() {
                // Reset Alpine component state through DOM
                const clubFilterComponent = document.querySelector('[x-data^="clubFilter"]');
                if (clubFilterComponent) {
                    const component = Alpine.$data(clubFilterComponent, 'clubFilter');
                    component.selectedClubs = component.clubs.map(c => c.club_id);
                }

                // Reset date filter
                dateFilter.value = '';
                customDateRange.classList.add('hidden');
                dateFrom.value = '';
                dateTo.value = '';

                // Show all events
                eventItems.forEach(item => {
                    item.style.display = 'block';
                });

                // Update counter
                eventsCount.textContent = `${eventItems.length} event${eventItems.length !== 1 ? 's' : ''} found`;

                // Hide empty state
                emptyState.classList.add('hidden');
            }

            // Event listeners
            applyButton.addEventListener('click', applyFilters);
            clearButton.addEventListener('click', clearFilters);
            resetButton.addEventListener('click', clearFilters);
        });
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('clubFilter', (config) => ({
                // Component state
                clubs: config.clubs,
                selectedClubs: config.initialSelected,
                searchQuery: '',
                isOpen: false,

                // Get club name from ID
                getClubName(clubId) {
                    const club = this.clubs.find(c => c.club_id == clubId);
                    return club ? club.club_name : '';
                },

                // Toggle individual club selection
                toggleClub(clubId) {
                    if (this.selectedClubs.includes(clubId)) {
                        this.selectedClubs = this.selectedClubs.filter(id => id !== clubId);
                    } else {
                        this.selectedClubs.push(clubId);
                    }
                },

                // Toggle all clubs
                toggleAll() {
                    if (this.selectAll) {
                        this.selectedClubs = [];
                    } else {
                        this.selectedClubs = this.clubs.map(c => c.club_id);
                    }
                },

                // Filtered clubs based on search query
                get filteredClubs() {
                    const query = this.searchQuery.toLowerCase();
                    return this.clubs.filter(club =>
                        club.club_name.toLowerCase().includes(query)
                    );
                },

                // Computed select all state
                get selectAll() {
                    return this.clubs.length > 0 &&
                        this.clubs.every(club => this.selectedClubs.includes(club.club_id));
                },

                // Computed indeterminate state
                get selectAllIndeterminate() {
                    return !this.selectAll &&
                        this.selectedClubs.length > 0 &&
                        this.selectedClubs.length < this.clubs.length;
                }
            }));
        });
    </script>
@endsection
