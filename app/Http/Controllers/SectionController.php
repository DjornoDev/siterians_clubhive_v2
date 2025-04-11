<?php

namespace App\Http\Controllers;

use App\Models\Section;
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
