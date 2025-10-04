<?php
/**
 * Standalone script to create an enhanced Excel template with multiple sheets and data validation
 * Run this script to generate the new template file
 */

// Check if PhpSpreadsheet is available
if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
    echo "PhpSpreadsheet is not installed. Installing...\n";
    echo "Run: composer require phpoffice/phpspreadsheet\n";
    exit(1);
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

// Sample class and section data (you can modify this based on your actual data)
$classesData = [
    [
        'grade_level' => 'Grade 7',
        'sections' => ['A', 'B', 'C', 'D']
    ],
    [
        'grade_level' => 'Grade 8', 
        'sections' => ['A', 'B', 'C', 'D']
    ],
    [
        'grade_level' => 'Grade 9',
        'sections' => ['A', 'B', 'C']
    ],
    [
        'grade_level' => 'Grade 10',
        'sections' => ['A', 'B', 'C']
    ],
    [
        'grade_level' => 'Grade 11',
        'sections' => ['STEM', 'HUMSS', 'ABM', 'GAS']
    ],
    [
        'grade_level' => 'Grade 12',
        'sections' => ['STEM', 'HUMSS', 'ABM', 'GAS']
    ]
];

// Create new spreadsheet
$spreadsheet = new Spreadsheet();

// Create Guide Sheet
createGuideSheet($spreadsheet, $classesData);

// Create Data Sheet with validation
createDataSheet($spreadsheet, $classesData);

// Set Data sheet as active
$spreadsheet->setActiveSheetIndex(1);

// Create filename
$filename = 'users_bulk_upload_template_' . date('Y-m-d') . '.xlsx';

// Create writer and save
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

echo "Template created successfully: $filename\n";

function createGuideSheet($spreadsheet, $classesData)
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
    
    foreach ($classesData as $classData) {
        $sheet->setCellValue('A' . $row, $classData['grade_level']);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        $sheet->setCellValue('B' . $row, 'Sections:');
        $sectionsText = implode(', ', $classData['sections']);
        $sheet->setCellValue('C' . $row, $sectionsText);
        $row++;
        $row++; // Extra space
    }
    
    // Adding new sections guide
    $sheet->setCellValue('A' . $row, 'HOW TO ADD NEW CLASS/SECTION OPTIONS:');
    $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
    $row++;
    
    $addingInstructions = [
        '1. Go to the main Classes management page in the admin panel',
        '2. Add the new class and/or sections there first',
        '3. Return to bulk upload and click "Refresh" on the reference table',
        '4. Download a new template to get updated data validation',
        '',
        'NOTE: You must add classes/sections through the admin panel first',
        'before they can be used in bulk upload.'
    ];
    
    foreach ($addingInstructions as $instruction) {
        $sheet->setCellValue('A' . $row, $instruction);
        if (strpos($instruction, 'NOTE:') === 0) {
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        }
        $row++;
    }
    
    // Auto-size columns
    foreach (range('A', 'E') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
}

function createDataSheet($spreadsheet, $classesData)
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
    addDataValidation($dataSheet, $classesData);
    
    // Freeze header row
    $dataSheet->freezePane('A2');
    
    // Auto-size columns
    foreach (range('A', 'O') as $col) {
        $dataSheet->getColumnDimension($col)->setAutoSize(true);
    }
}

function addDataValidation($sheet, $classesData)
{
    // Role validation (Column C)
    $roleValidation = $sheet->getCell('C2')->getDataValidation();
    $roleValidation->setType(DataValidation::TYPE_LIST);
    $roleValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
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
    $sexValidation->setType(DataValidation::TYPE_LIST);
    $sexValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
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
    $classNames = array_column($classesData, 'grade_level');
    if (!empty($classNames)) {
        $classValidation = $sheet->getCell('N2')->getDataValidation();
        $classValidation->setType(DataValidation::TYPE_LIST);
        $classValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
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
    
    // Section validation (Column O)
    $allSections = [];
    foreach ($classesData as $classData) {
        $allSections = array_merge($allSections, $classData['sections']);
    }
    $allSections = array_unique($allSections);
    
    if (!empty($allSections)) {
        $sectionValidation = $sheet->getCell('O2')->getDataValidation();
        $sectionValidation->setType(DataValidation::TYPE_LIST);
        $sectionValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
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
