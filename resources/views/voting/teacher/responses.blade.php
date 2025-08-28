@extends('clubs.layouts.navigation')
@section('title', $club->club_name . ' - Voting Responses')

@section('club_content')
    <div class="bg-white shadow rounded-lg p-6">
        <!-- Dashboard Header with Election Selector -->
        <div
            class="bg-gradient-to-r from-blue-700 to-indigo-800 -mx-6 -mt-6 px-6 pt-8 pb-8 mb-6 text-white rounded-t-lg shadow-md">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-3xl font-bold mb-1">Election Dashboard</h2>
                    <p class="text-blue-100">Track voting results and participation stats</p>
                </div>
                <div class="w-full md:w-64 relative">
                    <label for="election-selector" class="block text-sm font-medium text-blue-100 mb-2">Select
                        Election</label>
                    <div class="relative">
                        <select id="election-selector"
                            class="form-select w-full pl-4 pr-10 py-2 rounded-md shadow-sm border-0
                        bg-white/90 backdrop-blur-sm text-gray-800 font-medium
                        focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Loading elections...</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div class="mt-4 flex justify-end">
                <button id="export-csv-btn"
                    class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Results to CSV
                </button>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="hidden">
            <div class="flex flex-col items-center justify-center py-16">
                <div class="animate-spin rounded-full h-16 w-16 border-t-3 border-b-3 border-blue-600 mb-4"></div>
                <p class="text-gray-500 text-lg">Loading election data...</p>
            </div>
        </div>

        <!-- Main dashboard content -->
        <div id="dashboard-content" class="hidden">
            <!-- Summary stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="relative bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200 shadow-sm overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center mb-2">
                            <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800">Total Votes</h3>
                        </div>
                        <p class="text-4xl font-bold text-blue-600 mb-1" id="total-votes">0</p>
                        <div class="text-sm text-gray-600 flex items-center">
                            <div class="flex items-center text-blue-700 mr-2">
                                <span id="vote-percentage" class="font-medium">0%</span>
                            </div>
                            participation rate
                        </div>
                    </div>
                    <div class="absolute right-0 bottom-0 -mb-8 -mr-10 opacity-10">
                        <svg class="h-32 w-32 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M2 10c0-3.967 3.69-7 8-7 4.31 0 8 3.033 8 7s-3.69 7-8 7a9.165 9.165 0 01-1.504-.123 5.976 5.976 0 01-3.935 1.107.75.75 0 01-.584-1.143 3.478 3.478 0 00.522-1.756C2.979 13.825 2 12.025 2 10z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="relative bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border border-green-200 shadow-sm overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center mb-2">
                            <svg class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800">Eligible Voters</h3>
                        </div>
                        <p class="text-4xl font-bold text-green-600 mb-1" id="eligible-voters">0</p>
                        <div class="text-sm text-gray-600" id="remaining-voters">0 haven't voted yet</div>
                    </div>
                    <div class="absolute right-0 bottom-0 -mb-8 -mr-10 opacity-10">
                        <svg class="h-32 w-32 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="relative bg-gradient-to-br from-amber-50 to-amber-100 p-6 rounded-xl border border-amber-200 shadow-sm overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center mb-2">
                            <svg class="h-6 w-6 text-amber-500 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-800">Time Remaining</h3>
                        </div>
                        <p class="text-4xl font-bold text-amber-600 mb-1" id="time-remaining">--:--:--</p>
                        <div class="text-sm text-gray-600" id="end-date">Ends: --</div>
                    </div>
                    <div class="absolute right-0 bottom-0 -mb-8 -mr-10 opacity-10">
                        <svg class="h-32 w-32 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Results by Position -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Results by Position
                    </h2>
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700">
                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Auto-refreshing
                        </span>
                    </div>
                </div>
                <div id="results-container" class="space-y-8">
                    <!-- Results will be populated here dynamically -->
                </div>
            </div>

            <!-- Recent Voting Activity -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Recent Voting Activity
                    </h2>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Student</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vote Time</th>
                                </tr>
                            </thead>
                            <tbody id="recent-votes-table" class="divide-y divide-gray-200 bg-white">
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">No voting activity yet
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- No data message -->
        <div id="no-data-message" class="hidden">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-10 text-center mt-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-500 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Election Data Available</h3>
                <p class="text-gray-600 max-w-md mx-auto">No responses yet. Create an election and add candidates to get
                    started with monitoring your voting results.</p>
            </div>
        </div>
    </div>

    <!-- Chart.js library for graphs -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS for dashboard -->
    <style>
        /* Custom scrollbar for better UX */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth animations */
        .chart-canvas,
        .dashboard-card {
            transition: all 0.3s ease;
        }

        /* Loading animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Chart center text positioning */
        .relative.aspect-square {
            position: relative;
        }

        .chart-center-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 2;
            pointer-events: none;
            width: 100%;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const electionSelector = document.getElementById('election-selector');
            const dashboardContent = document.getElementById('dashboard-content');
            const loadingIndicator = document.getElementById('loading-indicator');
            const noDataMessage = document.getElementById('no-data-message');
            let refreshInterval;
            let currentElection = null;
            let resultsCharts = {}; // To store chart instances

            loadElections();

            // Event listener for election selection
            electionSelector.addEventListener('change', function() {
                const electionId = this.value;
                if (electionId) {
                    loadElectionData(electionId);
                } else {
                    dashboardContent.classList.add('hidden');
                    noDataMessage.classList.remove('hidden');
                    clearInterval(refreshInterval);
                }
            });

            async function loadElections() {
                try {
                    loadingIndicator.classList.remove('hidden');

                    const response = await fetch('{{ route('clubs.voting.elections', $club) }}');
                    const data = await response.json();

                    if (data.success && data.elections.length > 0) {
                        electionSelector.innerHTML = '';

                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select an election';
                        electionSelector.appendChild(defaultOption);

                        data.elections.forEach(election => {
                            const option = document.createElement('option');
                            option.value = election.election_id;
                            option.textContent = election.title +
                                (election.is_published ? ' (Published)' : ' (Draft)') +
                                (new Date(election.end_date) < new Date() ? ' (Ended)' : '');
                            electionSelector.appendChild(option);
                        });

                        // If there's an active election, select it by default
                        const activeElection = data.elections.find(e =>
                            e.is_published && new Date(e.end_date) > new Date()
                        );

                        if (activeElection) {
                            electionSelector.value = activeElection.election_id;
                            loadElectionData(activeElection.election_id);
                        } else if (data.elections.length > 0) {
                            // Just load the first election if no active one
                            noDataMessage.classList.add('hidden');
                        } else {
                            noDataMessage.classList.remove('hidden');
                        }
                    } else {
                        electionSelector.innerHTML = '<option value="">No elections available</option>';
                        noDataMessage.classList.remove('hidden');
                    }

                    loadingIndicator.classList.add('hidden');
                } catch (error) {
                    console.error('Error loading elections:', error);
                    electionSelector.innerHTML = '<option value="">Error loading elections</option>';
                    loadingIndicator.classList.add('hidden');
                    noDataMessage.classList.remove('hidden');
                }
            }

            async function loadElectionData(electionId) {
                try {
                    dashboardContent.classList.add('hidden');
                    loadingIndicator.classList.remove('hidden');
                    noDataMessage.classList.add('hidden');

                    // Clear any existing refresh interval
                    if (refreshInterval) {
                        clearInterval(refreshInterval);
                    }

                    const response = await fetch(`{{ route('clubs.voting.results', [$club, ':electionId']) }}`
                        .replace(':electionId', electionId));
                    const data = await response.json();

                    if (data.success) {
                        currentElection = data.election;
                        displayDashboard(
                            data); // Set up an interval to refresh data every 10 seconds for real-time updates
                        refreshInterval = setInterval(() => {
                            loadElectionData(electionId);
                        }, 30000); //refresh every 30 seconds
                    } else {
                        noDataMessage.classList.remove('hidden');
                        noDataMessage.querySelector('p').textContent = data.message ||
                            'Error loading election data';
                    }

                    loadingIndicator.classList.add('hidden');
                } catch (error) {
                    console.error('Error loading election data:', error);
                    loadingIndicator.classList.add('hidden');
                    noDataMessage.classList.remove('hidden');
                    noDataMessage.querySelector('p').textContent = 'Error loading election data';
                }
            }

            function displayDashboard(data) {
                const {
                    election,
                    votes,
                    eligibleVoters,
                    results,
                    recentActivity
                } = data;

                // Update summary statistics
                document.getElementById('total-votes').textContent = votes.length;
                document.getElementById('eligible-voters').textContent = eligibleVoters;

                const votePercentage = eligibleVoters > 0 ?
                    Math.round((votes.length / eligibleVoters) * 100) :
                    0;
                document.getElementById('vote-percentage').textContent = `${votePercentage}%`;
                document.getElementById('remaining-voters').textContent =
                    `${eligibleVoters - votes.length} haven't voted yet`;

                // Update time remaining
                updateTimeRemaining(election.end_date);

                // Display results by position
                displayResults(results);

                // Update recent voting activity
                displayRecentActivity(recentActivity);

                // Enable export button when data is available
                const exportButton = document.getElementById('export-csv-btn');
                exportButton.disabled = false;
                exportButton.onclick = () => exportResultsToCsv(election, results, votes, eligibleVoters);

                dashboardContent.classList.remove('hidden');
            }

            function createWinnersSection(results) {
                // Calculate winners for each position
                const winners = [];
                // Extended position order to include all standard positions plus handle custom ones
                const positionOrder = [
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

                Object.entries(results).forEach(([position, candidates]) => {
                    if (candidates && candidates.length > 0) {
                        const sortedCandidates = [...candidates].sort((a, b) => b.votes - a.votes);
                        const winner = sortedCandidates[0];

                        if (winner.votes > 0) {
                            winners.push({
                                position: position,
                                name: winner.name,
                                partylist: winner.partylist,
                                votes: winner.votes
                            });
                        }
                    }
                });

                if (winners.length === 0) return null;

                // Sort winners by position order (standard positions first, then custom positions alphabetically)
                winners.sort((a, b) => {
                    const posA = positionOrder.indexOf(a.position);
                    const posB = positionOrder.indexOf(b.position);
                    // Both positions are in the standard order
                    if (posA !== -1 && posB !== -1) return posA - posB;
                    // Position A is standard, B is custom - A comes first
                    if (posA !== -1) return -1;
                    // Position B is standard, A is custom - B comes first
                    if (posB !== -1) return 1;
                    // Both are custom positions - sort alphabetically
                    return a.position.localeCompare(b.position);
                });

                const winnersSection = document.createElement('div');
                winnersSection.className =
                    'mb-8 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-6 shadow-sm';

                winnersSection.innerHTML = `
                    <div class="flex items-center mb-4">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732L14.146 12.8l-1.179 4.456a1 1 0 01-1.934 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732L9.854 7.2l1.179-4.456A1 1 0 0112 2z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">🏆 Election Winners</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        ${winners.map(winner => `
                                            <div class="bg-white rounded-lg border border-yellow-200 p-4 shadow-sm">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center mb-2">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                                                WINNER
                                                            </span>
                                                        </div>
                                                        <h3 class="font-semibold text-gray-900 text-lg">${winner.name}</h3>
                                                        <p class="text-sm text-gray-600 mb-1">${winner.position}</p>
                                                        <p class="text-xs text-gray-500">${winner.partylist}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-2xl font-bold text-yellow-600">${winner.votes}</div>
                                                        <div class="text-xs text-gray-500">votes</div>
                                                    </div>
                                                </div>
                                            </div>
                                        `).join('')}
                    </div>
                `;

                return winnersSection;
            }

            function displayResults(results) {
                const resultsContainer = document.getElementById('results-container');
                resultsContainer.innerHTML = '';

                if (!results || Object.keys(results).length === 0) {
                    const emptyState = document.createElement('div');
                    emptyState.className = 'bg-white p-10 text-center rounded-xl border border-gray-200 shadow-sm';
                    emptyState.innerHTML = `
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-500 mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Votes Recorded Yet</h3>
                    <p class="text-gray-500 max-w-md mx-auto">Waiting for students to cast their votes. Results will appear here automatically as votes come in.</p>
                `;
                    resultsContainer.appendChild(emptyState);
                    return;
                }

                // Create winners summary section
                const winnersSection = createWinnersSection(results);
                if (winnersSection) {
                    resultsContainer.appendChild(winnersSection);
                }

                // Create a grid layout for position cards
                const grid = document.createElement('div');
                grid.className = 'grid grid-cols-1 xl:grid-cols-2 gap-6';
                resultsContainer.appendChild(grid);

                // Create a sorting utility for positions
                const positionOrder = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor'];
                const sortedPositions = Object.entries(results).sort((a, b) => {
                    const posA = positionOrder.indexOf(a[0]);
                    const posB = positionOrder.indexOf(b[0]);

                    // If both positions are in our preset order, use that order
                    if (posA !== -1 && posB !== -1) {
                        return posA - posB;
                    }
                    // If only one is in preset order, prioritize it
                    if (posA !== -1) return -1;
                    if (posB !== -1) return 1;

                    // Otherwise alphabetical
                    return a[0].localeCompare(b[0]);
                });

                sortedPositions.forEach(([position, candidates], index) => {
                    const section = document.createElement('div');
                    section.className =
                        'bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md';

                    // Create a colored header based on position index (using different colors)
                    const headerColors = [
                        'bg-blue-600', 'bg-purple-600', 'bg-green-600', 'bg-amber-600',
                        'bg-pink-600', 'bg-indigo-600', 'bg-red-600', 'bg-teal-600'
                    ];
                    const colorIndex = index % headerColors.length;

                    // Create position header with colored background and gradient
                    const header = document.createElement('div');
                    header.className =
                        `${headerColors[colorIndex]} bg-gradient-to-r from-${headerColors[colorIndex].replace('bg-', '')} to-${headerColors[colorIndex].replace('bg-', '').replace('-600', '-500')} px-6 py-4`;
                    header.innerHTML = `
                    <h3 class="text-lg font-medium text-white flex items-center">
                        <svg class="h-5 w-5 mr-2 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        ${position}
                    </h3>
                `;
                    section.appendChild(header);

                    // Sort candidates by votes (descending)
                    const sortedCandidates = [...candidates].sort((a, b) => b.votes - a.votes);

                    // Calculate total votes for this position for percentage calculations
                    const totalVotes = sortedCandidates.reduce((sum, candidate) => sum + candidate.votes,
                        0);

                    // Create content container with padding and spacing
                    const contentContainer = document.createElement('div');
                    contentContainer.className = 'p-6';

                    // Create container for chart and results
                    const contentDiv = document.createElement('div');
                    contentDiv.className = 'md:flex gap-6';
                    contentContainer.appendChild(contentDiv);

                    // Create chart container with drop shadow
                    const chartContainer = document.createElement('div');
                    chartContainer.className = 'md:w-2/5 mb-6 md:mb-0 flex justify-center items-center';
                    // Wrap canvas in a container for better styling
                    const chartWrapper = document.createElement('div');
                    chartWrapper.className =
                        'relative w-full max-w-[250px] aspect-square flex items-center justify-center';
                    const canvas = document.createElement('canvas');
                    canvas.id = `chart-${index}`;
                    canvas.className = 'chart-canvas';

                    chartWrapper.appendChild(canvas);
                    chartContainer.appendChild(chartWrapper);
                    contentDiv.appendChild(chartContainer);

                    // Create results list with enhanced styling
                    const resultsList = document.createElement('div');
                    resultsList.className = 'md:w-3/5 md:pl-0';

                    // Add a small heading for candidates
                    const candidatesHeading = document.createElement('div');
                    candidatesHeading.className =
                        'mb-3 text-sm font-medium text-gray-500 uppercase tracking-wider flex justify-between items-center';
                    candidatesHeading.innerHTML = `
                    <span>Candidates</span>
                    <span class="text-xs text-${headerColors[colorIndex].replace('bg-', '')} bg-${headerColors[colorIndex].replace('bg-', '').replace('-600', '-50')} rounded-full px-2 py-1">
                        ${totalVotes} total vote${totalVotes !== 1 ? 's' : ''}
                    </span>
                `;
                    resultsList.appendChild(candidatesHeading);

                    // Create a container for candidate results
                    const candidatesContainer = document.createElement('div');
                    candidatesContainer.className =
                        'space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar';
                    resultsList.appendChild(candidatesContainer);

                    // Add candidate results with progress bars
                    sortedCandidates.forEach((candidate, i) => {
                        // Calculate percentage for this candidate
                        const votePercentage = totalVotes > 0 ? Math.round((candidate.votes /
                            totalVotes) * 100) : 0;

                        const candidateItem = document.createElement('div');
                        candidateItem.className = 'p-3 rounded-lg transition-all ' +
                            (i === 0 && candidate.votes > 0 ?
                                `border-l-4 border-${headerColors[colorIndex].replace('bg-', '')} bg-${headerColors[colorIndex].replace('bg-', '').replace('-600', '-50/30')}` :
                                'bg-gray-50');

                        // Get ranking icon/badge
                        let rankingBadge = '';
                        if (i === 0 && candidate.votes > 0) {
                            rankingBadge = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-yellow-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Leading
                            </span>
                        `;
                        } else if (i === 1 && candidate.votes > 0 && votePercentage > 0) {
                            rankingBadge = `
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                2nd
                            </span>
                        `;
                        }

                        candidateItem.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center">
                                ${rankingBadge}
                                <span class="font-medium text-gray-900">${candidate.name}</span>
                            </div>
                            <span class="text-sm font-semibold ${i === 0 && candidate.votes > 0 ? 'text-' + headerColors[colorIndex].replace('bg-', '') : 'text-gray-700'}">
                                ${candidate.votes} <span class="text-gray-500 font-normal">(${votePercentage}%)</span>
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 mb-1.5">${candidate.partylist}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                            <div class="h-2.5 rounded-full ${i === 0 && candidate.votes > 0 ? headerColors[colorIndex] : 'bg-gray-400'}"
                                 style="width: ${votePercentage}%"></div>
                        </div>
                    `;

                        candidatesContainer.appendChild(candidateItem);
                    });

                    contentDiv.appendChild(resultsList);
                    section.appendChild(contentContainer);
                    grid.appendChild(section);

                    // Create chart with enhanced styling
                    createChart(`chart-${index}`, sortedCandidates, headerColors[colorIndex]);
                });
            }

            function createChart(canvasId, candidates, themeColor) {
                const ctx = document.getElementById(canvasId).getContext('2d');

                // Destroy existing chart if it exists
                if (resultsCharts[canvasId]) {
                    resultsCharts[canvasId].destroy();
                }
                // If there are no votes, show a blank chart with message
                if (candidates.every(c => c.votes === 0)) {
                    resultsCharts[canvasId] = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['No Votes'],
                            datasets: [{
                                data: [1],
                                backgroundColor: ['#edf2f7'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false
                                }
                            }
                        }
                    });

                    // Add a "No votes yet" text in the center
                    const chartInstance = resultsCharts[canvasId];
                    const chartArea = chartInstance.chartArea;

                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = '14px sans-serif';
                    ctx.fillStyle = '#718096';
                    ctx.fillText('No votes', chartArea.left + chartArea.width / 2, chartArea.top + chartArea
                        .height / 2);

                    return;
                }

                // Generate visually distinct colors based on the theme color
                const generateColors = () => {
                    // Extract the theme color base (like 'blue', 'green', etc.)
                    const colorBase = themeColor.replace('bg-', '').replace('-600', '');

                    // Create a palette of colors based on the theme
                    const baseColors = [
                        `#${getColorHex(colorBase, 500)}`,
                        `#${getColorHex(colorBase, 400)}`,
                        `#${getColorHex(colorBase, 300)}`,
                    ];

                    // Additional colors for diversity
                    const additionalColors = [
                        '#60a5fa', '#34d399', '#a78bfa', '#f472b6',
                        '#fbbf24', '#818cf8', '#fb7185', '#2dd4bf'
                    ];

                    // Combine and ensure we have enough colors
                    let colors = [...baseColors];

                    // If we need more colors than in our base set, add from additionalColors
                    while (colors.length < candidates.length) {
                        colors.push(additionalColors[colors.length % additionalColors.length]);
                    }

                    return colors;
                };

                const chartColors = generateColors();
                resultsCharts[canvasId] = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: candidates.map(c => c.name),
                        datasets: [{
                            data: candidates.map(c => c.votes),
                            backgroundColor: chartColors,
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 3,
                            hoverBorderColor: '#ffffff',
                            hoverOffset: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 13,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                displayColors: false,
                                padding: 10,
                                callbacks: {
                                    label: function(context) {
                                        const votes = context.raw;
                                        const percentage = Math.round((votes / context.dataset.data
                                            .reduce((a, b) => a + b, 0)) * 100);
                                        return `Votes: ${votes} (${percentage}%)`;
                                    },
                                    afterLabel: function(context) {
                                        const candidate = candidates[context.dataIndex];
                                        return `Partylist: ${candidate.partylist}`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Helper function to get hex values for Tailwind colors
            function getColorHex(color, shade) {
                const colorMap = {
                    blue: {
                        300: '93c5fd',
                        400: '60a5fa',
                        500: '3b82f6',
                        600: '2563eb'
                    },
                    green: {
                        300: '86efac',
                        400: '4ade80',
                        500: '22c55e',
                        600: '16a34a'
                    },
                    purple: {
                        300: 'c4b5fd',
                        400: 'a78bfa',
                        500: '8b5cf6',
                        600: '7c3aed'
                    },
                    pink: {
                        300: 'f9a8d4',
                        400: 'f472b6',
                        500: 'ec4899',
                        600: 'db2777'
                    },
                    yellow: {
                        300: 'fcd34d',
                        400: 'fbbf24',
                        500: 'f59e0b',
                        600: 'd97706'
                    },
                    indigo: {
                        300: 'a5b4fc',
                        400: '818cf8',
                        500: '6366f1',
                        600: '4f46e5'
                    },
                    red: {
                        300: 'fca5a5',
                        400: 'f87171',
                        500: 'ef4444',
                        600: 'dc2626'
                    },
                    teal: {
                        300: '5eead4',
                        400: '2dd4bf',
                        500: '14b8a6',
                        600: '0d9488'
                    }
                };

                return colorMap[color]?.[shade] || '6b7280'; // default gray if color not found
            }

            function displayRecentActivity(recentActivity) {
                const table = document.getElementById('recent-votes-table');

                if (!recentActivity || recentActivity.length === 0) {
                    table.innerHTML = `
                    <tr>
                        <td colspan="2" class="px-6 py-5 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-8 w-8 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-500">No voting activity yet</span>
                            </div>
                        </td>
                    </tr>
                `;
                    return;
                }

                table.innerHTML = '';

                recentActivity.forEach((activity, index) => {
                    const row = document.createElement('tr');
                    row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';

                    const studentCell = document.createElement('td');
                    studentCell.className = 'px-6 py-4 whitespace-nowrap';
                    studentCell.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-medium text-sm">${activity.voter_name.substring(0, 2)}</span>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">${activity.voter_name}</div>
                        </div>
                    </div>
                `;

                    const timeCell = document.createElement('td');
                    timeCell.className = 'px-6 py-4 whitespace-nowrap';
                    timeCell.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm text-gray-500">${formatDate(activity.created_at)}</span>
                    </div>
                `;

                    row.appendChild(studentCell);
                    row.appendChild(timeCell);
                    table.appendChild(row);
                });
            }

            function updateTimeRemaining(endDate) {
                const endDateTime = new Date(endDate);
                const now = new Date();

                document.getElementById('end-date').textContent = `Ends: ${formatDate(endDate)}`;

                if (now >= endDateTime) {
                    document.getElementById('time-remaining').textContent = 'Ended';
                    return;
                }

                const diff = endDateTime - now;
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                let timeText = '';

                if (days > 0) {
                    timeText = `${days}d ${hours}h ${minutes}m`;
                } else {
                    timeText =
                        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }

                document.getElementById('time-remaining').textContent = timeText;

                // Keep updating every second if election is still ongoing
                if (now < endDateTime) {
                    setTimeout(() => updateTimeRemaining(endDate), 1000);
                }
            }

            function formatDate(dateString) {
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
            // Function to export election results to CSV
            function exportResultsToCsv(election, results, votes, eligibleVoters) {
                // Show loading state on button
                const exportBtn = document.getElementById('export-csv-btn');
                const originalBtnText = exportBtn.innerHTML;
                exportBtn.disabled = true;
                exportBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generating CSV...
                `;

                try {
                    // Generate CSV content with enhanced structure for spreadsheets
                    let csvContent = [];

                    // Get current date and time for report generation timestamp
                    const now = new Date();
                    const reportTimestamp = formatDate(now);
                    const electionStatus = now > new Date(election.end_date) ? 'Completed' :
                        (now > new Date(election.start_date) ? 'In Progress' : 'Not Started');

                    // Calculate voting duration
                    const startDate = new Date(election.start_date);
                    const endDate = new Date(election.end_date);
                    const durationMs = endDate - startDate;
                    const durationHours = Math.round(durationMs / (1000 * 60 * 60));
                    const durationDays = Math.round(durationMs / (1000 * 60 * 60 * 24) * 10) / 10;

                    // Create helper function for section separators (helps with spreadsheet readability)
                    const addSeparator = () => csvContent.push([]);

                    // Pre-calculate all winners data for summary section
                    const winnersData = [];
                    const positionOrder = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor'];
                    const sortedPositions = Object.entries(results).sort((a, b) => {
                        const posA = positionOrder.indexOf(a[0]);
                        const posB = positionOrder.indexOf(b[0]);

                        if (posA !== -1 && posB !== -1) return posA - posB;
                        if (posA !== -1) return -1;
                        if (posB !== -1) return 1;
                        return a[0].localeCompare(b[0]);
                    });

                    sortedPositions.forEach(([position, candidates]) => {
                        const totalVotes = candidates.reduce((sum, candidate) => sum + candidate.votes, 0);
                        const sortedCandidates = [...candidates].sort((a, b) => b.votes - a.votes);

                        if (totalVotes > 0 && sortedCandidates.length > 0) {
                            const winner = sortedCandidates[0];
                            const votePercentage = totalVotes > 0 ? Math.round((winner.votes / totalVotes) *
                                100) : 0;
                            winnersData.push({
                                position,
                                name: winner.name,
                                partylist: winner.partylist,
                                votes: winner.votes,
                                totalVotes,
                                percentage: votePercentage
                            });
                        }
                    });

                    // ===== REPORT HEADER =====
                    // Add prominent title that will stand out in spreadsheet
                    csvContent.push([`${election.title.toUpperCase()} - ELECTION RESULTS`]);
                    csvContent.push(['Report Generated:', reportTimestamp]);
                    addSeparator();

                    // ===== ELECTION INFORMATION =====
                    csvContent.push(['ELECTION INFORMATION']);
                    csvContent.push(['Title:', election.title]);
                    csvContent.push(['Description:', election.description]);
                    csvContent.push(['Status:', electionStatus]);
                    csvContent.push(['Start Date:', formatDate(election.start_date)]);
                    csvContent.push(['End Date:', formatDate(election.end_date)]);
                    csvContent.push(['Duration:', `${durationHours} hours (${durationDays} days)`]);
                    addSeparator();

                    // ===== PARTICIPATION SUMMARY =====
                    csvContent.push(['PARTICIPATION SUMMARY']);
                    csvContent.push(['Total Eligible Voters:', eligibleVoters]);
                    csvContent.push(['Total Votes Cast:', votes.length]);
                    const participationRate = eligibleVoters > 0 ? Math.round((votes.length / eligibleVoters) *
                        100) : 0;
                    csvContent.push(['Participation Rate:', `${participationRate}%`]);
                    csvContent.push(['Non-Voters:', eligibleVoters - votes.length]);

                    // Add turnout evaluation metric
                    let turnoutEvaluation = '';
                    if (participationRate >= 75) turnoutEvaluation = 'Excellent';
                    else if (participationRate >= 50) turnoutEvaluation = 'Good';
                    else if (participationRate >= 30) turnoutEvaluation = 'Fair';
                    else turnoutEvaluation = 'Poor';
                    csvContent.push(['Turnout Evaluation:', turnoutEvaluation]);
                    addSeparator();

                    // ===== WINNERS SUMMARY TABLE =====
                    // This table provides a quick reference of all winners
                    if (winnersData.length > 0) {
                        csvContent.push(['WINNERS SUMMARY TABLE']);
                        // Add header columns for the summary table
                        csvContent.push(['Position', 'Winner', 'Partylist', 'Votes Received',
                            'Total Position Votes', 'Percentage'
                        ]);

                        winnersData.forEach(winner => {
                            csvContent.push([
                                winner.position,
                                winner.name,
                                winner.partylist,
                                winner.votes,
                                winner.totalVotes,
                                `${winner.percentage}%`
                            ]);
                        });
                        addSeparator();
                    }

                    // ===== DETAILED RESULTS BY POSITION =====
                    csvContent.push(['DETAILED RESULTS BY POSITION']);

                    // Add results for each position in a tabular format
                    for (const [position, candidates] of sortedPositions) {
                        // Add position header
                        csvContent.push([]);
                        csvContent.push([`POSITION: ${position.toUpperCase()}`]);

                        // Add detailed table header
                        csvContent.push(['Rank', 'Candidate Name', 'Partylist', 'Votes', 'Percentage', 'Status']);

                        // Calculate total votes for this position
                        const totalVotes = candidates.reduce((sum, candidate) => sum + candidate.votes, 0);

                        // Sort candidates by vote count (descending)
                        const sortedCandidates = [...candidates].sort((a, b) => b.votes - a.votes);

                        // Add each candidate with detailed information
                        sortedCandidates.forEach((candidate, index) => {
                            const votePercentage = totalVotes > 0 ? Math.round((candidate.votes /
                                totalVotes) * 100) : 0;
                            const status = index === 0 && candidate.votes > 0 ? 'WINNER' : '';

                            csvContent.push([
                                index + 1, // Rank
                                candidate.name,
                                candidate.partylist,
                                candidate.votes,
                                `${votePercentage}%`,
                                status
                            ]);
                        });

                        // Add a summary row for this position
                        if (totalVotes > 0) {
                            csvContent.push(['', 'TOTAL', '', totalVotes, '100%', '']);
                        }
                    }
                    addSeparator();

                    // ===== VOTING TIMELINE INFORMATION =====
                    if (votes.length > 0 && votes[0].created_at) {
                        csvContent.push(['VOTING TIMELINE']);

                        // Sort votes by time
                        const sortedVotes = [...votes].sort((a, b) =>
                            new Date(a.created_at) - new Date(b.created_at)
                        );

                        // Add first and last vote timestamps
                        if (sortedVotes.length > 0) {
                            csvContent.push(['First Vote Cast:', formatDate(sortedVotes[0].created_at)]);
                            csvContent.push(['Last Vote Cast:', formatDate(sortedVotes[sortedVotes.length - 1]
                                .created_at)]);

                            // Calculate voting session duration
                            const firstVote = new Date(sortedVotes[0].created_at);
                            const lastVote = new Date(sortedVotes[sortedVotes.length - 1].created_at);
                            const durationMs = lastVote - firstVote;

                            // Format duration in hours and minutes for readability
                            const durationHours = Math.floor(durationMs / (1000 * 60 * 60));
                            const durationMinutes = Math.floor((durationMs % (1000 * 60 * 60)) / (1000 * 60));
                            csvContent.push(['Voting Session Duration:',
                                `${durationHours} hours, ${durationMinutes} minutes`
                            ]);

                            // Calculate votes per hour (voting rate)
                            const votingHours = durationMs / (1000 * 60 * 60);
                            if (votingHours > 0) {
                                const votesPerHour = Math.round((votes.length / votingHours) * 10) / 10;
                                csvContent.push(['Average Voting Rate:', `${votesPerHour} votes per hour`]);
                            }
                        }

                        // Add vote time distribution if we have multiple votes
                        if (sortedVotes.length > 1) {
                            // Count votes by hour of the day (for voting pattern analysis)
                            csvContent.push([]);
                            csvContent.push(['VOTES BY HOUR OF DAY']);
                            csvContent.push(['Hour', 'Number of Votes', 'Percentage']);

                            const votesByHour = {};
                            sortedVotes.forEach(vote => {
                                const hour = new Date(vote.created_at).getHours();
                                votesByHour[hour] = (votesByHour[hour] || 0) + 1;
                            });

                            // Add hourly vote data for analysis
                            for (let hour = 0; hour < 24; hour++) {
                                const count = votesByHour[hour] || 0;
                                if (count > 0) {
                                    const percentage = Math.round((count / votes.length) * 100);
                                    // Format hour in 12-hour format for readability
                                    const hourFormatted = hour === 0 ? '12 AM' :
                                        hour < 12 ? `${hour} AM` :
                                        hour === 12 ? '12 PM' :
                                        `${hour - 12} PM`;
                                    csvContent.push([hourFormatted, count, `${percentage}%`]);
                                }
                            }
                        }
                    }

                    // Add report footer with metadata
                    addSeparator();
                    csvContent.push(['Generated by Club Hive Voting System']);
                    csvContent.push(['Report Date:', reportTimestamp]);
                    csvContent.push(['© Siterians ClubHive']);

                    // Convert to CSV format with improved escape handling
                    const csv = csvContent.map(row =>
                        row.map(cell => {
                            // Enhanced cell escaping for better spreadsheet compatibility
                            if (cell === undefined || cell === null) return '';
                            const cellStr = String(cell);
                            if (cellStr.includes(',') || cellStr.includes('"') || cellStr.includes('\n') ||
                                cellStr.includes('\r')) {
                                return `"${cellStr.replace(/"/g, '""')}"`;
                            }
                            return cellStr;
                        }).join(',')
                    ).join('\n');

                    // Create a download link with improved filename format
                    const dateStr = now.toISOString().split('T')[0]; // YYYY-MM-DD
                    const timeStr = now.toISOString().split('T')[1].substring(0, 5).replace(':', ''); // HHMM
                    const cleanTitle = election.title.replace(/[^a-z0-9]/gi, '_').toLowerCase();
                    const fileName = `election_results_${cleanTitle}_${dateStr}_${timeStr}.csv`;

                    const blob = new Blob([csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.setAttribute('href', url);
                    link.setAttribute('download', fileName);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className =
                        'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                    successAlert.textContent = 'CSV export successful! File is downloading.';
                    document.body.appendChild(successAlert);

                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
                } catch (error) {
                    console.error('Error exporting CSV:', error);

                    // Show error message
                    const errorAlert = document.createElement('div');
                    errorAlert.className =
                        'fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                    errorAlert.textContent = 'Error exporting results. Please try again.';
                    document.body.appendChild(errorAlert);

                    setTimeout(() => {
                        errorAlert.remove();
                    }, 3000);
                } finally {
                    // Restore button state
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = originalBtnText;
                }
            }
        });
    </script>
@endpush
