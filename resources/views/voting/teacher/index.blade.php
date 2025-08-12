@extends('layouts.voting_navigations')
@section('title', 'Voting Management')

@section('voting_content')
    <div tabindex="-1" x-data="{
        lastChecksum: '{{ md5(
            json_encode([
                App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first(),
                App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first()
                    ? App\Models\Candidate::where(
                        'election_id',
                        App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first()->election_id,
                    )->get()
                    : [],
                App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first()
                    ? App\Models\Vote::where(
                        'election_id',
                        App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first()->election_id,
                    )->count()
                    : 0,
            ]),
        ) }}',
        checkForVotingChanges() {
            // Skip refresh check if any modal is open
            const candidateModal = document.getElementById('candidateModal');
            const resetConfirmModal = document.getElementById('resetConfirmModal');
    
            // Skip refresh if any modal is open
            if ((candidateModal && !candidateModal.classList.contains('hidden')) ||
                (resetConfirmModal && !resetConfirmModal.classList.contains('hidden'))) {
                return;
            }
    
            // Skip refresh if any form input is focused or has value
            const formInputs = document.querySelectorAll('input, textarea');
            for (const input of formInputs) {
                if (document.activeElement === input || input.value.trim() !== '') {
                    return;
                }
            }
    
            fetch('{{ route('voting.check-changes') }}?checksum=' + this.lastChecksum)
                .then(response => response.json())
                .then(data => {
                    if (data.hasChanges) {
                        this.lastChecksum = data.checksum;
                        window.location.reload();
                    }
                });
        },
    
        init() {
            // Check for voting changes every 5 seconds
            setInterval(() => this.checkForVotingChanges(), 5000);
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
            $election = App\Models\Election::where('club_id', 1)->where('end_date', '>', now())->latest()->first();

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

            {{-- Election and candidates already queried above --}}

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
                                                    @if (!$election->is_published)
                                                        <div class="ml-auto flex space-x-2">
                                                            <button type="button" class="text-blue-600 hover:text-blue-800"
                                                                onclick="editCandidate({{ $candidate->candidate_id }})">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path
                                                                        d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                                </svg>
                                                            </button>
                                                            <button type="button" class="text-red-600 hover:text-red-800"
                                                                onclick="deleteCandidate({{ $candidate->candidate_id }}, '{{ $candidate->name }}')">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
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
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
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
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('voting.store') }}" method="POST" class="space-y-6">
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
    <div id="candidateModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center hidden overflow-y-auto">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto my-8 overflow-hidden">
            <div class="flex justify-between items-center border-b px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                <h3 class="text-lg font-semibold text-white">Add Election Candidates</h3>
                <button type="button" class="text-white hover:text-gray-200" onclick="closeModal()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <form id="candidate-form" class="space-y-6">
                    <!-- Position (common for all candidates) -->
                    <div class="mb-6" x-data="{
                        showDropdown: false,
                        positionValue: '',
                        positions: [
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
                        ],
                        filteredPositions() {
                            if (!this.positionValue) return this.positions;
                            return this.positions.filter(pos =>
                                pos.toLowerCase().includes(this.positionValue.toLowerCase())
                            );
                        }
                    }">
                        <label for="position-0" class="block text-sm font-medium text-gray-700">Position</label>
                        <div class="relative">
                            <input type="text" id="position-0" name="position" x-model="positionValue"
                                @input="showDropdown = true" @focus="showDropdown = true"
                                @blur="setTimeout(() => showDropdown = false, 200)"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
                                placeholder="Enter or select position" autocomplete="off" required>

                            <!-- Dropdown with suggestions -->
                            <div x-show="showDropdown && filteredPositions().length > 0"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-auto">
                                <template x-for="position in filteredPositions()" :key="position">
                                    <div @click="positionValue = position; showDropdown = false"
                                        class="px-3 py-2 hover:bg-blue-50 cursor-pointer text-sm">
                                        <span x-text="position"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Enter the position for which you're adding candidates (e.g.,
                            President, Secretary)</p>
                    </div>
                    <div class="space-y-6" id="candidates-container">
                        <!-- Initial candidate block with candidates -->
                        <div class="candidate-block border p-4 rounded-md transition-all">
                            <h4 class="font-medium text-gray-700 mb-4">Candidates for this position</h4>

                            <!-- First candidate (initial) -->
                            <div class="mb-4 relative">
                                <label for="candidate-name-0" class="block text-sm font-medium text-gray-700">Candidate
                                    Name</label>
                                <input type="text" id="candidate-name-0"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
                                    placeholder="Search for a student" autocomplete="off"
                                    onkeyup="searchStudents(event, 0)">
                                <input type="hidden" id="candidate-id-0" name="user_id[]" required>
                                <div id="search-results-0"
                                    class="absolute z-10 w-full bg-white shadow-md rounded-md mt-1 hidden max-h-48 overflow-y-auto">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="partylist-0" class="block text-sm font-medium text-gray-700">Partylist</label>
                                <input type="text" id="partylist-0" name="partylist[]"
                                    class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                            </div>

                            <!-- Additional candidates will be added here -->
                        </div>
                    </div>

                    <div>
                        <button type="button" id="add-other"
                            class="flex items-center text-blue-600 hover:text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Other
                        </button>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 mr-2"
                            onclick="closeModal()">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Candidates
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Floating Action Button and Reset Text -->
    <div class="fixed bottom-8 right-8 flex flex-col items-center space-y-2">
        @if (isset($election) && $election && !$election->is_published)
            <button id="show-candidate-modal"
                class="w-14 h-14 rounded-full bg-blue-600 hover:bg-blue-700 shadow-lg flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        @endif
        <button id="reset-page" class="text-sm text-gray-600 hover:text-gray-900 hover:underline cursor-pointer">
            Reset
        </button>
    </div> <!-- Reset Confirmation Modal -->
    <div id="resetConfirmModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center hidden overflow-y-auto">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto my-8 overflow-hidden">
            <div class="flex justify-between items-center border-b px-6 py-4 bg-red-600">
                <h3 class="text-lg font-semibold text-white">Confirm Reset</h3>
                <button type="button" class="text-white hover:text-gray-200" onclick="closeResetModal()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex items-center mb-4 text-red-600">
                        <svg class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h4 class="text-lg font-bold">Warning: This action cannot be undone</h4>
                    </div>
                    <p class="text-gray-700 mb-4">You are about to reset <strong>ALL</strong> voting data, including:</p>
                    <ul class="list-disc pl-5 mb-4 text-gray-700 space-y-1">
                        <li>All elections</li>
                        <li>All candidates</li>
                        <li>All votes and voting records</li>
                        <li>All voting statistics and results</li>
                    </ul>
                    <p class="text-gray-700 mb-4">This will completely clear the voting system and cannot be recovered.</p>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Please type <strong class="font-bold">RESET</strong> below to confirm.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="reset-confirmation" class="block text-sm font-medium text-gray-700">Type "RESET" to
                        confirm:</label>
                    <input type="text" id="reset-confirmation"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
                        placeholder="Type RESET here" autocomplete="off">
                </div>
                <div class="flex justify-end pt-4 border-t">
                    <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 mr-2"
                        onclick="closeResetModal()">
                        Cancel
                    </button>
                    <button id="confirm-reset-btn" type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 opacity-50 cursor-not-allowed"
                        disabled>
                        Reset All Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Candidate Modal -->
    <div id="editCandidateModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center hidden overflow-y-auto">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto my-8 overflow-hidden">
            <div class="flex justify-between items-center border-b px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                <h3 class="text-lg font-semibold text-white">Edit Candidate</h3>
                <button type="button" class="text-white hover:text-gray-200" onclick="closeEditModal()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="edit-candidate-form" class="space-y-6">
                    <input type="hidden" id="edit-candidate-id">
                    <input type="hidden" id="edit-position" name="position">

                    <div class="mb-4 relative">
                        <label for="edit-candidate-name" class="block text-sm font-medium text-gray-700">Candidate
                            Name</label>
                        <input type="text" id="edit-candidate-name"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm"
                            placeholder="Search for a student" autocomplete="off" onkeyup="searchStudentsForEdit(event)">
                        <input type="hidden" id="edit-user-id" name="user_id" required>
                        <div id="edit-search-results"
                            class="absolute z-10 w-full bg-white shadow-md rounded-md mt-1 hidden max-h-48 overflow-y-auto">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="edit-partylist" class="block text-sm font-medium text-gray-700">Partylist</label>
                        <input type="text" id="edit-partylist" name="partylist"
                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 mr-2"
                            onclick="closeEditModal()">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Candidate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteCandidateModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center hidden overflow-y-auto">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto my-8 overflow-hidden">
            <div class="flex justify-between items-center border-b px-6 py-4 bg-red-600">
                <h3 class="text-lg font-semibold text-white">Confirm Delete</h3>
                <button type="button" class="text-white hover:text-gray-200" onclick="closeDeleteModal()">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <div class="flex items-center mb-4 text-red-600">
                        <svg class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h4 class="text-lg font-bold">Are you sure?</h4>
                    </div>
                    <p class="text-gray-700 mb-4">You are about to delete candidate <strong
                            id="delete-candidate-name"></strong>.</p>
                    <p class="text-gray-700 mb-4">This action cannot be undone.</p>
                </div>
                <input type="hidden" id="delete-candidate-id">
                <div class="flex justify-end pt-4 border-t">
                    <button type="button" class="px-4 py-2 text-gray-600 hover:text-gray-800 mr-2"
                        onclick="closeDeleteModal()">
                        Cancel
                    </button>
                    <button id="confirm-delete-btn" type="button"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete Candidate
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let candidateCount = 1; // Start with 1 since we already have candidate-0

        // Show the modal when clicking the floating action button
        const showCandidateModalBtn = document.getElementById('show-candidate-modal');
        if (showCandidateModalBtn) {
            showCandidateModalBtn.addEventListener('click', function() {
                document.getElementById('candidateModal').classList.remove('hidden');
                document.getElementById('candidateModal').classList.add('flex');
            });
        }

        // Close the modal
        function closeModal() {
            document.getElementById('candidateModal').classList.add('hidden');
            document.getElementById('candidateModal').classList.remove('flex');

            // Reset the scrollable state
            const candidateBlock = document.querySelector('.candidate-block');
            if (candidateCount < 2) {
                candidateBlock.classList.remove('max-h-80', 'overflow-y-auto');
            }
        } // Add another candidate name and partylist for the same position
        document.getElementById('add-other').addEventListener('click', function() {
            const currentBlock = document.querySelector('.candidate-block');

            // Create a container for the new candidate inputs
            const candidateFields = document.createElement('div');
            candidateFields.className = 'candidate-fields mt-4 border-t pt-4';
            candidateFields.innerHTML = `
                <div class="mb-4 relative">
                    <label for="candidate-name-${candidateCount}" class="block text-sm font-medium text-gray-700">Candidate Name</label>
                    <input type="text" id="candidate-name-${candidateCount}" 
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" 
                        placeholder="Search for a student"
                        autocomplete="off"
                        onkeyup="searchStudents(event, ${candidateCount})">
                    <input type="hidden" id="candidate-id-${candidateCount}" name="user_id[]" required>
                    <div id="search-results-${candidateCount}" class="absolute z-10 w-full bg-white shadow-md rounded-md mt-1 hidden max-h-48 overflow-y-auto"></div>
                </div>
                
                <div class="mb-4">
                    <label for="partylist-${candidateCount}" class="block text-sm font-medium text-gray-700">Partylist</label>
                    <input type="text" id="partylist-${candidateCount}" name="partylist[]" 
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm" 
                        required>
                </div>
            `;

            currentBlock.appendChild(candidateFields);
            candidateCount++;

            // Make the container scrollable when there are 2 or more candidates
            if (candidateCount >= 2) {
                currentBlock.classList.add('max-h-80', 'overflow-y-auto');
            }
        });

        // Search for students as user types in the candidate name field
        let searchTimeout;

        function searchStudents(event, index) {
            const searchTerm = event.target.value;
            const resultsContainer = document.getElementById(`search-results-${index}`);

            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Hide results if search term is empty
            if (searchTerm.trim() === '') {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
                return;
            }

            // Set new timeout (300ms delay to reduce API calls)
            searchTimeout = setTimeout(() => {
                // Make API call to search students
                fetch(`/voting/search-students?query=${encodeURIComponent(searchTerm)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Clear previous results
                        resultsContainer.innerHTML = '';

                        if (data.length === 0) {
                            // Show "no results" message
                            resultsContainer.innerHTML =
                                '<div class="p-3 text-gray-500">No students found</div>';
                        } else {
                            // Display each student found
                            data.forEach(student => {
                                const item = document.createElement('div');
                                item.className = 'p-3 hover:bg-gray-100 cursor-pointer';
                                item.textContent = `${student.name} (${student.email})`;

                                // When a student is selected
                                item.addEventListener('click', function() {
                                    // Set the name in the input field
                                    document.getElementById(`candidate-name-${index}`).value =
                                        student.name;
                                    // Set the student ID in the hidden field
                                    document.getElementById(`candidate-id-${index}`).value =
                                        student.user_id;
                                    // Hide the results
                                    resultsContainer.classList.add('hidden');
                                });

                                resultsContainer.appendChild(item);
                            });
                        }

                        // Show the results dropdown
                        resultsContainer.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error searching for students:', error);
                    });
            }, 300);
        } // Functions for editing and deleting candidates
        function editCandidate(candidateId) {
            // Clear previous search results
            document.getElementById('edit-search-results').innerHTML = '';
            document.getElementById('edit-search-results').classList.add('hidden');

            // Set loading state
            document.getElementById('edit-candidate-name').value = 'Loading...';
            document.getElementById('edit-partylist').value = '';
            document.getElementById('edit-user-id').value = '';
            document.getElementById('edit-candidate-id').value = candidateId;

            // Show the modal
            document.getElementById('editCandidateModal').classList.remove('hidden');
            document.getElementById('editCandidateModal').classList.add('flex');

            // Fetch candidate data
            fetch(`/voting/edit-candidate/${candidateId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Populate the form with candidate data
                        document.getElementById('edit-candidate-name').value = data.candidate.name;
                        document.getElementById('edit-partylist').value = data.candidate.partylist;
                        document.getElementById('edit-user-id').value = data.candidate.user_id;
                        document.getElementById('edit-position').value = data.candidate.position;
                    } else {
                        // Show error message
                        alert(data.message || 'Error retrieving candidate data');
                        closeEditModal();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error retrieving candidate data');
                    closeEditModal();
                });
        }

        function closeEditModal() {
            document.getElementById('editCandidateModal').classList.add('hidden');
            document.getElementById('editCandidateModal').classList.remove('flex');
            document.getElementById('edit-search-results').classList.add('hidden');
        }

        function deleteCandidate(candidateId, candidateName) {
            // Set the candidate ID and name in the delete confirmation modal
            document.getElementById('delete-candidate-id').value = candidateId;
            document.getElementById('delete-candidate-name').textContent = candidateName;

            // Show the delete confirmation modal
            document.getElementById('deleteCandidateModal').classList.remove('hidden');
            document.getElementById('deleteCandidateModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteCandidateModal').classList.add('hidden');
            document.getElementById('deleteCandidateModal').classList.remove('flex');
        }

        // Function to search students for the edit form
        let editSearchTimeout;

        function searchStudentsForEdit(event) {
            const searchTerm = event.target.value;
            const resultsContainer = document.getElementById('edit-search-results');

            // Clear previous timeout
            if (editSearchTimeout) {
                clearTimeout(editSearchTimeout);
            }

            // Hide results if search term is empty
            if (searchTerm.trim() === '') {
                resultsContainer.innerHTML = '';
                resultsContainer.classList.add('hidden');
                return;
            }

            // Set new timeout (300ms delay to reduce API calls)
            editSearchTimeout = setTimeout(() => {
                // Make API call to search students
                fetch(`/voting/search-students?query=${encodeURIComponent(searchTerm)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Clear previous results
                        resultsContainer.innerHTML = '';

                        if (data.length === 0) {
                            // Show "no results" message
                            resultsContainer.innerHTML =
                                '<div class="p-3 text-gray-500">No students found</div>';
                        } else {
                            // Display each student found
                            data.forEach(student => {
                                const item = document.createElement('div');
                                item.className = 'p-3 hover:bg-gray-100 cursor-pointer';
                                item.textContent = `${student.name} (${student.email})`;

                                // When a student is selected
                                item.addEventListener('click', function() {
                                    // Set the name in the input field
                                    document.getElementById('edit-candidate-name').value =
                                        student.name;
                                    // Set the student ID in the hidden field
                                    document.getElementById('edit-user-id').value = student
                                        .user_id;
                                    // Hide the results
                                    resultsContainer.classList.add('hidden');
                                });

                                resultsContainer.appendChild(item);
                            });
                        }

                        // Show the results dropdown
                        resultsContainer.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error searching for students:', error);
                    });
            }, 300);
        }

        // Handle edit form submission
        document.getElementById('edit-candidate-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const candidateId = document.getElementById('edit-candidate-id').value;

            // Check for empty required fields
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            `;

            const formData = new FormData(this);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Submit data to update the candidate
            fetch(`/voting/update-candidate/${candidateId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        closeEditModal();

                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className =
                            'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                        successAlert.textContent = 'Candidate updated successfully!';
                        document.body.appendChild(successAlert);

                        // Remove the success message after 3 seconds
                        setTimeout(() => {
                            successAlert.remove();
                        }, 3000);

                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'Error updating candidate');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating candidate');
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });

        // Handle candidate form submission
        document.getElementById('candidate-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Check for empty required fields
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields');
                return;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Saving...
            `;

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Get all candidates data
            const position = document.getElementById('position-0').value;
            const userIds = document.getElementsByName('user_id[]');
            const partylists = document.getElementsByName('partylist[]');

            // For each candidate, use the same position
            for (let i = 0; i < userIds.length; i++) {
                if (userIds[i].value) {
                    formData.append('candidates[]', JSON.stringify({
                        user_id: userIds[i].value,
                        position: position,
                        partylist: partylists[i].value
                    }));
                }
            }

            // Submit data to the server
            fetch('/voting/save-candidate', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Server returned ' + response.status + ' ' + response.statusText);
                    }

                    // Check if the response is JSON before parsing
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Expected JSON response but got ' + contentType);
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        closeModal();

                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className =
                            'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                        successAlert.textContent = 'Candidates added successfully!';
                        document.body.appendChild(successAlert);

                        // Remove the success message after 3 seconds
                        setTimeout(() => {
                            successAlert.remove();
                        }, 3000);

                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'Error saving candidates');
                    }
                })
                .catch(error => {
                    alert('Error saving candidates: ' + error.message);
                    console.error('Error:', error);
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
        });

        // Handle delete confirmation
        document.getElementById('confirm-delete-btn').addEventListener('click', function() {
            const candidateId = document.getElementById('delete-candidate-id').value;

            // Show loading state
            const originalBtnText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Deleting...
            `;

            // Submit request to delete the candidate
            fetch(`/voting/delete-candidate/${candidateId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        closeDeleteModal();

                        // Show success message
                        const successAlert = document.createElement('div');
                        successAlert.className =
                            'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                        successAlert.textContent = 'Candidate deleted successfully!';
                        document.body.appendChild(successAlert);

                        // Remove the success message after 3 seconds
                        setTimeout(() => {
                            successAlert.remove();
                        }, 3000);

                        // Reload the page to show updated data
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        alert(data.message || 'Error deleting candidate');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting candidate');
                })
                .finally(() => {
                    // Restore button state
                    this.disabled = false;
                    this.innerHTML = originalBtnText;
                });
        });

        // Reset button and reset confirmation functionality
        const resetPageBtn = document.getElementById('reset-page');
        if (resetPageBtn) {
            resetPageBtn.addEventListener('click', function() {
                // Show the reset confirmation modal
                document.getElementById('resetConfirmModal').classList.remove('hidden');
                document.getElementById('resetConfirmModal').classList.add('flex');
            });
        }

        // Close reset confirmation modal
        function closeResetModal() {
            document.getElementById('resetConfirmModal').classList.add('hidden');
            document.getElementById('resetConfirmModal').classList.remove('flex');
            document.getElementById('reset-confirmation').value = '';
            document.getElementById('confirm-reset-btn').disabled = true;
            document.getElementById('confirm-reset-btn').classList.add('opacity-50', 'cursor-not-allowed');
        }

        // Handle reset confirmation input
        const resetConfirmationInput = document.getElementById('reset-confirmation');
        if (resetConfirmationInput) {
            resetConfirmationInput.addEventListener('input', function() {
                const confirmResetBtn = document.getElementById('confirm-reset-btn');
                if (this.value === 'RESET') {
                    confirmResetBtn.disabled = false;
                    confirmResetBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    confirmResetBtn.disabled = true;
                    confirmResetBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        }

        // Handle reset confirmation button click
        const confirmResetBtn = document.getElementById('confirm-reset-btn');
        if (confirmResetBtn) {
            confirmResetBtn.addEventListener('click', function() {
                // Show loading state
                const originalBtnText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Resetting...
                `;
                // Submit request to reset voting data
                fetch('{{ route('voting.reset') }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close the modal
                            closeResetModal();

                            // Show success message
                            const successAlert = document.createElement('div');
                            successAlert.className =
                                'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                            successAlert.textContent = data.message ||
                                'All voting data has been reset successfully.';
                            document.body.appendChild(successAlert);

                            // Remove the success message after 3 seconds
                            setTimeout(() => {
                                successAlert.remove();
                            }, 3000);

                            // Reload the page to show updated state
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            alert(data.message || 'Error resetting voting data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error resetting voting data');
                    })
                    .finally(() => {
                        // Restore button state
                        this.disabled = false;
                        this.innerHTML = originalBtnText;
                    });
            });
        }
    </script>
@endsection
