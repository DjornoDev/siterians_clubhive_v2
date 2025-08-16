@extends('layouts.dashboard')

@section('title', 'Pending Events for Approval')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pending Events for Approval</h1>
                    <p class="text-gray-600 mt-1">Review and approve event requests from all clubs</p>
                </div>
                <div class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $pendingEvents->total() }} Pending
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pending Events List -->
        @if ($pendingEvents->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach ($pendingEvents as $event)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->event_name }}</h3>
                                        <span
                                            class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                            Pending
                                        </span>
                                    </div>

                                    <div class="mt-2 space-y-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Club:</span> {{ $event->club->club_name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Organizer:</span> {{ $event->organizer->name }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Date:</span>
                                            {{ $event->event_date->format('F j, Y') }}
                                            @if ($event->event_time)
                                                at {{ $event->event_time }}
                                            @endif
                                        </p>
                                        @if ($event->event_location)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Location:</span> {{ $event->event_location }}
                                            </p>
                                        @endif
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Visibility:</span>
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $event->event_visibility === 'PUBLIC' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $event->event_visibility === 'PUBLIC' ? 'Public' : 'Club Only' }}
                                            </span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Submitted:</span>
                                            {{ $event->created_at->format('F j, Y g:i A') }}
                                        </p>
                                    </div>

                                    @if ($event->event_description)
                                        <div class="mt-3">
                                            <p class="text-sm text-gray-700">
                                                {{ Str::limit($event->event_description, 200) }}</p>
                                        </div>
                                    @endif

                                    @if ($event->supporting_documents)
                                        <div class="mt-3">
                                            <a href="{{ route('events.download-document', $event) }}"
                                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                                Download Supporting Document
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col space-y-2 ml-4">
                                    <a href="{{ route('events.approval.show', $event) }}"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 text-center">
                                        Review Details
                                    </a>

                                    <!-- Quick Actions -->
                                    <div class="flex space-x-2">
                                        <form action="{{ route('events.approve', $event) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to approve this event?')"
                                                class="bg-green-600 text-white px-3 py-1 rounded text-xs font-medium hover:bg-green-700">
                                                Quick Approve
                                            </button>
                                        </form>

                                        <button onclick="openRejectModal({{ $event->event_id }})"
                                            class="bg-red-600 text-white px-3 py-1 rounded text-xs font-medium hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t rounded-lg shadow">
                {{ $pendingEvents->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Events</h3>
                <p class="text-gray-600">All events have been processed. New event requests will appear here for your
                    review.</p>
            </div>
        @endif
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4 text-red-600">Reject Event</h3>

            <form id="reject-form" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <p class="text-sm text-gray-600">Please provide a reason for rejecting this event:</p>

                    <div>
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection
                            Reason</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            placeholder="Explain why this event cannot be approved..."></textarea>
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
        function openRejectModal(eventId) {
            const modal = document.getElementById('reject-modal');
            const form = document.getElementById('reject-form');
            form.action = `/events/${eventId}/reject`;
            modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            const modal = document.getElementById('reject-modal');
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
