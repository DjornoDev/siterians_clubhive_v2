<?php

namespace App\Exports;

use App\Models\Club;
use App\Models\Election;

class VotingResultsExport
{
    protected $club;

    // Constants for headers
    private const ELECTION_ID = 'Election ID';
    private const ELECTION_TITLE = 'Election Title';
    private const POSITION = 'Position';
    private const CANDIDATE_NAME = 'Candidate Name';
    private const PARTY_LIST = 'Party List';
    private const VOTE_COUNT = 'Vote Count';
    private const PERCENTAGE = 'Percentage';
    private const STATUS = 'Status';
    private const ELECTION_START = 'Election Start';
    private const ELECTION_END = 'Election End';
    private const SECTION = 'Section';
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function collection()
    {
        $elections = Election::where('club_id', $this->club->club_id)
            ->with(['candidates.user', 'votes.voteDetails.candidate.user'])
            ->get();

        $results = collect();

        // Add winners summary section
        $this->addWinnersSummary($elections, $results);
        $this->addSeparator($results);
        $this->addDetailedResults($elections, $results);

        return $results;
    }

    private function addWinnersSummary($elections, &$results)
    {
        $results->push($this->createHeaderRow('🏆 ELECTION WINNERS SUMMARY'));

        foreach ($elections as $election) {
            $candidatesByPosition = $election->candidates->groupBy('position');

            foreach ($candidatesByPosition as $position => $candidates) {
                $winner = $this->findWinner($candidates, $election);

                if ($winner) {
                    $results->push($this->createWinnerRow($election, $position, $winner));
                }
            }
        }
    }

    private function addDetailedResults($elections, &$results)
    {
        $results->push($this->createHeaderRow('📊 DETAILED ELECTION RESULTS'));

        foreach ($elections as $election) {
            $candidatesByPosition = $election->candidates->groupBy('position');

            foreach ($candidatesByPosition as $position => $candidates) {
                $this->addPositionResults($election, $position, $candidates, $results);
            }
        }
    }

    private function findWinner($candidates, $election)
    {
        $candidateVotes = $this->calculateVotes($candidates, $election);

        if (empty($candidateVotes) || $candidateVotes[0]['votes'] <= 0) {
            return null;
        }

        return $candidateVotes[0];
    }

    private function calculateVotes($candidates, $election)
    {
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

        // Sort by votes (descending)
        usort($candidateVotes, function ($a, $b) {
            return $b['votes'] - $a['votes'];
        });

        return $candidateVotes;
    }

    private function addPositionResults($election, $position, $candidates, &$results)
    {
        $candidateVotes = $this->calculateVotes($candidates, $election);
        $totalVotes = array_sum(array_column($candidateVotes, 'votes'));

        foreach ($candidateVotes as $index => $candidateData) {
            $results->push($this->createResultRow(
                $election,
                $position,
                $candidateData,
                $index,
                $totalVotes
            ));
        }
    }

    private function createHeaderRow($sectionTitle)
    {
        return [
            self::SECTION => $sectionTitle,
            self::ELECTION_ID => '',
            self::ELECTION_TITLE => '',
            self::POSITION => '',
            self::CANDIDATE_NAME => '',
            self::PARTY_LIST => '',
            self::VOTE_COUNT => '',
            self::PERCENTAGE => '',
            self::STATUS => '',
            self::ELECTION_START => '',
            self::ELECTION_END => '',
        ];
    }

    private function createWinnerRow($election, $position, $winnerData)
    {
        $candidate = $winnerData['candidate'];
        $votes = $winnerData['votes'];

        return [
            self::SECTION => 'WINNER',
            self::ELECTION_ID => $election->election_id,
            self::ELECTION_TITLE => $election->title,
            self::POSITION => $position,
            self::CANDIDATE_NAME => $candidate->user ? $candidate->user->name : 'N/A',
            self::PARTY_LIST => $candidate->partylist ?? 'N/A',
            self::VOTE_COUNT => $votes,
            self::PERCENTAGE => $this->calculatePercentage($votes, $votes) . '%', // 100% for winner in this context
            self::STATUS => 'WINNER',
            self::ELECTION_START => $this->formatDate($election->start_date),
            self::ELECTION_END => $this->formatDate($election->end_date),
        ];
    }

    private function createResultRow($election, $position, $candidateData, $index, $totalVotes)
    {
        $candidate = $candidateData['candidate'];
        $votes = $candidateData['votes'];
        $percentage = $this->calculatePercentage($votes, $totalVotes);
        $status = $this->determineStatus($index, $votes);

        return [
            self::SECTION => 'RESULT',
            self::ELECTION_ID => $election->election_id,
            self::ELECTION_TITLE => $election->title,
            self::POSITION => $position,
            self::CANDIDATE_NAME => $candidate->user ? $candidate->user->name : 'N/A',
            self::PARTY_LIST => $candidate->partylist ?? 'N/A',
            self::VOTE_COUNT => $votes,
            self::PERCENTAGE => $percentage . '%',
            self::STATUS => $status,
            self::ELECTION_START => $this->formatDate($election->start_date),
            self::ELECTION_END => $this->formatDate($election->end_date),
        ];
    }

    private function addSeparator(&$results)
    {
        $results->push($this->createHeaderRow(''));
    }

    private function calculatePercentage($votes, $totalVotes)
    {
        return $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 1) : 0;
    }

    private function determineStatus($index, $votes)
    {
        if ($index === 0 && $votes > 0) {
            return 'WINNER';
        }

        if ($votes > 0) {
            return 'CANDIDATE';
        }

        return 'NO VOTES';
    }

    private function formatDate($date)
    {
        return $date ? $date->format(self::DATE_FORMAT) : 'N/A';
    }
}
