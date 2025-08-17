<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidate;

echo "=== Checking Candidate Positions in Database ===\n\n";

$positions = Candidate::select('position')->distinct()->pluck('position');

if ($positions->count() > 0) {
    echo "Found " . $positions->count() . " distinct positions:\n";
    foreach ($positions as $position) {
        echo "- " . $position . "\n";
    }
} else {
    echo "No candidates found in database.\n";
}

echo "\n=== Position Handling Test ===\n";

// Test the standard position order that we defined
$standardPositions = [
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

echo "\nStandard positions that should be prioritized:\n";
foreach ($standardPositions as $pos) {
    echo "- " . $pos . "\n";
}

// Check if any database positions are custom (not in standard list)
$customPositions = $positions->diff($standardPositions);

if ($customPositions->count() > 0) {
    echo "\nCustom positions found in database:\n";
    foreach ($customPositions as $customPos) {
        echo "- " . $customPos . " (CUSTOM)\n";
    }
} else {
    echo "\nNo custom positions found - all positions match standard list.\n";
}

echo "\n=== Testing Winner Logic ===\n";

// Test elections with candidates
$elections = \App\Models\Election::with(['candidates.user', 'votes.voteDetails.candidate.user'])->get();

foreach ($elections as $election) {
    echo "\nElection: " . $election->title . "\n";

    $candidatesByPosition = $election->candidates->groupBy('position');

    foreach ($candidatesByPosition as $position => $candidates) {
        echo "  Position: " . $position . "\n";

        $candidateVotes = [];
        foreach ($candidates as $candidate) {
            $voteCount = $election->votes()
                ->whereHas('voteDetails', function ($query) use ($candidate) {
                    $query->where('candidate_id', $candidate->candidate_id);
                })
                ->count();

            $candidateVotes[] = [
                'candidate' => $candidate,
                'votes' => $voteCount
            ];
        }

        // Sort by votes
        usort($candidateVotes, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });

        foreach ($candidateVotes as $index => $candidateData) {
            $candidate = $candidateData['candidate'];
            $votes = $candidateData['votes'];
            $status = $index === 0 && $votes > 0 ? 'WINNER' : 'CANDIDATE';

            echo "    - " . ($candidate->user ? $candidate->user->name : 'Unknown') .
                " (" . ($candidate->partylist ?? 'No Party') . "): " .
                $votes . " votes [" . $status . "]\n";
        }
    }
}

echo "\nPosition check complete!\n";
