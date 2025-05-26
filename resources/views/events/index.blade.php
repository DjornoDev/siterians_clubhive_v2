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
    }" class="container mx-auto px-4 py-8">
        <!-- Enhanced Header Section with visual elements -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg mb-10 overflow-hidden relative">
            <div class="absolute right-0 top-0 opacity-10">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M8 2V6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3 10H21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div class="px-6 py-8 md:px-10 md:py-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Upcoming Events</h1>
                <p class="text-indigo-100 text-lg max-w-2xl">Discover exciting events hosted by your favorite clubs.
                    Connect, learn, and have fun!</p>
            </div>
        </div>

        <!-- Improved Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-10">
            <div class="flex flex-col gap-6">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                            clip-rule="evenodd" />
                    </svg>
                    Filter Events
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Club Filter (Dropdown) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Clubs</label>
                        <div x-data="clubFilter({
                            clubs: {{ $clubs->toJson() }},
                            initialSelected: {{ $clubs->pluck('club_id')->toJson() }}
                        })" x-id="['club-filter']" class="relative" @click.away="isOpen = false">
                            <!-- Hidden input to store selected club IDs -->
                            <input type="hidden" name="club[]" :value="selectedClubs.join(',')" id="selected-clubs">

                            <!-- Dropdown Trigger -->
                            <div class="cursor-pointer rounded-lg border border-gray-300 bg-gray-50 p-2 flex items-center justify-between"
                                @click="isOpen = !isOpen">
                                <div class="flex flex-wrap gap-2 flex-1">
                                    <template x-for="(clubId, index) in selectedClubs" :key="clubId">
                                        <template x-if="index < 3">
                                            <div
                                                class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full text-sm flex items-center">
                                                <span x-text="getClubName(clubId)"></span>
                                                <button @click.stop="toggleClub(clubId)" type="button"
                                                    class="ml-1 hover:text-indigo-900 focus:outline-none">
                                                    ×
                                                </button>
                                            </div>
                                        </template>
                                    </template>
                                    <span x-show="selectedClubs.length > 3" class="text-gray-500 text-sm">
                                        +<span x-text="selectedClubs.length - 3"></span>
                                    </span>
                                    <span x-show="selectedClubs.length === 0" class="text-gray-500 text-sm">
                                        Select clubs...
                                    </span>
                                </div>
                                <svg class="h-5 w-5 text-gray-400 transition-transform duration-200"
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
                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-transparent">
                                </div>

                                <!-- Select All -->
                                <div class="flex items-center p-2 hover:bg-gray-50 cursor-pointer border-b border-gray-200">
                                    <input type="checkbox" :checked="selectAll" :indeterminate="selectAllIndeterminate"
                                        @change="toggleAll"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label class="ml-2 text-sm text-gray-700">Select All</label>
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
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700" x-text="club.club_name"></label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date-filter" class="block text-sm font-semibold text-gray-700 mb-3">Date Range</label>
                        <select id="date-filter"
                            class="w-full rounded-lg border-gray-300 bg-gray-50 pl-4 pr-10 py-3 text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 appearance-none">
                            <option value="">All Dates</option>
                            <option value="upcoming">Upcoming Events</option>
                            <option value="this-month">This Month</option>
                            <option value="next-month">Next Month</option>
                            <option value="custom">Custom Date Range</option>
                        </select>

                        <!-- Custom Date Range (initially hidden) -->
                        <div id="custom-date-range" class="mt-3 grid grid-cols-2 gap-3 hidden">
                            <div>
                                <label for="date-from" class="block text-xs font-medium text-gray-700 mb-1">From</label>
                                <input type="date" id="date-from"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 pl-4 pr-4 py-2 text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            </div>
                            <div>
                                <label for="date-to" class="block text-xs font-medium text-gray-700 mb-1">To</label>
                                <input type="date" id="date-to"
                                    class="w-full rounded-lg border-gray-300 bg-gray-50 pl-4 pr-4 py-2 text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-gray-200">
                    <span id="events-count"
                        class="inline-flex items-center rounded-full bg-indigo-100 px-4 py-2 text-sm font-medium text-indigo-800">
                        {{ $events->total() }} event{{ $events->total() !== 1 ? 's' : '' }} found
                    </span>

                    <div class="flex gap-3">
                        <button id="clear-filters"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </button>
                        <button id="apply-filters"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
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

        <!-- Redesigned Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full event-item border border-gray-100"
                    data-club-id="{{ $event->club->club_id }}"
                    data-event-date="{{ $event->event_date->format('Y-m-d') }}">
                    <!-- Color Banner based on club -->
                    <div class="h-2 bg-gradient-to-r from-indigo-500 to-purple-500"></div>

                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Date Badge -->
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="bg-indigo-50 text-indigo-700 rounded-lg px-3 py-1 text-sm font-semibold inline-flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $event->event_date->format('M j, Y') }}
                            </div>
                            @if ($event->event_time)
                                <div class="text-gray-500 text-sm font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $event->event_time }}
                                </div>
                            @endif
                        </div>

                        <!-- Event Name -->
                        <h3 class="text-xl font-bold text-gray-800 mb-2 hover:text-indigo-600 transition-colors">
                            {{ $event->event_name }}</h3>

                        <!-- Event Description -->
                        @if ($event->event_description)
                            <p class="text-gray-600 mb-4 flex-1">{{ Str::limit($event->event_description, 100) }}</p>
                        @endif                        <!-- Club Badge and Visibility -->
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="bg-purple-100 text-purple-600 rounded-full h-8 w-8 flex items-center justify-center mr-2">
                                    {{ strtoupper(substr($event->club->club_name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $event->club->club_name }}</span>
                            </div>
                            <div>
                                @include('clubs.partials.event-visibility-badge', ['event' => $event])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Enhanced Pagination -->
        <div class="mt-10 flex justify-center">
            {{ $events->links() }}
        </div>

        <!-- Improved Empty State -->
        <div id="empty-state" class="hidden mt-8 text-center py-16 bg-indigo-50 rounded-xl shadow-inner">
            <div class="max-w-md mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-indigo-300 mb-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="text-xl font-semibold text-indigo-700 mb-3">No events found</h3>
                <p class="text-gray-600 mb-8">There are no upcoming events matching your selected filters. Try adjusting
                    your filters to see more events.</p>
                <button id="reset-filters"
                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-300 inline-flex items-center shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
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
