<?php

namespace App\Exports;

use App\Models\User;

class UsersExport
{
    public function collection()
    {
        return User::with(['section.schoolClass'])->get()->map(function ($user) {
            $className = $user->section && $user->section->schoolClass
                ? 'Grade ' . $user->section->schoolClass->grade_level
                : 'N/A';

            return [
                'ID' => $user->user_id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Role' => ucfirst($user->role),
                'Status' => ucfirst($user->status),
                'Gender' => ucfirst($user->sex),
                'Address' => $user->address,
                'Contact Number' => $user->contact_no,
                'Section' => $user->section ? $user->section->section_name : 'N/A',
                'Class' => $className,
                'Mother Name' => $user->mother_name,
                'Mother Contact' => $user->mother_contact_no,
                'Father Name' => $user->father_name,
                'Father Contact' => $user->father_contact_no,
                'Created At' => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
