@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Club Events</h1>
            @can('create', [App\Models\Event::class, $club])
                <button type="button" onclick="openCreateModal()"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 
                           flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Create Event
                </button>
            @endcan
        </div>

        @include('clubs.events.partials.create-event-modal')
        @include('clubs.events.partials.edit-event-modal')
        @include('clubs.events.partials.delete-event-modal')

        @if ($todayEvents->isEmpty() && $upcomingEvents->isEmpty())
            @can('create', [App\Models\Event::class, $club])
                <div class="bg-white rounded-xl shadow-md p-8 text-center border border-dashed border-gray-300">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="bg-gray-100 text-gray-500 p-4 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-medium text-gray-900 mb-1">No events yet</h3>
                            <p class="text-gray-500 mb-4">Create your first event for this club!</p>
                            <button type="button" onclick="openCreateModal()"
                                class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 
                                      shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Create an Event
                            </button>
                        </div>
                    </div>
                </div>
            @endcan
        @else
            {{-- Today's Events Section --}}
            @if ($todayEvents->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-2">Today's Events</h2>
                    <div class="grid gap-6">
                        @foreach ($todayEvents as $event)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100">
                                <div class="p-6">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                                        {{-- Event Content --}}
                                        @include('clubs.events.partials.event-card-content', [
                                            'event' => $event,
                                        ])
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Upcoming Events Section --}}
            @if ($upcomingEvents->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b border-gray-200 pb-2">Upcoming Events</h2>
                    <div class="grid gap-6">
                        @foreach ($upcomingEvents as $event)
                            <div
                                class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100">
                                <div class="p-6">
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                                        {{-- Event Content --}}
                                        @include('clubs.events.partials.event-card-content', [
                                            'event' => $event,
                                        ])
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $upcomingEvents->links() }}
                    </div>
                </div>
            @endif
        @endif

        <!-- JavaScript for modals -->
        <script>
            function openCreateModal() {
                const modal = document.getElementById('create-event-modal');
                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');

                // Focus on first input when modal opens
                setTimeout(() => {
                    modal.querySelector('input[type="text"]').focus();
                }, 100);

                // Prevent body scroll
                document.body.style.overflow = 'hidden';
            }

            function openEditModal(button) {
                const modal = document.getElementById('edit-event-modal');
                const eventId = button.dataset.eventId;

                // Set form action
                const form = modal.querySelector('form');
                form.action = `/clubs/{{ $club->club_id }}/events/${eventId}`;

                // Populate fields
                modal.querySelector('#event_id').value = eventId;
                modal.querySelector('#event_name').value = button.dataset.eventName;
                modal.querySelector('#event_description').value = button.dataset.eventDescription;
                modal.querySelector('#event_date').value = button.dataset.eventDate;
                modal.querySelector('#event_time').value = button.dataset.eventTime;
                modal.querySelector('#event_location').value = button.dataset.eventLocation;

                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');

                // Focus on first input when modal opens
                setTimeout(() => {
                    modal.querySelector('input[type="text"]').focus();
                }, 100);

                // Prevent body scroll
                document.body.style.overflow = 'hidden';
            }

            function openDeleteModal(button) {
                const modal = document.getElementById('delete-event-modal');
                const eventId = button.dataset.eventId;

                // Set form action
                const form = modal.querySelector('form');
                form.action = `/clubs/{{ $club->club_id }}/events/${eventId}`;

                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');

                // Focus on confirm button
                setTimeout(() => {
                    modal.querySelector('button[type="submit"]').focus();
                }, 100);

                // Prevent body scroll
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                modal.classList.add('hidden');
                modal.setAttribute('aria-hidden', 'true');

                // Re-enable body scroll
                document.body.style.overflow = 'auto';
            }

            // Close modal on escape key press
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    document.querySelectorAll('.modal').forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            const modalId = modal.id;
                            closeModal(modalId);
                        }
                    });
                }
            });

            // Close modal when clicking outside content
            document.addEventListener('click', function(event) {
                document.querySelectorAll('.modal').forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        const modalContent = modal.querySelector('.modal-content');
                        if (!modalContent.contains(event.target) && modal.contains(event.target)) {
                            const modalId = modal.id;
                            closeModal(modalId);
                        }
                    }
                });
            });
        </script>
    </div>
@endsection
