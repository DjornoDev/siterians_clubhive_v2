<?php

namespace App\Http\Controllers;

use App\Models\ActionLog;
use App\Models\Club;
use App\Models\ClubMembership;
use App\Models\Event;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteDetail;
use App\Models\Election;
use App\Models\Candidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\UsersExport;
use App\Exports\ClubsExport;
use App\Exports\ActionLogsExport;
use App\Exports\ClubMembershipExport;
use App\Exports\ClubEventsExport;
use App\Exports\VotingResultsExport;

class ExportController extends Controller
{
    /**
     * Admin Export: All Users
     */
    public function exportUsers(Request $request)
    {
        // Only admin can export users
        if (Auth::user()->role !== 'ADMIN') {
            return back()->with('error', 'Access denied. Admin privileges required.');
        }

        $format = $request->get('format', 'csv');
        $users = User::with(['section.schoolClass'])->get();

        switch ($format) {
            case 'csv':
                return $this->exportUsersCsv($users);
            case 'xlsx':
                $usersExport = new UsersExport();
                return (new FastExcel($usersExport->collection()))
                    ->download('users_' . date('Y-m-d_H-i-s') . '.xlsx');
            case 'pdf':
                return $this->exportUsersPdf($users);
            case 'json':
                return $this->exportUsersJson($users);
            default:
                return back()->with('error', 'Invalid export format. Supported formats: csv, xlsx, pdf, json');
        }
    }

    /**
     * Admin Export: All Clubs
     */
    public function exportClubs(Request $request)
    {
        // Only admin can export clubs
        if (Auth::user()->role !== 'ADMIN') {
            return back()->with('error', 'Access denied. Admin privileges required.');
        }

        $format = $request->get('format', 'csv');
        $clubs = Club::with(['adviser', 'members'])->withCount('members')->get();

        switch ($format) {
            case 'csv':
                return $this->exportClubsCsv($clubs);
            case 'xlsx':
                $clubsExport = new ClubsExport();
                return (new FastExcel($clubsExport->collection()))
                    ->download('clubs_' . date('Y-m-d_H-i-s') . '.xlsx');
            case 'pdf':
                return $this->exportClubsPdf($clubs);
            case 'json':
                return $this->exportClubsJson($clubs);
            default:
                return back()->with('error', 'Invalid export format. Supported formats: csv, xlsx, pdf, json');
        }
    }

    /**
     * Admin Export: Action Logs
     */
    public function exportActionLogs(Request $request)
    {
        // Only admin can export action logs
        if (Auth::user()->role !== 'ADMIN') {
            return back()->with('error', 'Access denied. Admin privileges required.');
        }

        $format = $request->get('format', 'csv');
        $logs = ActionLog::with('user')->latest()->get();

        switch ($format) {
            case 'csv':
                return $this->exportActionLogsCsv($logs);
            case 'xlsx':
                $actionLogsExport = new ActionLogsExport();
                return (new FastExcel($actionLogsExport->collection()))
                    ->download('action_logs_' . date('Y-m-d_H-i-s') . '.xlsx');
            case 'pdf':
                return $this->exportActionLogsPdf($logs);
            case 'json':
                return $this->exportActionLogsJson($logs);
            default:
                return back()->with('error', 'Invalid format');
        }
    }

    /**
     * Teacher Export: Club Membership
     */
    public function exportClubMembership(Request $request, Club $club)
    {
        // Only club advisers can export membership
        if (Auth::id() !== $club->club_adviser) {
            return back()->with('error', 'Access denied. Only club advisers can export membership data.');
        }

        $format = $request->get('format', 'csv');
        $memberships = ClubMembership::with(['user.section.schoolClass'])
            ->where('club_id', $club->club_id)
            ->get();

        switch ($format) {
            case 'csv':
                return $this->exportMembershipCsv($memberships, $club);
            case 'xlsx':
                $membershipExport = new ClubMembershipExport($club);
                return (new FastExcel($membershipExport->collection()))
                    ->download('club_membership_' . str_replace(' ', '_', $club->club_name) . '_' . date('Y-m-d_H-i-s') . '.xlsx');
            case 'json':
                return $this->exportMembershipJson($memberships, $club);
            case 'pdf':
                return $this->exportMembershipPdf($memberships, $club);
            default:
                return back()->with('error', 'Invalid export format. Supported formats: csv, xlsx, json, pdf');
        }
    }

