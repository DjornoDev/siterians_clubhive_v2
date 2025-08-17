<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Action Logs Report</title>
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

        .col-user {
            width: 12%;
        }

        .col-role {
            width: 8%;
        }

        .col-category {
            width: 10%;
        }

        .col-type {
            width: 10%;
        }

        .col-description {
            width: 25%;
        }

        .col-status {
            width: 6%;
        }

        .col-ip {
            width: 10%;
        }

        .col-date {
            width: 10%;
        }

        .col-agent {
            width: 4%;
        }

        .status-badge {
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
            display: inline-block;
        }

        .status-success {
            background-color: #28a745;
            color: white;
        }

        .status-warning {
            background-color: #ffc107;
            color: black;
        }

        .status-error {
            background-color: #dc3545;
            color: white;
        }

        .status-pending {
            background-color: #6c757d;
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

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Action Logs Report</h1>
        <p>Generated on {{ $exportDate }}</p>
        <p>Total Logs: {{ $totalLogs }}</p>
    </div>

    <div class="stats">
        <strong>Summary:</strong>
        <ul>
            <li>Total Records: {{ $logs->count() }}</li>
            <li>Success: {{ $logs->where('status', 'success')->count() }}</li>
            <li>Failed: {{ $logs->where('status', 'failed')->count() }}</li>
            <li>Pending: {{ $logs->where('status', 'pending')->count() }}</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-user">User</th>
                <th class="col-role">Role</th>
                <th class="col-category">Category</th>
                <th class="col-type">Type</th>
                <th class="col-description">Description</th>
                <th class="col-status">Status</th>
                <th class="col-ip">IP Address</th>
                <th class="col-date">Date</th>
                <th class="col-agent">UA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td class="col-id">{{ $log->id }}</td>
                    <td class="col-user small-text">{{ $log->user_name }}</td>
                    <td class="col-role tiny-text">{{ strtoupper($log->user_role) }}</td>
                    <td class="col-category tiny-text">{{ $log->action_category }}</td>
                    <td class="col-type tiny-text">{{ $log->action_type }}</td>
                    <td class="col-description tiny-text">{{ $log->action_description }}</td>
                    <td class="col-status">
                        <span class="status-badge status-{{ strtolower($log->status) }}">
                            {{ strtoupper($log->status) }}
                        </span>
                    </td>
                    <td class="col-ip tiny-text">{{ $log->ip_address ?? 'N/A' }}</td>
                    <td class="col-date tiny-text">
                        {{ $log->created_at ? $log->created_at->format('M j, Y H:i') : 'N/A' }}</td>
                    <td class="col-agent tiny-text">{{ substr($log->user_agent ?? 'N/A', 0, 20) }}...</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Siterians ClubHive - Action Logs Report | Generated: {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>

</html>
