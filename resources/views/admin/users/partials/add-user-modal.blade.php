<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Add New User</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Role</label>
                    <select name="role" id="roleSelect" required
                        class="w-full px-3 py-2 border rounded-lg @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        <option value="ADMIN">Admin</option>
                        <option value="TEACHER">Teacher</option>
                        <option value="STUDENT">Student</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="classSection" class="hidden">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Class</label>
                            <select name="class_id" id="class_id"
                                class="w-full px-3 py-2 border rounded-lg @error('class_id') border-red-500 @enderror">
                                <option value="">Select Class</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->class_id }}">Grade {{ $class->grade_level }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Section</label>
                            <select name="section_id" id="section_id"
                                class="w-full px-3 py-2 border rounded-lg @error('section_id') border-red-500 @enderror">
                                <option value="">Select Section</option>
                            </select>
                            @error('section_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="toggleSectionModal()"
                            class="mt-2 text-blue-600 text-sm hover:underline">
                            + Add New Section
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleUserModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add User
                </button>
            </div>
        </form>
    </div>
</div>
