{{-- resources/views/admin/users/partials/bulk-upload-modal.blade.php --}}
<div id="bulkUploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Bulk Upload Users</h3>
        <form action="{{ route('admin.users.bulk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <!-- File Upload Section -->
                <div>
                    <label class="block text-sm font-medium mb-1">Excel File (.xlsx, .xls)</label>
                    <input type="file" name="users_file" accept=".xlsx,.xls" required
                        class="w-full px-3 py-2 border rounded-lg @error('users_file') border-red-500 @enderror">
                    <p class="text-sm text-gray-500 mt-1">
                        Download <a href="{{ route('admin.users.template.download') }}"
                            class="text-blue-600 hover:underline font-medium">Excel template</a> for proper formatting
                    </p>
                </div>

                <!-- Excel Format Instructions -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h4 class="font-medium mb-2 text-blue-800">📋 Excel Format Instructions</h4>
                    <div class="text-sm text-blue-800 space-y-2">
                        <p><strong>Required Fields:</strong> Name, Email, Role, Password</p>
                        <p><strong>For Students:</strong> Class and Section are required (must match exactly from
                            reference table below)</p>
                        <p><strong>For Teachers:</strong> Class and Section are optional (leave blank)</p>
                        <p><strong>Available Roles:</strong> Only TEACHER and STUDENT (ADMIN not allowed)</p>
                        <p><strong>Password Rules:</strong> Minimum 8 characters</p>
                        <p><strong>Optional Fields:</strong> Sex, Address, Contact No., Parent/Guardian information</p>
                        <p class="mt-2 text-orange-700"><strong>⚠️ Important:</strong> Class and Section names must
                            match exactly from the reference table below. Copy-paste is recommended to avoid typos.</p>
                    </div>
                </div>

                <!-- Dynamic Class and Section Reference -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-medium text-gray-700">📖 Current Class and Section Reference</h4>
                        <button type="button" onclick="refreshClassSectionReference()"
                            class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-sync-alt mr-1"></i> Refresh
                        </button>
                    </div>
                    <div id="classSectionReference" class="space-y-3">
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
                    <h4 class="font-medium mb-2 text-green-800">💡 Excel Tips for Better Results</h4>
                    <div class="text-sm text-green-800 space-y-1">
                        <p>• <strong>Copy-paste</strong> class and section names from the reference table above</p>
                        <p>• <strong>Use data validation</strong> in Excel: Select cells → Data → Data Validation → List
                            → Type valid values</p>
                        <p>• <strong>Freeze header row</strong> so column names stay visible while scrolling</p>
                        <p>• <strong>Save as .xlsx</strong> format for best compatibility</p>
                        <p>• <strong>Test with 1-2 users first</strong> before uploading large files</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleBulkModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Upload Users
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Load class and section reference when modal opens
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('bulkUploadModal')) {
            loadClassSectionReference();
        }
    });

    function loadClassSectionReference() {
        fetch('{{ route('admin.users.class-section-reference') }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('classSectionReference');
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
                document.getElementById('classSectionReference').innerHTML =
                    '<p class="text-red-500 text-center py-4">Error loading reference data. Please try again.</p>';
            });
    }

    function refreshClassSectionReference() {
        loadClassSectionReference();
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            // Show temporary success message
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check text-green-600"></i>';
            button.classList.add('text-green-600');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('text-green-600');
            }, 1000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
