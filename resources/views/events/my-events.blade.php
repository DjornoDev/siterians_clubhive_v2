@extends('layouts.dashboard')

@section('title', 'My Events')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">My Events</h1>
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Track and manage your event submissions</p>
                </div>
                <div
                    class="bg-blue-50 text-blue-600 px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium border border-blue-200 text-center sm:text-left">
                    {{ $events->total() }} {{ Str::plural('Event', $events->total()) }}
                </div>
            </div>
        </div>

        <!-- Events List -->
        @if ($events->count() > 0)
            <div class="grid gap-4 sm:gap-6">
                @foreach ($events as $event)
                    <div
                        class="bg-white rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 overflow-hidden">
                        <!-- Event Header -->
                        <div class="p-4 sm:p-6 border-b border-gray-100">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3 mb-2">
                                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">
                                            {{ $event->event_name }}</h2>
                                        @if ($event->approval_status === 'pending')
                                            <span
                                                class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200 w-fit">
                                                <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5"></div>
                                                Pending Review
                                            </span>
                                        @elseif($event->approval_status === 'approved')
                                            <span
                                                class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200 w-fit">
                                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></div>
                                                Approved
                                            </span>
                                        @elseif($event->approval_status === 'rejected')
                                            <span
                                                class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200 w-fit">
                                                <div class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></div>
                                                Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 text-xs sm:text-sm text-gray-600">
                                        <span
                                            class="font-medium text-gray-700 truncate">{{ $event->club->club_name }}</span>
                                        <span class="hidden sm:inline text-gray-400">•</span>
                                        <span>Submitted {{ $event->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                @if ($event->approval_status === 'approved')
                                    <div class="w-full lg:w-auto lg:ml-6">
                                        <a href="{{ route('clubs.events.index', $event->club) }}"
                                            class="inline-flex items-center justify-center w-full lg:w-auto px-3 sm:px-4 py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            <span class="hidden sm:inline">View Event</span>
                                            <span class="sm:hidden">View</span>
                                        </a>
                                    </div>
                                @endif
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

                                    @if ($event->approved_at)
                                        <div class="flex items-center text-xs sm:text-sm">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-2 sm:mr-3 flex-shrink-0"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-gray-600 truncate">Approved
                                                {{ $event->approved_at->diffForHumans() }}</span>
                                        </div>
                                    @endif
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

                            <!-- Status Messages -->
                            @if ($event->approval_status === 'rejected' && $event->rejection_reason)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-400 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium text-red-800 mb-1 text-sm sm:text-base">Event Rejected
                                            </h4>
                                            <p class="text-xs sm:text-sm text-red-700 mb-2">{{ $event->rejection_reason }}
                                            </p>
                                            <p class="text-xs text-red-600">You can create a new event with the necessary
                                                changes.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($event->approval_status === 'pending')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                                    <div class="flex items-start">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 mt-0.5 mr-2 sm:mr-3 flex-shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-medium text-yellow-800 mb-1 text-sm sm:text-base">Awaiting
                                                Review</h4>
                                            <p class="text-xs sm:text-sm text-yellow-700">Your event is being reviewed by
                                                the SSLG
                                                adviser. You'll receive a notification once it's processed.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($events->hasPages())
                <div class="bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                            Showing {{ $events->firstItem() }} to {{ $events->lastItem() }} of {{ $events->total() }}
                            results
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg border border-gray-200 p-8 sm:p-12 text-center">
                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-400 mx-auto mb-3 sm:mb-4" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Events Created</h3>
                <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base">You haven't created any events yet. Start by
                    creating an event for one of
                    your
                    clubs.</p>
                <a href="{{ route('clubs.index') }}"
                    class="inline-block bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium hover:bg-blue-700 transition-colors">
                    Browse Clubs
                </a>
            </div>
        @endif
    </div>
@endsection
