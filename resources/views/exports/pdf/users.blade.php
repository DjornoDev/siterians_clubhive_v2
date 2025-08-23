<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Users Report</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 11px;
        }

        .stats {
            margin: 15px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 3px;
            font-size: 9px;
        }

        .stats ul {
            margin: 5px 0;
            padding-left: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 4px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: left;
            font-size: 8px;
            line-height: 1.2;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        /* Column widths - optimized for landscape A4 */
        .col-id {
            width: 5%;
        }

        .col-name {
            width: 12%;
        }

        .col-email {
            width: 15%;
        }

        .col-role {
            width: 8%;
        }

        .col-status {
            width: 7%;
        }

        .col-contact {
            width: 10%;
        }

        .col-grade {
            width: 10%;
        }

        .col-parent {
            width: 25%;
        }

        .col-created {
            width: 8%;
        }

        .role-badge {
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }

        .role-admin {
            background-color: #dc3545;
            color: white;
        }

        .role-teacher {
            background-color: #28a745;
            color: white;
        }

        .role-student {
            background-color: #007bff;
            color: white;
        }

        .small-text {
            font-size: 7px;
            line-height: 1.1;
        }

        .tiny-text {
            font-size: 6px;
            line-height: 1.0;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .role-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .role-admin {
            background-color: #dc3545;
            color: white;
        }

        .role-teacher {
            background-color: #28a745;
            color: white;
        }

        .role-student {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Users Report</h1>
        <p>Generated on {{ $exportDate }}</p>
        <p>Total Users: {{ $totalUsers }}</p>
    </div>

    <div class="stats">
        <strong>Summary:</strong>
        <ul>
            <li>Total Users: {{ $users->count() }}</li>
            <li>Administrators: {{ $users->where('role', 'ADMIN')->count() }}</li>
            <li>Teachers: {{ $users->where('role', 'TEACHER')->count() }}</li>
            <li>Students: {{ $users->where('role', 'STUDENT')->count() }}</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-name">Name</th>
                <th class="col-email">Email</th>
                <th class="col-role">Role</th>
                <th class="col-status">Status</th>
                <th class="col-contact">Contact/Sex</th>
                <th class="col-grade">Grade/Section</th>
                <th class="col-parent">Parent Info</th>
                <th class="col-created">Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td class="col-id">{{ $user->user_id }}</td>
                    <td class="col-name">{{ $user->name }}</td>
                    <td class="col-email small-text">{{ $user->email }}</td>
                    <td class="col-role">
                        <span class="role-badge role-{{ strtolower($user->role) }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="col-status">{{ $user->status }}</td>
                    <td class="col-contact tiny-text">
                        {{ $user->contact_no ?? 'N/A' }}<br>
                        <em>{{ $user->sex ?? 'N/A' }}</em>
                    </td>
                    <td class="col-grade tiny-text">
                        @if ($user->section && $user->section->schoolClass)
                            Grade {{ $user->section->schoolClass->grade_level }}<br>
                            {{ $user->section->section_name }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="col-parent tiny-text">
                        @if ($user->mother_name || $user->father_name)
                            @if ($user->mother_name)
                                <strong>M:</strong> {{ $user->mother_name }}<br>
                                @if ($user->mother_contact_no)
                                    <span style="font-size: 5px;">{{ $user->mother_contact_no }}</span><br>
                                @endif
                            @endif
                            @if ($user->father_name)
                                <strong>F:</strong> {{ $user->father_name }}<br>
                                @if ($user->father_contact_no)
                                    <span style="font-size: 5px;">{{ $user->father_contact_no }}</span>
                                @endif
                            @endif
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="col-created tiny-text">
                        {{ $user->created_at ? $user->created_at->format('M j, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Siterians ClubHive - Users Report | Generated: {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>

</html>
