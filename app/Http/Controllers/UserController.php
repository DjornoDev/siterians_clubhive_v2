<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\SchoolClass;
use App\Models\Club;
use App\Models\Event;
use App\Models\ClubMembership;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->when($request->filled('role'), function ($query) use ($request) { // Changed from has() to filled()
                $query->where('role', $request->input('role'));
            })
            ->paginate(10);

        $classes = SchoolClass::with('sections')->get();

        return view('admin.users.index', compact('users', 'classes'));
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users',
                'role' => 'required|in:ADMIN,TEACHER,STUDENT',
                'class_id' => 'nullable|required_if:role,STUDENT|exists:tbl_classes,class_id',
                'section_id' => 'nullable|required_if:role,STUDENT|exists:tbl_sections,section_id',
                'password' => 'required|min:8',
            ]);

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'section_id' => $validated['section_id'] ?? null,
                'password' => Hash::make($validated['password']),
            ]);

            // Automatically add students to SSG club (ID 1)
            if ($validated['role'] === 'STUDENT') {
                ClubMembership::create([
                    'club_id' => 1,
                    'user_id' => $user->user_id,
                    'club_role' => 'MEMBER',
                    'joined_date' => now(),
                    'club_accessibility' => null
                ]);
            }

            return redirect()->route('admin.users.index')->with('user_added', true);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    // Add this method to UserController
    // This method handles the bulk upload of users from a CSV file
    public function bulkStore(Request $request)
    {
        $request->validate([
            'users_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('users_file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($csvData);

        // Validate CSV header
        $expectedHeader = ['name', 'email', 'role', 'password', 'class_id', 'section_id'];
        if ($header !== $expectedHeader) {
            return redirect()->back()
                ->withErrors(['users_file' => 'Invalid CSV format. Please use the provided template.']);
        }

        $errors = [];
        $successCount = 0;

        foreach ($csvData as $index => $row) {
            $data = array_combine($header, $row);
            $rowNumber = $index + 2; // Account for header row

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users,email',
                'role' => 'required|in:ADMIN,TEACHER,STUDENT',
                'password' => 'required|min:8',
                'class_id' => 'nullable|required_if:role,STUDENT|exists:tbl_classes,class_id',
                'section_id' => 'nullable|required_if:role,STUDENT|exists:tbl_sections,section_id',
            ]);

            if ($validator->fails()) {
                $errors["Row $rowNumber"] = $validator->errors()->all();
                continue;
            }

            try {
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'section_id' => $data['role'] === 'STUDENT' ? $data['section_id'] : null,
                    'password' => Hash::make($data['password']),
                ]);

                if ($user->role === 'STUDENT') {
                    ClubMembership::create([
                        'club_id' => 1,
                        'user_id' => $user->user_id,
                        'club_role' => 'MEMBER',
                        'joined_date' => now(),
                        'club_accessibility' => null
                    ]);
                }

                $successCount++;
            } catch (\Exception $e) {
                $errors["Row $rowNumber"] = ["Error creating user: " . $e->getMessage()];
            }
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->with('bulk_errors', $errors)
                ->withInput();
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Successfully imported $successCount users");
    }



    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('tbl_users', 'email')->ignore($user->user_id, 'user_id'),
                ],
                'role' => 'required|in:ADMIN,TEACHER,STUDENT',
                'class_id' => 'nullable|required_if:role,STUDENT|exists:tbl_classes,class_id',
                'section_id' => 'nullable|required_if:role,STUDENT|exists:tbl_sections,section_id',
                'password' => 'nullable|min:8',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'section_id' => $validated['section_id'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy(User $user, Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Incorrect password'
            ], 401);
        }

        try {
            $user->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }
}
