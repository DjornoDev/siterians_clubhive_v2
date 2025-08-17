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
    public function index()
    {
        // Check if the authenticated user is a teacher who is adviser of club ID 1
        if (Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1)) {
            return view('voting.teacher.index');
        }

        // For students, return the student voting view
        if (Auth::user()->role === 'STUDENT') {
            return view('voting.student.index');
        }

        // Redirect others back
        return redirect()->back()->with('error', 'You do not have access to the voting system.');
    }

    public function store(Request $request)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Validate request
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
                'club_id' => 1,
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
                    'end_date' => $election->end_date,
                    'club_id' => 1
                ]
            );

            return redirect()->route('voting.index')->with('success', 'Voting created successfully! You can now add candidates.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the voting: ' . $e->getMessage());
        }
    }

    /**
     * Search for students who are members of club ID 1
     */
    public function searchStudents(Request $request)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = $request->input('query');

        // Get members of club with ID 1 (SSLG)
        $students = User::whereHas('clubMemberships', function ($q) {
            $q->where('club_id', 1);
        })
            ->where('role', 'STUDENT')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->select('user_id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($students);
    }
    public function responses()
    {
        // Only allow teachers who are advisers of club ID 1 to access responses
        if (Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1)) {
            return view('voting.teacher.responses');
        }

        // Redirect others back
        return redirect()->back()->with('error', 'You do not have access to voting responses.');
    }
    /**
     * Save candidate information for an election
     */
    public function saveCandidate(Request $request)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
                    'club_id' => 1,
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
                        'end_date' => $election->end_date,
                        'club_id' => 1
                    ]
                );

                return redirect()->route('voting.index')->with('success', 'Voting created successfully! You can now add candidates.');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'An error occurred while creating the voting: ' . $e->getMessage());
            }
        }        // Handle candidate creation
        // First, preprocess the candidates array since it's coming as JSON strings
        $candidates = [];
        if ($request->has('candidates')) {
            foreach ($request->candidates as $candidateJson) {
                $candidates[] = json_decode($candidateJson, true);
            }
            $request->merge(['candidates' => $candidates]);
        }

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
                // If no election_id provided, get the latest active election
                $election = Election::where('club_id', 1)
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

            // First, remove existing candidates for this position to start fresh
            Candidate::where('election_id', $election->election_id)
                ->where('position', $position)
                ->delete();

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
    }
    public function togglePublished()
    {
        // Only allow teachers who are advisers of club ID 1 to toggle published status
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            abort(403, 'You do not have permission to toggle voting publication status');
        }

        // Get current active election or create one if none exists
        $election = Election::where('club_id', 1)->latest()->first();

        if (!$election) {
            // If no election exists, create a default one
            $election = Election::create([
                'title' => 'SSLG Election',
                'description' => 'SSLG election for new student officers',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'club_id' => 1,
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
     * Get candidates for the active election
     */    public function getCandidates()
    {
        try {
            // Get the latest published election
            $election = Election::where('club_id', 1)
                ->where('is_published', true)
                ->where('end_date', '>', now())
                ->latest()
                ->first();

            if (!$election) {
                // Log that no active election was found
                \Log::info('No active election found in getCandidates');

                return response()->json([
                    'success' => false,
                    'message' => 'No active election found.'
                ], 404);
            }

            // Log the found election
            \Log::info('Found active election', ['election_id' => $election->election_id, 'title' => $election->title]);

            // Get candidates grouped by position
            $candidates = Candidate::where('election_id', $election->election_id)
                ->join('tbl_users', 'tbl_candidates.user_id', '=', 'tbl_users.user_id')
                ->select('tbl_candidates.*', 'tbl_users.name', 'tbl_users.profile_picture')
                ->orderBy('position')
                ->get()
                ->groupBy('position');

            // Log candidates count
            \Log::info('Candidates found', [
                'count' => $candidates->count(),
                'positions' => $candidates->keys()
            ]);

            return response()->json([
                'success' => true,
                'election' => $election,
                'candidates' => $candidates
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in getCandidates: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error retrieving election data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit votes for the election
     */
    public function submitVote(Request $request)
    {
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
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
     */
    public function checkVoted($electionId)
    {
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
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
     */
    public function getMyVote($electionId)
    {
        if (Auth::user()->role !== 'STUDENT') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
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
     */
    public function getTeacherElections()
    {
        // Check if the authenticated user is a teacher who is adviser of club ID 1
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            // Get all elections for this teacher's club
            $elections = Election::where('club_id', 1)
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
     */
    public function getElectionResults($electionId)
    {
        // Check if the authenticated user is a teacher who is adviser of club ID 1
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
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
     * Only accessible to teachers who are advisers of club ID 1.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */    public function resetVotingData(Request $request)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
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
     * Check for changes in voting data for real-time updates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVotingChanges(Request $request)
    {
        $currentChecksum = $request->query('checksum', '');
        $role = Auth::user()->role;

        try {
            // Different data to check based on user role
            if ($role === 'TEACHER') {
                // For teachers, include more details including votes
                $election = Election::where('club_id', 1)
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
                $election = Election::where('club_id', 1)
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
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editCandidate($candidateId)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
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
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCandidate(Request $request, $candidateId)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validated = $request->validate([
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
     * @param int $candidateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCandidate($candidateId)
    {
        // Authorization check
        if (!(Auth::user()->role === 'TEACHER' && Auth::user()->advisedClubs->contains('club_id', 1))) {
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
}
