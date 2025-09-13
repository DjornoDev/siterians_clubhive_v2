@extends('clubs.layouts.navigation')
@section('title', $club->club_name . ' - Voting Management')

@section('club_content')
    <!-- Debug Info -->
    {{-- <div class="bg-red-100 p-4 mb-4">
        <p>Club: {{ $club->club_name ?? 'No Club' }}</p>
        <p>Club ID: {{ $club->club_id ?? 'No ID' }}</p>
        <p>User: {{ Auth::user()->name ?? 'No User' }}</p>

        <p>Role: {{ Auth::user()->role ?? 'No Role' }}</p>
    </div> --}}

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

        <!-- Election Process Information Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Election Creation Info -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-blue-900">Create Election</h3>
                </div>
                <p class="text-sm text-blue-700 mb-3">Set up a new election by providing:</p>
                <ul class="text-xs text-blue-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Election title & description
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        End date for voting period
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Initial status (Draft mode)
                    </li>
                </ul>
            </div>

            <!-- Candidate Management Info -->
            <div class="bg-gradient-to-br from-emerald-50 to-green-100 rounded-xl p-6 border border-emerald-200">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-emerald-900">Add Candidates</h3>
                </div>
                <p class="text-sm text-emerald-700 mb-3">Manage election candidates by:</p>
                <ul class="text-xs text-emerald-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Searching for club members
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Assigning positions & partylist
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Position uniqueness validation
                    </li>
                </ul>
            </div>

            <!-- During Election Info -->
            <div class="bg-gradient-to-br from-amber-50 to-yellow-100 rounded-xl p-6 border border-amber-200">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-amber-900">During Election</h3>
                </div>
                <p class="text-sm text-amber-700 mb-3">Monitor election progress:</p>
                <ul class="text-xs text-amber-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Real-time voting statistics
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Voter participation tracking
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Live results dashboard
                    </li>
                </ul>
            </div>

            <!-- After Election Info -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-100 rounded-xl p-6 border border-purple-200">
                <div class="flex items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-purple-900">Final Results</h3>
                </div>
                <p class="text-sm text-purple-700 mb-3">Access complete election data:</p>
                <ul class="text-xs text-purple-600 space-y-1">
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Official winner declarations
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Export reports (CSV, PDF, Excel)
                    </li>
                    <li class="flex items-center">
                        <svg class="w-3 h-3 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Detailed voting analytics
                    </li>
                </ul>
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

            // Additional election statistics
            $totalVotes = $election ? \App\Models\Vote::where('election_id', $election->election_id)->count() : 0;
            $eligibleVoters = \App\Models\ClubMembership::where('club_id', $club->club_id)
                ->where('club_role', 'MEMBER')
                ->count();
            $votePercentage = $eligibleVoters > 0 ? round(($totalVotes / $eligibleVoters) * 100, 1) : 0;

            // Get recent elections for history
            $recentElections = App\Models\Election::where('club_id', $club->club_id)
                ->where('end_date', '<=', now())
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        @endphp

        <!-- Election Status and Statistics -->
        @if ($hasOngoingElection || $recentElections->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            Election Status & Statistics
                        </h3>
                        @if ($hasOngoingElection)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                                Live Election
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if ($hasOngoingElection)
                        <!-- Current Election Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-200">
                                <div class="text-2xl font-bold text-blue-600">{{ $totalVotes }}</div>
                                <div class="text-sm text-blue-700">Total Votes Cast</div>
                            </div>
                            <div class="bg-emerald-50 rounded-lg p-4 text-center border border-emerald-200">
                                <div class="text-2xl font-bold text-emerald-600">{{ $eligibleVoters }}</div>
                                <div class="text-sm text-emerald-700">Eligible Voters</div>
                            </div>
                            <div class="bg-amber-50 rounded-lg p-4 text-center border border-amber-200">
                                <div class="text-2xl font-bold text-amber-600">{{ $votePercentage }}%</div>
                                <div class="text-sm text-amber-700">Participation Rate</div>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4 text-center border border-purple-200">
                                <div class="text-2xl font-bold text-purple-600">{{ $candidates->count() }}</div>
                                <div class="text-sm text-purple-700">Positions Contested</div>
                            </div>
                        </div>

                        <!-- Election Progress Bar -->
                        <div class="mb-6">
                            <div class="flex justify-between text-sm font-medium text-gray-700 mb-2">
                                <span>Voting Progress</span>
                                <span>{{ $votePercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-emerald-500 h-3 rounded-full transition-all duration-300"
                                    style="width: {{ $votePercentage }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ $totalVotes }} voted</span>
                                <span>{{ $eligibleVoters - $totalVotes }} remaining</span>
                            </div>
                        </div>

                        <!-- Time Remaining -->
                        @if ($election)
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4 border border-orange-200">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-semibold text-orange-800">Election ends on:</div>
                                        <div class="text-sm text-orange-700">
                                            {{ \Carbon\Carbon::parse($election->end_date)->format('F j, Y \a\t g:i A') }}
                                        </div>
                                        <div class="text-xs text-orange-600 mt-1">
                                            {{ \Carbon\Carbon::parse($election->end_date)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($recentElections->count() > 0)
                        <!-- Election History -->
                        <div class="{{ $hasOngoingElection ? 'mt-6 pt-6 border-t border-gray-200' : '' }}">
                            <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Recent Elections
                            </h4>
                            <div class="space-y-3">
                                @foreach ($recentElections as $pastElection)
                                    @php
                                        $pastVotes = \App\Models\Vote::where(
                                            'election_id',
                                            $pastElection->election_id,
                                        )->count();
                                        $pastParticipation =
                                            $eligibleVoters > 0 ? round(($pastVotes / $eligibleVoters) * 100, 1) : 0;
                                    @endphp
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <div class="font-medium text-gray-900">{{ $pastElection->title }}</div>
                                            <div class="text-sm text-gray-600">
                                                Ended {{ \Carbon\Carbon::parse($pastElection->end_date)->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4 text-sm">
                                            <div class="text-center">
                                                <div class="font-semibold text-blue-600">{{ $pastVotes }}</div>
                                                <div class="text-xs text-gray-500">Votes</div>
                                            </div>
                                            <div class="text-center">
                                                <div class="font-semibold text-emerald-600">{{ $pastParticipation }}%
                                                </div>
                                                <div class="text-xs text-gray-500">Turnout</div>
                                            </div>
                                            <div>
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Completed
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Best Practices & Tips -->
        <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-cyan-50 rounded-xl border border-indigo-200 p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="ml-3 text-lg font-semibold text-indigo-900">Election Management Tips</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-indigo-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Before Publishing
                    </h4>
                    <ul class="text-sm text-indigo-700 space-y-1">
                        <li>• Ensure all positions have at least one candidate</li>
                        <li>• Verify candidate information is accurate</li>
                        <li>• Double-check the election end date</li>
                        <li>• Review the election description for clarity</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-indigo-800 mb-2 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        During Election
                    </h4>
                    <ul class="text-sm text-indigo-700 space-y-1">
                        <li>• Monitor voting progress regularly</li>
                        <li>• Check for any technical issues</li>
                        <li>• Encourage student participation</li>
                        <li>• Cannot modify candidates once published</li>
                    </ul>
                </div>
            </div>

            <div class="mt-4 p-3 bg-white rounded-lg border border-indigo-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-500 mr-2 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.232 15.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-amber-800">Important Reminder</p>
                        <p class="text-sm text-amber-700 mt-1">
                            Once an election is published and students start voting, you cannot add, remove, or modify
                            candidates.
                            Make sure everything is set up correctly before publishing.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if ($hasOngoingElection)
            <!-- Current Election Status Alert -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-blue-900">
                                Active Election in Progress
                            </h3>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-2 animate-pulse"></span>
                                Live
                            </span>
                        </div>
                        <p class="text-sm text-blue-700 mt-2">
                            You currently have an active election running. You can manage candidates and monitor progress,
                            but you cannot create a new election until this one ends.
                        </p>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="{{ route('clubs.voting.responses', $club) }}"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                View Live Results
                            </a>
                            <button onclick="openCandidateModal()"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Manage Candidates
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            @if ($election)
                <!-- Election Management Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-white">{{ $election->title }}</h2>
                                <p class="mt-1 text-sm text-blue-100">
                                    Status:
                                    @if ($election->is_published)
                                        <span
                                            class="bg-green-500 text-white px-2 py-1 rounded-full text-xs">Published</span>
                                    @else
                                        <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs">Draft</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <!-- Publish/Unpublish Button -->
                                <button onclick="togglePublishStatus()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white {{ $election->is_published ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $election->is_published ? 'yellow' : 'green' }}-500">
                                    @if ($election->is_published)
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01.814-2.139m15.458 0A9.97 9.97 0 0118.186 12c-.637 1.626-1.567 3.033-2.711 4.175M3 3l18 18M9.878 9.878l4.242 4.242M9.878 9.878A3 3 0 105.121 14.12M14.12 14.12A3 3 0 109.878 9.878M14.12 14.12l4.242 4.242">
                                            </path>
                                        </svg>
                                        Unpublish
                                    @else
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Publish
                                    @endif
                                </button>

                                <!-- Add Candidate Button -->
                                @if ($election->is_published)
                                    <button type="button" disabled
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-400 bg-gray-100 cursor-not-allowed opacity-60">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                            </path>
                                        </svg>
                                        Election Published
                                    </button>
                                    <div class="text-xs text-gray-500 mt-1 max-w-48">
                                        Cannot modify candidates once election is published
                                    </div>
                                @else
                                    <button onclick="openCandidateModal()"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Add Candidates
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if ($candidates->count() > 0)
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
                                                    <div class="flex items-center justify-between">
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
                                                                <p class="font-medium text-gray-900">
                                                                    {{ $candidate->name }}
                                                                </p>
                                                                <div class="flex items-center mt-1">
                                                                    <span
                                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                        {{ $candidate->partylist }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex space-x-1">
                                                            @if ($election->is_published)
                                                                <button type="button" disabled
                                                                    class="text-gray-400 p-1 cursor-not-allowed opacity-50"
                                                                    title="Cannot edit - Election published">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button type="button" disabled
                                                                    class="text-gray-400 p-1 cursor-not-allowed opacity-50"
                                                                    title="Cannot remove - Election published">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <button
                                                                    onclick="editCandidate({{ $candidate->candidate_id }})"
                                                                    class="text-blue-600 hover:text-blue-800 p-1"
                                                                    title="Edit Candidate">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                                <button
                                                                    onclick="removeCandidate({{ $candidate->candidate_id }})"
                                                                    class="text-red-600 hover:text-red-800 p-1"
                                                                    title="Remove Candidate">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div
                                class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-50 to-indigo-100 mb-6 border border-blue-200">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Ready to Add Candidates</h3>
                            <p class="text-gray-600 text-base mb-4">Your election has been created successfully!</p>
                            <div class="max-w-md mx-auto">
                                <div class="bg-blue-50 rounded-lg p-4 mb-6 border border-blue-200">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-2" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-left">
                                            <p class="text-sm font-medium text-blue-800">Next Steps:</p>
                                            <ul class="text-sm text-blue-700 mt-1 space-y-1">
                                                <li>1. Add candidates for each position</li>
                                                <li>2. Assign partylists to candidates</li>
                                                <li>3. Review all information</li>
                                                <li>4. Publish when ready</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <button onclick="openCandidateModal()"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Your First Candidate
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif

        @if ($hasOngoingElection === false)
            <!-- Create Voting Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
                <div class="px-6 py-6 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-white flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create New Election
                            </h3>
                            <p class="mt-2 text-sm text-blue-100">
                                Set up a new democratic election for your club members to participate in.
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
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
                                <label for="description"
                                    class="block text-sm font-medium text-gray-700">Description</label>
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
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

    <!-- Candidate Modal -->
    <div id="candidateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add Candidates</h3>
                    <button onclick="closeCandidateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Information Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Student Eligibility Information</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p class="mb-2"><strong>Students who will appear in search:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Club members without positions in other clubs</li>
                                    <li>Club members not already added as candidates</li>
                                    <li>Club members can run for positions even if they hold a position in THIS club</li>
                                </ul>
                                <p class="mt-3 mb-1"><strong>Students who will NOT appear:</strong></p>
                                <ul class="list-disc list-inside space-y-1 ml-4">
                                    <li>Students with officer positions in other clubs</li>
                                    <li>Students already added as candidates in this election</li>
                                </ul>
                                <p class="mt-3 text-xs">Search works by student name or email address.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="candidateForm" class="space-y-6">
                    @csrf
                    <input type="hidden" name="election_id"
                        value="{{ isset($election) ? $election->election_id : '' }}">

                    <!-- Position Input with Combobox -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <div class="relative" x-data="positionCombobox()">
                            <input type="text" x-model="selectedPosition"
                                @input="filterPositions($event.target.value)" @focus="showDropdown = true"
                                @blur="setTimeout(() => showDropdown = false, 200)" id="position" name="position"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter or select position" required>

                            <!-- Dropdown -->
                            <div x-show="showDropdown && filteredPositions.length > 0"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                                <template x-for="position in filteredPositions" :key="position">
                                    <div @click="selectPosition(position)"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                        <span x-text="position"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Candidates Container -->
                    <div id="candidatesContainer">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">Candidates</h4>
                            <button type="button" onclick="addCandidateRow()"
                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                <svg class="-ml-0.5 mr-1 h-3 w-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Candidate
                            </button>
                        </div>

                        <div id="candidateRows">
                            <!-- Candidate rows will be added here -->
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeCandidateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Candidates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Candidate Modal -->
    <div id="editCandidateModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Candidate</h3>
                    <button onclick="closeEditCandidateModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editCandidateForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="edit-candidate-id" name="candidate_id">

                    <!-- Position Input -->
                    <div>
                        <label for="edit-position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <div class="relative" x-data="editPositionCombobox()">
                            <input type="text" x-model="selectedPosition"
                                @input="filterPositions($event.target.value)" @focus="showDropdown = true"
                                @blur="setTimeout(() => showDropdown = false, 200)" id="edit-position" name="position"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter or select position" required>

                            <!-- Dropdown -->
                            <div x-show="showDropdown && filteredPositions.length > 0"
                                class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                                <template x-for="position in filteredPositions" :key="position">
                                    <div @click="selectPosition(position)"
                                        class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                        <span x-text="position"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Student Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                        <div class="relative">
                            <input type="text" id="edit-student-search"
                                placeholder="Search by name or email (only eligible members shown)..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                onkeyup="editSearchStudents()" onfocus="editShowStudentDropdown()">
                            <input type="hidden" name="user_id" id="edit-user-id">

                            <!-- Student dropdown -->
                            <div id="edit-student-dropdown"
                                class="absolute z-20 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto hidden">
                                <!-- Students will be populated here -->
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Only club members without positions in other clubs are
                                shown</p>
                        </div>
                    </div>

                    <!-- Partylist -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Partylist</label>
                        <input type="text" id="edit-partylist" name="partylist"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Enter partylist name" required>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeEditCandidateModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Candidate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Election status
        const isPublished = @json($election->status === 'published');

        // Popup Notification System
        function showNotification(message, type = 'error') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification-popup');
            existingNotifications.forEach(notification => notification.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `notification-popup fixed top-4 right-4 z-50 max-w-sm w-full bg-white rounded-lg shadow-lg border-l-4 transform transition-all duration-300 ease-in-out translate-x-full`;

            if (type === 'success') {
                notification.className += ' border-green-400';
            } else if (type === 'warning') {
                notification.className += ' border-yellow-400';
            } else {
                notification.className += ' border-red-400';
            }

            notification.innerHTML = `
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            ${type === 'success' ? 
                                '<svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
                                type === 'warning' ?
                                '<svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path></svg>' :
                                '<svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                            }
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                ${type === 'success' ? 'Success' : type === 'warning' ? 'Warning' : 'Error'}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">${message}</p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex">
                            <button onclick="this.closest('.notification-popup').remove()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Check if election is published and show restrictions
        function checkElectionStatus(action) {
            if (isPublished) {
                showNotification(
                    'Cannot modify candidates after election has been published. Please create a new election to make changes.',
                    'warning');
                return false;
            }
            return true;
        }

        // Standard positions for the combobox
        const allStandardPositions = [
            'President',
            'Vice President',
            'Secretary',
            'Treasurer',
            'Auditor',
            'Public Information Officer (PIO)',
            'Protocol Officer',
            'Grade 7 Representative',
            'Grade 8 Representative',
            'Grade 9 Representative',
            'Grade 10 Representative',
            'Grade 11 Representative',
            'Grade 12 Representative'
        ];

        // Get positions that already have candidates
        const occupiedPositions = @json($candidates->keys()->toArray());

        // Filter out positions that already have candidates
        const availablePositions = allStandardPositions.filter(position =>
            !occupiedPositions.includes(position)
        );

        function positionCombobox() {
            return {
                selectedPosition: '',
                showDropdown: false,
                filteredPositions: availablePositions,

                filterPositions(value) {
                    this.filteredPositions = availablePositions.filter(position =>
                        position.toLowerCase().includes(value.toLowerCase())
                    );
                },

                selectPosition(position) {
                    this.selectedPosition = position;
                    this.showDropdown = false;
                    document.getElementById('position').value = position;
                }
            }
        }

        function editPositionCombobox() {
            return {
                selectedPosition: '',
                showDropdown: false,
                filteredPositions: allStandardPositions, // Allow editing to any position

                filterPositions(value) {
                    this.filteredPositions = allStandardPositions.filter(position =>
                        position.toLowerCase().includes(value.toLowerCase())
                    );
                },

                selectPosition(position) {
                    this.selectedPosition = position;
                    this.showDropdown = false;
                    document.getElementById('edit-position').value = position;
                }
            }
        }

        let candidateRowIndex = 0;
        let allStudents = [];
        let studentInfo = null;

        // Fetch all students when page loads
        document.addEventListener('DOMContentLoaded', function() {
            fetchAllStudents();
        });

        async function fetchAllStudents() {
            try {
                const response = await fetch(`{{ route('clubs.voting.search-students', $club) }}`);
                const data = await response.json();
                if (data.success) {
                    allStudents = data.students;
                    studentInfo = data.info;
                    updateStudentInfoDisplay();
                }
            } catch (error) {
                console.error('Error fetching students:', error);
            }
        }

        function updateStudentInfoDisplay() {
            // Update the info section with current statistics
            if (studentInfo) {
                const infoHtml = `
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Current Club Statistics</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Total Club Members:</span> ${studentInfo.total_club_members}
                            </div>
                            <div>
                                <span class="font-medium">Available for Selection:</span> ${studentInfo.available_students}
                            </div>
                        </div>
                        ${studentInfo.students_with_other_positions.length > 0 ? `
                                                    <div class="mt-3">
                                                        <span class="font-medium text-orange-600">Members with positions in other clubs (${studentInfo.students_with_other_positions.length}):</span>
                                                        <ul class="mt-1 text-xs text-gray-600 list-disc list-inside">
                                                            ${studentInfo.students_with_other_positions.map(student => 
                                                                `<li>${student.name} - ${student.club_memberships.map(m => m.club_position + ' in ' + m.club.club_name).join(', ')}</li>`
                                                            ).join('')}
                                                        </ul>
                                                    </div>
                                                ` : ''}
                        ${studentInfo.already_candidates.length > 0 ? `
                                                    <div class="mt-3">
                                                        <span class="font-medium text-blue-600">Already added as candidates (${studentInfo.already_candidates.length}):</span>
                                                        <ul class="mt-1 text-xs text-gray-600 list-disc list-inside">
                                                            ${studentInfo.already_candidates.map(student => 
                                                                `<li>${student.name} - ${student.candidates.map(c => c.position).join(', ')}</li>`
                                                            ).join('')}
                                                        </ul>
                                                    </div>
                                                ` : ''}
                    </div>
                `;

                // Insert this after the blue info box
                const infoSection = document.querySelector('.bg-blue-50');
                if (infoSection && !document.querySelector('#student-stats')) {
                    const statsDiv = document.createElement('div');
                    statsDiv.id = 'student-stats';
                    statsDiv.innerHTML = infoHtml;
                    infoSection.parentNode.insertBefore(statsDiv, infoSection.nextSibling);
                } else if (document.querySelector('#student-stats')) {
                    document.querySelector('#student-stats').innerHTML = infoHtml;
                }
            }
        }

        function openCandidateModal() {
            if (!checkElectionStatus('add candidate')) {
                return;
            }
            document.getElementById('candidateModal').classList.remove('hidden');
            // Add first candidate row
            addCandidateRow();
        }

        function closeCandidateModal() {
            document.getElementById('candidateModal').classList.add('hidden');
            // Reset form
            document.getElementById('candidateForm').reset();
            document.getElementById('candidateRows').innerHTML = '';
            candidateRowIndex = 0;
        }

        function addCandidateRow() {
            const candidateRows = document.getElementById('candidateRows');
            const rowIndex = candidateRowIndex++;

            const candidateRow = document.createElement('div');
            candidateRow.className = 'candidate-row mb-4 p-4 border border-gray-200 rounded-lg';
            candidateRow.id = `candidate-row-${rowIndex}`;

            candidateRow.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Student Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                            <div class="relative">
                                <input type="text" 
                                       id="student-search-${rowIndex}"
                                       placeholder="Search by name or email (only eligible members shown)..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                       onkeyup="searchStudents(${rowIndex})"
                                       onfocus="showStudentDropdown(${rowIndex})">
                                <input type="hidden" name="candidates[${rowIndex}][user_id]" id="user-id-${rowIndex}">
                                
                                <!-- Student dropdown -->
                                <div id="student-dropdown-${rowIndex}" class="absolute z-20 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto hidden">
                                    <!-- Students will be populated here -->
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Only club members without positions in other clubs are shown</p>
                            </div>
                        </div>
                        
                        <!-- Partylist -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Partylist</label>
                            <input type="text" 
                                   name="candidates[${rowIndex}][partylist]" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" 
                                   placeholder="Enter partylist name"
                                   required>
                        </div>
                    </div>
                    
                    <!-- Remove button -->
                    <button type="button" onclick="removeCandidateRow(${rowIndex})" class="ml-4 text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            `;

            candidateRows.appendChild(candidateRow);
        }

        function removeCandidateRow(rowIndex) {
            const row = document.getElementById(`candidate-row-${rowIndex}`);
            if (row) {
                row.remove();
                // Refresh all dropdowns to make the removed student available again
                refreshAllDropdowns(-1); // Use -1 to refresh all dropdowns
            }
        }

        function getSelectedStudentIds() {
            const selectedIds = [];
            const candidateRows = document.querySelectorAll('.candidate-row');

            candidateRows.forEach(row => {
                const userIdInput = row.querySelector('input[name*="[user_id]"]');
                if (userIdInput && userIdInput.value) {
                    selectedIds.push(userIdInput.value);
                }
            });

            return selectedIds;
        }

        function searchStudents(rowIndex) {
            const searchInput = document.getElementById(`student-search-${rowIndex}`);
            const searchValue = searchInput.value.toLowerCase();
            const dropdown = document.getElementById(`student-dropdown-${rowIndex}`);

            if (searchValue.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            // Get already selected student IDs to exclude them from search results
            const selectedStudentIds = getSelectedStudentIds();

            const filteredStudents = allStudents.filter(student =>
                (student.name.toLowerCase().includes(searchValue) ||
                    student.email.toLowerCase().includes(searchValue)) &&
                !selectedStudentIds.includes(student.user_id.toString())
            );

            let dropdownHTML = '';
            filteredStudents.forEach(student => {
                dropdownHTML += `
                    <div onclick="selectStudent(${rowIndex}, ${student.user_id}, '${student.name}')" 
                         class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                ${student.profile_picture ? 
                                    `<img src="/storage/profile_pictures/${student.profile_picture}" class="w-6 h-6 rounded-full object-cover">` :
                                    `<div class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center text-xs text-white">${student.name.charAt(0).toUpperCase()}</div>`
                                }
                            </div>
                            <div class="ml-3">
                                <span class="block font-medium">${student.name}</span>
                                <span class="block text-sm text-gray-500">${student.email}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            dropdown.innerHTML = dropdownHTML;
            dropdown.classList.remove('hidden');
        }

        function showStudentDropdown(rowIndex) {
            const searchInput = document.getElementById(`student-search-${rowIndex}`);
            if (searchInput.value.length >= 2) {
                searchStudents(rowIndex);
            }
        }

        function selectStudent(rowIndex, userId, name) {
            document.getElementById(`student-search-${rowIndex}`).value = name;
            document.getElementById(`user-id-${rowIndex}`).value = userId;
            document.getElementById(`student-dropdown-${rowIndex}`).classList.add('hidden');

            // Refresh other open dropdowns to remove the newly selected student
            refreshAllDropdowns(rowIndex);
        }

        function refreshAllDropdowns(excludeRowIndex) {
            const candidateRows = document.querySelectorAll('.candidate-row');

            candidateRows.forEach(row => {
                const rowId = row.id;
                const currentRowIndex = rowId.split('-')[2]; // Extract row index from 'candidate-row-X'

                // If excludeRowIndex is -1, refresh all dropdowns, otherwise exclude the specified row
                if (excludeRowIndex === -1 || currentRowIndex != excludeRowIndex) {
                    const searchInput = document.getElementById(`student-search-${currentRowIndex}`);
                    const dropdown = document.getElementById(`student-dropdown-${currentRowIndex}`);

                    // If dropdown is open and has search text, refresh it
                    if (searchInput && !dropdown.classList.contains('hidden') && searchInput.value.length >= 2) {
                        searchStudents(currentRowIndex);
                    }
                }
            });
        }

        // Hide dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="student-dropdown-"]');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target) && !event.target.matches('[id^="student-search-"]')) {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // Form submission
        document.getElementById('candidateForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const position = document.getElementById('position').value;
            if (!position) {
                showNotification('Please enter a position', 'warning');
                return;
            }

            const candidateRows = document.querySelectorAll('.candidate-row');
            if (candidateRows.length === 0) {
                showNotification('Please add at least one candidate', 'warning');
                return;
            }

            const candidates = [];
            let isValid = true;

            candidateRows.forEach((row, index) => {
                const userId = row.querySelector(`input[name*="[user_id]"]`).value;
                const partylist = row.querySelector(`input[name*="[partylist]"]`).value;

                if (!userId || !partylist) {
                    isValid = false;
                    return;
                }

                candidates.push({
                    position: position,
                    user_id: userId,
                    partylist: partylist
                });
            });

            if (!isValid) {
                showNotification('Please fill in all candidate information', 'warning');
                return;
            }

            try {
                const response = await fetch(`{{ route('clubs.voting.save-candidate', $club) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        election_id: {{ isset($election) ? $election->election_id : 'null' }},
                        candidates: candidates
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Candidates saved successfully', 'success');
                    closeCandidateModal();
                    setTimeout(() => location.reload(), 1000); // Refresh to show new candidates
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error saving candidates:', error);
                showNotification('An error occurred while saving candidates', 'error');
            }
        });

        // Toggle publish status
        async function togglePublishStatus() {
            try {
                const response = await fetch(`{{ route('clubs.voting.toggle-published', $club) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Election status updated successfully', 'success');
                    setTimeout(() => location.reload(), 1000); // Refresh to show updated status
                } else {
                    showNotification('Error toggling publish status', 'error');
                }
            } catch (error) {
                console.error('Error toggling publish status:', error);
                showNotification('An error occurred while updating status', 'error');
            }
        }

        // Edit candidate
        async function editCandidate(candidateId) {
            if (!checkElectionStatus('edit candidate')) {
                return;
            }

            try {
                // Fetch candidate data
                const response = await fetch(`/clubs/{{ $club->getRouteKey() }}/voting/edit-candidate/${candidateId}`);
                const data = await response.json();

                if (data.success) {
                    // Populate the edit form
                    document.getElementById('edit-candidate-id').value = data.candidate.candidate_id;
                    document.getElementById('edit-position').value = data.candidate.position;
                    document.getElementById('edit-student-search').value = data.candidate.name;
                    document.getElementById('edit-user-id').value = data.candidate.user_id;
                    document.getElementById('edit-partylist').value = data.candidate.partylist;

                    // Show the edit modal
                    document.getElementById('editCandidateModal').classList.remove('hidden');
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error fetching candidate data:', error);
                showNotification('An error occurred while loading candidate data', 'error');
            }
        }

        function closeEditCandidateModal() {
            document.getElementById('editCandidateModal').classList.add('hidden');
            // Reset form
            document.getElementById('editCandidateForm').reset();
        }

        // Edit form student search functions
        function editSearchStudents() {
            const searchInput = document.getElementById('edit-student-search');
            const searchValue = searchInput.value.toLowerCase();
            const dropdown = document.getElementById('edit-student-dropdown');

            if (searchValue.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            const filteredStudents = allStudents.filter(student =>
                student.name.toLowerCase().includes(searchValue) ||
                student.email.toLowerCase().includes(searchValue)
            );

            let dropdownHTML = '';
            filteredStudents.forEach(student => {
                dropdownHTML += `
                    <div onclick="editSelectStudent(${student.user_id}, '${student.name}')" 
                         class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                ${student.profile_picture ? 
                                    `<img src="/storage/profile_pictures/${student.profile_picture}" class="w-6 h-6 rounded-full object-cover">` :
                                    `<div class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center text-xs text-white">${student.name.charAt(0).toUpperCase()}</div>`
                                }
                            </div>
                            <div class="ml-3">
                                <span class="block font-medium">${student.name}</span>
                                <span class="block text-sm text-gray-500">${student.email}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            dropdown.innerHTML = dropdownHTML;
            dropdown.classList.remove('hidden');
        }

        function editShowStudentDropdown() {
            const searchInput = document.getElementById('edit-student-search');
            if (searchInput.value.length >= 2) {
                editSearchStudents();
            }
        }

        function editSelectStudent(userId, name) {
            document.getElementById('edit-student-search').value = name;
            document.getElementById('edit-user-id').value = userId;
            document.getElementById('edit-student-dropdown').classList.add('hidden');
        }

        // Edit form submission
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('editCandidateForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const candidateId = document.getElementById('edit-candidate-id').value;
                const position = document.getElementById('edit-position').value;
                const userId = document.getElementById('edit-user-id').value;
                const partylist = document.getElementById('edit-partylist').value;

                if (!position || !userId || !partylist) {
                    showNotification('Please fill in all fields', 'warning');
                    return;
                }

                try {
                    const response = await fetch(
                        `/clubs/{{ $club->getRouteKey() }}/voting/update-candidate/${candidateId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify({
                                position: position,
                                user_id: userId,
                                partylist: partylist
                            })
                        });

                    const data = await response.json();

                    if (data.success) {
                        showNotification('Candidate updated successfully', 'success');
                        closeEditCandidateModal();
                        setTimeout(() => location.reload(), 1000); // Refresh to show updated candidate
                    } else {
                        showNotification('Error: ' + data.message, 'error');
                    }
                } catch (error) {
                    console.error('Error updating candidate:', error);
                    showNotification('An error occurred while updating candidate', 'error');
                }
            });
        });

        // Remove candidate
        async function removeCandidate(candidateId) {
            if (!checkElectionStatus('remove candidate')) {
                return;
            }

            if (!confirm('Are you sure you want to remove this candidate?')) {
                return;
            }

            try {
                const response = await fetch(
                    `/clubs/{{ $club->getRouteKey() }}/voting/delete-candidate/${candidateId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                const data = await response.json();

                if (data.success) {
                    showNotification('Candidate removed successfully', 'success');
                    setTimeout(() => location.reload(), 1000); // Refresh to show updated candidates
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error removing candidate:', error);
                showNotification('An error occurred while removing candidate', 'error');
            }
        }
    </script>
@endsection
