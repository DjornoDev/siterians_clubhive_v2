<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Clubs Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .stats {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
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

        .category-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .category-academic {
            background-color: #007bff;
            color: white;
        }

        .category-sports {
            background-color: #28a745;
            color: white;
        }

        .category-service {
            background-color: #ffc107;
            color: black;
        }

        .description {
            max-width: 200px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Clubs Report</h1>
        <p>Generated on {{ $exportDate }}</p>
        <p>Total Clubs: {{ $totalClubs }}</p>
    </div>

    <div class="stats">
        <strong>Summary:</strong>
        <ul>
            <li>Total Clubs: {{ $clubs->count() }}</li>
            <li>Academic Clubs: {{ $clubs->where('category', 'academic')->count() }}</li>
            <li>Sports Clubs: {{ $clubs->where('category', 'sports')->count() }}</li>
            <li>Service Clubs: {{ $clubs->where('category', 'service')->count() }}</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Club Name</th>
                <th>Adviser</th>
                <th>Category</th>
                <th>Members</th>
                <th>Description</th>
                <th>Approval Required</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clubs as $club)
                <tr>
                    <td>{{ $club->club_id }}</td>
                    <td>{{ $club->club_name }}</td>
                    <td>{{ $club->adviser ? $club->adviser->name : 'N/A' }}</td>
                    <td>
                        <span class="category-badge category-{{ $club->category }}">
                            {{ ucfirst($club->category) }}
                        </span>
                    </td>
                    <td>{{ $club->members_count ?? 0 }}</td>
                    <td class="description">{{ $club->club_description ?? 'No description' }}</td>
                    <td>{{ $club->requires_approval ? 'Yes' : 'No' }}</td>
                    <td>{{ $club->created_at ? $club->created_at->format('M j, Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Siterians ClubHive - Clubs Report | Generated: {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>

</html>