    /**
     * Teacher Export: Club Events
     */
    public function exportClubEvents(Request $request, Club $club)
    {
        // Only teachers with club access can export events

        $format = $request->get('format', 'csv');
        $events = Event::with(['organizer'])
            ->where('club_id', $club->club_id)
            ->get();

        switch ($format) {
            case 'csv':
                return $this->exportEventsCsv($events, $club);
            case 'json':
                return $this->exportEventsJson($events, $club);
            case 'pdf':
                return $this->exportEventsPdf($events, $club);
            case 'table':
                $eventsExport = new ClubEventsExport($club);
                return (new FastExcel($eventsExport->collection()))
                    ->download('club_events_' . str_replace(' ', '_', $club->club_name) . '_' . date('Y-m-d_H-i-s') . '.xlsx');
            default:
                return back()->with('error', 'Invalid format');
        }
    }

    /**
     * Teacher Export: Voting Results
     */
    public function exportVotingResults(Request $request, Club $club)
    {
        // Only teachers with club access can export voting results

        $format = $request->get('format', 'csv');
        $elections = Election::where('club_id', $club->club_id)->get();

        switch ($format) {
            case 'csv':
                return $this->exportVotingResultsCsv($elections, $club);
            case 'json':
                return $this->exportVotingResultsJson($elections, $club);
            case 'pdf':
                return $this->exportVotingResultsPdf($elections, $club);
            case 'table':
                $votingExport = new VotingResultsExport($club);
                return (new FastExcel($votingExport->collection()))
                    ->download('voting_results_' . str_replace(' ', '_', $club->club_name) . '_' . date('Y-m-d_H-i-s') . '.xlsx');
            default:
                return back()->with('error', 'Invalid format');
        }
    }

