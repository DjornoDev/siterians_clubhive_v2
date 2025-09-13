<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\SchoolClass;
use App\Models\Club;
use App\Models\Event;
use App\Models\ClubMembership;
use App\Services\MainClubService;
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
                'Guardian\'s Name',
                'Guardian\'s Contact No.',
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
                    $user->guardian_name ?? '',
                    $user->guardian_contact_no ?? '',
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
                'guardian_name' => 'nullable|string|max:255',
                'guardian_contact_no' => 'nullable|string|max:20',
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
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_contact_no' => $validated['guardian_contact_no'] ?? null,
                'password' => Hash::make($validated['password']),
            ]);

            // Automatically add students to main club (SSLG)
            if ($validated['role'] === 'STUDENT') {
                ClubMembership::create([
                    'club_id' => MainClubService::getMainClubId(),
                    'user_id' => $user->user_id,
                    'club_role' => 'MEMBER',
                    'joined_date' => now(),
                    'club_accessibility' => null
                ]);
            }

            // Log user creation action
            ActionLog::create_log(
                'user_management',
                'created',
                "Created new user account for {$user->name}",
                [
                    'user_id' => $user->user_id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'created_by' => auth()->user()->name ?? 'System'
                ]
            );

            return redirect()->route('admin.users.index')->with('user_added', true);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    // Add this method to UserController
    // This method handles the bulk upload of users from an Excel file
    public function bulkStore(Request $request)
    {
        $request->validate([
            'users_file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('users_file');

        try {
            // Use FastExcel to read the Excel file
            $users = (new \Rap2hpoutre\FastExcel\FastExcel)->import($file->getRealPath());

            $errors = [];
            $successCount = 0;

            foreach ($users as $index => $row) {
                $rowNumber = $index + 2; // Account for header row

                // Validate required fields
                $validator = Validator::make($row, [
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
                    'guardian_name' => 'nullable|string|max:255',
                    'guardian_contact_no' => 'nullable|string|max:20',
                    'password' => 'required|min:8',
                    'class' => 'nullable|required_if:role,STUDENT|string',
                    'section' => 'nullable|required_if:role,STUDENT|string',
                ]);

                if ($validator->fails()) {
                    $errors["Row $rowNumber"] = $validator->errors()->all();
                    continue;
                }

                try {
                    // Find class and section by name instead of ID
                    $sectionId = null;
                    if ($row['role'] === 'STUDENT') {
                        $class = SchoolClass::where('grade_level', $row['class'])->first();
                        if (!$class) {
                            $errors["Row $rowNumber"] = ["Class '{$row['class']}' not found. Please check the reference table below."];
                            continue;
                        }

                        $section = Section::where('class_id', $class->class_id)
                            ->where('section_name', $row['section'])
                            ->first();
                        if (!$section) {
                            $errors["Row $rowNumber"] = ["Section '{$row['section']}' not found in class '{$row['class']}'. Please check the reference table below."];
                            continue;
                        }
                        $sectionId = $section->section_id;
                    }

                    $user = User::create([
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'role' => $row['role'],
                        'sex' => $row['sex'] ?? null,
                        'address' => $row['address'] ?? null,
                        'contact_no' => $row['contact_no'] ?? null,
                        'mother_name' => $row['mother_name'] ?? null,
                        'mother_contact_no' => $row['mother_contact_no'] ?? null,
                        'father_name' => $row['father_name'] ?? null,
                        'father_contact_no' => $row['father_contact_no'] ?? null,
                        'guardian_name' => $row['guardian_name'] ?? null,
                        'guardian_contact_no' => $row['guardian_contact_no'] ?? null,
                        'section_id' => $sectionId,
                        'password' => Hash::make($row['password']),
                    ]);

                    if ($user->role === 'STUDENT') {
                        ClubMembership::create([
                            'club_id' => MainClubService::getMainClubId(),
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
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['users_file' => 'Error reading Excel file: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Download Excel template for bulk user upload
     */
    public function downloadTemplate()
    {
        $classes = SchoolClass::with('sections')->get();

        // Create sample data for the template
        $sampleData = [
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'role' => 'STUDENT',
                'sex' => 'MALE',
                'address' => '123 Main St Quezon City',
                'contact_no' => '09123456789',
                'mother_name' => 'Maria Dela Cruz',
                'mother_contact_no' => '09234567890',
                'father_name' => 'Jose Dela Cruz',
                'father_contact_no' => '09345678901',
                'guardian_name' => 'Guardian Name',
                'guardian_contact_no' => '09456789012',
                'password' => 'Pass1234',
                'class' => 'Grade 7',
                'section' => 'A'
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'role' => 'TEACHER',
                'sex' => 'FEMALE',
                'address' => '456 Teacher Ave Manila',
                'contact_no' => '09111222333',
                'mother_name' => '',
                'mother_contact_no' => '',
                'father_name' => '',
                'father_contact_no' => '',
                'guardian_name' => '',
                'guardian_contact_no' => '',
                'password' => 'TeacherPass',
                'class' => '',
                'section' => ''
            ],
            [
                'name' => 'Sofia Gomez',
                'email' => 'sofia@example.com',
                'role' => 'STUDENT',
                'sex' => 'FEMALE',
                'address' => '789 Student Rd Makati',
                'contact_no' => '09444555666',
                'mother_name' => 'Ana Gomez',
                'mother_contact_no' => '09555666777',
                'father_name' => 'Carlos Gomez',
                'father_contact_no' => '09666777888',
                'guardian_name' => 'Guardian Gomez',
                'guardian_contact_no' => '09777888999',
                'password' => 'Student2024',
                'class' => 'Grade 8',
                'section' => 'B'
            ]
        ];

        // Create Excel file with sample data
        $filename = 'users_bulk_upload_template_' . date('Y-m-d') . '.xlsx';

        return (new \Rap2hpoutre\FastExcel\FastExcel($sampleData))
            ->download($filename, function ($item) {
                return [
                    'Name' => $item['name'],
                    'Email' => $item['email'],
                    'Role' => $item['role'],
                    'Sex' => $item['sex'],
                    'Address' => $item['address'],
                    'Contact No.' => $item['contact_no'],
                    'Mother Name' => $item['mother_name'],
                    'Mother Contact No.' => $item['mother_contact_no'],
                    'Father Name' => $item['father_name'],
                    'Father Contact No.' => $item['father_contact_no'],
                    'Guardian Name' => $item['guardian_name'],
                    'Guardian Contact No.' => $item['guardian_contact_no'],
                    'Password' => $item['password'],
                    'Class' => $item['class'],
                    'Section' => $item['section']
                ];
            });
    }

    /**
     * Get current valid class and section combinations for reference
     */
    public function getClassSectionReference()
    {
        $classes = SchoolClass::with('sections')->get();

        $reference = [];
        foreach ($classes as $class) {
            $reference[] = [
                'class' => $class->grade_level,
                'sections' => $class->sections->pluck('section_name')->toArray()
            ];
        }

        return response()->json($reference);
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
                'guardian_name' => 'nullable|string|max:255',
                'guardian_contact_no' => 'nullable|string|max:20',
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
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_contact_no' => $validated['guardian_contact_no'] ?? null,
                'section_id' => $validated['section_id'] ?? null,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Store original data before update for comparison
            $originalData = [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'sex' => $user->sex,
                'address' => $user->address,
                'contact_no' => $user->contact_no,
                'mother_name' => $user->mother_name,
                'mother_contact_no' => $user->mother_contact_no,
                'father_name' => $user->father_name,
                'father_contact_no' => $user->father_contact_no,
                'guardian_name' => $user->guardian_name,
                'guardian_contact_no' => $user->guardian_contact_no,
                'section_id' => $user->section_id,
            ];

            $user->update($updateData);

            // Log user update action with user-friendly description
            ActionLog::create_user_update_log(
                $user,
                $originalData,
                $updateData,
                auth()->user()->name ?? 'System'
            );

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
            // Store user data for logging before deletion
            $userData = [
                'user_id' => $user->user_id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'deleted_by' => auth()->user()->name ?? 'System'
            ];

            $user->delete();

            // Log user deletion action
            ActionLog::create_log(
                'user_management',
                'deleted',
                "Deleted user account for {$userData['user_name']}",
                $userData
            );

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

    /**
     * Bulk delete multiple users with password verification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'required|integer|exists:users,user_id',
            'password' => 'required|string'
        ]);

        // Verify admin password
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'error' => 'Incorrect password'
            ], 401);
        }

        try {
            $userIds = $request->user_ids;
            $deletedUsers = [];
            $failedUsers = [];

            foreach ($userIds as $userId) {
                try {
                    $user = User::find($userId);
                    if ($user) {
                        // Store user data for logging before deletion
                        $userData = [
                            'user_id' => $user->user_id,
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'user_role' => $user->role,
                            'deleted_by' => auth()->user()->name ?? 'System'
                        ];

                        $user->delete();

                        // Log user deletion action
                        ActionLog::create_log(
                            'user_management',
                            'deleted',
                            "Bulk deleted user account for {$userData['user_name']}",
                            $userData
                        );

                        $deletedUsers[] = $userData;
                    }
                } catch (\Exception $e) {
                    $failedUsers[] = [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ];
                }
            }

            if (empty($failedUsers)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully deleted ' . count($deletedUsers) . ' user(s)',
                    'deleted_count' => count($deletedUsers)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'error' => 'Some users could not be deleted',
                    'deleted_count' => count($deletedUsers),
                    'failed_users' => $failedUsers
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error during bulk deletion: ' . $e->getMessage()
            ], 500);
        }
    }
}
