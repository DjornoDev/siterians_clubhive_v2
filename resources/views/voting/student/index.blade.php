@extends('layouts.dashboard')

@section('title', 'Student Voting')

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="studentVoting()" x-init="init()" x-cloak>

        <!-- No Active Election Message -->
        <div x-show="!election && !loading" class="bg-white shadow rounded-lg p-6">
            <div class="text-center py-12">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <h2 class="mt-4 text-xl font-semibold text-gray-800">No Active Voting</h2>
                <p class="mt-2 text-gray-600">There's currently no ongoing election available.</p>
                <p class="mt-1 text-gray-500 text-sm">Please check back later.</p>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading || checkingVoteStatus"
            class="bg-white shadow rounded-lg p-6 flex justify-center items-center py-12">
            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="ml-3 text-gray-700">
                <span x-show="!checkingVoteStatus">Loading voting information...</span>
                <span x-show="checkingVoteStatus">Checking your voting status...</span>
            </span>
        </div>
        <!-- Election With No Candidates -->
        <div x-show="election && Object.keys(candidatesByPosition).length === 0 && !loading && !hasVoted"
            class="bg-white shadow rounded-lg p-6">
            <div class="border-b pb-4 mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    <span x-text="election ? election.title : ''">Election Title</span>
                </h2>
                <p class="mt-1 text-gray-600" x-text="election ? election.description : ''"></p>
                <p class="mt-3 text-sm text-gray-500">
                    Voting ends on <span x-text="election ? formatDate(election.end_date) : ''"></span>
                </p>
            </div>
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-800">No Candidates Available</h3>
                <p class="mt-2 text-gray-600">There's an ongoing election but no candidates have been added yet.</p>
                <p class="text-sm text-gray-500 mt-1">Please check back soon.</p>
            </div>
        </div>

        <!-- Thank You Message After Voting -->
        <div x-show="hasVoted && !loading" class="bg-white shadow rounded-lg p-6">
            <div class="text-center py-12">
                <div class="rounded-full bg-green-100 p-3 mx-auto w-16 h-16 flex items-center justify-center">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="mt-4 text-xl font-semibold text-gray-800">Thank You For Voting!</h2>
                <p class="mt-2 text-gray-600">Your vote has been successfully recorded.</p>

                <div x-show="myVoteDetails.length > 0" class="mt-8 max-w-md mx-auto">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Your Vote Summary:</h3>
                    <ul class="space-y-4">
                        <template x-for="vote in myVoteDetails" :key="vote.vote_detail_id">
                            <li class="bg-gray-50 rounded p-3">
                                <div class="flex items-center">
                                    <!-- Profile Picture -->
                                    <div class="flex-shrink-0 mr-3">
                                        <template x-if="vote.profile_picture">
                                            <img :src="'/storage/profile_pictures/' + vote.profile_picture"
                                                :alt="vote.candidate_name + ' profile'"
                                                class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                        </template>
                                        <template x-if="!vote.profile_picture">
                                            <div
                                                class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm">
                                                <span x-text="getInitials(vote.candidate_name)"></span>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Vote Info -->
                                    <div class="flex-grow">
                                        <div class="font-semibold text-gray-700" x-text="vote.position"></div>
                                        <div class="text-gray-600" x-text="vote.candidate_name"></div>
                                    </div>

                                    <!-- Party List -->
                                    <div class="text-sm text-gray-500 text-right" x-text="vote.partylist"></div>
                                </div>
                            </li>
                        </template>
                    </ul>
                    <p class="mt-4 text-sm text-gray-500">Voted on <span x-text="formatDateTime(votedAt)"></span></p>
                </div>
            </div>
        </div>
        <!-- Voting Form -->
        <div
            x-show="election && Object.keys(candidatesByPosition).length > 0 && !hasVoted && !loading && !checkingVoteStatus">
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-800">
                        <span x-text="election ? election.title : ''">Election Title</span>
                    </h2>
                    <p class="mt-1 text-gray-600" x-text="election ? election.description : ''"></p>
                    <p class="mt-3 text-sm text-gray-500">
                        Voting ends on <span x-text="election ? formatDate(election.end_date) : ''"></span>
                    </p>
                </div>

                <form @submit.prevent="openConfirmModal">
                    <div class="divide-y divide-gray-200">
                        <template x-for="(candidates, position) in candidatesByPosition" :key="position">
                            <div class="px-6 py-4">
                                <h3 class="font-medium text-gray-900 mb-3">
                                    <span x-text="position">Position Name</span>
                                </h3>
                                <div class="space-y-3">
                                    <template x-for="candidate in candidates" :key="candidate.candidate_id">
                                        <div class="relative flex items-start">
                                            <div class="flex items-center h-5">
                                                <input type="radio" :name="'vote_' + position"
                                                    :id="'candidate_' + candidate.candidate_id"
                                                    :value="candidate.candidate_id" x-model="selectedCandidates[position]"
                                                    required
                                                    class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                            </div>
                                            <div class="ml-3 flex items-center w-full">
                                                <!-- Profile Picture -->
                                                <div class="flex-shrink-0 mr-3">
                                                    <template x-if="candidate.profile_picture">
                                                        <img :src="'/storage/profile_pictures/' + candidate.profile_picture"
                                                            :alt="candidate.name + ' profile'"
                                                            class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                                    </template>
                                                    <template x-if="!candidate.profile_picture">
                                                        <div
                                                            class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-sm">
                                                            <span x-text="getInitials(candidate.name)"></span>
                                                        </div>
                                                    </template>
                                                </div>

                                                <!-- Candidate Info -->
                                                <label :for="'candidate_' + candidate.candidate_id"
                                                    class="text-gray-700 block flex-grow cursor-pointer">
                                                    <div class="font-medium" x-text="candidate.name">Candidate Name</div>
                                                    <div class="text-sm text-blue-600" x-text="candidate.partylist">Party
                                                        List</div>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Data Privacy Notice -->
                    <div class="px-6 py-4 bg-blue-50 border-t border-blue-200">
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Data Privacy Notice</h4>
                            <p class="text-xs text-blue-800 leading-relaxed">
                                In compliance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>,
                                your personal data and voting information will be collected and processed solely for the
                                purpose of this election.
                                Your information will be kept confidential and secure, and will not be shared with
                                unauthorized parties without your consent,
                                except as required by law. Your vote will remain anonymous, and only aggregated results will
                                be disclosed.
                                You have the right to access, correct, or request deletion of your personal data in
                                accordance with applicable laws.
                            </p>
                        </div>
                        <div class="flex items-start">
                            <input type="checkbox" id="privacyConsent" x-model="privacyConsent"
                                class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="privacyConsent" class="ml-2 text-xs text-blue-900 cursor-pointer leading-relaxed">
                                I acknowledge the <strong>Data Privacy Notice.</strong>
                                I consent to the collection and processing of my <strong>personal data</strong> for this
                                election.
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 flex justify-end">
                        <button type="submit" :disabled="!privacyConsent"
                            :class="privacyConsent ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                            class="px-4 py-2 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            Submit Vote
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div x-show="showConfirmModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div @click.away="showConfirmModal = false"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                    Confirm Your Vote
                                </h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Please review your selections. You can only vote once.
                                    </p>
                                    <div class="mt-3 max-h-60 overflow-y-auto">
                                        <template x-for="(candidateId, position) in selectedCandidates"
                                            :key="position">
                                            <div class="mb-3 pb-3 border-b border-gray-100">
                                                <div class="font-medium text-gray-900" x-text="position"></div>
                                                <template x-for="candidate in getCandidatesByPosition(position)"
                                                    :key="candidate.candidate_id">
                                                    <div x-show="candidate.candidate_id == candidateId"
                                                        class="flex items-center mt-2">
                                                        <!-- Profile Picture -->
                                                        <div class="flex-shrink-0 mr-3">
                                                            <template x-if="candidate.profile_picture">
                                                                <img :src="'/storage/profile_pictures/' + candidate.profile_picture"
                                                                    :alt="candidate.name + ' profile'"
                                                                    class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                                            </template>
                                                            <template x-if="!candidate.profile_picture">
                                                                <div
                                                                    class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium text-xs">
                                                                    <span x-text="getInitials(candidate.name)"></span>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <!-- Candidate Info -->
                                                        <div>
                                                            <span class="text-gray-700 font-medium"
                                                                x-text="candidate.name"></span>
                                                            <span class="text-sm text-blue-600 ml-2"
                                                                x-text="candidate.partylist"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Privacy Notice Reminder -->
                                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                                </path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-800">Data Privacy
                                                Acknowledged</span>
                                        </div>
                                        <p class="text-xs text-blue-700 mt-1">
                                            You have consented to the Data Privacy Act of 2012 compliance for this election.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="submitVote" :disabled="submitting" type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <span x-show="submitting" class="inline-block mr-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </span>
                            Submit
                        </button>
                        <button @click="showConfirmModal = false" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function studentVoting() {
            return {
                lastChecksum: '',
                loading: true,
                checkingVoteStatus: false,
                election: null,
                candidates: [],
                candidatesByPosition: {},
                selectedCandidates: {},
                hasVoted: false,
                myVoteDetails: [],
                votedAt: null,
                showConfirmModal: false,
                submitting: false,
                privacyConsent: false,
                init() {
                    // Initial load of data
                    this.fetchCandidates();
                    // checkIfVoted is now called from within fetchCandidates after election data is available

                    // Set up polling for real-time updates
                    setInterval(() => {
                        this.checkForChanges();
                    }, 10000); // Check every 10 seconds
                },
                checkForChanges() {
                    // Skip refresh if form is active or modal is open
                    if (this.showConfirmModal || document.querySelector('form:focus-within') || this.submitting) {
                        return;
                    }

                    // If we've already voted, still check if there are changes to update vote details
                    if (this.hasVoted && this.election && !this.checkingVoteStatus) {
                        this.checkIfVoted();
                        return;
                    }

                    fetch('{{ route('voting.check-changes') }}?checksum=' + this.lastChecksum)
                        .then(response => response.json())
                        .then(data => {
                            if (data.hasChanges) {
                                this.lastChecksum = data.checksum;
                                this.fetchCandidates();
                                // checkIfVoted will be called from fetchCandidates
                            }
                        });
                },
                fetchCandidates() {
                    this.loading = true;
                    fetch('{{ route('voting.candidates') }}')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Server returned ' + response.status + ' ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Candidates API response:', data);
                            if (data.success) {
                                this.election = data.election;
                                this.candidates = data.candidates;
                                this.candidatesByPosition = data.candidates;

                                console.log('Election:', this.election);
                                console.log('CandidatesByPosition:', this.candidatesByPosition);
                                console.log('CandidatesByPosition keys:', Object.keys(this.candidatesByPosition));

                                // Initialize selected candidates object with empty values
                                Object.keys(this.candidatesByPosition).forEach(position => {
                                    if (!this.selectedCandidates[position]) {
                                        this.selectedCandidates[position] = '';
                                    }
                                });

                                // Only check if voted after we have election data
                                this.checkIfVoted();
                            } else {
                                this.election = null;
                                this.candidates = [];
                                this.candidatesByPosition = {};
                                this.loading = false; // Set loading to false when no election
                                console.log('No success in data response');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching candidates:', error);
                            this.election = null;
                            this.candidates = [];
                            this.candidatesByPosition = {};
                            this.loading = false; // Set loading to false on error
                        })
                        .finally(() => {
                            // Don't set loading to false here, let checkIfVoted handle it
                            console.log('Final state - election:', this.election);
                            console.log('Final state - candidatesByPosition:', this.candidatesByPosition);
                        });
                },
                checkIfVoted() {
                    if (!this.election || !this.election.election_id) return;

                    // Set checking status to prevent form from showing briefly
                    this.checkingVoteStatus = true;

                    fetch('{{ route('voting.check-voted', ':id') }}'.replace(':id', this.election.election_id))
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.hasVoted = data.hasVoted;
                                console.log('Has voted check from server:', this.hasVoted);

                                // If user has voted, get vote details
                                if (this.hasVoted) {
                                    this.getMyVoteDetails();
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking if voted:', error);
                        })
                        .finally(() => {
                            this.checkingVoteStatus = false;
                            this.loading = false;
                        });
                },
                getMyVoteDetails() {
                    if (!this.election) return;

                    fetch('{{ route('voting.my-vote', ':id') }}'.replace(':id', this.election.election_id))
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.myVoteDetails = data.voteDetails;
                                this.votedAt = data.votedAt;
                            }
                        })
                        .catch(error => {
                            console.error('Error getting vote details:', error);
                        });
                },

                getCandidatesByPosition(position) {
                    return this.candidatesByPosition[position] || [];
                },

                openConfirmModal() {
                    // Check if privacy consent is given
                    if (!this.privacyConsent) {
                        alert('Please acknowledge the Data Privacy Notice before submitting your vote.');
                        return;
                    }

                    // Check if all positions have a selected candidate
                    const allPositionsSelected = Object.keys(this.candidatesByPosition).every(
                        position => !!this.selectedCandidates[position]
                    );

                    if (!allPositionsSelected) {
                        alert('Please select a candidate for each position.');
                        return;
                    }

                    this.showConfirmModal = true;
                },
                submitVote() {
                    this.submitting = true;

                    // Check if election exists before proceeding
                    if (!this.election || !this.election.election_id) {
                        alert('No active election found. Please refresh the page.');
                        this.submitting = false;
                        return;
                    }

                    // Prepare the votes data
                    const votes = Object.keys(this.selectedCandidates).map(position => ({
                        position: position,
                        candidate_id: this.selectedCandidates[position]
                    }));

                    // Submit the vote
                    fetch('{{ route('voting.submit') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                election_id: this.election.election_id,
                                votes: votes
                            })
                        })
                        .then(response => response.json()).then(data => {
                            if (data.success) {
                                this.hasVoted = true;
                                this.showConfirmModal = false;
                                // Store the vote timestamp to display immediately
                                this.votedAt = new Date().toISOString();
                                // Get the vote details
                                this.getMyVoteDetails();
                            } else {
                                alert(data.message || 'There was an error submitting your vote. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting vote:', error);
                            alert('There was an error submitting your vote. Please try again.');
                        })
                        .finally(() => {
                            this.submitting = false;
                        });
                },
                formatDate(dateString) {
                    if (!dateString) return '';

                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                },
                formatDateTime(dateString) {
                    if (!dateString) return '';

                    const options = {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    };
                    return new Date(dateString).toLocaleDateString(undefined, options);
                },
                getInitials(name) {
                    if (!name) return '?';
                    return name
                        .split(' ')
                        .map(word => word.charAt(0).toUpperCase())
                        .slice(0, 2)
                        .join('');
                }
            };
        }
    </script>
@endsection
