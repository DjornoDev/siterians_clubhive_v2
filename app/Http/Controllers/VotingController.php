<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\VoteDetail;
use App\Models\Club;
use App\Models\User;
use App\Models\ClubMembership;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class VotingController extends Controller
{
    /**
     * Display the voting index page
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Club $club)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user is a teacher and club adviser
        if ($user->role === 'TEACHER' && $user->user_id === $club->club_adviser) {
            return view('voting.teacher.index', compact('club'));
        }

        // Check if user is a student and club member
        if ($user->role === 'STUDENT' && $club->members()->where('tbl_club_membership.user_id', $user->user_id)->exists()) {
            return view('voting.student.index', compact('club'));
        }

        // Redirect unauthorized users
        return redirect()->back()->with('error', 'You do not have access to the voting system for this club.');
    }

    /**
     * Store a new election for a specific club
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Club $club)
    {
        // Authorization check - only club advisers can create elections
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return redirect()->back()->with('error', 'Unauthorized. Only club advisers can create elections.');
        }

        // Validate request
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'end_date' => 'required|date|after:today',
        ]);

        try {
            // Create the election for the specific club
            $election = Election::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_date' => now(),
                'end_date' => $validated['end_date'],
                'club_id' => $club->club_id,
                'is_published' => false,
            ]);

            // Log election creation
            ActionLog::create_log(
                'voting_management',
                'created',
                "Created new voting election: {$election->title} for club: {$club->club_name}",
                [
                    'election_id' => $election->election_id,
                    'title' => $election->title,
                    'end_date' => $election->end_date,
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name
                ]
            );

            return redirect()->route('clubs.voting.index', $club)->with('success', 'Voting created successfully! You can now add candidates.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the voting: ' . $e->getMessage());
        }
    }


    /**
     * Display voting responses for a specific club
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function responses(Club $club)
    {
        // Only allow club advisers to access responses
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return redirect()->back()->with('error', 'You do not have access to voting responses for this club.');
        }

        return view('voting.teacher.responses', compact('club'));
    }
    /**
     * Save candidate information for an election
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveCandidate(Request $request, Club $club)
    {
        try {
            // Authorization check - only club advisers can save candidates
            if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Log the incoming request for debugging
            Log::info('saveCandidate request received', [
                'club_id' => $club->club_id,
                'request_data' => $request->all()
            ]);

            // Handle voting creation
            if ($request->has('title')) {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'required|string',
                    'end_date' => 'required|date|after:today',
                ]);

                try {
                    // Create the election
                    $election = Election::create([
                        'title' => $validated['title'],
                        'description' => $validated['description'],
                        'start_date' => now(),
                        'end_date' => $validated['end_date'],
                        'club_id' => $club->club_id,
                        'is_published' => false,
                    ]);

                    // Log election creation
                    ActionLog::create_log(
                        'voting_management',
                        'created',
                        "Created new voting election: {$election->title}",
                        [
                            'election_id' => $election->election_id,
                            'title' => $election->title,
                            'end_date' => $validated['end_date'],
                            'club_id' => $club->club_id
                        ]
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Voting created successfully! You can now add candidates.',
                        'election_id' => $election->election_id
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'An error occurred while creating the voting: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Handle candidate creation

            $validated = $request->validate([
                'election_id' => 'nullable|exists:tbl_elections,election_id',
                'candidates' => 'required|array',
                'candidates.*.position' => 'required|string|max:100',
                'candidates.*.user_id' => 'required|exists:tbl_users,user_id',
                'candidates.*.partylist' => 'required|string|max:100',
            ]);

            try {
                DB::beginTransaction();

                // Get current election or create one if none exists
                $election = null;
                if (!empty($validated['election_id'])) {
                    $election = Election::find($validated['election_id']);
                } else {
                    // If no election_id provided, get the latest active election for this club
                    $election = Election::where('club_id', $club->club_id)
                        ->where('end_date', '>', now())
                        ->latest()
                        ->first();
                }

                if (!$election) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No active election found. Please create an election first.',
                    ], 404);
                }

                // Get the position from the first candidate (all candidates have the same position)
                $position = $validated['candidates'][0]['position'];

                // Check if there are already candidates for this position
                $existingCandidatesCount = Candidate::where('election_id', $election->election_id)
                    ->where('position', $position)
                    ->count();

                if ($existingCandidatesCount > 0) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Candidates already exist for the position '{$position}'. Cannot add new candidates to a position that already has candidates.",
                        'errors' => ['position' => ['This position already has candidates']]
                    ], 422);
                }

                // Process each candidate
                foreach ($validated['candidates'] as $candidateData) {
                    // Check if this user is already a candidate for any position in this election
                    $existingCandidate = Candidate::where('election_id', $election->election_id)
                        ->where('user_id', $candidateData['user_id'])
                        ->first();

                    if ($existingCandidate) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'This student is already a candidate for another position',
                            'errors' => ['candidates' => ['A student can only be a candidate for one position']]
                        ], 422);
                    }

                    // Check if this user already holds a position in any club
                    $candidateUser = User::find($candidateData['user_id']);
                    if ($candidateUser && $candidateUser->hasClubPosition()) {
                        $currentPosition = $candidateUser->getCurrentClubPosition();
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => "This student already holds the position '{$currentPosition->club_position}' in {$currentPosition->club->club_name}. Students with existing positions cannot run for new positions.",
                            'errors' => ['candidates' => ['Students with existing club positions cannot be candidates']]
                        ], 422);
                    }

                    // Create candidate
                    Candidate::create([
                        'election_id' => $election->election_id,
                        'user_id' => $candidateData['user_id'],
                        'position' => $position,
                        'partylist' => $candidateData['partylist'],
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Candidates saved successfully',
                'election_id' => $election->election_id
            ]);
        } catch (\Exception $e) {
            Log::error('Error in saveCandidate method', [
                'club_id' => $club->club_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving candidates: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Toggle the published status of an election for a specific club
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function togglePublished(Club $club)
    {
        // Only allow club advisers to toggle published status
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            abort(403, 'You do not have permission to toggle voting publication status for this club');
        }

        // Get current active election or create one if none exists
        $election = Election::where('club_id', $club->club_id)->latest()->first();

        if (!$election) {
            // If no election exists, create a default one
            $election = Election::create([
                'title' => $club->club_name . ' Election',
                'description' => $club->club_name . ' election for new student officers',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'club_id' => $club->club_id,
                'is_published' => false,
            ]);
        }

        // Toggle is_published status and force updated_at timestamp change to ensure checksum change
        $oldStatus = $election->is_published;
        $election->update([
            'is_published' => !$election->is_published,
            'updated_at' => now()
        ]);

        // Log the publishing toggle
        ActionLog::create_log(
            'voting_management',
            'updated',
            "Toggled election publication status: {$election->title} - " . ($election->is_published ? 'Published' : 'Unpublished'),
            [
                'election_id' => $election->election_id,
                'title' => $election->title,
                'old_status' => $oldStatus,
                'new_status' => $election->is_published
            ]
        );

        // Ensure the checksum changes by touching the election model
        $election->touch();

        return response()->json([
            'success' => true,
            'is_published' => $election->is_published
        ]);
    }

    /**
     * Get candidates for the active election in a specific club
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCandidates(Club $club)
    {
        try {
            // Get the latest published election for this club
            $election = Election::where('club_id', $club->club_id)
                ->where('is_published', true)
                ->where('end_date', '>', now())
                ->latest()
                ->first();

            if (!$election) {
                // Log that no active election was found
                Log::info('No active election found in getCandidates');

                return response()->json([
                    'success' => false,
                    'message' => 'No active election found.'
                ], 404);
            }

            // Log the found election
            Log::info('Found active election', ['election_id' => $election->election_id, 'title' => $election->title]);

            // Get candidates grouped by position
            $candidates = Candidate::where('election_id', $election->election_id)
                ->join('tbl_users', 'tbl_candidates.user_id', '=', 'tbl_users.user_id')
                ->select('tbl_candidates.*', 'tbl_users.name', 'tbl_users.profile_picture')
                ->orderBy('position')
                ->get()
                ->groupBy('position');

            // Log candidates count
            Log::info('Candidates found', [
                'count' => $candidates->count(),
                'positions' => $candidates->keys()
            ]);

            return response()->json([
                'success' => true,
                'election' => $election,
                'candidates' => $candidates
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getCandidates: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving election data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit votes for the election in a specific club
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitVote(Request $request, Club $club)
    {
        // Check if user is a student and member of this club
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Verify the user is a member of this club
        if (!$club->members()->where('tbl_club_membership.user_id', Auth::id())->exists()) {
            return response()->json(['success' => false, 'message' => 'You are not a member of this club'], 403);
        }
        try {
            // Get the election first to validate available positions
            $election = Election::findOrFail($request->input('election_id'));

            // Get all available positions for this election
            $availablePositions = Candidate::where('election_id', $election->election_id)
                ->select('position')
                ->distinct()
                ->pluck('position')
                ->toArray();

            // Add position validation
            $validated = $request->validate([
                'election_id' => 'required|exists:tbl_elections,election_id',
                'votes' => [
                    'required',
                    'array',
                    function ($attribute, $value, $fail) use ($availablePositions) {
                        // Check if all positions are voted for
                        $votedPositions = array_column($value, 'position');
                        sort($votedPositions);
                        sort($availablePositions);

                        if ($votedPositions !== $availablePositions) {
                            $fail('You must vote for all available positions.');
                        }
                    }
                ],
                'votes.*.position' => ['required', 'string', Rule::in($availablePositions)],
                'votes.*.candidate_id' => [
                    'required',
                    'exists:tbl_candidates,candidate_id',
                ]
            ]);

            // Check if election is active and published
            if (!$election->is_published || $election->end_date < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This election is not currently active.'
                ], 422);
            }

            DB::beginTransaction();            // Check if user has already voted
            $existingVote = Vote::where('election_id', $validated['election_id'])
                ->where('voter_id', Auth::id())
                ->exists();

            if ($existingVote) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'You have already voted in this election.'
                ], 422);
            }

            // Validate that each candidate exists for the election and their positions
            foreach ($validated['votes'] as $vote) {
                $candidate = Candidate::where('candidate_id', $vote['candidate_id'])
                    ->where('election_id', $validated['election_id'])
                    ->first();

                if (!$candidate || $candidate->position !== $vote['position']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid candidate selected for position ' . $vote['position']
                    ], 422);
                }
            }            // Create a single vote record in tbl_votes
            try {
                $vote = Vote::create([
                    'election_id' => $validated['election_id'],
                    'voter_id' => Auth::id()
                ]);

                // Refresh the model to ensure we have the latest data
                $vote->refresh();

                // Check if vote was created successfully
                if (!$vote || !$vote->vote_id) {
                    DB::rollBack();
                    Log::error('Failed to create vote record', [
                        'election_id' => $validated['election_id'],
                        'voter_id' => Auth::id(),
                        'vote_object' => $vote
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create vote record.'
                    ], 500);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Exception creating vote record', [
                    'election_id' => $validated['election_id'],
                    'voter_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create vote record: ' . $e->getMessage()
                ], 500);
            }

            // Record each position choice in tbl_vote_details
            foreach ($validated['votes'] as $voteData) {
                // Add detailed logging before candidate check
                Log::info('About to create vote detail', [
                    'vote_id' => $vote->vote_id,
                    'position' => $voteData['position'],
                    'candidate_id' => $voteData['candidate_id'],
                    'election_id' => $validated['election_id']
                ]);

                // Double-check that the candidate still exists before creating vote detail
                $candidateExists = Candidate::where('candidate_id', $voteData['candidate_id'])
                    ->where('election_id', $validated['election_id'])
                    ->exists();

                Log::info('Candidate existence check', [
                    'candidate_id' => $voteData['candidate_id'],
                    'election_id' => $validated['election_id'],
                    'exists' => $candidateExists
                ]);

                if (!$candidateExists) {
                    DB::rollBack();
                    Log::error('Candidate not found when creating vote detail', [
                        'candidate_id' => $voteData['candidate_id'],
                        'election_id' => $validated['election_id'],
                        'position' => $voteData['position']
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected candidate is no longer available.'
                    ], 422);
                }

                try {
                    // Use DB::table instead of model to avoid any Eloquent issues
                    $voteDetailId = DB::table('tbl_vote_details')->insertGetId([
                        'vote_id' => $vote->vote_id,
                        'position' => $voteData['position'],
                        'candidate_id' => $voteData['candidate_id'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Check if vote detail was created successfully
                    if (!$voteDetailId) {
                        DB::rollBack();
                        Log::error('Failed to create vote detail record using DB::table', [
                            'vote_id' => $vote->vote_id,
                            'position' => $voteData['position'],
                            'candidate_id' => $voteData['candidate_id']
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Failed to record vote details.'
                        ], 500);
                    }

                    Log::info('Vote detail created successfully', [
                        'vote_detail_id' => $voteDetailId,
                        'vote_id' => $vote->vote_id,
                        'position' => $voteData['position'],
                        'candidate_id' => $voteData['candidate_id']
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Exception creating vote detail record', [
                        'vote_id' => $vote->vote_id,
                        'position' => $voteData['position'],
                        'candidate_id' => $voteData['candidate_id'],
                        'error' => $e->getMessage(),
                        'sql_state' => $e->getCode()
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to record vote details: ' . $e->getMessage()
                    ], 500);
                }
            }

            DB::commit();

            // Log the vote submission
            ActionLog::create_log(
                'voting_management',
                'created',
                "Submitted vote for election: {$election->title}",
                [
                    'election_id' => $election->election_id,
                    'election_title' => $election->title,
                    'vote_id' => $vote->vote_id,
                    'positions_voted' => count($validated['votes'])
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Your vote has been recorded successfully.'
            ]);
        } catch (ValidationException $e) {
            Log::error('Vote validation error: ' . json_encode([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'data' => $request->all()
            ]));
            return response()->json([
                'success' => false,
                'message' => 'Invalid vote data provided.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Vote submission error: ' . json_encode([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]));
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if the user has already voted in the election
     * 
     * @param \App\Models\Club $club
     * @param int $electionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVoted(Club $club, $electionId)
    {
        // Check if user is a student and member of this club
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Verify the user is a member of this club
        if (!$club->members()->where('tbl_club_membership.user_id', Auth::id())->exists()) {
            return response()->json(['success' => false, 'message' => 'You are not a member of this club'], 403);
        }

        $hasVoted = Vote::where('election_id', $electionId)
            ->where('voter_id', Auth::id())
            ->exists();

        return response()->json([
            'success' => true,
            'hasVoted' => $hasVoted
        ]);
    }

    /**
     * Get the user's vote details for a specific election
     * 
     * @param \App\Models\Club $club
     * @param int $electionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyVote(Club $club, $electionId)
    {
        // Check if user is a student and member of this club
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Verify the user is a member of this club
        if (!$club->members()->where('tbl_club_membership.user_id', Auth::id())->exists()) {
            return response()->json(['success' => false, 'message' => 'You are not a member of this club'], 403);
        }

        try {
            // Find the vote record
            $vote = Vote::where('election_id', $electionId)
                ->where('voter_id', Auth::id())
                ->first();

            if (!$vote) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have not voted in this election'
                ], 404);
            }

            // Get the vote details with candidate information
            $voteDetails = $vote->voteDetails()
                ->join('tbl_candidates', 'tbl_vote_details.candidate_id', '=', 'tbl_candidates.candidate_id')
                ->join('tbl_users', 'tbl_candidates.user_id', '=', 'tbl_users.user_id')
                ->select(
                    'tbl_vote_details.*',
                    'tbl_candidates.position',
                    'tbl_candidates.partylist',
                    'tbl_users.name as candidate_name',
                    'tbl_users.profile_picture'
                )
                ->get();

            return response()->json([
                'success' => true,
                'voteDetails' => $voteDetails,
                'votedAt' => $vote->created_at
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving vote details: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving your voting details'
            ], 500);
        }
    }

    /**
     * Get all elections for teacher dashboard
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeacherElections(Club $club)
    {
        // Check if the authenticated user is a teacher and club adviser
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Get all elections for this teacher's club
            $elections = Election::where('club_id', $club->club_id)
                ->orderBy('end_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'elections' => $elections
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get detailed election results for teacher dashboard
     * 
     * @param \App\Models\Club $club
     * @param int $electionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getElectionResults(Club $club, $electionId)
    {
        // Check if the authenticated user is a teacher and club adviser
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Get the election
            $election = Election::findOrFail($electionId);

            // Get all votes for this election
            $votes = Vote::where('election_id', $electionId)->get();

            // Get all eligible voters (club members)
            $eligibleVoters = ClubMembership::where('club_id', $election->club_id)->count();

            // Get results by position
            $results = [];
            $positions = Candidate::where('election_id', $electionId)
                ->select('position')
                ->distinct()
                ->get()
                ->pluck('position');

            foreach ($positions as $position) {
                $candidates = Candidate::where('election_id', $electionId)
                    ->where('position', $position)
                    ->join('tbl_users', 'tbl_candidates.user_id', '=', 'tbl_users.user_id')
                    ->select(
                        'tbl_candidates.candidate_id',
                        'tbl_candidates.partylist',
                        'tbl_users.name'
                    )
                    ->get();                // Count votes for each candidate
                $candidatesWithVotes = $candidates->map(function ($candidate) use ($electionId) {
                    // Count votes using the VoteDetail model
                    $voteCount = VoteDetail::where('candidate_id', $candidate->candidate_id)
                        ->whereHas('vote', function ($query) use ($electionId) {
                            $query->where('election_id', $electionId);
                        })
                        ->count();

                    // Convert to array to easily add votes property
                    $candidateArray = $candidate->toArray();
                    $candidateArray['votes'] = $voteCount;
                    return $candidateArray;
                });

                $results[$position] = $candidatesWithVotes;
            }

            // Get recent voting activity
            $recentActivity = Vote::where('election_id', $electionId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->join('tbl_users', 'tbl_votes.voter_id', '=', 'tbl_users.user_id')
                ->select(
                    'tbl_votes.*',
                    'tbl_users.name as voter_name'
                )
                ->get();

            return response()->json([
                'success' => true,
                'election' => $election,
                'votes' => $votes,
                'eligibleVoters' => $eligibleVoters,
                'results' => $results,
                'recentActivity' => $recentActivity
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Reset all voting data including elections, candidates, votes, and vote details.
     * Only accessible to club advisers.
     *
     * @param Request $request
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetVotingData(Request $request, Club $club)
    {
        // Authorization check - only club advisers can reset voting data
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Disable foreign key checks without a transaction first
            // This is safer and avoids transaction issues with schema changes
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            try {
                // Clear all tables in the correct order (child tables first)
                DB::table('tbl_vote_details')->delete();
                DB::table('tbl_votes')->delete();
                DB::table('tbl_candidates')->delete();
                DB::table('tbl_elections')->delete();
            } catch (\Exception $innerException) {
                // Re-enable foreign key checks before rethrowing
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                throw $innerException;
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'success' => true,
                'message' => 'All voting data has been reset successfully.'
            ]);
        } catch (\Exception $e) {
            // Ensure foreign key checks are always re-enabled
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $fkException) {
                // If this also fails, log it but continue with the original error
                Log::error('Failed to re-enable foreign key checks: ' . $fkException->getMessage());
            }

            Log::error('Error resetting voting data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting voting data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search students for candidate selection
     * 
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchStudents(Club $club)
    {
        try {
            // Authorization check - only club advisers can search students
            if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get the current active election for this club
            $currentElection = Election::where('club_id', $club->club_id)
                ->where('end_date', '>', now())
                ->latest()
                ->first();

            // Get all students who are members of this club
            $studentsQuery = User::where('role', 'STUDENT')
                ->whereHas('clubMemberships', function ($query) use ($club) {
                    $query->where('club_id', $club->club_id);
                });

            // If there's an active election, exclude students who are already candidates
            if ($currentElection) {
                $studentsQuery->whereDoesntHave('candidates', function ($query) use ($currentElection) {
                    $query->where('election_id', $currentElection->election_id);
                });
            }

            // Exclude students who already hold positions in OTHER clubs
            // (Students can run for positions in their own club even if they have a position there)
            $studentsQuery->whereDoesntHave('clubMemberships', function ($query) use ($club) {
                $query->where('club_id', '!=', $club->club_id)
                    ->whereNotNull('club_position')
                    ->where('club_position', '!=', '');
            });

            $students = $studentsQuery
                ->select('user_id', 'name', 'email', 'profile_picture')
                ->orderBy('name')
                ->get();

            // Get additional info for club advisers about why students might not appear
            $totalClubMembers = User::where('role', 'STUDENT')
                ->whereHas('clubMemberships', function ($query) use ($club) {
                    $query->where('club_id', $club->club_id);
                })
                ->count();

            $studentsWithOtherPositions = User::where('role', 'STUDENT')
                ->whereHas('clubMemberships', function ($query) use ($club) {
                    $query->where('club_id', $club->club_id);
                })
                ->whereHas('clubMemberships', function ($query) use ($club) {
                    $query->where('club_id', '!=', $club->club_id)
                        ->whereNotNull('club_position')
                        ->where('club_position', '!=', '');
                })
                ->with(['clubMemberships' => function ($query) use ($club) {
                    $query->where('club_id', '!=', $club->club_id)
                        ->whereNotNull('club_position')
                        ->where('club_position', '!=', '')
                        ->with('club');
                }])
                ->get();

            $alreadyCandidates = [];
            if ($currentElection) {
                $alreadyCandidates = User::where('role', 'STUDENT')
                    ->whereHas('clubMemberships', function ($query) use ($club) {
                        $query->where('club_id', $club->club_id);
                    })
                    ->whereHas('candidates', function ($query) use ($currentElection) {
                        $query->where('election_id', $currentElection->election_id);
                    })
                    ->with(['candidates' => function ($query) use ($currentElection) {
                        $query->where('election_id', $currentElection->election_id);
                    }])
                    ->get();
            }

            return response()->json([
                'success' => true,
                'students' => $students,
                'info' => [
                    'total_club_members' => $totalClubMembers,
                    'available_students' => $students->count(),
                    'students_with_other_positions' => $studentsWithOtherPositions,
                    'already_candidates' => $alreadyCandidates,
                    'search_info' => 'Search by student name or email. Students with positions in other clubs or already added as candidates will not appear.'
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in searchStudents: ' . $e->getMessage(), [
                'club_id' => $club->club_id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for changes in voting data for real-time updates
     *
     * @param Request $request
     * @param \App\Models\Club $club
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVotingChanges(Request $request, Club $club)
    {
        $currentChecksum = $request->query('checksum', '');
        $role = Auth::user()->role;

        try {
            // Different data to check based on user role
            if ($role === 'TEACHER') {
                // For teachers, include more details including votes
                $election = Election::where('club_id', $club->club_id)
                    ->where('end_date', '>', now())
                    ->latest()
                    ->first();

                // Include all voting data for checksum generation
                $checksumData = [];

                if ($election) {
                    $checksumData[] = $election;

                    // Include candidates
                    $candidates = Candidate::where('election_id', $election->election_id)
                        ->get();
                    $checksumData[] = $candidates;

                    // Include votes count
                    $votesCount = Vote::where('election_id', $election->election_id)
                        ->count();
                    $checksumData[] = $votesCount;
                }

                // Generate a checksum from all the combined data
                $newChecksum = md5(json_encode($checksumData));

                // Compare checksums
                $hasChanges = $currentChecksum !== $newChecksum;

                return response()->json([
                    'hasChanges' => $hasChanges,
                    'checksum' => $newChecksum
                ]);
            } else {
                // For students, just check the election and candidates
                $election = Election::where('club_id', $club->club_id)
                    ->where('is_published', true)
                    ->where('end_date', '>', now())
                    ->latest()
                    ->first();

                $checksumData = [];

                if ($election) {
                    $checksumData[] = $election;

                    // Include candidates
                    $candidates = Candidate::where('election_id', $election->election_id)
                        ->get();
                    $checksumData[] = $candidates;
                }

                // Generate a checksum from the election and candidates data
                $newChecksum = md5(json_encode($checksumData));

                // Compare checksums
                $hasChanges = $currentChecksum !== $newChecksum;

                return response()->json([
                    'hasChanges' => $hasChanges,
                    'checksum' => $newChecksum
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking voting changes: ' . $e->getMessage());
            return response()->json([
                'hasChanges' => false,
                'error' => 'Error checking for changes'
            ]);
        }
    }

    /**
     * Get candidate data for editing
     * 
     * @param \App\Models\Club $club
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCandidate(Club $club, $candidateId)
    {
        // Authorization check - only club advisers can edit candidates
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Find the candidate with the given ID
            $candidate = Candidate::with('user')
                ->findOrFail($candidateId);

            // Check if the election is published
            $election = Election::findOrFail($candidate->election_id);
            if ($election->is_published) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot edit candidate. The election has already been published.'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'candidate' => [
                    'candidate_id' => $candidate->candidate_id,
                    'position' => $candidate->position,
                    'partylist' => $candidate->partylist,
                    'user_id' => $candidate->user_id,
                    'name' => $candidate->user->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving candidate data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update candidate information
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Club $club
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCandidate(Request $request, Club $club, $candidateId)
    {
        // Authorization check - only club advisers can update candidates
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'position' => 'required|string|max:100',
            'user_id' => 'required|exists:tbl_users,user_id',
            'partylist' => 'required|string|max:100',
        ]);

        try {
            // Find the candidate
            $candidate = Candidate::findOrFail($candidateId);

            // Check if the election is published
            $election = Election::findOrFail($candidate->election_id);
            if ($election->is_published) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update candidate. The election has already been published.'
                ], 403);
            }

            // Check if the selected user is already a candidate for another position in this election
            $existingCandidate = Candidate::where('election_id', $candidate->election_id)
                ->where('user_id', $validated['user_id'])
                ->where('candidate_id', '!=', $candidateId)
                ->first();

            if ($existingCandidate) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student is already a candidate for another position'
                ], 422);
            }

            // Update the candidate
            $candidate->update([
                'position' => $validated['position'],
                'user_id' => $validated['user_id'],
                'partylist' => $validated['partylist']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Candidate updated successfully',
                'candidate' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a candidate
     * 
     * @param \App\Models\Club $club
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCandidate(Club $club, $candidateId)
    {
        // Authorization check - only club advisers can delete candidates
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Find the candidate
            $candidate = Candidate::findOrFail($candidateId);

            // Check if the election is published
            $election = Election::findOrFail($candidate->election_id);
            if ($election->is_published) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete candidate. The election has already been published.'
                ], 403);
            }

            // Delete the candidate
            $candidate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Candidate deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting candidate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update club member positions based on election results
     * This method should be called after an election ends
     * 
     * @param \App\Models\Club $club
     * @param int $electionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMemberPositions(Club $club, $electionId)
    {
        // Authorization check - only club advisers can update member positions
        if (Auth::user()->role !== 'TEACHER' || Auth::user()->user_id !== $club->club_adviser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Get the election
            $election = Election::where('club_id', $club->club_id)
                ->where('election_id', $electionId)
                ->first();

            if (!$election) {
                return response()->json([
                    'success' => false,
                    'message' => 'Election not found'
                ], 404);
            }

            // Check if election has ended
            if ($election->end_date > now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Election has not ended yet'
                ], 422);
            }

            DB::beginTransaction();

            // Get all positions in this election
            $positions = Candidate::where('election_id', $electionId)
                ->select('position')
                ->distinct()
                ->pluck('position');

            $updatedCount = 0;

            foreach ($positions as $position) {
                // Get the candidate with the most votes for this position
                $winner = Candidate::where('election_id', $electionId)
                    ->where('position', $position)
                    ->withCount('votes')
                    ->orderBy('votes_count', 'desc')
                    ->first();

                if ($winner && $winner->votes_count > 0) {
                    // Check if the winner already has a position in another club
                    $winnerUser = User::find($winner->user_id);
                    if ($winnerUser && $winnerUser->hasClubPosition($club->club_id)) {
                        $currentPosition = $winnerUser->getCurrentClubPosition();
                        // Log this issue but continue with other positions
                        ActionLog::create_log(
                            'voting_management',
                            'warning',
                            "Cannot assign position '{$position}' to {$winnerUser->first_name} {$winnerUser->last_name} - already holds '{$currentPosition->club_position}' in {$currentPosition->club->club_name}",
                            [
                                'election_id' => $electionId,
                                'club_id' => $club->club_id,
                                'user_id' => $winner->user_id,
                                'blocked_position' => $position,
                                'existing_position' => $currentPosition->club_position,
                                'existing_club' => $currentPosition->club->club_name
                            ]
                        );
                        continue; // Skip this position assignment
                    }

                    // First, remove the position from any existing member who holds it
                    ClubMembership::where('club_id', $club->club_id)
                        ->where('club_position', $position)
                        ->update(['club_position' => null]);

                    // Then assign the position to the new winner
                    $membership = ClubMembership::where('club_id', $club->club_id)
                        ->where('user_id', $winner->user_id)
                        ->first();

                    if ($membership) {
                        $membership->update(['club_position' => $position]);
                        $updatedCount++;
                    }
                }
            }

            DB::commit();

            // Log the position updates
            ActionLog::create_log(
                'voting_management',
                'updated',
                "Updated {$updatedCount} member positions after election: {$election->title}",
                [
                    'election_id' => $electionId,
                    'club_id' => $club->club_id,
                    'positions_updated' => $updatedCount
                ]
            );

            return response()->json([
                'success' => true,
                'message' => "Successfully updated {$updatedCount} member positions",
                'positions_updated' => $updatedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating member positions: ' . $e->getMessage()
            ], 500);
        }
    }
}
