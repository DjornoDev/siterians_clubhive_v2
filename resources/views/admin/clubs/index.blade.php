@extends('layouts.dashboard')

@section('title', 'Manage Clubs | ClubHive')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Page Header with gradient background -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Club Management</h1>
                    <p class="text-blue-100 mt-1">Create and manage clubs for your students</p>
                </div>
                <button onclick="toggleClubModal()"
                    class="bg-white text-blue-700 px-5 py-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 font-medium shadow-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add New Club
                </button>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Clubs</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ count($clubs) }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Active Advisers</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">
                            {{ $clubs->whereNotNull('club_adviser')->count() }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Available Teachers</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ count($teachers) }}</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Hunting Day</p>
                        <h3 class="text-lg font-bold text-gray-800 mt-1">
                            @php
                                $huntingDay = \App\Services\MainClubService::isHuntingDayActive();
                            @endphp
                            <span class="{{ $huntingDay ? 'text-green-600' : 'text-red-600' }}">
                                {{ $huntingDay ? 'Active' : 'Inactive' }}
                            </span>
                        </h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter and Search Bar -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="relative w-full sm:w-auto">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="club-search" name="club_search_field"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5"
                    placeholder="Search clubs..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');"
                    onblur="this.setAttribute('readonly', 'readonly');">
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <select id="adviser-filter"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">All Advisers</option>
                    @foreach ($advisers as $adviser)
                        <option value="{{ $adviser->name }}">{{ $adviser->name }}</option>
                    @endforeach
                </select>

                <button id="clear-filters"
                    class="bg-gray-100 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-200 transition-colors">
                    Clear
                </button>
            </div>
        </div>

        <!-- Add Club Modal -->
        @include('admin.clubs.partials.add-club-modal') <!-- Import View, Edit, Delete, Password Verification Modals -->
        @include('admin.clubs.partials.view-club-modal')
        @include('admin.clubs.partials.edit-club-modal')
        @include('admin.clubs.partials.delete-club-modal')
        @include('admin.clubs.partials.password-verification-modal')

        <!-- Refined Club Cards Grid with Better Proportions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-6">
            @forelse ($clubs as $club)
                <div
                    class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-102 flex flex-col h-full border border-gray-100">
                    <!-- Banner Section - Slightly shorter -->
                    <div class="relative h-40 bg-gray-100">
                        @if ($club->club_banner && Storage::disk('public')->exists($club->club_banner))
                            <img src="{{ asset(Storage::url($club->club_banner)) }}" alt="{{ $club->club_name }} Banner"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif

                        <!-- Logo Overlay - Smaller -->
                        <div class="absolute -bottom-10 left-4">
                            <div
                                class="w-20 h-20 rounded-full border-3 border-white shadow-md bg-white flex items-center justify-center">
                                @if ($club->club_logo && Storage::disk('public')->exists($club->club_logo))
                                    <img src="{{ asset(Storage::url($club->club_logo)) }}"
                                        alt="{{ $club->club_name }} Logo"
                                        class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Club Details with refined spacing -->
                    <div class="pt-14 px-4 pb-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $club->club_name }}</h3>

                        @if ($club->club_description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">{{ $club->club_description }}</p>
                        @else
                            <div class="flex-grow mb-2"></div>
                        @endif

                        <div class="border-t border-gray-100 pt-3 mt-auto">
                            <div class="flex items-center mb-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-gray-700 font-medium truncate">Adviser:
                                    {{ $club->adviser->name ?? 'No Adviser Assigned' }}
                                </span>
                            </div>

                            <!-- Refined Button -->
                            <button
                                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors duration-200 flex items-center justify-center view-club-btn"
                                data-id="{{ $club->club_id }}" data-name="{{ $club->club_name }}"
                                data-description="{{ $club->club_description }}"
                                data-adviser="{{ $club->adviser->name ?? 'No Adviser Assigned' }}"
                                data-logo="{{ $club->club_logo && Storage::disk('public')->exists($club->club_logo) ? asset(Storage::url($club->club_logo)) : '' }}"
                                data-banner="{{ $club->club_banner && Storage::disk('public')->exists($club->club_banner) ? asset(Storage::url($club->club_banner)) : '' }}"
                                data-category="{{ $club->category ?? 'academic' }}"
                                data-requires-approval="{{ $club->requires_approval ? '1' : '0' }}">
                                <span>View Details</span>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-gray-500 text-lg">No clubs found. Create your first club!</div>
                </div>
            @endforelse
        </div>

    </div>

    <script>
        //For Opening the Add Modal
        function toggleClubModal() {
            document.getElementById('addClubModal').classList.toggle('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('addClubModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });

        // Add event listeners to all view buttons when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.view-club-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const description = this.getAttribute('data-description');
                    const adviser = this.getAttribute('data-adviser');
                    const logo = this.getAttribute('data-logo');
                    const banner = this.getAttribute('data-banner');
                    const category = this.getAttribute('data-category');
                    const requiresApproval = this.getAttribute('data-requires-approval');

                    viewClubDetails(id, name, description, adviser, logo, banner, category,
                        requiresApproval);
                    console.log(id, name);
                });
            });
        });

        // Club Search & Filter Implementation
        document.addEventListener('DOMContentLoaded', function() {
            // Get just the club cards grid, not the stats summary grid
            const clubCardsContainer = document.querySelector(
                '.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3.xl\\:grid-cols-4');
            // Get all club cards - direct children divs of the container (excluding any with col-span-full)
            const clubCards = clubCardsContainer.querySelectorAll(':scope > div:not(.col-span-full)');
            const searchInput = document.getElementById('club-search');
            const adviserFilter = document.getElementById('adviser-filter');
            const clearFiltersBtn = document.getElementById('clear-filters');
            const gridContainer = clubCardsContainer; // Create "no results" element that we'll show/hide as needed
            const noResultsEl = document.createElement('div');
            noResultsEl.className = 'col-span-full text-center py-10';
            noResultsEl.style.display = 'none';
            noResultsEl.innerHTML = '<div class="text-gray-500 text-lg">No clubs match your search criteria.</div>';
            gridContainer.appendChild(noResultsEl);

            // Search functionality
            searchInput.addEventListener('input', filterClubs);

            // Adviser filter functionality
            adviserFilter.addEventListener('change', filterClubs); // Clear filters button
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                adviserFilter.selectedIndex = 0;
                filterClubs();
            });

            // Function to filter clubs based on search and filter criteria
            function filterClubs() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedAdviser = adviserFilter.value.toLowerCase();
                let visibleCount = 0;

                clubCards.forEach(card => {
                    try {
                        // Get club name (should be h3 element)
                        const clubNameEl = card.querySelector('h3');
                        const clubName = clubNameEl ? clubNameEl.textContent.toLowerCase() : '';

                        // Get club description (p element, might not exist)
                        const clubDescriptionEl = card.querySelector('p');
                        const clubDescription = clubDescriptionEl ? clubDescriptionEl.textContent
                            .toLowerCase() : '';

                        // For adviser, look for the specific text containing "Adviser:"
                        const adviserLine = Array.from(card.querySelectorAll('span')).find(span =>
                            span.textContent.includes('Adviser:')
                        );
                        const clubAdviser = adviserLine ? adviserLine.textContent.toLowerCase() : '';

                        // Check if club matches search term
                        const matchesSearch = searchTerm === '' ||
                            clubName.includes(searchTerm) ||
                            clubDescription.includes(searchTerm);

                        // Check if club matches adviser filter
                        const matchesAdviser = selectedAdviser === '' ||
                            clubAdviser.includes(selectedAdviser); // Show or hide based on filters
                        if (matchesSearch && matchesAdviser) {
                            card.style.display = ''; // Reset to default display
                            visibleCount++;
                        } else {
                            card.style.display = 'none';
                        }
                    } catch (error) {
                        console.error("Error processing club card:", error);
                        // Keep card visible if there's an error
                        card.style.display = '';
                        visibleCount++;
                    }
                }); // Toggle visibility of "no results" message
                if (visibleCount === 0) {
                    noResultsEl.style.display = 'block';
                } else {
                    noResultsEl.style.display = 'none';
                }
            }

            // Add visual feedback for the search and filter inputs
            searchInput.addEventListener('focus', function() {
                this.classList.add('ring-2', 'ring-blue-300');
            });

            searchInput.addEventListener('blur', function() {
                this.classList.remove('ring-2', 'ring-blue-300');
            });

            adviserFilter.addEventListener('focus', function() {
                this.classList.add('ring-2', 'ring-blue-300');
            });

            adviserFilter.addEventListener('blur', function() {
                this.classList.remove('ring-2', 'ring-blue-300');
            });
        });
    </script>

    <!-- Include the modal scripts -->
    @include('admin.clubs.partials.club-modal-scripts')
@endsection
