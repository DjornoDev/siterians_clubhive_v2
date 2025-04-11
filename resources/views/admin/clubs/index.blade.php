@extends('layouts.dashboard')

@section('title', 'Manage Clubs | ClubHive')

@section('content')
    <div class="p-4">
        <div class="p-4 border-2 rounded-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Club Management</h2>
                <button onclick="toggleClubModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Add Club
                </button>
            </div>

            <!-- Add Club Modal -->
            @include('admin.clubs.partials.add-club-modal')

            <!-- Import View, Edit, Delete Modals -->
            @include('admin.clubs.partials.view-club-modal')
            @include('admin.clubs.partials.edit-club-modal')
            @include('admin.clubs.partials.delete-club-modal')
        </div>

        <!-- Refined Club Cards Grid with Better Proportions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-6">
            @forelse ($clubs as $club)
                <div
                    class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-102 flex flex-col h-full border border-gray-100">
                    <!-- Banner Section - Slightly shorter -->
                    <div class="relative h-40 bg-gray-100">
                        @if ($club->club_banner)
                            <img src="{{ asset(Storage::url($club->club_banner)) }}" alt="{{ $club->club_name }} Banner"
                                class="w-full h-full object-cover">
                        @endif

                        <!-- Logo Overlay - Smaller -->
                        <div class="absolute -bottom-10 left-4">
                            <div
                                class="w-20 h-20 rounded-full border-3 border-white shadow-md bg-white flex items-center justify-center">
                                @if ($club->club_logo)
                                    <img src="{{ asset(Storage::url($club->club_logo)) }}" alt="{{ $club->club_name }} Logo"
                                        class="w-full h-full rounded-full object-cover">
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
                                data-logo="{{ $club->club_logo ? asset(Storage::url($club->club_logo)) : '' }}"
                                data-banner="{{ $club->club_banner ? asset(Storage::url($club->club_banner)) : '' }}">
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

                    viewClubDetails(id, name, description, adviser, logo, banner);
                    console.log(id, name);
                });
            });
        });
    </script>

    <!-- Include the modal scripts -->
    @include('admin.clubs.partials.club-modal-scripts')
@endsection
