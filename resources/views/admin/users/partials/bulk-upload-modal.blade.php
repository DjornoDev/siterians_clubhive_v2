{{-- resources/views/admin/users/partials/bulk-upload-modal.blade.php --}}
<div id="bulkUploadModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Bulk Upload Users</h3>
        <form action="{{ route('admin.users.bulk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-6">
                <!-- File Upload Section -->
                <div>
                    <label class="block text-sm font-medium mb-1">CSV File</label>
                    <input type="file" name="users_file" accept=".csv" required
                        class="w-full px-3 py-2 border rounded-lg @error('users_file') border-red-500 @enderror">
                    <p class="text-sm text-gray-500 mt-1">Download <a href="/sample-users.csv"
                            class="text-blue-600 hover:underline">CSV template</a></p>
                </div>

                <!-- Reference Tables -->
                <div class="space-y-4">
                    <!-- Class Reference -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-3 text-gray-700">📚 Class Reference (Fixed)</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            @foreach ($classes as $class)
                                <div class="flex items-center justify-between p-2 bg-white rounded border">
                                    <span>Grade {{ $class->grade_level }}</span>
                                    <span class="font-mono bg-gray-100 px-2 py-1 rounded">class_id:
                                        {{ $class->class_id }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Section Reference -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-medium mb-3 text-gray-700">📖 Section Reference (May Change)</h4>
                        <div class="grid gap-2 text-sm">
                            @foreach ($classes as $class)
                                <div class="mb-3">
                                    <div class="font-medium mb-2 text-gray-600">
                                        Grade {{ $class->grade_level }} (class_id: {{ $class->class_id }})
                                    </div>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach ($class->sections as $section)
                                            <div class="p-2 bg-white rounded border flex items-center justify-between">
                                                <span>{{ $section->section_name }}</span>
                                                <span
                                                    class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">section_id:
                                                    {{ $section->section_id }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- CSV Format Example -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h4 class="font-medium mb-2 text-blue-800">📋 CSV Format Example</h4>
                    <pre class="text-sm bg-white p-3 rounded border border-blue-100 overflow-x-auto">
                    name,email,role,password,class_id,section_id
                    Juan Dela Cruz,juan@example.com,STUDENT,Pass1234,1,3
                    Maria Santos,maria@example.com,TEACHER,TeacherPass,,
                    Admin User,admin@example.com,ADMIN,SecurePass123,,
                    Sofia Gomez,sofia@example.com,STUDENT,Student2024,2,20</pre>
                    <p class="text-sm text-blue-800 mt-2">
                        🔑 <span class="font-medium">Password Rules:</span> Minimum 8 characters<br>
                        ⚠️ <span class="font-medium">Note:</span> class_id/section_id only required for STUDENTS
                    </p>
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
