<?php

namespace App\Exports;

use App\Models\Club;

class ClubMembershipExport
{
    protected $club;

    public function __construct(Club $club)
    {
        $this->club = $club;
    }

    public function collection()
    {
        return $this->club->memberships()->with(['user.section.schoolClass'])->get()->map(function ($membership) {
            $className = $membership->user->section && $membership->user->section->schoolClass
                ? 'Grade ' . $membership->user->section->schoolClass->grade_level
                : 'N/A';

            $accessibility = is_array($membership->club_accessibility)
                ? implode(', ', $membership->club_accessibility)
                : $membership->club_accessibility;

            return [
                'Member ID' => $membership->user->user_id,
                'Name' => $membership->user->name,
                'Email' => $membership->user->email,
                'Role' => ucfirst($membership->club_role),
                'Position' => $membership->club_position ?? 'Member',
                'Section' => $membership->user->section ? $membership->user->section->section_name : 'N/A',
                'Class' => $className,
                'Contact Number' => $membership->user->contact_no,
                'Joined Date' => $membership->joined_date ? $membership->joined_date->format('Y-m-d') : 'N/A',
                'Club Accessibility' => $accessibility,
            ];
        });
    }
}