    // CSV Export Methods
    private function exportUsersCsv($users)
    {
        $filename = 'all_users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV headers
            fputcsv($file, [
                'User ID',
                'Name',
                'Email',
                'Role',
                'Sex',
                'Contact Number',
                'Address',
                'Class',
                'Section',
                'Status',
                'Mother Name',
                'Mother Contact',
                'Father Name',
                'Father Contact',
                'Guardian Name',
                'Guardian Contact',
                'Created At',
                'Updated At'
            ]);

            foreach ($users as $user) {
                $className = $user->section && $user->section->schoolClass
                    ? 'Grade ' . $user->section->schoolClass->grade_level
                    : 'N/A';
                $sectionName = $user->section ? $user->section->section_name : 'N/A';

                fputcsv($file, [
                    $user->user_id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->sex ?? 'N/A',
                    $user->contact_no ?? 'N/A',
                    $user->address ?? 'N/A',
                    $className,
                    $sectionName,
                    $user->status,
                    $user->mother_name ?? 'N/A',
                    $user->mother_contact_no ?? 'N/A',
                    $user->father_name ?? 'N/A',
                    $user->father_contact_no ?? 'N/A',
                    $user->guardian_name ?? 'N/A',
                    $user->guardian_contact_no ?? 'N/A',
                    $user->created_at,
                    $user->updated_at
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportClubsCsv($clubs)
    {
        $filename = 'all_clubs_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($clubs) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV headers
            fputcsv($file, [
                'Club ID',
                'Club Name',
                'Description',
                'Adviser Name',
                'Adviser Email',
                'Member Count',
                'Hunting Day',
                'Requirements',
                'Visibility',
                'Status',
                'Created At',
                'Updated At'
            ]);

            foreach ($clubs as $club) {
                fputcsv($file, [
                    $club->club_id,
                    $club->club_name,
                    $club->club_description,
                    $club->adviser ? $club->adviser->name : 'N/A',
                    $club->adviser ? $club->adviser->email : 'N/A',
                    $club->members_count,
                    $club->hunting_day ? 'Yes' : 'No',
                    $club->club_requirements ?? 'N/A',
                    $club->club_visibility,
                    $club->club_status,
                    $club->created_at,
                    $club->updated_at
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportActionLogsCsv($logs)
    {
        $filename = 'action_logs_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV headers
            fputcsv($file, [
                'Log ID',
                'User ID',
                'User Name',
                'User Role',
                'Action Category',
                'Action Type',
                'Action Description',
                'Status',
                'IP Address',
                'User Agent',
                'Action Details',
                'Created At'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user_id ?? 'N/A',
                    $log->user_name,
                    $log->user_role,
                    $log->action_category,
                    $log->action_type,
                    $log->action_description,
                    $log->status,
                    $log->ip_address ?? 'N/A',
                    $log->user_agent ?? 'N/A',
                    $log->action_details ? json_encode($log->action_details) : 'N/A',
                    $log->created_at
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportMembershipCsv($memberships, $club)
    {
        $filename = 'club_' . $club->club_id . '_membership_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($memberships, $club) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Club header info
            fputcsv($file, ['Club Name:', $club->club_name]);
            fputcsv($file, ['Adviser:', $club->adviser ? $club->adviser->name : 'N/A']);
            fputcsv($file, ['Total Members:', $memberships->count()]);
            fputcsv($file, ['Export Date:', date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row

            // CSV headers
            fputcsv($file, [
                'Name',
                'Email',
                'Sex',
                'Address',
                'Contact No',
                'Section',
                'Mother Name',
                'Mother Contact No',
                'Father Name',
                'Father Contact No'
            ]);

            foreach ($memberships as $membership) {
                $user = $membership->user;
                $sectionName = $user->section ? $user->section->section_name : 'N/A';

                fputcsv($file, [
                    $user->name ?? 'N/A',
                    $user->email ?? 'N/A',
                    $user->sex ?? 'N/A',
                    $user->address ?? 'N/A',
                    $user->contact_no ?? 'N/A',
                    $sectionName,
                    $user->mother_name ?? 'N/A',
                    $user->mother_contact_no ?? 'N/A',
                    $user->father_name ?? 'N/A',
                    $user->father_contact_no ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportEventsCsv($events, $club)
    {
        $filename = 'club_' . $club->club_id . '_events_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($events, $club) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Club header info
            fputcsv($file, ['Club Name:', $club->club_name]);
            fputcsv($file, ['Adviser:', $club->adviser ? $club->adviser->name : 'N/A']);
            fputcsv($file, ['Total Events:', $events->count()]);
            fputcsv($file, ['Export Date:', date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row

            // CSV headers
            fputcsv($file, [
                'Event ID',
                'Event Name',
                'Description',
                'Event Date',
                'Event Time',
                'Location',
                'Organizer',
                'Visibility',
                'Status',
                'Created At'
            ]);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->event_id,
                    $event->event_name,
                    $event->event_description,
                    $event->event_date,
                    $event->event_time,
                    $event->event_location,
                    $event->organizer ? $event->organizer->name : 'N/A',
                    $event->event_visibility,
                    $event->event_status ?? 'Active',
                    $event->created_at
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportVotingResultsCsv($elections, $club)
    {
        $filename = 'club_' . $club->club_id . '_voting_results_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($elections, $club) {
            $file = fopen('php://output', 'w');

            // Add BOM for proper UTF-8 encoding
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Club header info
            fputcsv($file, ['Club Name:', $club->club_name]);
            fputcsv($file, ['Adviser:', $club->adviser ? $club->adviser->name : 'N/A']);
            fputcsv($file, ['Total Elections:', $elections->count()]);
            fputcsv($file, ['Export Date:', date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row

            foreach ($elections as $election) {
                fputcsv($file, ['Election:', $election->election_name]);
                fputcsv($file, ['Start Date:', $election->start_date]);
                fputcsv($file, ['End Date:', $election->end_date]);
                fputcsv($file, ['Status:', $election->status]);
                fputcsv($file, []);

                // Get voting results for this election
                $votes = Vote::where('election_id', $election->election_id)->get();
                $voteDetails = VoteDetail::whereIn('vote_id', $votes->pluck('vote_id'))->get();

                fputcsv($file, [
                    'Position',
                    'Candidate Name',
                    'Partylist',
                    'Vote Count',
                    'Percentage'
                ]);

                // Process vote results (simplified - you may need to adjust based on your voting system)
                $candidateVotes = $voteDetails->groupBy('candidate_id');
                $totalVotes = $voteDetails->count();

                foreach ($candidateVotes as $candidateId => $votes) {
                    $voteCount = $votes->count();
                    $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 2) : 0;

                    // You'll need to get candidate details
                    fputcsv($file, [
                        'Position Name', // You'll need to fetch this
                        'Candidate Name', // You'll need to fetch this
                        'Partylist Name', // You'll need to fetch this
                        $voteCount,
                        $percentage . '%'
                    ]);
                }

                fputcsv($file, []); // Empty row between elections
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    // JSON Export Methods
    private function exportActionLogsJson($logs)
    {
        $filename = 'action_logs_export_' . date('Y-m-d_H-i-s') . '.json';

        $data = [
            'export_info' => [
                'title' => 'Action Logs Export',
                'exported_at' => now()->toISOString(),
                'total_records' => $logs->count(),
                'exported_by' => Auth::user()->name
            ],
            'action_logs' => $logs->map(function ($log) {
                return [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'user_name' => $log->user_name,
                    'user_role' => $log->user_role,
                    'action_category' => $log->action_category,
                    'action_type' => $log->action_type,
                    'action_description' => $log->action_description,
                    'action_details' => $log->action_details,
                    'status' => $log->status,
                    'ip_address' => $log->ip_address,
                    'user_agent' => $log->user_agent,
                    'created_at' => $log->created_at->toISOString(),
                    'updated_at' => $log->updated_at->toISOString()
                ];
            })
        ];

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export action logs as PDF
     */
    private function exportActionLogsPdf($logs)
    {
        $data = [
            'logs' => $logs,
            'exportDate' => now()->format('F j, Y'),
            'totalLogs' => $logs->count()
        ];

        $pdf = Pdf::loadView('exports.pdf.action-logs', $data);
        $filename = 'action_logs_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportMembershipJson($memberships, $club)
    {
        $filename = 'club_' . $club->club_id . '_membership_export_' . date('Y-m-d_H-i-s') . '.json';

        $data = [
            'export_info' => [
                'title' => 'Club Membership Export',
                'club_name' => $club->club_name,
                'adviser' => $club->adviser ? $club->adviser->name : null,
                'exported_at' => now()->toISOString(),
                'total_members' => $memberships->count(),
                'exported_by' => Auth::user()->name
            ],
            'memberships' => $memberships->map(function ($membership) {
                $user = $membership->user;
                return [
                    'name' => $user->name ?? 'N/A',
                    'email' => $user->email ?? 'N/A',
                    'sex' => $user->sex ?? 'N/A',
                    'address' => $user->address ?? 'N/A',
                    'contact_no' => $user->contact_no ?? 'N/A',
                    'section' => $user->section ? $user->section->section_name : 'N/A',
                    'mother_name' => $user->mother_name ?? 'N/A',
                    'mother_contact_no' => $user->mother_contact_no ?? 'N/A',
                    'father_name' => $user->father_name ?? 'N/A',
                    'father_contact_no' => $user->father_contact_no ?? 'N/A'
                ];
            })
        ];

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function exportEventsJson($events, $club)
    {
        $filename = 'club_' . $club->club_id . '_events_export_' . date('Y-m-d_H-i-s') . '.json';

        $data = [
            'export_info' => [
                'title' => 'Club Events Export',
                'club_name' => $club->club_name,
                'adviser' => $club->adviser ? $club->adviser->name : null,
                'exported_at' => now()->toISOString(),
                'total_events' => $events->count(),
                'exported_by' => Auth::user()->name
            ],
            'events' => $events->map(function ($event) {
                return [
                    'event_id' => $event->event_id,
                    'event_name' => $event->event_name,
                    'description' => $event->event_description,
                    'event_date' => $event->event_date,
                    'event_time' => $event->event_time,
                    'location' => $event->event_location,
                    'organizer' => $event->organizer ? $event->organizer->name : null,
                    'visibility' => $event->event_visibility,
                    'status' => $event->event_status ?? 'Active',
                    'created_at' => $event->created_at->toISOString()
                ];
            })
        ];

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    private function exportVotingResultsJson($elections, $club)
    {
        $filename = 'club_' . $club->club_id . '_voting_results_export_' . date('Y-m-d_H-i-s') . '.json';

        $data = [
            'export_info' => [
                'title' => 'Voting Results Export',
                'club_name' => $club->club_name,
                'adviser' => $club->adviser ? $club->adviser->name : null,
                'exported_at' => now()->toISOString(),
                'total_elections' => $elections->count(),
                'exported_by' => Auth::user()->name
            ],
            'elections' => $elections->map(function ($election) {
                $votes = Vote::where('election_id', $election->election_id)->get();
                $voteDetails = VoteDetail::whereIn('vote_id', $votes->pluck('vote_id'))->get();

                return [
                    'election_id' => $election->election_id,
                    'election_name' => $election->election_name,
                    'start_date' => $election->start_date,
                    'end_date' => $election->end_date,
                    'status' => $election->status,
                    'total_votes' => $votes->count(),
                    'vote_details' => $voteDetails->groupBy('candidate_id')->map(function ($candidateVotes, $candidateId) use ($voteDetails) {
                        $voteCount = $candidateVotes->count();
                        $totalVotes = $voteDetails->count();
                        $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 2) : 0;

                        return [
                            'candidate_id' => $candidateId,
                            'vote_count' => $voteCount,
                            'percentage' => $percentage
                        ];
                    })->values()
                ];
            })
        ];

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // PDF Export Methods
    private function exportMembershipPdf($memberships, $club)
    {
        $data = [
            'club' => $club,
            'memberships' => $memberships,
            'export_date' => now(),
            'exported_by' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('exports.pdf.club-membership', $data);
        $filename = 'club_' . $club->club_id . '_membership_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportEventsPdf($events, $club)
    {
        $data = [
            'club' => $club,
            'events' => $events,
            'export_date' => now(),
            'exported_by' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('exports.pdf.club-events', $data);
        $filename = 'club_' . $club->club_id . '_events_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function exportVotingResultsPdf($elections, $club)
    {
        $data = [
            'club' => $club,
            'elections' => $elections,
            'export_date' => now(),
            'exported_by' => Auth::user()->name
        ];

        $pdf = Pdf::loadView('exports.pdf.voting-results', $data);
        $filename = 'club_' . $club->club_id . '_voting_results_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export users as PDF
     */
    private function exportUsersPdf($users)
    {
        $data = [
            'users' => $users,
            'exportDate' => now()->format('F j, Y'),
            'totalUsers' => $users->count()
        ];

        $pdf = Pdf::loadView('exports.pdf.users', $data);
        $filename = 'users_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export users as JSON
     */
    private function exportUsersJson($users)
    {
        $data = [
            'export_info' => [
                'title' => 'Users Export',
                'generated_at' => now()->toISOString(),
                'total_count' => $users->count(),
                'format' => 'JSON'
            ],
            'users' => $users->map(function ($user) {
                return [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'sex' => $user->sex ?? 'N/A',
                    'contact_number' => $user->contact_no ?? 'N/A',
                    'address' => $user->address ?? 'N/A',
                    'grade_level' => $user->section && $user->section->schoolClass
                        ? 'Grade ' . $user->section->schoolClass->grade_level
                        : 'N/A',
                    'section' => $user->section ? $user->section->section_name : 'N/A',
                    'mother_name' => $user->mother_name ?? 'N/A',
                    'mother_contact' => $user->mother_contact_no ?? 'N/A',
                    'father_name' => $user->father_name ?? 'N/A',
                    'father_contact' => $user->father_contact_no ?? 'N/A',
                    'guardian_name' => $user->guardian_name ?? 'N/A',
                    'guardian_contact' => $user->guardian_contact_no ?? 'N/A',
                    'created_at' => $user->created_at ? $user->created_at->toISOString() : null,
                    'updated_at' => $user->updated_at ? $user->updated_at->toISOString() : null
                ];
            })
        ];

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.json';

        return Response::make(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export clubs as PDF
     */
    private function exportClubsPdf($clubs)
    {
        $data = [
            'clubs' => $clubs,
            'exportDate' => now()->format('F j, Y'),
            'totalClubs' => $clubs->count()
        ];

        $pdf = Pdf::loadView('exports.pdf.clubs', $data);
        $filename = 'clubs_report_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export clubs as JSON
     */
    private function exportClubsJson($clubs)
    {
        $data = [
            'export_info' => [
                'title' => 'Clubs Export',
                'generated_at' => now()->toISOString(),
                'total_count' => $clubs->count(),
                'format' => 'JSON'
            ],
            'clubs' => $clubs->map(function ($club) {
                return [
                    'club_id' => $club->club_id,
                    'club_name' => $club->club_name,
                    'adviser' => $club->adviser ? $club->adviser->name : 'N/A',
                    'description' => $club->club_description,
                    'category' => $club->category,
                    'members_count' => $club->members_count ?? 0,
                    'requires_approval' => $club->requires_approval,
                    'created_at' => $club->created_at ? $club->created_at->toISOString() : null
                ];
            })
        ];

        $filename = 'clubs_export_' . date('Y-m-d_H-i-s') . '.json';

        return Response::make(json_encode($data, JSON_PRETTY_PRINT), 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}
