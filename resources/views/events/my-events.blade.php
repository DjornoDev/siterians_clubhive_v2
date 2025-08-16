@extends('layouts.dashboard')

@section('title', 'My Events')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Events</h1>
                    <p class="text-gray-600 mt-1">Track the status of events you've created</p>
                </div>
                <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $events->total() }} Events
                </div>
            </div>
        </div>

        <!-- Events List -->
        @if ($events->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach ($events as $event)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $event->event_name }}</h3>
                                        @if ($event->approval_status === 'pending')
                                            <span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Pending Approval
                                            </span>
                                        @elseif($event->approval_status === 'approved')
                                            <span
                                                class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Approved
                                            </span>
                                        @elseif($event->approval_status === 'rejected')
                                            <span
                                                class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">
                                                Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-2 space-y-1">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Club:</span> {{ $event->club->club_name }}
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
                                        @if ($event->approved_at)
                                            <p class="text-sm text-gray-600">
                                                <span class="font-medium">Approved:</span>
                                                {{ $event->approved_at->format('F j, Y g:i A') }}
                                            </p>
                                        @endif
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

                                    @if ($event->approval_status === 'rejected' && $event->rejection_reason)
                                        <div class="mt-4 bg-red-50 border border-red-200 rounded-md p-3">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-red-800">Event Rejected</h3>
                                                    <div class="mt-2 text-sm text-red-700">
                                                        <p>{{ $event->rejection_reason }}</p>
                                                    </div>
                                                    <div class="mt-3">
                                                        <p class="text-xs text-red-600">You can create a new event with the
                                                            necessary changes.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($event->approval_status === 'pending')
                                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <h3 class="text-sm font-medium text-yellow-800">Awaiting Approval</h3>
                                                    <div class="mt-2 text-sm text-yellow-700">
                                                        <p>Your event is pending review by the SSLG adviser. You'll be
                                                            notified once it's processed.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if ($event->approval_status === 'approved')
                                    <div class="ml-4">
                                        <a href="{{ route('clubs.events.index', $event->club) }}"
                                            class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                                            View in Club
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t rounded-lg shadow">
                {{ $events->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Events Created</h3>
                <p class="text-gray-600 mb-4">You haven't created any events yet. Start by creating an event for one of your
                    clubs.</p>
                <a href="{{ route('clubs.index') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                    Browse Clubs
                </a>
            </div>
        @endif
    </div>
@endsection
