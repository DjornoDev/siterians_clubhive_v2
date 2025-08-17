<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Club;
use App\Exports\VotingResultsExport;

echo "=== Testing VotingResultsExport with Custom Positions ===\n\n";

// Find a club with elections  
$club = Club::whereHas('elections', function ($query) {
    $query->whereHas('candidates');
})->first();

if (!$club) {
    echo "No club with elections found.\n";
    exit;
}

echo "Testing export for club: " . $club->club_name . "\n\n";

// Create the export
$export = new VotingResultsExport($club);
$results = $export->collection();

echo "Export results:\n";
echo "Total rows: " . $results->count() . "\n\n";

// Show first few rows to see structure
$rows = $results->take(10);
foreach ($rows as $index => $row) {
    echo "Row " . ($index + 1) . ":\n";
    foreach ($row as $key => $value) {
        echo "  " . $key . ": " . $value . "\n";
    }
    echo "\n";
}

// Look specifically for custom positions
echo "=== Looking for Custom Positions in Export ===\n";
$customPositionRows = $results->filter(function ($row) {
    return isset($row['Position']) && $row['Position'] === 'Testing Text Input POsition';
});

if ($customPositionRows->count() > 0) {
    echo "Found " . $customPositionRows->count() . " rows with custom position:\n";
    foreach ($customPositionRows as $row) {
        echo "- Section: " . $row['Section'] .
            ", Position: " . $row['Position'] .
            ", Candidate: " . $row['Candidate Name'] .
            ", Status: " . $row['Status'] . "\n";
    }
} else {
    echo "No custom position rows found in export.\n";
}

// Check winners section
echo "\n=== Winners in Export ===\n";
$winnerRows = $results->filter(function ($row) {
    return isset($row['Status']) && $row['Status'] === 'WINNER';
});

if ($winnerRows->count() > 0) {
    echo "Found " . $winnerRows->count() . " winners in export:\n";
    foreach ($winnerRows as $row) {
        echo "- " . $row['Position'] . ": " . $row['Candidate Name'] .
            " (" . $row['Party List'] . ") - " . $row['Vote Count'] . " votes\n";
    }
} else {
    echo "No winners found in export.\n";
}

echo "\nExport test complete!\n";
