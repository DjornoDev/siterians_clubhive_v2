@extends('layouts.dashboard')

@section('title', 'Events | ClubHive')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg mb-10 overflow-hidden">
            <div class="px-6 py-8 md:px-10 md:py-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Events</h1>
                <p class="text-indigo-100 text-lg max-w-2xl">View all upcoming events organized by all clubs.</p>
            </div>
        </div>

        <!-- Improved Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-10">
            <div class="flex flex-col gap-6">
                <h2 class="text-xl font-semibold text-gray-800">Filter Events</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Club Filter (Checkboxes) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Clubs</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2 border rounded-lg p-3 bg-gray-50">
                            <div class="flex items-center mb-2">
                                <input id="select-all-clubs" type="checkbox"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="select-all-clubs" class="ml-2 text-sm font-medium text-gray-700">Select
                                    All</label>
                            </div>
                            <div class="border-t border-gray-200 my-2"></div>

                            <div class="flex items-center">
                                <input id="club-1" name="club[]" value="1" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-1" class="ml-2 text-sm text-gray-700">Supreme Secondary Learner
                                    Government</label>
                            </div>
                            <div class="flex items-center">
                                <input id="club-2" name="club[]" value="2" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-2" class="ml-2 text-sm text-gray-700">Robotics Club</label>
                            </div>
                            <div class="flex items-center">
                                <input id="club-3" name="club[]" value="3" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-3" class="ml-2 text-sm text-gray-700">Debate Society</label>
                            </div>
                            <div class="flex items-center">
                                <input id="club-4" name="club[]" value="4" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-4" class="ml-2 text-sm text-gray-700">Art & Photography Club</label>
                            </div>
                            <div class="flex items-center">
                                <input id="club-5" name="club[]" value="5" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-5" class="ml-2 text-sm text-gray-700">Science Explorers</label>
                            </div>
                            <div class="flex items-center">
                                <input id="club-6" name="club[]" value="6" type="checkbox"
                                    class="club-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="club-6" class="ml-2 text-sm text-gray-700">Sports Excellence Team</label>
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
                            <option value="next-3-months">Next 3 Months</option>
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
                        4 events found
                    </span>

                    <div class="flex gap-3">
                        <button id="clear-filters"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Clear Filters
                        </button>
                        <button id="apply-filters"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events List -->
        <div class="space-y-6">
            <!-- Event 1 -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Annual Leadership Summit</h3>
                <p class="text-gray-600 mb-4">Join us for a day of workshops, guest speakers, and networking opportunities
                    designed to enhance your leadership skills and connect with fellow student leaders.</p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Friday, February 14, 2025</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>8:00AM - 5:00PM</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Supreme Secondary Learner Government</span>
                    </div>
                </div>
            </div>

            <!-- Event 2 -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tech Innovation Showcase</h3>
                <p class="text-gray-600 mb-4">Discover the latest technological innovations created by our talented
                    students. Experience demos, prototypes, and learn about the future of technology.</p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Sunday, March 5, 2025</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>1:00PM - 6:00PM</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Robotics Club</span>
                    </div>
                </div>
            </div>

            <!-- Event 3 -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Environmental Awareness Day</h3>
                <p class="text-gray-600 mb-4">Help make our campus greener! Join us for a day of tree planting, recycling
                    activities, and educational workshops about sustainable living practices.</p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Wednesday, April 12, 2025</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>9:00AM - 3:00PM</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Science Explorers</span>
                    </div>
                </div>
            </div>

            <!-- Event 4 -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Charity Fundraiser Gala</h3>
                <p class="text-gray-600 mb-4">An elegant evening to raise funds for our annual community outreach programs.
                    Join us for dinner, entertainment, and the opportunity to support important causes.</p>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Thursday, May 9, 2025</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>6:00PM - 10:00PM</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Supreme Secondary Learner Government</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden mt-8 text-center py-16 bg-gray-50 rounded-xl shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mx-auto text-gray-400 mb-6" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">No events found</h3>
            <p class="text-gray-500 max-w-md mx-auto mb-8">There are no upcoming events for the selected club. Try
                selecting a different club or check back later.</p>
            <button
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-300 inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Reset Filter
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter elements
            const clubCheckboxes = document.querySelectorAll('.club-checkbox');
            const selectAllClubs = document.getElementById('select-all-clubs');
            const dateFilter = document.getElementById('date-filter');
            const customDateRange = document.getElementById('custom-date-range');
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');
            const applyButton = document.getElementById('apply-filters');
            const clearButton = document.getElementById('clear-filters');

            const eventItems = document.querySelectorAll('.space-y-6 > div');
            const emptyState = document.getElementById('empty-state');
            const eventsCount = document.getElementById('events-count');

            // Initialize - check all clubs by default
            selectAllClubs.checked = true;
            clubCheckboxes.forEach(checkbox => {
                checkbox.checked = true;
            });

            // Toggle custom date range visibility
            dateFilter.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateRange.classList.remove('hidden');
                } else {
                    customDateRange.classList.add('hidden');
                }
            });

            // Select All clubs functionality
            selectAllClubs.addEventListener('change', function() {
                const isChecked = this.checked;
                clubCheckboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            });

            // When any club checkbox changes, update "Select All" status
            clubCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(clubCheckboxes).every(cb => cb.checked);
                    const noneChecked = Array.from(clubCheckboxes).every(cb => !cb.checked);

                    selectAllClubs.checked = allChecked;
                    selectAllClubs.indeterminate = !allChecked && !noneChecked;
                });
            });

            // Function to parse date from event item
            function getEventDate(eventItem) {
                const dateElement = eventItem.querySelector('.flex.items-center:nth-child(1) span');
                if (!dateElement) return null;

                const dateText = dateElement.textContent.trim();
                // Parse date text like "Friday, February 14, 2025"
                return new Date(dateText);
            }

            // Function to get club ID from event item
            function getClubId(eventItem) {
                const clubNameElement = eventItem.querySelector('.flex.items-center:nth-child(3) span');
                if (!clubNameElement) return null;

                const clubName = clubNameElement.textContent.trim();
                return getClubIdByName(clubName);
            }

            // Function to apply filters
            function applyFilters() {
                // Get selected clubs
                const selectedClubs = Array.from(clubCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                // Get date filter value
                const selectedDateOption = dateFilter.value;

                // Get current date for date filtering
                const currentDate = new Date();
                const today = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate());
                const currentMonth = currentDate.getMonth();
                const currentYear = currentDate.getFullYear();

                let visibleEvents = 0;

                eventItems.forEach(item => {
                    let showEvent = true;

                    // Club filter
                    const eventClubId = getClubId(item);
                    if (eventClubId && !selectedClubs.includes(eventClubId)) {
                        showEvent = false;
                    }

                    // Date filter
                    if (showEvent && selectedDateOption) {
                        const eventDate = getEventDate(item);
                        if (eventDate) {
                            const eventMonth = eventDate.getMonth();
                            const eventYear = eventDate.getFullYear();

                            if (selectedDateOption === 'upcoming') {
                                if (eventDate < today) {
                                    showEvent = false;
                                }
                            } else if (selectedDateOption === 'this-month') {
                                if (eventMonth !== currentMonth || eventYear !== currentYear) {
                                    showEvent = false;
                                }
                            } else if (selectedDateOption === 'next-month') {
                                const nextMonth = (currentMonth + 1) % 12;
                                const nextMonthYear = currentMonth === 11 ? currentYear + 1 : currentYear;
                                if (eventMonth !== nextMonth || eventYear !== nextMonthYear) {
                                    showEvent = false;
                                }
                            } else if (selectedDateOption === 'next-3-months') {
                                // Check if event is within the next 3 months
                                const threeMonthsFromNow = new Date(today);
                                threeMonthsFromNow.setMonth(currentMonth + 3);

                                if (eventDate > threeMonthsFromNow || eventDate < today) {
                                    showEvent = false;
                                }
                            } else if (selectedDateOption === 'custom') {
                                // Custom date range
                                const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
                                const toDate = dateTo.value ? new Date(dateTo.value) : null;

                                if (fromDate && eventDate < fromDate) {
                                    showEvent = false;
                                }

                                if (toDate) {
                                    // Set toDate to end of the day for inclusive comparison
                                    const toDateEnd = new Date(toDate);
                                    toDateEnd.setHours(23, 59, 59, 999);

                                    if (eventDate > toDateEnd) {
                                        showEvent = false;
                                    }
                                }
                            }
                        }
                    }

                    // Show or hide based on filter results
                    if (showEvent) {
                        item.classList.remove('hidden');
                        visibleEvents++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                // Update counter
                eventsCount.textContent = `${visibleEvents} event${visibleEvents !== 1 ? 's' : ''} found`;

                // Show empty state if no events for selected filters
                if (visibleEvents === 0) {
                    emptyState.classList.remove('hidden');
                } else {
                    emptyState.classList.add('hidden');
                }
            }

            // Helper function to get club ID from name
            function getClubIdByName(name) {
                const clubMap = {
                    'Supreme Secondary Learner Government': '1',
                    'Robotics Club': '2',
                    'Debate Society': '3',
                    'Art & Photography Club': '4',
                    'Science Explorers': '5',
                    'Sports Excellence Team': '6'
                };

                return clubMap[name] || '';
            }

            // Clear all filters
            function clearFilters() {
                // Select all clubs
                selectAllClubs.checked = true;
                selectAllClubs.indeterminate = false;
                clubCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });

                // Reset date filter
                dateFilter.value = '';
                customDateRange.classList.add('hidden');
                dateFrom.value = '';
                dateTo.value = '';

                // Show all events
                eventItems.forEach(item => {
                    item.classList.remove('hidden');
                });

                // Update counter
                eventsCount.textContent = `${eventItems.length} event${eventItems.length !== 1 ? 's' : ''} found`;

                // Hide empty state
                emptyState.classList.add('hidden');
            }

            // Event listeners
            applyButton.addEventListener('click', applyFilters);
            clearButton.addEventListener('click', clearFilters);

            // Reset filter button in empty state
            const resetButton = document.querySelector('#empty-state button');
            if (resetButton) {
                resetButton.addEventListener('click', clearFilters);
            }
        });
    </script>
@endsection
