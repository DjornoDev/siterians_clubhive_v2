@props(['event'])

<div class="flex-1">
    <div class="flex items-start gap-4">
        <!-- Left Calendar Icon - more visually appealing -->
        <div
            class="hidden sm:flex flex-col flex-shrink-0 items-center justify-center w-16 h-16 bg-gradient-to-b from-indigo-500 to-purple-600 text-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-white text-indigo-600 w-full text-center text-xs font-bold py-1">
                {{ $event->event_date->format('M') }}
            </div>
            <div class="flex items-center justify-center flex-1 w-full">
                <span class="text-xl font-bold">{{ $event->event_date->format('d') }}</span>
            </div>
        </div>

        <!-- Main Content with improved styling -->
        <div class="flex-1">
            <!-- Title with hover effect -->
            <h2 class="text-xl font-bold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors">
                {{ $event->event_name }}
            </h2>

            <!-- Event visibility badge -->
            <div class="mb-2">
                @include('clubs.partials.event-visibility-badge', ['event' => $event])
            </div>

            <!-- Description with better spacing -->
            @if ($event->event_description)
                <p class="text-gray-600 mb-3">{{ $event->event_description }}</p>
            @endif

            <!-- Event details with enhanced icons and formatting -->
            <div class="mt-4 grid sm:grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2 text-gray-600">
                    <div class="rounded-full bg-indigo-100 p-1.5 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-medium">{{ $event->event_date->format('l, F j') }}</span>
                    @if ($event->event_time)
                        <span class="text-indigo-500 font-medium">• {{ $event->event_time }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 text-gray-600">
                    <div class="rounded-full bg-indigo-100 p-1.5 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="font-medium">{{ $event->event_location }}</span>
                </div>
            </div>

            <!-- Organizer with badge-style element -->
            <div
                class="mt-4 inline-flex items-center gap-2 text-sm bg-purple-50 text-purple-700 px-3 py-1.5 rounded-full">
                <div class="rounded-full bg-purple-100 p-1 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-purple-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span class="font-medium">{{ $event->organizer->name }}</span>
            </div>
        </div>
    </div>
</div>

@can('update', $event)
    <div class="flex flex-col sm:flex-row gap-2 self-end sm:self-start mt-4 sm:mt-0">
    <button type="button"
            class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-1.5 border border-transparent hover:border-indigo-200"
            data-event-id="{{ $event->event_id }}" data-event-name="{{ $event->event_name }}"
            data-event-description="{{ $event->event_description }}"
            data-event-date="{{ $event->event_date->format('Y-m-d') }}" data-event-time="{{ $event->event_time }}"
            data-event-location="{{ $event->event_location }}" data-event-visibility="{{ $event->event_visibility }}"
            onclick="openEditModal(this)" aria-label="Edit event">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Edit</span>
        </button>
        <button type="button"
            class="text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-1.5 border border-transparent hover:border-red-200"
            data-event-id="{{ $event->event_id }}" onclick="openDeleteModal(this)" aria-label="Delete event">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Delete</span>
        </button>
    </div>
@endcan
