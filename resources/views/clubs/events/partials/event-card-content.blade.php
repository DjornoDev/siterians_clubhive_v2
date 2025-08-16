@props(['event'])

<!-- Enhanced Event Card with Modern Design -->
<div
    class="relative bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-gray-200 overflow-hidden group">
    <!-- Header Section with Status Indicator -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

    <!-- Event Visibility Badge -->
    <div class="absolute top-3 right-3 z-10">
        @if ($event->event_visibility === 'PUBLIC')
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                        clip-rule="evenodd" />
                </svg>
                Public
            </span>
        @else
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                        clip-rule="evenodd" />
                </svg>
                Club Only
            </span>
        @endif
    </div>

    <div class="p-6">
        <!-- Main Content Area -->
        <div class="flex gap-4">
            <!-- Date Display Calendar -->
            <div class="flex-shrink-0">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg shadow-md overflow-hidden flex flex-col text-white">
                    <div class="bg-white text-indigo-600 text-xs font-bold py-1 text-center">
                        {{ $event->event_date->format('M') }}
                    </div>
                    <div class="flex-1 flex items-center justify-center">
                        <span class="text-lg font-bold">{{ $event->event_date->format('d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="flex-1 min-w-0">
                <!-- Event Title -->
                <h3
                    class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-200">
                    {{ $event->event_name }}
                </h3>

                <!-- Event Description -->
                @if ($event->event_description)
                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                        {{ Str::limit($event->event_description, 100) }}
                    </p>
                @endif

                <!-- Event Metadata Grid -->
                <div class="space-y-2">
                    <!-- Date and Time -->
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="font-medium">{{ $event->event_date->format('l, M j, Y') }}</span>
                        @if ($event->event_time)
                            <span class="ml-2 text-indigo-600 font-medium">at {{ $event->event_time }}</span>
                        @endif
                    </div>

                    <!-- Location -->
                    @if ($event->event_location)
                        <div class="flex items-center text-sm text-gray-600">
                            <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center mr-3">
                                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <span class="font-medium">{{ $event->event_location }}</span>
                        </div>
                    @endif

                    <!-- Organizer -->
                    <div class="flex items-center text-sm text-gray-600">
                        <div class="w-5 h-5 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <svg class="w-3 h-3 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="font-medium">{{ $event->organizer->name }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supporting Documents Section -->
        @if ($event->supporting_documents)
            <div class="mt-4 p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $event->supporting_documents_original_name ?? 'Supporting Document' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                @if ($event->supporting_documents_size)
                                    {{ number_format($event->supporting_documents_size / 1024, 1) }} KB
                                @endif
                                @if ($event->supporting_documents_mime_type)
                                    •
                                    {{ strtoupper(pathinfo($event->supporting_documents_original_name, PATHINFO_EXTENSION)) }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('events.download-document', $event) }}"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        @can('update', $event)
            <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
                <button type="button"
                    @click="openEditModal({
                            id: {{ json_encode($event->event_id) }},
                            name: {{ json_encode($event->event_name) }},
                            description: {{ json_encode($event->event_description) }},
                            date: {{ json_encode($event->event_date->format('Y-m-d')) }},
                            time: {{ json_encode($event->event_time) }},
                            location: {{ json_encode($event->event_location) }},
                            visibility: {{ json_encode($event->event_visibility) }}
                        })"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Event
                </button>
                <button type="button"
                    @click="openDeleteModal({
                            id: {{ json_encode($event->event_id) }},
                            name: {{ json_encode($event->event_name) }}
                        })"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        @endcan
    </div>

    <!-- Hover Effect Overlay -->
    <div
        class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 to-purple-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none rounded-xl">
    </div>
</div>
