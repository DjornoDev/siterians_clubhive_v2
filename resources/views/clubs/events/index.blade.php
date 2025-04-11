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

        <div class="grid gap-6 mb-8">
            @forelse ($events as $event)
                <div
                    class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="hidden sm:flex flex-shrink-0 items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $event->event_name }}</h2>
                                        <p class="text-gray-600">{{ $event->event_description }}</p>

                                        <div class="mt-4 grid sm:grid-cols-2 gap-2 text-sm text-gray-500">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $event->event_date->format('F j') }}</span>
                                                @if ($event->event_time)
                                                    <span>• {{ $event->event_time }}</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span>{{ $event->event_location }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>Organized by {{ $event->organizer->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @can('update', $event)
                                <div class="flex space-x-3 self-end sm:self-start">
                                    <button type="button"
                                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-2 rounded-lg transition-colors flex items-center gap-1"
                                        data-event-id="{{ $event->event_id }}" data-event-name="{{ $event->event_name }}"
                                        data-event-description="{{ $event->event_description }}"
                                        data-event-date="{{ $event->event_date->format('Y-m-d') }}"
                                        data-event-time="{{ $event->event_time }}"
                                        data-event-location="{{ $event->event_location }}" onclick="openEditModal(this)"
                                        aria-label="Edit event">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <button type="button"
                                        class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-colors flex items-center gap-1"
                                        data-event-id="{{ $event->event_id }}" onclick="openDeleteModal(this)"
                                        aria-label="Delete event">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
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
            @endforelse
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>

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
