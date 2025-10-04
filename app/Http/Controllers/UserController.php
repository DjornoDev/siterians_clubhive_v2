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
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                // Debug: Log the row data to see what we're getting
                \Log::info("Row $rowNumber raw data:", $row);
                
                // Normalize column names (handle different header formats)
                $normalizedRow = [];
                foreach ($row as $key => $value) {
                    // More aggressive normalization
                    $normalizedKey = strtolower(trim($key));
                    $normalizedKey = str_replace([' ', '.', '-', '_'], '_', $normalizedKey);
                    $normalizedKey = preg_replace('/_{2,}/', '_', $normalizedKey); // Remove multiple underscores
                    $normalizedKey = trim($normalizedKey, '_'); // Remove leading/trailing underscores
                    $normalizedRow[$normalizedKey] = $value;
                }
                
                // Additional mapping for common variations
                $columnMappings = [
                    'contact_no' => ['contact_no', 'contact_number', 'phone', 'phone_number'],
                    'mother_contact_no' => ['mother_contact_no', 'mother_contact_number', 'mother_phone'],
                    'father_contact_no' => ['father_contact_no', 'father_contact_number', 'father_phone'],
                    'guardian_contact_no' => ['guardian_contact_no', 'guardian_contact_number', 'guardian_phone'],
                ];
                
                // Apply column mappings
                foreach ($columnMappings as $targetKey => $possibleKeys) {
                    if (!isset($normalizedRow[$targetKey])) {
                        foreach ($possibleKeys as $possibleKey) {
                            if (isset($normalizedRow[$possibleKey])) {
                                $normalizedRow[$targetKey] = $normalizedRow[$possibleKey];
                                break;
                            }
                        }
                    }
                }
                
                \Log::info("Row $rowNumber column mappings:", array_keys($normalizedRow));
                \Log::info("Row $rowNumber normalized data:", $normalizedRow);
                
                // Map normalized keys to expected keys
                $mappedRow = [
                    'name' => $normalizedRow['name'] ?? null,
                    'email' => $normalizedRow['email'] ?? null,
                    'role' => $normalizedRow['role'] ?? null,
                    'sex' => $normalizedRow['sex'] ?? null,
                    'address' => $normalizedRow['address'] ?? null,
                    'contact_no' => $normalizedRow['contact_no'] ?? null,
                    'mother_name' => $normalizedRow['mother_name'] ?? null,
                    'mother_contact_no' => $normalizedRow['mother_contact_no'] ?? null,
                    'father_name' => $normalizedRow['father_name'] ?? null,
                    'father_contact_no' => $normalizedRow['father_contact_no'] ?? null,
                    'guardian_name' => $normalizedRow['guardian_name'] ?? null,
                    'guardian_contact_no' => $normalizedRow['guardian_contact_no'] ?? null,
                    'password' => $normalizedRow['password'] ?? null,
                    'class' => $this->normalizeClassName($normalizedRow['class'] ?? null),
                    'section' => $normalizedRow['section'] ?? null,
                ];
                
                \Log::info("Row $rowNumber mapped data:", $mappedRow);

                // Validate required fields
                $validator = Validator::make($mappedRow, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:tbl_users,email',
                    'role' => 'required|in:TEACHER,STUDENT',
                    'sex' => 'nullable|in:MALE,FEMALE',
                    'address' => 'nullable|string|max:500',
                    'contact_no' => 'nullable|regex:/^09[0-9]{9}$/',
                    'mother_name' => 'nullable|string|max:255',
                    'mother_contact_no' => 'nullable|regex:/^09[0-9]{9}$/',
                    'father_name' => 'nullable|string|max:255',
                    'father_contact_no' => 'nullable|regex:/^09[0-9]{9}$/',
                    'guardian_name' => 'nullable|string|max:255',
                    'guardian_contact_no' => 'nullable|regex:/^09[0-9]{9}$/',
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
                    if ($mappedRow['role'] === 'STUDENT') {
                        $class = SchoolClass::where('grade_level', $mappedRow['class'])->first();
                        if (!$class) {
                            $availableClasses = SchoolClass::pluck('grade_level')->implode(', ');
                            $errors["Row $rowNumber"] = ["Class '{$mappedRow['class']}' not found. Available classes: {$availableClasses}"];
                            continue;
                        }

                        $section = Section::where('class_id', $class->class_id)
                            ->where('section_name', $mappedRow['section'])
                            ->first();
                        if (!$section) {
                            $availableSections = Section::where('class_id', $class->class_id)->pluck('section_name')->implode(', ');
                            $errors["Row $rowNumber"] = ["Section '{$mappedRow['section']}' not found in class '{$mappedRow['class']}'. Available sections for this class: {$availableSections}"];
                            continue;
                        }
                        $sectionId = $section->section_id;
                    }

                    $user = User::create([
                        'name' => $mappedRow['name'],
                        'email' => $mappedRow['email'],
                        'role' => $mappedRow['role'],
                        'sex' => $mappedRow['sex'] ?? null,
                        'address' => $mappedRow['address'] ?? null,
                        'contact_no' => $mappedRow['contact_no'] ?? null,
                        'mother_name' => $mappedRow['mother_name'] ?? null,
                        'mother_contact_no' => $mappedRow['mother_contact_no'] ?? null,
                        'father_name' => $mappedRow['father_name'] ?? null,
                        'father_contact_no' => $mappedRow['father_contact_no'] ?? null,
                        'guardian_name' => $mappedRow['guardian_name'] ?? null,
                        'guardian_contact_no' => $mappedRow['guardian_contact_no'] ?? null,
                        'section_id' => $sectionId,
                        'password' => Hash::make($mappedRow['password']),
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
     * Download pre-made Excel template for bulk user upload (preserves formatting)
     */
    public function downloadTemplate()
    {
        // Path to your manually created template
        $templatePath = public_path('templates/users_bulk_upload_template.xlsx');
        
        // Check if template exists
        if (!file_exists($templatePath)) {
            return redirect()->back()
                ->withErrors(['template' => 'Template file not found. Please contact administrator.']);
        }
        
        // Create filename with date
        $filename = 'users_bulk_upload_template_' . date('Y-m-d') . '.xlsx';
        
        // Use readfile for direct binary output (preserves all Excel formatting)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($templatePath));
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($templatePath);
        exit;
    }

    /**
     * Create Guide Sheet with instructions and reference data
     */
    private function createGuideSheet($spreadsheet, $classes)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Guide');
        
        $row = 1;
        
        // Title
        $sheet->setCellValue('A' . $row, 'BULK UPLOAD GUIDE & INSTRUCTIONS');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(16);
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $row += 2;
        
        // Instructions
        $sheet->setCellValue('A' . $row, 'INSTRUCTIONS:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $instructions = [
            '1. Use the "Data" sheet to enter user information',
            '2. Required fields: Name, Email, Role, Password',
            '3. For Students: Class and Section are required',
            '4. For Teachers: Class and Section should be left blank',
            '5. Available Roles: TEACHER, STUDENT (ADMIN not allowed)',
            '6. Password must be at least 8 characters',
            '7. Use dropdown menus in the Data sheet for validation',
            '8. Copy exact values from the reference tables below'
        ];
        
        foreach ($instructions as $instruction) {
            $sheet->setCellValue('A' . $row, $instruction);
            $row++;
        }
        $row++;
        
        // Field Descriptions
        $sheet->setCellValue('A' . $row, 'FIELD DESCRIPTIONS:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $fields = [
            ['Field', 'Required', 'Description', 'Valid Values'],
            ['Name', 'Yes', 'Full name of the user', 'Any text'],
            ['Email', 'Yes', 'Must be unique and valid', 'Valid email format'],
            ['Role', 'Yes', 'User role in the system', 'TEACHER, STUDENT'],
            ['Sex', 'No', 'Gender of the user', 'MALE, FEMALE, or blank'],
            ['Address', 'No', 'Home address', 'Any text'],
            ['Contact No.', 'No', 'Phone number', 'Any text'],
            ['Mother Name', 'No', 'Mother\'s full name', 'Any text'],
            ['Mother Contact No.', 'No', 'Mother\'s phone number', 'Any text'],
            ['Father Name', 'No', 'Father\'s full name', 'Any text'],
            ['Father Contact No.', 'No', 'Father\'s phone number', 'Any text'],
            ['Guardian Name', 'No', 'Guardian\'s full name', 'Any text'],
            ['Guardian Contact No.', 'No', 'Guardian\'s phone number', 'Any text'],
            ['Password', 'Yes', 'User password (min 8 chars)', 'At least 8 characters'],
            ['Class', 'For Students', 'Student\'s class/grade level', 'See reference below'],
            ['Section', 'For Students', 'Student\'s section', 'See reference below']
        ];
        
        foreach ($fields as $fieldData) {
            $col = 'A';
            foreach ($fieldData as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        // Style the header row
        $sheet->getStyle('A' . ($row - count($fields)) . ':D' . ($row - count($fields)))->getFont()->setBold(true);
        $row++;
        
        // Class and Section Reference
        $sheet->setCellValue('A' . $row, 'CLASS AND SECTION REFERENCE:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Available Classes:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        foreach ($classes as $class) {
            $sheet->setCellValue('A' . $row, $class->grade_level);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
            
            $sheet->setCellValue('B' . $row, 'Sections:');
            $sectionsText = implode(', ', $class->sections->pluck('section_name')->toArray());
            $sheet->setCellValue('C' . $row, $sectionsText);
            $row++;
            $row++; // Extra space
        }
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Create Data Sheet with sample data and validation
     */
    private function createDataSheet($spreadsheet, $classes)
    {
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Data');
        
        // Headers
        $headers = [
            'Name', 'Email', 'Role', 'Sex', 'Address', 'Contact No.',
            'Mother Name', 'Mother Contact No.', 'Father Name', 'Father Contact No.',
            'Guardian Name', 'Guardian Contact No.', 'Password', 'Class', 'Section'
        ];
        
        $col = 'A';
        foreach ($headers as $header) {
            $dataSheet->setCellValue($col . '1', $header);
            $dataSheet->getStyle($col . '1')->getFont()->setBold(true);
            $col++;
        }
        
        // Sample data
        $sampleData = [
            [
                'Juan Dela Cruz', 'juan@example.com', 'STUDENT', 'MALE', '123 Main St Quezon City', '09123456789',
                'Maria Dela Cruz', '09234567890', 'Jose Dela Cruz', '09345678901',
                'Guardian Name', '09456789012', 'Pass1234', 'Grade 7', 'A'
            ],
            [
                'Maria Santos', 'maria@example.com', 'TEACHER', 'FEMALE', '456 Teacher Ave Manila', '09111222333',
                '', '', '', '', '', '', 'TeacherPass', '', ''
            ]
        ];
        
        $row = 2;
        foreach ($sampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $dataSheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        // Add data validation
        $this->addDataValidation($dataSheet, $classes);
        
        // Freeze header row
        $dataSheet->freezePane('A2');
        
        // Auto-size columns
        foreach (range('A', 'O') as $col) {
            $dataSheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Add data validation to the Data sheet
     */
    private function addDataValidation($sheet, $classes)
    {
        // Role validation (Column C)
        $roleValidation = $sheet->getCell('C2')->getDataValidation();
        $roleValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $roleValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $roleValidation->setAllowBlank(false);
        $roleValidation->setShowInputMessage(true);
        $roleValidation->setShowErrorMessage(true);
        $roleValidation->setShowDropDown(true);
        $roleValidation->setErrorTitle('Invalid Role');
        $roleValidation->setError('Please select a valid role from the dropdown.');
        $roleValidation->setPromptTitle('Select Role');
        $roleValidation->setPrompt('Choose TEACHER or STUDENT');
        $roleValidation->setFormula1('"TEACHER,STUDENT"');
        
        // Copy role validation to more rows
        for ($i = 3; $i <= 100; $i++) {
            $sheet->getCell('C' . $i)->setDataValidation(clone $roleValidation);
        }
        
        // Sex validation (Column D)
        $sexValidation = $sheet->getCell('D2')->getDataValidation();
        $sexValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $sexValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $sexValidation->setAllowBlank(true);
        $sexValidation->setShowInputMessage(true);
        $sexValidation->setShowErrorMessage(true);
        $sexValidation->setShowDropDown(true);
        $sexValidation->setErrorTitle('Invalid Sex');
        $sexValidation->setError('Please select a valid option from the dropdown.');
        $sexValidation->setPromptTitle('Select Sex');
        $sexValidation->setPrompt('Choose MALE, FEMALE, or leave blank');
        $sexValidation->setFormula1('"MALE,FEMALE"');
        
        // Copy sex validation to more rows
        for ($i = 3; $i <= 100; $i++) {
            $sheet->getCell('D' . $i)->setDataValidation(clone $sexValidation);
        }
        
        // Class validation (Column N)
        $classNames = $classes->pluck('grade_level')->toArray();
        if (!empty($classNames)) {
            $classValidation = $sheet->getCell('N2')->getDataValidation();
            $classValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $classValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $classValidation->setAllowBlank(true);
            $classValidation->setShowInputMessage(true);
            $classValidation->setShowErrorMessage(true);
            $classValidation->setShowDropDown(true);
            $classValidation->setErrorTitle('Invalid Class');
            $classValidation->setError('Please select a valid class from the dropdown.');
            $classValidation->setPromptTitle('Select Class');
            $classValidation->setPrompt('Choose from available classes');
            $classValidation->setFormula1('"' . implode(',', $classNames) . '"');
            
            // Copy class validation to more rows
            for ($i = 3; $i <= 100; $i++) {
                $sheet->getCell('N' . $i)->setDataValidation(clone $classValidation);
            }
        }
        
        // Section validation (Column O) - This would ideally be dependent on class selection
        // For now, we'll include all sections
        $allSections = [];
        foreach ($classes as $class) {
            $allSections = array_merge($allSections, $class->sections->pluck('section_name')->toArray());
        }
        $allSections = array_unique($allSections);
        
        if (!empty($allSections)) {
            $sectionValidation = $sheet->getCell('O2')->getDataValidation();
            $sectionValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $sectionValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $sectionValidation->setAllowBlank(true);
            $sectionValidation->setShowInputMessage(true);
            $sectionValidation->setShowErrorMessage(true);
            $sectionValidation->setShowDropDown(true);
            $sectionValidation->setErrorTitle('Invalid Section');
            $sectionValidation->setError('Please select a valid section from the dropdown.');
            $sectionValidation->setPromptTitle('Select Section');
            $sectionValidation->setPrompt('Choose from available sections');
            $sectionValidation->setFormula1('"' . implode(',', $allSections) . '"');
            
            // Copy section validation to more rows
            for ($i = 3; $i <= 100; $i++) {
                $sheet->getCell('O' . $i)->setDataValidation(clone $sectionValidation);
            }
        }
    }

    /**
     * Clean and simple template method using FastExcel
     */
    private function downloadSimpleTemplate()
    {
        $classes = SchoolClass::with('sections')->get();

        // Create clean sample data with just examples
        $sampleData = [
            // Student example 1
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
                'class' => $classes->isNotEmpty() ? $classes->first()->grade_level : '7',
                'section' => $classes->isNotEmpty() && $classes->first()->sections->isNotEmpty() ? $classes->first()->sections->first()->section_name : 'Alpha'
            ],
            // Teacher example
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
                'password' => 'TeacherPass123',
                'class' => '',
                'section' => ''
            ],
            // Student example 2
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
                'class' => $classes->count() > 1 ? $classes->skip(1)->first()->grade_level : '8',
                'section' => $classes->count() > 1 && $classes->skip(1)->first()->sections->isNotEmpty() ? $classes->skip(1)->first()->sections->first()->section_name : 'Alpha'
            ]
        ];

        // Add 10 empty rows for user data entry
        for ($i = 0; $i < 10; $i++) {
            $sampleData[] = [
                'name' => '',
                'email' => '',
                'role' => '',
                'sex' => '',
                'address' => '',
                'contact_no' => '',
                'mother_name' => '',
                'mother_contact_no' => '',
                'father_name' => '',
                'father_contact_no' => '',
                'guardian_name' => '',
                'guardian_contact_no' => '',
                'password' => '',
                'class' => '',
                'section' => ''
            ];
        }

        // Create Excel file with clean sample data
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
     * Get formatted list of available classes
     */
    private function getClassesList($classes)
    {
        if ($classes->isEmpty()) {
            return 'No classes available';
        }
        
        return $classes->pluck('grade_level')->implode(', ');
    }

    /**
     * Get formatted list of available sections
     */
    private function getSectionsList($classes)
    {
        if ($classes->isEmpty()) {
            return 'No sections available';
        }
        
        $allSections = [];
        foreach ($classes as $class) {
            $classSections = $class->sections->pluck('section_name')->toArray();
            $allSections[] = $class->grade_level . ': ' . implode(', ', $classSections);
        }
        
        return implode(' | ', $allSections);
    }

    /**
     * Create enhanced template with guide sheet and dynamic data
     */
    private function downloadEnhancedTemplate()
    {
        $classes = SchoolClass::with('sections')->get();
        
        // Create temporary files for both sheets
        $guideFile = storage_path('app/temp_guide_' . time() . '.xlsx');
        $dataFile = storage_path('app/temp_data_' . time() . '.xlsx');
        $finalFile = storage_path('app/users_bulk_upload_template_' . date('Y-m-d') . '.xlsx');
        
        try {
            // Create Guide Sheet data
            $guideData = $this->createGuideSheetData($classes);
            (new \Rap2hpoutre\FastExcel\FastExcel($guideData))->export($guideFile);
            
            // Create Data Sheet with dynamic examples
            $dataSheetData = $this->createDataSheetData($classes);
            (new \Rap2hpoutre\FastExcel\FastExcel($dataSheetData))->export($dataFile);
            
            // Combine sheets using a simple approach
            $this->combineExcelSheets($guideFile, $dataFile, $finalFile);
            
            // Clean up temp files
            if (file_exists($guideFile)) unlink($guideFile);
            if (file_exists($dataFile)) unlink($dataFile);
            
            // Download the final file
            $filename = 'users_bulk_upload_template_' . date('Y-m-d') . '.xlsx';
            
            return response()->download($finalFile, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            // Clean up temp files on error
            if (file_exists($guideFile)) unlink($guideFile);
            if (file_exists($dataFile)) unlink($dataFile);
            if (file_exists($finalFile)) unlink($finalFile);
            
            // Fallback to simple template
            return $this->downloadSimpleTemplate();
        }
    }

    /**
     * Create guide sheet data with instructions and reference
     */
    private function createGuideSheetData($classes)
    {
        $guideData = [];
        
        // Title row
        $guideData[] = [
            'field' => 'BULK UPLOAD GUIDE',
            'description' => 'Instructions for uploading users',
            'required' => '',
            'valid_values' => '',
            'notes' => ''
        ];
        
        // Empty row
        $guideData[] = [
            'field' => '',
            'description' => '',
            'required' => '',
            'valid_values' => '',
            'notes' => ''
        ];
        
        // Field descriptions
        $fields = [
            ['Name', 'Full name of the user', 'YES', 'Any text', 'Must not be empty'],
            ['Email', 'Must be unique and valid email', 'YES', 'Valid email format', 'Must be unique in system'],
            ['Role', 'User role in the system', 'YES', 'TEACHER or STUDENT', 'ADMIN not allowed in bulk upload'],
            ['Sex', 'Gender of the user', 'NO', 'MALE, FEMALE, or blank', 'Optional field'],
            ['Address', 'Home address', 'NO', 'Any text', 'Optional field'],
            ['Contact No.', 'Phone number', 'NO', 'Any text', 'Optional field'],
            ['Mother Name', 'Mother\'s full name', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Mother Contact No.', 'Mother\'s phone number', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Father Name', 'Father\'s full name', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Father Contact No.', 'Father\'s phone number', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Guardian Name', 'Guardian\'s full name', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Guardian Contact No.', 'Guardian\'s phone number', 'NO', 'Any text', 'Optional (mainly for students)'],
            ['Password', 'User password', 'YES', 'Minimum 8 characters', 'Must be at least 8 characters'],
            ['Class', 'Student\'s class/grade level', 'For STUDENTS', 'See available classes below', 'Required for students, blank for teachers'],
            ['Section', 'Student\'s section', 'For STUDENTS', 'See available sections below', 'Required for students, blank for teachers']
        ];
        
        foreach ($fields as $field) {
            $guideData[] = [
                'field' => $field[0],
                'description' => $field[1],
                'required' => $field[2],
                'valid_values' => $field[3],
                'notes' => $field[4]
            ];
        }
        
        // Empty row
        $guideData[] = [
            'field' => '',
            'description' => '',
            'required' => '',
            'valid_values' => '',
            'notes' => ''
        ];
        
        // Available classes header
        $guideData[] = [
            'field' => 'AVAILABLE CLASSES',
            'description' => 'Copy these exact names',
            'required' => '',
            'valid_values' => '',
            'notes' => ''
        ];
        
        // List all classes and their sections
        foreach ($classes as $class) {
            $sections = $class->sections->pluck('section_name')->implode(', ');
            $guideData[] = [
                'field' => $class->grade_level,
                'description' => 'Available sections',
                'required' => '',
                'valid_values' => $sections,
                'notes' => 'Copy exact names'
            ];
        }
        
        return $guideData;
    }

    /**
     * Create data sheet with dynamic examples
     */
    private function createDataSheetData($classes)
    {
        $dataSheetData = [];
        
        // Get first class and section for examples
        $firstClass = $classes->first();
        $firstSection = $firstClass && $firstClass->sections->isNotEmpty() ? $firstClass->sections->first()->section_name : 'Alpha';
        
        $secondClass = $classes->count() > 1 ? $classes->skip(1)->first() : $firstClass;
        $secondSection = $secondClass && $secondClass->sections->isNotEmpty() ? $secondClass->sections->first()->section_name : 'Alpha';
        
        // Student example 1
        $dataSheetData[] = [
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
            'class' => $firstClass ? $firstClass->grade_level : '7',
            'section' => $firstSection
        ];
        
        // Teacher example
        $dataSheetData[] = [
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
            'password' => 'TeacherPass123',
            'class' => '',
            'section' => ''
        ];
        
        // Student example 2
        $dataSheetData[] = [
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
            'class' => $secondClass ? $secondClass->grade_level : '8',
            'section' => $secondSection
        ];
        
        // Add 10 empty rows
        for ($i = 0; $i < 10; $i++) {
            $dataSheetData[] = [
                'name' => '',
                'email' => '',
                'role' => '',
                'sex' => '',
                'address' => '',
                'contact_no' => '',
                'mother_name' => '',
                'mother_contact_no' => '',
                'father_name' => '',
                'father_contact_no' => '',
                'guardian_name' => '',
                'guardian_contact_no' => '',
                'password' => '',
                'class' => '',
                'section' => ''
            ];
        }
        
        return $dataSheetData;
    }

    /**
     * Simple method to combine two Excel files into one with multiple sheets
     */
    private function combineExcelSheets($guideFile, $dataFile, $outputFile)
    {
        // For now, just use the data file as the main file
        // This is a simplified approach - in a full implementation,
        // you'd use PhpSpreadsheet to properly combine sheets
        copy($dataFile, $outputFile);
    }

    /**
     * Normalize class name from "Grade 7" format to just "7"
     */
    private function normalizeClassName($className)
    {
        if (empty($className) || $className === null) {
            return null;
        }
        
        // Convert to string first
        $className = (string) $className;
        
        // Handle "Grade 7", "Grade 8", etc. → "7", "8", etc.
        if (preg_match('/grade\s*(\d+)/i', $className, $matches)) {
            return $matches[1];
        }
        
        // Handle just numbers like "7", "8", etc.
        if (preg_match('/^\s*(\d+)\s*$/', $className, $matches)) {
            return $matches[1];
        }
        
        // Return as-is if already in correct format (but ensure it's a string)
        return trim($className);
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
