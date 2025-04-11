<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Edit User</h3>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" id="editName" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="editEmail" required
                        class="w-full px-3 py-2 border rounded-lg">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role" id="editRole" required class="w-full px-3 py-2 border rounded-lg"
                        onchange="toggleEditClassSection()">
                        <option value="">Select Role</option>
                        <option value="ADMIN">Admin</option>
                        <option value="TEACHER">Teacher</option>
                        <option value="STUDENT">Student</option>
                    </select>
                </div>

                <div id="editClassSection" class="hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Class</label>
                            <select name="class_id" id="editClassId" class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->class_id }}">Grade {{ $class->grade_level }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Section</label>
                            <select name="section_id" id="editSectionId" class="w-full px-3 py-2 border rounded-lg">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" id="editPassword" class="w-full px-3 py-2 border rounded-lg"
                        placeholder="Leave blank to keep current">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>