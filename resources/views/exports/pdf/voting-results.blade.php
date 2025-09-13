<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $club->club_name }} - Voting Results Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #7C3AED;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #7C3AED;
            font-size: 24px;
            margin: 0 0 10px 0;
        }

        .header h2 {
            color: #6B7280;
            font-size: 16px;
            font-weight: normal;
            margin: 0;
        }

        .club-info {
            background-color: #FAF5FF;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #7C3AED;
        }

        .election-section {
            margin-bottom: 40px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
        }

        .election-header {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
            padding: 15px;
            margin: 0;
        }

        .election-header h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .election-stats {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #E5E7EB;
            background-color: #F9FAFB;
        }

        .stats-label {
            font-size: 10px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .stats-value {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .results-table th {
            background-color: #F3F4F6;
            border: 1px solid #D1D5DB;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }

        .results-table td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            font-size: 10px;
        }

        .results-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .winner-row {
            background-color: #D1FAE5 !important;
            font-weight: bold;
        }

        .winner-badge {
            background-color: #059669;
            color: white;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-completed {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-ongoing {
            background-color: #DBEAFE;
            color: #1E40AF;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .participation-analysis {
            background-color: #F8FAFC;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .progress-bar {
            width: 100%;
            background-color: #E5E7EB;
            border-radius: 10px;
            height: 8px;
            overflow: hidden;
            margin: 5px 0;
        }

        .progress-fill {
            height: 100%;
            border-radius: 10px;
        }

        .progress-excellent {
            background-color: #059669;
        }

        .progress-good {
            background-color: #3B82F6;
        }

        .progress-fair {
            background-color: #F59E0B;
        }

        .progress-poor {
            background-color: #EF4444;
        }

        .signatures {
            margin-top: 50px;
            display: table;
            width: 100%;
        }

        .signature-row {
            display: table-row;
        }

        .signature-cell {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 20px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 30px 0 10px 0;
            height: 1px;
        }

        .signature-label {
            font-size: 10px;
            color: #6B7280;
            margin-bottom: 5px;
        }

        .signature-name {
            font-size: 11px;
            font-weight: bold;
        }

        .signature-title {
            font-size: 9px;
            color: #9CA3AF;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 9px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(124, 58, 237, 0.1);
            z-index: -1;
            font-weight: bold;
        }

        .page-break {
            page-break-before: always;
        }

        .chart-placeholder {
            width: 100%;
            height: 100px;
            background-color: #F3F4F6;
            border: 2px dashed #D1D5DB;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            font-style: italic;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="watermark">OFFICIAL</div>

    <!-- Header -->
    <div class="header">
        <div style="display: table; width: 100%; margin-bottom: 15px;">
            <div style="display: table-cell; width: 80px; vertical-align: middle;">
                <div
                    style="width: 70px; height: 70px; background-color: #7C3AED; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 24px;">
                    CH
                </div>
            </div>
            <div style="display: table-cell; vertical-align: middle; text-align: left; padding-left: 20px;">
                <h1 style="margin: 0; font-size: 28px;">{{ $club->club_name }}</h1>
                <h2 style="margin: 5px 0 0 0; font-size: 18px;">OFFICIAL VOTING RESULTS REPORT</h2>
                <div
                    style="margin-top: 10px; padding: 8px 15px; background-color: #7C3AED; color: white; border-radius: 20px; display: inline-block; font-size: 9px; font-weight: bold;">
                    🏆 DEMOCRATIC ELECTION RESULTS
                </div>
            </div>
        </div>

        <div
            style="background: linear-gradient(90deg, #7C3AED 0%, #8B5CF6 100%); color: white; padding: 12px 20px; border-radius: 8px; text-align: center; margin-top: 15px;">
            <p style="font-size: 11px; margin: 0; font-weight: bold;">
                📅 Generated on {{ $export_date->format('F d, Y \a\t g:i A') }}
            </p>
            <p style="font-size: 9px; margin: 3px 0 0 0; opacity: 0.9;">
                Document Reference: VR-{{ $club->club_id }}-{{ $export_date->format('Ymd-His') }}
            </p>
        </div>

        <div
            style="background-color: #FEF2F2; border-left: 4px solid #EF4444; padding: 10px 15px; margin-top: 15px; border-radius: 0 8px 8px 0;">
            <p style="font-size: 10px; color: #B91C1C; margin: 0; font-weight: bold;">
                🔒 CONFIDENTIAL DOCUMENT - OFFICIAL ELECTION RESULTS
            </p>
            <p style="font-size: 9px; color: #DC2626; margin: 3px 0 0 0;">
                Contains sensitive voting data. Handle according to privacy policies.
            </p>
        </div>
    </div>

    <!-- Club Information -->
    <div class="club-info">
        <h3 style="color: #374151; font-size: 14px; margin: 0 0 10px 0;">CLUB INFORMATION</h3>
        <p><strong>Club Name:</strong> {{ $club->club_name }}</p>
        <p><strong>Club Adviser:</strong> {{ $club->adviser ? $club->adviser->name : 'No adviser assigned' }}</p>
        @if ($club->adviser)
            <p><strong>Adviser Email:</strong> {{ $club->adviser->email }}</p>
        @endif
        <p><strong>Total Elections:</strong> {{ $elections->count() }}</p>
        <p><strong>Democratic Process:</strong> {{ $elections->count() }} election(s) conducted with transparency and
            accountability</p>
    </div>

    <!-- Elections Results -->
    @foreach ($elections as $electionIndex => $election)
        @php
            $votes = \App\Models\Vote::where('election_id', $election->election_id)->get();
            $voteDetails = \App\Models\VoteDetail::whereIn('vote_id', $votes->pluck('vote_id'))->get();
            $candidates = \App\Models\Candidate::where('election_id', $election->election_id)
                ->with(['user'])
                ->get();
            $totalVotes = $votes->count();
            $eligibleVoters = \App\Models\ClubMembership::where('club_id', $club->club_id)
                ->where('club_role', 'MEMBER')
                ->count();
            $participationRate = $eligibleVoters > 0 ? round(($totalVotes / $eligibleVoters) * 100, 2) : 0;
            $candidateVotes = $voteDetails->groupBy('candidate_id')->map->count();
            $positions = $candidates->groupBy('position');
        @endphp

        <div class="election-section {{ $electionIndex > 0 ? 'page-break' : '' }}">
            <!-- Election Header -->
            <div class="election-header">
                <h3>{{ $election->election_name }}</h3>
                <p style="margin: 0; font-size: 12px; opacity: 0.9;">
                    {{ \Carbon\Carbon::parse($election->start_date)->format('M d, Y H:i') }} -
                    {{ \Carbon\Carbon::parse($election->end_date)->format('M d, Y H:i') }}
                </p>
                <p style="margin: 5px 0 0 0; font-size: 11px; opacity: 0.8;">
                    Status:
                    <span class="{{ $election->status === 'COMPLETED' ? 'status-completed' : 'status-ongoing' }}">
                        {{ $election->status }}
                    </span>
                </p>
            </div>

            <!-- Election Statistics -->
            <div class="election-stats">
                <div class="stats-row">
                    <div class="stats-cell">
                        <div class="stats-label">Total Votes</div>
                        <div class="stats-value">{{ $totalVotes }}</div>
                    </div>
                    <div class="stats-cell">
                        <div class="stats-label">Eligible Voters</div>
                        <div class="stats-value">{{ $eligibleVoters }}</div>
                    </div>
                    <div class="stats-cell">
                        <div class="stats-label">Participation Rate</div>
                        <div class="stats-value">{{ $participationRate }}%</div>
                    </div>
                    <div class="stats-cell">
                        <div class="stats-label">Candidates</div>
                        <div class="stats-value">{{ $candidates->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Participation Analysis -->
            <div class="participation-analysis">
                <h4 style="margin: 0 0 10px 0; font-size: 12px; color: #374151;">PARTICIPATION ANALYSIS</h4>
                @php
                    $turnoutLevel =
                        $participationRate >= 75
                            ? 'Excellent'
                            : ($participationRate >= 50
                                ? 'Good'
                                : ($participationRate >= 30
                                    ? 'Fair'
                                    : 'Poor'));
                    $progressClass =
                        $participationRate >= 75
                            ? 'progress-excellent'
                            : ($participationRate >= 50
                                ? 'progress-good'
                                : ($participationRate >= 30
                                    ? 'progress-fair'
                                    : 'progress-poor'));
                @endphp
                <p style="margin: 0 0 5px 0; font-size: 11px;">
                    <strong>Voter Turnout:</strong> {{ $turnoutLevel }} ({{ $participationRate }}%)
                </p>
                <div class="progress-bar">
                    <div class="progress-fill {{ $progressClass }}" style="width: {{ $participationRate }}%"></div>
                </div>
                <p style="margin: 5px 0 0 0; font-size: 10px; color: #6B7280;">
                    {{ $totalVotes }} out of {{ $eligibleVoters }} eligible voters participated
                </p>
            </div>

            <!-- Results Table -->
            <table class="results-table">
                <thead>
                    <tr>
                        <th style="width: 20%">Position</th>
                        <th style="width: 25%">Candidate</th>
                        <th style="width: 15%">Class</th>
                        <th style="width: 15%">Partylist</th>
                        <th style="width: 10%">Votes</th>
                        <th style="width: 10%">Percentage</th>
                        <th style="width: 5%">Result</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($positions as $positionId => $positionCandidates)
                        @php
                            $positionVoteDetails = $voteDetails->whereIn(
                                'candidate_id',
                                $positionCandidates->pluck('candidate_id'),
                            );
                            $positionTotalVotes = $positionVoteDetails->count();
                            $sortedCandidates = $positionCandidates->sortByDesc(function ($candidate) use (
                                $candidateVotes,
                            ) {
                                return $candidateVotes->get($candidate->candidate_id, 0);
                            });
                        @endphp

                        @foreach ($sortedCandidates as $index => $candidate)
                            @php
                                $candidateVoteCount = $candidateVotes->get($candidate->candidate_id, 0);
                                $percentage =
                                    $positionTotalVotes > 0
                                        ? round(($candidateVoteCount / $positionTotalVotes) * 100, 2)
                                        : 0;
                                $isWinner = $index === 0 && $candidateVoteCount > 0;
                            @endphp
                            <tr class="{{ $isWinner ? 'winner-row' : '' }}">
                                <td style="{{ $index > 0 ? 'border-top: none;' : '' }}">
                                    @if ($index === 0)
                                        <strong>{{ $candidate->position ?? 'Unknown Position' }}</strong>
                                        <br><small style="color: #6B7280;">{{ $positionCandidates->count() }}
                                            candidates</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $candidate->user ? $candidate->user->name : 'Unknown Candidate' }}</strong>
                                </td>
                                <td>
                                    @if ($candidate->user && $candidate->user->section)
                                        {{ $candidate->user->section->schoolClass ? 'Grade ' . $candidate->user->section->schoolClass->grade_level : '' }}
                                        {{ $candidate->user->section->section_name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $candidate->partylist ?? 'Independent' }}</td>
                                <td style="text-align: center; font-weight: bold;">{{ $candidateVoteCount }}</td>
                                <td style="text-align: center;">{{ $percentage }}%</td>
                                <td style="text-align: center;">
                                    @if ($isWinner)
                                        <span class="winner-badge">WIN</span>
                                    @else
                                        <small style="color: #6B7280;">—</small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

            <!-- Winners Summary -->
            @php
                $winners = [];
                foreach ($positions as $positionId => $positionCandidates) {
                    $sortedCandidates = $positionCandidates->sortByDesc(function ($candidate) use ($candidateVotes) {
                        return $candidateVotes->get($candidate->candidate_id, 0);
                    });
                    $winner = $sortedCandidates->first();
                    if ($winner && $candidateVotes->get($winner->candidate_id, 0) > 0) {
                        $winners[] = $winner;
                    }
                }
            @endphp

            @if (count($winners) > 0)
                <div style="margin-top: 20px; background-color: #D1FAE5; padding: 15px; border-radius: 8px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 12px; color: #065F46;">🏆 WINNERS SUMMARY</h4>
                    @foreach ($winners as $winner)
                        <p style="margin: 3px 0; font-size: 11px;">
                            <strong>{{ $winner->position ?? 'Unknown Position' }}:</strong>
                            {{ $winner->user ? $winner->user->name : 'Unknown Candidate' }}
                            ({{ $candidateVotes->get($winner->candidate_id, 0) }} votes)
                        </p>
                    @endforeach
                </div>
            @endif

            <!-- Voting Timeline (if available) -->
            <div style="margin-top: 15px; font-size: 10px; color: #6B7280;">
                <p><strong>Election Duration:</strong>
                    {{ \Carbon\Carbon::parse($election->start_date)->diffInDays(\Carbon\Carbon::parse($election->end_date)) + 1 }}
                    days</p>
                <p><strong>Average Votes per Position:</strong>
                    {{ $positions->count() > 0 ? round($totalVotes / $positions->count(), 1) : 0 }}</p>
            </div>
        </div>
    @endforeach

    <!-- Overall Summary (if multiple elections) -->
    @if ($elections->count() > 1)
        <div
            style="margin-top: 30px; background-color: #FAF5FF; padding: 20px; border-radius: 8px; border: 1px solid #E5E7EB;">
            <h3 style="color: #374151; font-size: 14px; margin: 0 0 15px 0;">HISTORICAL ELECTION SUMMARY</h3>

            @php
                $avgParticipation = $elections->avg(function ($election) use ($club) {
                    $votes = \App\Models\Vote::where('election_id', $election->election_id)->count();
                    $eligible = \App\Models\ClubMembership::where('club_id', $club->club_id)
                        ->where('club_role', 'MEMBER')
                        ->count();
                    return $eligible > 0 ? ($votes / $eligible) * 100 : 0;
                });

                $democraticHealth =
                    $avgParticipation >= 60 ? 'Strong' : ($avgParticipation >= 40 ? 'Good' : 'Developing');
            @endphp

            <div style="display: table; width: 100%;">
                <div style="display: table-row;">
                    <div
                        style="display: table-cell; width: 33.33%; padding: 10px; text-align: center; border: 1px solid #E5E7EB; background-color: white;">
                        <div style="font-size: 10px; color: #7C3AED; margin-bottom: 3px;">TOTAL ELECTIONS</div>
                        <div style="font-size: 16px; font-weight: bold;">{{ $elections->count() }}</div>
                    </div>
                    <div
                        style="display: table-cell; width: 33.33%; padding: 10px; text-align: center; border: 1px solid #E5E7EB; background-color: white;">
                        <div style="font-size: 10px; color: #7C3AED; margin-bottom: 3px;">AVG PARTICIPATION</div>
                        <div style="font-size: 16px; font-weight: bold;">{{ round($avgParticipation, 1) }}%</div>
                    </div>
                    <div
                        style="display: table-cell; width: 33.33%; padding: 10px; text-align: center; border: 1px solid #E5E7EB; background-color: white;">
                        <div style="font-size: 10px; color: #7C3AED; margin-bottom: 3px;">DEMOCRATIC HEALTH</div>
                        <div style="font-size: 16px; font-weight: bold;">{{ $democraticHealth }}</div>
                    </div>
                </div>
            </div>

            <p style="margin: 15px 0 0 0; font-size: 10px; color: #6B7280; text-align: center; font-style: italic;">
                This club demonstrates {{ strtolower($democraticHealth) }} democratic practices with consistent
                election processes.
            </p>
        </div>
    @endif

    <!-- Signatures -->
    <div class="signatures">
        <div
            style="text-align: center; margin-bottom: 20px; padding: 15px; background-color: #F8FAFC; border-radius: 8px;">
            <h3 style="color: #374151; font-size: 14px; margin: 0 0 10px 0;">OFFICIAL AUTHENTICATION</h3>
            <p style="font-size: 10px; color: #6B7280; margin: 0;">
                This document contains official election results verified and authenticated by the following
                authorities:
            </p>
        </div>

        <div class="signature-row">
            <div class="signature-cell">
                <div
                    style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 15px; margin: 0 10px; background-color: #FEFEFE;">
                    <div class="signature-line"></div>
                    <div class="signature-label">Prepared by</div>
                    <div class="signature-name">{{ $exported_by }}</div>
                    <div class="signature-title">Club Adviser</div>
                    <div style="font-size: 8px; color: #9CA3AF; margin-top: 5px;">
                        Date: {{ $export_date->format('M d, Y') }}
                    </div>
                </div>
            </div>
            <div class="signature-cell">
                <div
                    style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 15px; margin: 0 10px; background-color: #FEFEFE;">
                    <div class="signature-line"></div>
                    <div class="signature-label">Election Commissioner</div>
                    <div class="signature-name">_________________________</div>
                    <div class="signature-title">Student Government Officer</div>
                    <div style="font-size: 8px; color: #9CA3AF; margin-top: 5px;">
                        Date: _______________
                    </div>
                </div>
            </div>
            <div class="signature-cell">
                <div
                    style="border: 1px solid #E5E7EB; border-radius: 8px; padding: 15px; margin: 0 10px; background-color: #FEFEFE;">
                    <div class="signature-line"></div>
                    <div class="signature-label">Approved by</div>
                    <div class="signature-name">_________________________</div>
                    <div class="signature-title">School Administrator</div>
                    <div style="font-size: 8px; color: #9CA3AF; margin-top: 5px;">
                        Date: _______________
                    </div>
                </div>
            </div>
        </div>

        <div
            style="margin-top: 20px; text-align: center; padding: 10px; background-color: #FEF3C7; border-radius: 8px; border: 1px solid #F59E0B;">
            <p style="font-size: 9px; color: #92400E; margin: 0; font-weight: bold;">
                ⚠️ IMPORTANT: This document requires official signatures before it becomes legally binding.
            </p>
            <p style="font-size: 8px; color: #B45309; margin: 5px 0 0 0;">
                All signatures must be authenticated and witnessed according to school election policies.
            </p>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Siterians ClubHive - {{ $club->club_name }} Official Election Results</p>
        <p><strong>CONFIDENTIAL DOCUMENT</strong> - Contains official election results and voter data</p>
        <p>Generated by ClubHive System | Document ID: VR-{{ $club->club_id }}-{{ date('Ymd') }} | Page 1 of 1</p>
    </div>
</body>

</html>
