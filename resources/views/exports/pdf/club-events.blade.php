<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $club->club_name }} - Events Report</title>
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
            border-bottom: 2px solid #16A34A;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #16A34A;
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
            background-color: #F0FDF4;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #16A34A;
        }

        .club-info h3 {
            color: #374151;
            font-size: 14px;
            margin: 0 0 10px 0;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }

        .stats-row {
            display: table-row;
        }

        .stats-cell {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #E5E7EB;
            background-color: #F9FAFB;
        }

        .stats-label {
            font-size: 10px;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stats-value {
            font-size: 18px;
            font-weight: bold;
            color: #1F2937;
        }

        .events-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .events-table th {
            background-color: #F3F4F6;
            border: 1px solid #D1D5DB;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }

        .events-table td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            font-size: 10px;
        }

        .events-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .status-upcoming {
            background-color: #DBEAFE;
            color: #1E40AF;
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

        .visibility-public {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .visibility-private {
            background-color: #FEF3C7;
            color: #92400E;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .event-upcoming {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .event-past {
            opacity: 0.7;
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
            color: rgba(22, 163, 74, 0.1);
            z-index: -1;
            font-weight: bold;
        }

        .timeline-section {
            margin-top: 30px;
            background-color: #F8FAFC;
            padding: 15px;
            border-radius: 8px;
        }

        .timeline-item {
            margin-bottom: 15px;
            padding: 10px;
            background-color: white;
            border-left: 3px solid #16A34A;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="watermark">{{ $club->club_name }}</div>

    <!-- Header -->
    <div class="header">
        <h1>{{ $club->club_name }}</h1>
        <h2>EVENTS REPORT</h2>
        <p style="font-size: 11px; color: #6B7280; margin: 10px 0 0 0;">
            Generated on {{ $export_date->format('F d, Y \a\t g:i A') }}
        </p>
    </div>

    <!-- Club Information -->
    <div class="club-info">
        <h3>CLUB INFORMATION</h3>
        <p><strong>Club Name:</strong> {{ $club->club_name }}</p>
        <p><strong>Description:</strong> {{ $club->club_description ?? 'No description available' }}</p>
        <p><strong>Club Adviser:</strong> {{ $club->adviser ? $club->adviser->name : 'No adviser assigned' }}</p>
        @if ($club->adviser)
            <p><strong>Adviser Email:</strong> {{ $club->adviser->email }}</p>
        @endif
        <p><strong>Total Events:</strong> {{ $events->count() }}</p>
        <p><strong>Club Activity Level:</strong>
            @if ($events->count() >= 10)
                Very Active
            @elseif($events->count() >= 5)
                Active
            @elseif($events->count() >= 2)
                Moderate
            @else
                Low Activity
            @endif
        </p>
    </div>

    <!-- Statistics -->
    @php
        $now = now();
        $upcomingEvents = $events
            ->filter(function ($event) use ($now) {
                return $event->event_date >= $now->toDateString();
            })
            ->count();
        $pastEvents = $events->count() - $upcomingEvents;
        $publicEvents = $events->where('event_visibility', 'PUBLIC')->count();
        $thisYearEvents = $events
            ->filter(function ($event) {
                return \Carbon\Carbon::parse($event->event_date)->year === date('Y');
            })
            ->count();
    @endphp

    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <div class="stats-label">Total Events</div>
                <div class="stats-value">{{ $events->count() }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Upcoming Events</div>
                <div class="stats-value">{{ $upcomingEvents }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Past Events</div>
                <div class="stats-value">{{ $pastEvents }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Public Events</div>
                <div class="stats-value">{{ $publicEvents }}</div>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <table class="events-table">
        <thead>
            <tr>
                <th style="width: 8%">Event ID</th>
                <th style="width: 20%">Event Name</th>
                <th style="width: 25%">Description</th>
                <th style="width: 12%">Date</th>
                <th style="width: 8%">Time</th>
                <th style="width: 15%">Location</th>
                <th style="width: 12%">Visibility</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events->sortBy('event_date') as $event)
                @php
                    $isUpcoming = $event->event_date >= now()->toDateString();
                    $isPast = $event->event_date < now()->toDateString();
                @endphp
                <tr class="{{ $isUpcoming ? 'event-upcoming' : ($isPast ? 'event-past' : '') }}">
                    <td><strong>{{ $event->event_id }}</strong></td>
                    <td>
                        <strong>{{ $event->event_name }}</strong>
                        @if ($isUpcoming)
                            <br><span class="status-upcoming">Upcoming</span>
                        @elseif($isPast)
                            <br><span class="status-completed">Completed</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($event->event_description, 80) }}</td>
                    <td>
                        <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</strong>
                        <br><small>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</small>
                    </td>
                    <td>{{ $event->event_time }}</td>
                    <td>{{ $event->event_location }}</td>
                    <td>
                        <span
                            class="{{ $event->event_visibility === 'PUBLIC' ? 'visibility-public' : 'visibility-private' }}">
                            {{ $event->event_visibility }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Event Timeline Section -->
    @if ($upcomingEvents > 0)
        <div class="timeline-section">
            <h3 style="color: #374151; font-size: 14px; margin-bottom: 15px;">UPCOMING EVENTS TIMELINE</h3>
            @foreach ($events->filter(function ($event) use ($now) {
            return $event->event_date >= $now->toDateString();
        })->sortBy('event_date')->take(5) as $event)
                <div class="timeline-item">
                    <strong>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</strong> -
                    <strong>{{ $event->event_name }}</strong>
                    <br>
                    <small>{{ $event->event_time }} at {{ $event->event_location }}</small>
                    <br>
                    <small style="color: #6B7280;">{{ Str::limit($event->event_description, 100) }}</small>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Monthly Event Distribution -->
    @php
        $monthlyEvents = $events
            ->groupBy(function ($event) {
                return \Carbon\Carbon::parse($event->event_date)->format('Y-m');
            })
            ->map->count();
    @endphp

    @if ($monthlyEvents->count() > 1)
        <div style="margin-top: 30px;">
            <h3 style="color: #374151; font-size: 14px; margin-bottom: 15px;">MONTHLY EVENT DISTRIBUTION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #F3F4F6;">
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: left;">Month</th>
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: center;">Event Count</th>
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: center;">Activity Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($monthlyEvents->take(12) as $month => $count)
                        <tr>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px;">
                                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
                            </td>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px; text-align: center;">
                                {{ $count }}</td>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px; text-align: center;">
                                @if ($count >= 4)
                                    High
                                @elseif($count >= 2)
                                    Medium
                                @else
                                    Low
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Signatures -->
    <div class="signatures">
        <div class="signature-row">
            <div class="signature-cell">
                <div class="signature-line"></div>
                <div class="signature-label">Prepared by</div>
                <div class="signature-name">{{ $exported_by }}</div>
                <div class="signature-title">Club Adviser</div>
            </div>
            <div class="signature-cell">
                <div class="signature-line"></div>
                <div class="signature-label">Verified by</div>
                <div class="signature-name">_________________</div>
                <div class="signature-title">Activities Coordinator</div>
            </div>
            <div class="signature-cell">
                <div class="signature-line"></div>
                <div class="signature-label">Approved by</div>
                <div class="signature-name">_________________</div>
                <div class="signature-title">Principal</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Siterians ClubHive - {{ $club->club_name }} Events Report</p>
        <p>This document contains event planning and coordination information.</p>
        <p><strong>Official Document</strong> | Generated by ClubHive System | Page 1 of 1</p>
    </div>
</body>

</html>
