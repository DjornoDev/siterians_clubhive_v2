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

            return [
                'Name' => $membership->user->name ?? 'N/A',
                'Email' => $membership->user->email ?? 'N/A',
                'Sex' => $membership->user->sex ?? 'N/A',
                'Address' => $membership->user->address ?? 'N/A',
                'Contact No' => $membership->user->contact_no ?? 'N/A',
                'Section' => $membership->user->section ? $membership->user->section->section_name : 'N/A',
                'Mother Name' => $membership->user->mother_name ?? 'N/A',
                'Mother Contact No' => $membership->user->mother_contact_no ?? 'N/A',
                'Father Name' => $membership->user->father_name ?? 'N/A',
                'Father Contact No' => $membership->user->father_contact_no ?? 'N/A',
            ];
        });
    }
}
