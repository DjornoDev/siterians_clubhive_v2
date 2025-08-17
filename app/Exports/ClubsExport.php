<?php

namespace App\Exports;

use App\Models\Club;

class ClubsExport
{
    public function collection()
    {
        return Club::with(['adviser', 'memberships'])->get()->map(function ($club) {
            return [
                'ID' => $club->club_id,
                'Club Name' => $club->club_name,
                'Adviser' => $club->adviser ? $club->adviser->name : 'N/A',
                'Description' => strip_tags($club->club_description),
                'Category' => $club->category,
                'Members Count' => $club->memberships->count(),
                'Requires Approval' => $club->requires_approval ? 'Yes' : 'No',
                'Club Hunting Day' => $club->is_club_hunting_day ? 'Yes' : 'No',
                'Created At' => $club->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
