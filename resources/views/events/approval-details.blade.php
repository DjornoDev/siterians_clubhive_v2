@extends('layouts.dashboard')

@section('title', 'Event Approval Details')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('events.pending') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        ← Back to Pending Events
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2">Event Approval Details</h1>
                </div>
                <div class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    Pending Approval
                </div>
            </div>
        </div>

        <!-- Event Details -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h2 class="text-lg font-semibold text-gray-900">{{ $event->event_name }}</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Event Information</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event Name</dt>
                                <dd class="text-sm text-gray-900">{{ $event->event_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date</dt>
                                <dd class="text-sm text-gray-900">{{ $event->event_date->format('F j, Y') }}</dd>
                            </div>
                            @if ($event->event_time)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Time</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->event_time }}</dd>
                                </div>
                            @endif
                            @if ($event->event_location)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="text-sm text-gray-900">{{ $event->event_location }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Visibility</dt>
                                <dd class="text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $event->event_visibility === 'PUBLIC' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $event->event_visibility === 'PUBLIC' ? 'Public' : 'Club Only' }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Organization Details</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Club</dt>
                                <dd class="text-sm text-gray-900">{{ $event->club->club_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Organizer</dt>
                                <dd class="text-sm text-gray-900">{{ $event->organizer->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Organizer Email</dt>
                                <dd class="text-sm text-gray-900">{{ $event->organizer->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Club Adviser</dt>
                                <dd class="text-sm text-gray-900">{{ $event->club->adviser->name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                                <dd class="text-sm text-gray-900">{{ $event->created_at->format('F j, Y g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Description -->
                @if ($event->event_description)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Description</h3>
                        <div class="bg-gray-50 rounded-md p-4">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $event->event_description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Supporting Documents -->
                @if ($event->supporting_documents)
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-3">Supporting Documents</h3>
                        <div class="bg-gray-50 rounded-md p-4">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $event->supporting_documents_original_name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ strtoupper(pathinfo($event->supporting_documents_original_name, PATHINFO_EXTENSION)) }}
                                        •
                                        {{ number_format($event->supporting_documents_size / 1024, 1) }} KB
                                    </p>
                                </div>
                                <a href="{{ route('events.download-document', $event) }}"
                                    class="ml-4 bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="border-t pt-6">
                    <div class="flex justify-end space-x-4">
                        <button onclick="openRejectModal()"
                            class="bg-red-600 text-white px-6 py-2 rounded-md font-medium hover:bg-red-700">
                            Reject Event
                        </button>

                        <form action="{{ route('events.approve', $event) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to approve this event? Once approved, it will be visible to members.')"
                                class="bg-green-600 text-white px-6 py-2 rounded-md font-medium hover:bg-green-700">
                                Approve Event
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-red-600">Reject Event</h3>

            <form action="{{ route('events.reject', $event) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Please provide a detailed reason for rejecting this event:</p>

                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection
                            Reason</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Explain why this event cannot be approved (minimum 10 characters)..."></textarea>
                    </div>

                    <div class="text-xs text-gray-500">
                        The organizer will receive this rejection notice and can resubmit the event with the necessary
                        changes.
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 border rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal() {
            document.getElementById('reject-modal').classList.remove('hidden');
            document.getElementById('reject-modal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('reject-modal').classList.add('hidden');
            document.getElementById('reject-modal').classList.remove('flex');
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
