@props(['event'])

<!-- Simple Event Card - Full Width Layout -->
<div
    class="bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
    <div class="p-4">
        <!-- Header with Title and Badge -->
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-semibold text-gray-900 hover:text-blue-600 transition-colors flex-1">
                {{ $event->event_name }}
            </h3>
            <div class="ml-4">
                @if ($event->event_visibility === 'PUBLIC')
                    <span
                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                clip-rule="evenodd" />
                        </svg>
                        Public
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Club Only
                    </span>
                @endif
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex gap-6">
            <!-- Date Display -->
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-blue-600 rounded-lg flex flex-col text-white text-center">
                    <div class="bg-white text-blue-600 text-xs font-semibold py-1 rounded-t-lg">
                        {{ $event->event_date->format('M') }}
                    </div>
                    <div class="flex-1 flex items-center justify-center">
                        <span class="text-lg font-bold">{{ $event->event_date->format('d') }}</span>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="flex-1 min-w-0">
                <!-- Event Description -->
                @if ($event->event_description)
                    <p class="text-gray-600 text-sm mb-4">
                        {{ $event->event_description }}
                    </p>
                @endif

                <!-- Event Metadata Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                    <!-- Date and Time -->
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <div class="font-medium">{{ $event->event_date->format('l, M j, Y') }}</div>
                            @if ($event->event_time)
                                <div class="text-blue-600 font-medium">{{ $event->event_time }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Location -->
                    @if ($event->event_location)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <div class="font-medium">Location</div>
                                <div>{{ $event->event_location }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Organizer -->
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <div>
                            <div class="font-medium">Organizer</div>
                            <div>{{ $event->organizer->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supporting Documents Section -->
        @if ($event->documents && $event->documents->count() > 0)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center mb-2">
                    <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h4 class="text-sm font-medium text-gray-900">
                        Event Documents ({{ $event->documents->count() }})
                    </h4>
                </div>

                <div class="space-y-2">
                    @foreach ($event->documents as $document)
                        <div class="flex items-center justify-between p-2 bg-white rounded border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-6 h-6 bg-blue-100 rounded flex items-center justify-center">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-900">
                                        {{ $document->original_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $document->formatted_file_size }}
                                        @if ($document->file_extension)
                                            • {{ strtoupper($document->file_extension) }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('events.download-document', ['event' => $event, 'document' => $document]) }}"
                                class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif ($event->supporting_documents)
            <!-- Legacy single document support -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
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
                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
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
            <div class="mt-4 pt-4 border-t border-gray-200 flex gap-2 justify-end">
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
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </button>
                <button type="button"
                    @click="openDeleteModal({
                            id: {{ json_encode($event->event_id) }},
                            name: {{ json_encode($event->event_name) }}
                        })"
                    class="inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded border border-red-200 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </div>
        @endcan
    </div>
</div>
