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
                        Download <a href="{{ asset('templates/users_bulk_upload_template.xlsx') }}"
                            class="text-blue-600 hover:underline font-medium" download="users_bulk_upload_template_{{ date('Y-m-d') }}.xlsx">Excel template</a> for proper formatting
                    </p>
                </div>

                <!-- Quick Instructions -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium mb-1 text-blue-800"> Quick Start</h4>
                            <p class="text-sm text-blue-700">Download the template with built-in dropdowns and validation. Just fill in your data!</p>
                        </div>
                        <button type="button" onclick="toggleInstructionsModal()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            View Full Instructions
                        </button>
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
    function copyToClipboard(text, buttonElement) {
        navigator.clipboard.writeText(text).then(() => {
            // Show temporary success message
            if (buttonElement) {
                const originalHTML = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="fas fa-check text-green-600"></i>';
                buttonElement.classList.add('text-green-600');

                setTimeout(() => {
                    buttonElement.innerHTML = originalHTML;
                    buttonElement.classList.remove('text-green-600');
                }, 1000);
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
