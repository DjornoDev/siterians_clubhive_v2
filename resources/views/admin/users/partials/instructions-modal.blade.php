{{-- resources/views/admin/users/partials/instructions-modal.blade.php --}}
<div id="instructionsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">📋 Bulk Upload Instructions & Guide</h3>
            <button type="button" onclick="toggleInstructionsModal()" 
                class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="space-y-6">
            <!-- Excel Format Instructions -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h4 class="font-medium mb-3 text-blue-800">📋 Excel Format Instructions</h4>
                <div class="text-sm text-blue-800 space-y-2">
                    <p><strong>Required Fields:</strong> Name, Email, Role, Password</p>
                    <p><strong>For Students:</strong> Class and Section are required (must match exactly from reference table below)</p>
                    <p><strong>For Teachers:</strong> Class and Section are optional (leave blank)</p>
                    <p><strong>Available Roles:</strong> Only TEACHER and STUDENT (ADMIN not allowed)</p>
                    <p><strong>Password Rules:</strong> Minimum 8 characters</p>
                    <p><strong>Contact Number Format:</strong> Philippine mobile format (09xxxxxxxxx) - 11 digits starting with 09</p>
                    <p><strong>Optional Fields:</strong> Sex, Address, Contact No., Parent/Guardian information</p>
                    <p class="mt-2 text-orange-700"><strong>⚠️ Important:</strong> Class and Section names must match exactly from the reference table below. Copy-paste is recommended to avoid typos.</p>
                </div>
            </div>

            <!-- Template Information -->
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <h4 class="font-medium mb-3 text-purple-800">📁 Template Features</h4>
                <div class="text-sm text-purple-800 space-y-2">
                    <p><strong>The Excel template includes built-in data validation:</strong></p>
                    <ul class="list-disc list-inside ml-4 space-y-1">
                        <li><strong>Role Dropdown (Column C):</strong> TEACHER/STUDENT validation (C2:C1000)</li>
                        <li><strong>Sex Dropdown (Column D):</strong> MALE/FEMALE validation (D2:D1000)</li>
                        <li><strong>Contact Number Validation:</strong> Philippine mobile format validation (09xxxxxxxxx)</li>
                        <li><strong>Password Validation:</strong> Column M requires minimum 8 characters (M2:M1000)</li>
                        <li><strong>Clean Layout:</strong> Simple headers with sample data and empty rows</li>
                    </ul>
                    <div class="mt-3 p-3 bg-green-50 rounded border border-green-300">
                        <p class="text-sm"><strong>🎨 Google Sheets Color Coding:</strong></p>
                        <div class="text-xs mt-2 space-y-2">
                            <div>
                                <strong>Role Colors:</strong> 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #34a853;"></span>STUDENT (Green) | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #4285f4;"></span>TEACHER (Blue)
                            </div>
                            <div>
                                <strong>Sex Colors:</strong> 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #00ffff;"></span>MALE (Cyan) | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #ff00ff;"></span>FEMALE (Magenta)
                            </div>
                            <div>
                                <strong>Class Colors (Grade 7-12):</strong><br>
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #FF4C4C;"></span>7 | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #FF9900;"></span>8 | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #FFD700;"></span>9 | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #4CAF50;"></span>10 | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #4285F4;"></span>11 | 
                                <span class="inline-block w-3 h-3 rounded mr-1" style="background-color: #9C27B0;"></span>12
                            </div>
                        </div>
                        <p class="text-xs mt-2 italic">Set up conditional formatting and dropdowns in Google Sheets using these colors for better visual organization.</p>
                    </div>
                </div>
            </div>

            <!-- Dynamic Class and Section Reference -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium text-gray-700">📖 Current Class and Section Reference</h4>
                    <button type="button" onclick="refreshInstructionsClassSectionReference()"
                        class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                </div>
                <div id="instructionsClassSectionReference" class="space-y-3">
                    <div class="text-center py-4">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
                        <p class="text-sm text-gray-500 mt-2">Loading reference data...</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    This table shows all currently available class and section combinations.
                    Use these exact names in your Excel file.
                </p>
            </div>

            <!-- Excel Tips -->
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h4 class="font-medium mb-3 text-green-800">💡 Google Sheets Workflow Tips</h4>
                <div class="text-sm text-green-800 space-y-1">
                    <p>• <strong>Download Excel template</strong> and import to Google Sheets</p>
                    <p>• <strong>Set up conditional formatting</strong> with the colors shown above for visual clarity</p>
                    <p>• <strong>Use data validation</strong> - dropdowns will still work after import</p>
                    <p>• <strong>Copy exact values</strong> from the reference table below for Class and Section fields</p>
                    <p>• <strong>Leave teacher Class/Section blank</strong> - only students need these fields</p>
                    <p>• <strong>Export as .xlsx</strong> when ready to upload</p>
                    <p>• <strong>Test with 1-2 users first</strong> before uploading large files</p>
                </div>
            </div>

            <!-- Adding New Sections Guide -->
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <h4 class="font-medium mb-3 text-yellow-800">➕ Adding New Class/Section Options</h4>
                <div class="text-sm text-yellow-800 space-y-2">
                    <p><strong>If you need to add new classes or sections:</strong></p>
                    <ol class="list-decimal list-inside ml-4 space-y-1">
                        <li>Go to the main Classes management page in the admin panel</li>
                        <li>Add the new class and/or sections there first</li>
                        <li>Return to this bulk upload and click "Refresh" on the reference table</li>
                        <li>Download a new template to get updated data validation</li>
                    </ol>
                    <p class="mt-2 text-orange-700"><strong>⚠️ Note:</strong> You must add classes/sections through the admin panel first before they can be used in bulk upload.</p>
                </div>
            </div>

            <!-- Field Descriptions -->
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                <h4 class="font-medium mb-3 text-indigo-800">📝 Field Descriptions</h4>
                <div class="text-sm text-indigo-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium mb-2">Required Fields:</h5>
                            <ul class="space-y-1 text-xs">
                                <li><strong>Name:</strong> Full name of the user</li>
                                <li><strong>Email:</strong> Must be unique and valid</li>
                                <li><strong>Role:</strong> TEACHER or STUDENT only</li>
                                <li><strong>Password:</strong> Minimum 8 characters</li>
                                <li><strong>Class:</strong> Required for students</li>
                                <li><strong>Section:</strong> Required for students</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium mb-2">Optional Fields:</h5>
                            <ul class="space-y-1 text-xs">
                                <li><strong>Sex:</strong> Male, Female, or leave blank</li>
                                <li><strong>Address:</strong> Home address</li>
                                <li><strong>Contact No.:</strong> Phone number</li>
                                <li><strong>Parent/Guardian Name:</strong> For students</li>
                                <li><strong>Parent/Guardian Contact:</strong> Emergency contact</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="mt-6 flex justify-end">
            <button type="button" onclick="toggleInstructionsModal()"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Got it!
            </button>
        </div>
    </div>
