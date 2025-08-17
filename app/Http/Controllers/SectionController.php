<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\ActionLog;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:tbl_classes,class_id',
            'section_name' => 'required|string|max:50',
        ]);

        $section = Section::create($validated);

        // Log section creation
        ActionLog::create_log(
            'user_management',
            'created',
            "Created new section: {$section->section_name}",
            [
                'section_id' => $section->section_id,
                'section_name' => $section->section_name,
                'class_id' => $section->class_id
            ]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Section added successfully',
                'section' => $section
            ]);
        }

        return redirect()->route('admin.users.index')->with('section_added', true);
    }
}
