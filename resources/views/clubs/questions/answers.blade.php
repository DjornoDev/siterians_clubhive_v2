@section('title', $club->club_name . ' - Join Request Answers')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('clubs.people.index', $club) }}" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">People</span>
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('clubs.people.index', $club) }}"
                                class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                People
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-900">Join Request Answers</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Join Request Details</h1>
                        <p class="mt-1 text-sm text-gray-600">Review answers from {{ $joinRequest->user->name }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $joinRequest->status === 'pending'
                            ? 'bg-yellow-100 text-yellow-800'
                            : ($joinRequest->status === 'approved'
                                ? 'bg-green-100 text-green-800'
                                : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($joinRequest->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6">
                <!-- Student Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Student Information</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $joinRequest->user->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $joinRequest->user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Request Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $joinRequest->created_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $joinRequest->status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($joinRequest->status === 'approved'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($joinRequest->status) }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                        @if ($joinRequest->message)
                            <div class="mt-4">
                                <dt class="text-sm font-medium text-gray-500">Optional Message</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $joinRequest->message }}</dd>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Question Answers -->
                @if ($joinRequest->questionAnswers->count() > 0)
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Question Answers</h3>
                        <div class="space-y-6">
                            @foreach ($joinRequest->questionAnswers as $answer)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="mb-3">
                                        <h4 class="text-base font-medium text-gray-900">
                                            {{ $answer->clubQuestion->question }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                   {{ $answer->clubQuestion->question_type === 'text'
                                                       ? 'bg-blue-100 text-blue-800'
                                                       : ($answer->clubQuestion->question_type === 'textarea'
                                                           ? 'bg-green-100 text-green-800'
                                                           : ($answer->clubQuestion->question_type === 'select'
                                                               ? 'bg-purple-100 text-purple-800'
                                                               : 'bg-orange-100 text-orange-800')) }}">
                                                {{ ucfirst($answer->clubQuestion->question_type) }}
                                            </span>
                                            @if ($answer->clubQuestion->is_required)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Required
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-md p-3">
                                        @if ($answer->clubQuestion->question_type === 'textarea')
                                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $answer->answer }}</p>
                                        @else
                                            <p class="text-sm text-gray-900">{{ $answer->answer }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No answers provided</h3>
                        <p class="mt-1 text-sm text-gray-500">This join request was submitted before questions were added to
                            the club.</p>
                    </div>
                @endif
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between">
                    <a href="{{ route('clubs.people.index', $club) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        ← Back to People
                    </a>

                    @if ($joinRequest->status === 'pending')
                        <div class="flex gap-3">
                            <!-- These actions would typically be handled in the people page -->
                            <button type="button" onclick="window.history.back()"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Review in People Tab
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