</div>

<script>
    function toggleInstructionsModal() {
        const modal = document.getElementById('instructionsModal');
        modal.classList.toggle('hidden');
        
        // Load reference data when modal opens
        if (!modal.classList.contains('hidden')) {
            loadInstructionsClassSectionReference();
        }
    }

    function loadInstructionsClassSectionReference() {
        fetch('{{ route('admin.users.class-section-reference') }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('instructionsClassSectionReference');
                if (data.length === 0) {
                    container.innerHTML =
                        '<p class="text-gray-500 text-center py-4">No classes and sections found.</p>';
                    return;
                }

                let html = '';
                data.forEach(classData => {
                    html += `
                    <div class="mb-4">
                        <div class="font-medium mb-2 text-gray-600 bg-white px-3 py-2 rounded border">
                            ${classData.class}
                        </div>
                        <div class="grid grid-cols-3 gap-2 ml-4">
                `;

                    classData.sections.forEach(section => {
                        html += `
                        <div class="p-2 bg-white rounded border flex items-center justify-between text-sm">
                            <span class="font-mono">${section}</span>
                            <button type="button" onclick="copyToClipboard('${section}')" 
                                class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    `;
                    });

                    html += `
                        </div>
                    </div>
                `;
                });

                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Error loading reference:', error);
                document.getElementById('instructionsClassSectionReference').innerHTML =
                    '<p class="text-red-500 text-center py-4">Error loading reference data. Please try again.</p>';
            });
    }

    function refreshInstructionsClassSectionReference() {
        loadInstructionsClassSectionReference();
    }
</script>