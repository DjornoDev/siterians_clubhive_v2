@extends('clubs.layouts.navigation')
@section('title', $club->club_name . ' - Voting Management')

@section('club_content')
    <!-- Debug Info -->
    <div class="bg-red-100 p-4 mb-4">
        <p>Club: {{ $club->club_name ?? 'No Club' }}</p>
        <p>Club ID: {{ $club->club_id ?? 'No ID' }}</p>
        <p>User: {{ Auth::user()->name ?? 'No User' }}</p>
        <p>Role: {{ Auth::user()->role ?? 'No Role' }}</p>
    </div>

    <div class="bg-blue-100 p-4 mb-4">
        <h1 class="text-2xl font-bold">Voting Management Page</h1>
        <p>This is a test to see if the view is rendering.</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8 border-b border-gray-200 pb-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Manage Voting
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">
                        Create and manage votings for your students to participate in.
                    </p>
                </div>
            </div>
        </div>

        @php
            // Get the current election and its candidates upfront
            $election = App\Models\Election::where('club_id', $club->club_id)
                ->where('end_date', '>', now())
                ->latest()
                ->first();

            $hasOngoingElection = !is_null($election);

            $candidates = $election
                ? App\Models\Candidate::where('election_id', $election->election_id)
                    ->join('tbl_users', 'tbl_candidates.user_id', '=', 'tbl_users.user_id')
                    ->select('tbl_candidates.*', 'tbl_users.name', 'tbl_users.profile_picture')
                    ->orderBy('position')
                    ->get()
                    ->groupBy('position')
                : collect();
        @endphp

        @if ($hasOngoingElection)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            There is an ongoing election. You can create a new voting once the current election has ended.
                        </p>
                    </div>
                </div>
            </div>

            @if ($election && $candidates->count() > 0)
                <!-- Candidates List Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Current Election Candidates</h2>
                        <p class="mt-1 text-sm text-blue-100">
                            @if ($election->is_published)
                                View all candidates for the ongoing election
                            @else
                                These candidates will be visible to voters once you publish the election
                            @endif
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($candidates as $position => $positionCandidates)
                                <div
                                    class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                                    <div
                                        class="bg-gradient-to-r from-indigo-50 to-blue-50 px-4 py-3 border-b border-gray-200">
                                        <h3 class="text-lg font-semibold text-gray-800">{{ $position }}</h3>
                                    </div>
                                    <div class="divide-y divide-gray-200">
                                        @foreach ($positionCandidates as $candidate)
                                            <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                                <div class="flex items-center space-x-4">
                                                    <div class="flex-shrink-0">
                                                        @if ($candidate->profile_picture)
                                                            <img src="{{ asset('storage/profile_pictures/' . $candidate->profile_picture) }}"
                                                                alt="{{ $candidate->name }} profile"
                                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                        @else
                                                            <div
                                                                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-lg">
                                                                {{ strtoupper(substr($candidate->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $candidate->name }}</p>
                                                        <div class="flex items-center mt-1">
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $candidate->partylist }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8 bg-white rounded-xl shadow-lg mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-lg">No candidates found for the current election.</p>
                    <p class="text-gray-400 text-sm mt-1">Use the + button below to add candidates</p>
                </div>
            @endif
        @endif

        @if ($hasOngoingElection === false)
            <!-- Create Voting Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg divide-y divide-gray-200">
                <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-blue-700">
                    <h3 class="text-lg leading-6 font-medium text-white">Create New Voting</h3>
                    <p class="mt-1 text-sm text-blue-100">
                        Fill out this form to create a new voting opportunity for your students.
                    </p>
                </div>
                <div class="px-6 py-5">
                    <form action="{{ route('clubs.voting.store', $club) }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <div class="mt-1">
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Enter a descriptive title for the voting">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">The title should clearly indicate what the voting is
                                    about.</p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <div class="mt-1">
                                    <textarea name="description" id="description" rows="4" required
                                        class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Provide detailed information about this voting">{{ old('description') }}</textarea>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Explain the purpose of the voting and any relevant
                                    instructions for participants.</p>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                <div class="mt-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                        required min="{{ date('Y-m-d') }}"
                                        class="pl-10 shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">The date when the voting will end and results will be
                                    available.</p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-5 border-t border-gray-200">
                            <button type="reset"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                Reset Form
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Voting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
