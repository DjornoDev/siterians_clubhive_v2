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
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->input('role'));
            })
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->whereHas('section', function ($q) use ($request) {
                    $q->where('class_id', $request->input('class_id'));
                });
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->input('section_id'));
            })
            ->paginate(10);

        $classes = SchoolClass::with('sections')->get();
        $sections = collect();

        if ($request->filled('class_id')) {
            $sections = Section::where('class_id', $request->input('class_id'))->get();
        }

        return view('admin.users.index', compact('users', 'classes', 'sections'));
    }

    public function export(Request $request)
    {
        $users = User::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->when($request->filled('role'), function ($query) use ($request) {
                $query->where('role', $request->input('role'));
            })
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->whereHas('section', function ($q) use ($request) {
                    $q->where('class_id', $request->input('class_id'));
                });
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->input('section_id'));
            })
            ->with(['section.schoolClass'])
            ->get();

        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'User ID',
                'Name',
                'Email',
                'Role',
                'Sex',
                'Address',
                'Contact No.',
                'Class',
                'Section',
                'Mother\'s Name',
                'Mother\'s Contact No.',
                'Father\'s Name',
                'Father\'s Contact No.',
                'Created At',
                'Updated At'
            ]);

            // User data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->user_id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->sex ?? '',
                    $user->address ?? '',
                    $user->contact_no ?? '',
                    $user->section ? 'Grade ' . $user->section->schoolClass->grade_level : '',
                    $user->section ? $user->section->section_name : '',
                    $user->mother_name ?? '',
                    $user->mother_contact_no ?? '',
                    $user->father_name ?? '',
                    $user->father_contact_no ?? '',
                    $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
                    $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users',
                'role' => 'required|in:TEACHER,STUDENT',
                'sex' => 'nullable|in:MALE,FEMALE',
                'address' => 'nullable|string|max:500',
                'contact_no' => 'nullable|string|max:20',
                'class_id' => 'nullable|required_if:role,STUDENT|exists:tbl_classes,class_id',
                'section_id' => 'nullable|required_if:role,STUDENT|exists:tbl_sections,section_id',
                'mother_name' => 'nullable|string|max:255',
                'mother_contact_no' => 'nullable|string|max:20',
                'father_name' => 'nullable|string|max:255',
                'father_contact_no' => 'nullable|string|max:20',
                'password' => 'required|min:8',
            ]);

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'sex' => $validated['sex'] ?? null,
                'address' => $validated['address'] ?? null,
                'contact_no' => $validated['contact_no'] ?? null,
                'section_id' => $validated['section_id'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_contact_no' => $validated['mother_contact_no'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'father_contact_no' => $validated['father_contact_no'] ?? null,
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
        $expectedHeader = ['name', 'email', 'role', 'sex', 'address', 'contact_no', 'mother_name', 'mother_contact_no', 'father_name', 'father_contact_no', 'password', 'class_id', 'section_id'];
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
                'role' => 'required|in:TEACHER,STUDENT',
                'sex' => 'nullable|in:MALE,FEMALE',
                'address' => 'nullable|string|max:500',
                'contact_no' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:255',
                'mother_contact_no' => 'nullable|string|max:20',
                'father_name' => 'nullable|string|max:255',
                'father_contact_no' => 'nullable|string|max:20',
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
                    'sex' => $data['sex'] ?? null,
                    'address' => $data['address'] ?? null,
                    'contact_no' => $data['contact_no'] ?? null,
                    'mother_name' => $data['mother_name'] ?? null,
                    'mother_contact_no' => $data['mother_contact_no'] ?? null,
                    'father_name' => $data['father_name'] ?? null,
                    'father_contact_no' => $data['father_contact_no'] ?? null,
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
            // If email or password is being changed, verify admin's password
            $emailChanged = $request->filled('email') && $user->email !== $request->email;
            $passwordChanged = $request->filled('password');

            if (($emailChanged || $passwordChanged) && $request->has('admin_password')) {
                // Verify the admin's password
                if (!Hash::check($request->admin_password, auth()->user()->password)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Invalid password. Please try again.'
                    ], 401);
                }
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('tbl_users', 'email')->ignore($user->user_id, 'user_id'),
                ],
                'role' => 'required|in:TEACHER,STUDENT',
                'sex' => 'nullable|in:MALE,FEMALE',
                'address' => 'nullable|string|max:500',
                'contact_no' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:255',
                'mother_contact_no' => 'nullable|string|max:20',
                'father_name' => 'nullable|string|max:255',
                'father_contact_no' => 'nullable|string|max:20',
                'class_id' => 'nullable|required_if:role,STUDENT|exists:tbl_classes,class_id',
                'section_id' => 'nullable|required_if:role,STUDENT|exists:tbl_sections,section_id',
                'password' => 'nullable|min:8',
                'admin_password' => 'nullable|string', // Added for verification
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'sex' => $validated['sex'] ?? null,
                'address' => $validated['address'] ?? null,
                'contact_no' => $validated['contact_no'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_contact_no' => $validated['mother_contact_no'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'father_contact_no' => $validated['father_contact_no'] ?? null,
                'section_id' => $validated['section_id'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Return appropriate response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully'
                ]);
            } else {
                return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
            }
        } catch (\Exception $e) {
            // Return appropriate error response based on request type
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error updating user: ' . $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->with('error', 'Error updating user: ' . $e->getMessage());
            }
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

    /**
     * Check if a user with the given name or email already exists.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkExists(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $excludeId = $request->input('exclude');

        if (!in_array($field, ['name', 'email'])) {
            return response()->json(['error' => 'Invalid field'], 400);
        }

        $query = User::where($field, $value);

        // If we're excluding a user (for edit validation), add the condition
        if ($excludeId) {
            $query->where('user_id', '!=', $excludeId);
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }

    public function getUserDetails(Request $request, User $user)
    {
        // Verify password first
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid password'
            ], 401);
        }

        // Load relationships for detailed view
        $user->load([
            'section.schoolClass',
            'clubMemberships.club',
            'posts',
            'organizedEvents'
        ]);

        // If the user is a teacher, also load the clubs they advise
        if ($user->role === 'TEACHER') {
            $advisedClubs = Club::where('club_adviser', $user->user_id)
                ->withCount('memberships')
                ->get();
            $user->advised_clubs = $advisedClubs;
        }

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
