@extends('layouts.dashboard')

@section('title', 'Pending Events for Approval')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pending Events</h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Review and approve event requests from all clubs</p>
                </div>
                <div
                    class="bg-orange-50 text-orange-600 px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium border border-orange-200 text-center sm:text-left">
                    {{ $pendingEvents->total() }} {{ Str::plural('Request', $pendingEvents->total()) }}
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4">
                <div class="flex items-start">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                <div class="flex items-start">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400 mt-0.5 mr-2 sm:mr-3 flex-shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-xs sm:text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Pending Events List -->
        @if ($pendingEvents->count() > 0)
            <div class="grid gap-4 sm:gap-6">
                @foreach ($pendingEvents as $event)
                    <div
                        class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 overflow-hidden">
                        <!-- Event Header -->
                        <div class="p-4 sm:p-6 border-b border-gray-100">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">
                                            {{ $event->event_name }}</h2>
                                        <span
                                            class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 w-fit">
                                            <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></div>
                                            Awaiting Review
                                        </span>
                                    </div>

                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 text-xs sm:text-sm text-gray-600">
                                        <span
                                            class="font-medium text-gray-700 truncate">{{ $event->club->club_name }}</span>
                                        <span class="hidden sm:inline text-gray-400">•</span>
                                        <span class="truncate">By {{ $event->organizer->name }}</span>
                                        <span class="hidden sm:inline text-gray-400">•</span>
                                        <span>{{ $event->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 w-full lg:w-auto lg:ml-6">
                                    <a href="{{ route('events.approval.show', $event) }}"
                                        class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Review Details</span>
                                        <span class="sm:hidden">Review</span>
                                    </a>

                                    <div class="flex gap-2">
                                        <form action="{{ route('events.approve', $event) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to approve this event?')"
                                                class="w-full inline-flex items-center justify-center px-2 sm:px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition-colors">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Approve</span>
                                            </button>
                                        </form>

                                        <button onclick="openRejectModal({{ $event->event_id }})"
                                            class="flex-1 inline-flex items-center justify-center px-2 sm:px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Reject</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Event Content -->
                        <div class="p-4 sm:p-6">
                            <!-- Event Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-4">
                                <div class="space-y-2 sm:space-y-3">
                                    <div class="flex items-center text-xs sm:text-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600 truncate">
                                            {{ $event->event_date->format('F j, Y') }}
                                            @if ($event->event_time)
                                                <span class="hidden sm:inline">at</span> {{ $event->event_time }}
                                            @endif
                                        </span>
                                    </div>

                                    @if ($event->event_location)
                                        <div class="flex items-center text-xs sm:text-sm">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-gray-600 truncate">{{ $event->event_location }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-2 sm:space-y-3">
                                    <div class="flex items-center text-xs sm:text-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        <span class="text-gray-600">
                                            {{ $event->event_visibility === 'PUBLIC' ? 'Public Event' : 'Club Members Only' }}
                                        </span>
                                    </div>

                                    <div class="flex items-center text-xs sm:text-sm">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-gray-600 truncate">Submitted
                                            {{ $event->created_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Description -->
                            @if ($event->event_description)
                                <div class="mb-4">
                                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base">
                                        {{ Str::limit($event->event_description, 300) }}
                                    </p>
                                </div>
                            @endif

                            <!-- Event Documents -->
                            @if ($event->documents && $event->documents->count() > 0)
                                <div class="mb-4">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-700">
                                                Event Documents ({{ $event->documents->count() }})
                                            </span>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach ($event->documents as $document)
                                                <div
                                                    class="flex items-center justify-between bg-white rounded-md p-3 border border-blue-200">
                                                    <div class="flex items-center min-w-0 flex-1">
                                                        <div class="bg-blue-100 rounded p-1 mr-3">
                                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                                {{ $document->original_name }}</p>
                                                            <p class="text-xs text-gray-500">
                                                                {{ $document->formatted_file_size }} •
                                                                {{ strtoupper($document->file_extension) }}</p>
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('events.documents.download', $document) }}"
                                                        class="ml-3 inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-4-4m4 4l4-4m6 8a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        Download
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @elseif ($event->supporting_documents)
                                <!-- Legacy single document support -->
                                <div class="mb-4">
                                    <a href="{{ route('events.download-document', $event) }}"
                                        class="inline-flex items-center px-2 sm:px-3 py-2 bg-gray-50 border border-gray-200 rounded-md text-xs sm:text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 text-gray-500 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Download Supporting Document</span>
                                        <span class="sm:hidden">Download Document</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($pendingEvents->hasPages())
                <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                            Showing {{ $pendingEvents->firstItem() }} to {{ $pendingEvents->lastItem() }} of
                            {{ $pendingEvents->total() }} results
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $pendingEvents->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg border border-gray-200 p-8 sm:p-12 text-center">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">All Events Reviewed</h3>
                <p class="text-gray-600 text-sm sm:text-base">No pending events for approval. New event requests will
                    appear here for your review.</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-sm sm:max-w-md">
                <h3 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 text-red-600">Reject Event</h3>

                <form id="reject-form" method="POST" action="">
                    @csrf
                    <div class="space-y-3 sm:space-y-4">
                        <p class="text-xs sm:text-sm text-gray-600">Please provide a reason for rejecting this event:</p>

                        <div>
                            <label for="rejection_reason"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Rejection
                                Reason</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                                class="w-full text-xs sm:text-sm rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                placeholder="Explain why this event cannot be approved..."></textarea>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                        <button type="button" onclick="closeRejectModal()"
                            class="w-full sm:w-auto px-3 sm:px-4 py-2 border rounded-md hover:bg-gray-50 text-xs sm:text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                            class="w-full sm:w-auto px-3 sm:px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-xs sm:text-sm">
                            Reject Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(eventId) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            form.action = `/events/${eventId}/reject`;
            modal.style.display = 'block';
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
            modal.style.display = 'none';
            modal.classList.add('hidden');
            document.getElementById('rejection_reason').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('reject-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
@endsection
