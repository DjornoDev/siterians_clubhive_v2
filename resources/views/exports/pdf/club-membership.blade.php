<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $club->club_name }} - Membership Report</title>
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
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #4F46E5;
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
            background-color: #F8FAFC;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #4F46E5;
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

        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .members-table th {
            background-color: #F3F4F6;
            border: 1px solid #D1D5DB;
            padding: 8px;
            font-size: 11px;
            font-weight: bold;
            text-align: left;
        }

        .members-table td {
            border: 1px solid #E5E7EB;
            padding: 6px 8px;
            font-size: 10px;
        }

        .members-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .status-active {
            background-color: #D1FAE5;
            color: #065F46;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-inactive {
            background-color: #FEE2E2;
            color: #991B1B;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
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

        .page-break {
            page-break-after: always;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72px;
            color: rgba(79, 70, 229, 0.1);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="watermark">{{ $club->club_name }}</div>

    <!-- Header -->
    <div class="header">
        <h1>{{ $club->club_name }}</h1>
        <h2>MEMBERSHIP REPORT</h2>
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
        <p><strong>Club Status:</strong> {{ $club->club_status }}</p>
        <p><strong>Visibility:</strong> {{ $club->club_visibility }}</p>
        <p><strong>Total Members:</strong> {{ $memberships->count() }}</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stats-row">
            <div class="stats-cell">
                <div class="stats-label">Total Members</div>
                <div class="stats-value">{{ $memberships->count() }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Active Members</div>
                <div class="stats-value">{{ $memberships->where('membership_status', 'ACTIVE')->count() }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Male Members</div>
                <div class="stats-value">{{ $memberships->where('user.sex', 'MALE')->count() }}</div>
            </div>
            <div class="stats-cell">
                <div class="stats-label">Female Members</div>
                <div class="stats-value">{{ $memberships->where('user.sex', 'FEMALE')->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Members Table -->
    <table class="members-table">
        <thead>
            <tr>
                <th>Member ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Sex</th>
                <th>Contact</th>
                <th>Class</th>
                <th>Section</th>
                <th>Status</th>
                <th>Role</th>
                <th>Joined Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($memberships as $membership)
                @php $user = $membership->user; @endphp
                <tr>
                    <td>{{ $user->user_id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->sex ?? 'N/A' }}</td>
                    <td>{{ $user->contact_no ?? 'N/A' }}</td>
                    <td>
                        @if ($user->section && $user->section->schoolClass)
                            Grade {{ $user->section->schoolClass->grade_level }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td>{{ $user->section ? $user->section->section_name : 'N/A' }}</td>
                    <td>
                        <span
                            class="{{ $membership->membership_status === 'ACTIVE' ? 'status-active' : 'status-inactive' }}">
                            {{ $membership->membership_status }}
                        </span>
                    </td>
                    <td>{{ $membership->role ?? 'Member' }}</td>
                    <td>{{ $membership->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Grade Level Distribution -->
    @php
        $gradeStats = $memberships
            ->groupBy(function ($m) {
                return $m->user->section && $m->user->section->schoolClass
                    ? $m->user->section->schoolClass->grade_level
                    : 'N/A';
            })
            ->map->count();
    @endphp

    @if ($gradeStats->count() > 1)
        <div style="margin-top: 30px;">
            <h3 style="color: #374151; font-size: 14px; margin-bottom: 15px;">GRADE LEVEL DISTRIBUTION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #F3F4F6;">
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: left;">Grade Level</th>
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: center;">Member Count</th>
                        <th style="border: 1px solid #D1D5DB; padding: 8px; text-align: center;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gradeStats as $grade => $count)
                        <tr>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px;">
                                {{ $grade === 'N/A' ? 'Unknown Grade' : 'Grade ' . $grade }}
                            </td>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px; text-align: center;">
                                {{ $count }}</td>
                            <td style="border: 1px solid #E5E7EB; padding: 6px 8px; text-align: center;">
                                {{ round(($count / $memberships->count()) * 100, 1) }}%
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
                <div class="signature-title">Department Head</div>
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
        <p>© {{ date('Y') }} Siterians ClubHive - {{ $club->club_name }} Membership Report</p>
        <p>This document contains confidential student information. Handle with care.</p>
        <p><strong>Confidential Document</strong> | Generated by ClubHive System | Page 1 of 1</p>
    </div>
</body>

</html>
