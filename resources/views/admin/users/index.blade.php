@extends('layouts.dashboard')

@section('title', 'Manage Users | ClubHive')

@section('content')
    <div class="p-6">
        <div class="bg-white p-8 border border-gray-200 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
                <button onclick="toggleUserModal()"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium flex items-center gap-2 shadow-sm hover:shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add User
                </button>
            </div>

            <!-- Filters -->
            <div class="mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200 shadow-sm">
                <form action="{{ route('admin.users.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-5 items-end">
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Name or email..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Role</label>
                        <select name="role"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">All Roles</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="TEACHER" {{ request('role') === 'TEACHER' ? 'selected' : '' }}>Teacher</option>
                            <option value="STUDENT" {{ request('role') === 'STUDENT' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 w-full md:w-auto font-medium shadow-sm">
                            Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                            class="bg-white text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-100 transition-colors duration-200 w-full md:w-auto text-center font-medium border border-gray-300">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- User Table -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Email</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100"
                                data-user-id="{{ $user->user_id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 mr-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-lg">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="text-sm font-medium text-gray-800">{{ $user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @php
                                        $emailParts = explode('@', $user->email);
                                        $maskedLocal = substr($emailParts[0], 0, 3) . '*****';
                                        echo $maskedLocal . '@' . $emailParts[1];
                                    @endphp
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium shadow-sm
                                    @switch($user->role)
                                        @case('ADMIN') bg-purple-100 text-purple-800 border border-purple-200 @break
                                        @case('TEACHER') bg-blue-100 text-blue-800 border border-blue-200 @break
                                        @case('STUDENT') bg-emerald-100 text-emerald-800 border border-emerald-200 @break
                                    @endswitch">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <button
                                            onclick="openEditModal('{{ $user->user_id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->section->class_id ?? '' }}', '{{ $user->section_id ?? '' }}')"
                                            class="text-blue-600 hover:text-blue-900 transition-colors duration-150 flex items-center gap-1 border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button"
                                            onclick="openDeleteModal('{{ $user->user_id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                            class="text-red-600 hover:text-red-900 transition-colors duration-150 flex items-center gap-1 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-600">No users found matching your criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <div class="pagination-wrapper">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>

            <!-- Add User Modal -->
            @include('admin.users.partials.add-user-modal')

            <!-- Edit User Modal -->
            @include('admin.users.partials.edit-user-modal')

            <!-- Delete User Modal -->
            @include('admin.users.partials.delete-user-modal')

            <!-- Add Section Modal -->
            @include('admin.users.partials.add-section-modal')

            <!-- Password Verification Modal -->
            @include('admin.users.partials.password-verification-modal')

            <!-- User Success Modal -->
            <div id="userSuccessModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div
                    class="bg-white rounded-lg p-6 w-full max-w-sm mx-auto shadow-lg transform transition-all duration-300 opacity-0 translate-y-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">User Added Successfully</h3>
                        <p class="mt-2 text-sm text-gray-500">The new user has been added to the system.</p>
                    </div>
                </div>
            </div>

            <!-- Section Success Modal -->
            <div id="sectionSuccessModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div
                    class="bg-white rounded-lg p-6 w-full max-w-sm mx-auto shadow-lg transform transition-all duration-300 opacity-0 translate-y-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Section Added Successfully</h3>
                        <p class="mt-2 text-sm text-gray-500">The new section has been added to the system.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User Modal Interactions
        function toggleUserModal() {
            document.getElementById('addUserModal').classList.toggle('hidden');
        }

        function toggleSectionModal() {
            document.getElementById('addSectionModal').classList.toggle('hidden');
        }

        // Dynamic Class/Section Handling
        document.getElementById('roleSelect').addEventListener('change', function() {
            const classSection = document.getElementById('classSection');
            classSection.classList.toggle('hidden', this.value !== 'STUDENT');
        });

        // Dynamic Section Loading
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            if (!classId) return;

            fetch(`/get-sections/${classId}`)
                .then(response => response.json())
                .then(sections => {
                    const sectionSelect = document.getElementById('section_id');
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.section_id;
                        option.textContent = section.section_name;
                        sectionSelect.appendChild(option);
                    });
                });
        });

        // Add this helper function
        function fetchSections(classId) {
            if (!classId) return;

            fetch(`/get-sections/${classId}`)
                .then(response => response.json())
                .then(sections => {
                    const sectionSelect = document.getElementById('section_id');
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    sections.forEach(section => {
                        const option = new Option(section.section_name, section.section_id);
                        sectionSelect.add(option);
                    });
                });
        }

        // Add to your existing JavaScript
        document.getElementById('addSectionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toggleSectionModal(); // Close section modal
                        showSectionSuccessModal(); // Show section success

                        // Refresh sections dropdown in Add User modal
                        const classSelect = document.getElementById('class_id');
                        if (classSelect.value) {
                            classSelect.dispatchEvent(new Event('change'));
                        }
                    } else {
                        // Handle validation errors
                        console.error(data.errors);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        // Success modal display functions
        function showUserSuccessModal() {
            const modal = document.getElementById('userSuccessModal');
            showModal(modal);
        }

        function showSectionSuccessModal() {
            const modal = document.getElementById('sectionSuccessModal');
            showModal(modal);
        }

        function showModal(modal) {
            const content = modal.querySelector('.bg-white');
            modal.classList.remove('hidden');
            setTimeout(() => content.classList.remove('opacity-0', 'translate-y-4'), 10);
            setTimeout(() => modal.classList.add('hidden'), 3000);
        }

        // Edit Modal Functions
        function openEditModal(userId, name, email, role, classId, sectionId) {
            const form = document.getElementById('editUserForm');
            form.action = `/admin/users/${userId}`;

            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;

            const classSection = document.getElementById('editClassSection');
            if (role === 'STUDENT') {
                classSection.classList.remove('hidden');
                document.getElementById('editClassId').value = classId;
                const event = new Event('change');
                document.getElementById('editClassId').dispatchEvent(event);
                setTimeout(() => {
                    document.getElementById('editSectionId').value = sectionId;
                }, 500);
            } else {
                classSection.classList.add('hidden');
            }

            document.getElementById('editUserModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }

        // Delete Modal Functions
        function openDeleteModal(userId, userName, userEmail) {
            // Set the form action
            document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;

            // Set the user ID in the hidden field
            document.getElementById('userId').value = userId;

            // Set the user details in the modal
            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteUserEmail').textContent = userEmail;

            // Show the modal
            document.getElementById('deleteUserModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteUserModal').classList.add('hidden');
        }

        function showPasswordModal() {
            document.getElementById('passwordVerificationModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordVerificationModal').classList.add('hidden');
            // Clear password input when closing modal
            document.getElementById('passwordInput').value = '';
            // Clear any error message
            const errorElement = document.getElementById('passwordError');
            errorElement.classList.add('hidden');
            errorElement.textContent = '';
            // Remove any error styling
            document.getElementById('passwordInput').classList.remove('border-red-500');
        }

        function showSuccessModal() {
            const modal = document.getElementById('deleteSuccessModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 2000);
        }

        // Handle password verification
        document.getElementById('verifyPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('passwordInput').value;
            const deleteForm = document.getElementById('deleteUserForm');
            const userId = document.getElementById('userId').value;
            const errorElement = document.getElementById('passwordError');

            // Hide any previous error
            errorElement.classList.add('hidden');
            errorElement.textContent = '';

            fetch(deleteForm.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        password: password
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDeleteModal();
                        closePasswordModal();
                        showSuccessModal();
                        // Remove user row from table
                        document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                    } else {
                        // Show error message in the form
                        errorElement.textContent = data.error || 'Password verification failed';
                        errorElement.classList.remove('hidden');
                        // Optional: Add a shake animation to the password input
                        document.getElementById('passwordInput').classList.add('border-red-500');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorElement.textContent = 'An error occurred. Please try again.';
                    errorElement.classList.remove('hidden');
                });
        });

        // Toggle Class Section in Edit Modal
        function toggleEditClassSection() {
            const roleSelect = document.getElementById('editRole');
            const classSection = document.getElementById('editClassSection');
            classSection.classList.toggle('hidden', roleSelect.value !== 'STUDENT');
        }

        // Load Sections for Edit Modal
        document.getElementById('editClassId').addEventListener('change', function() {
            const classId = this.value;
            if (!classId) return;

            fetch(`/get-sections/${classId}`)
                .then(response => response.json())
                .then(sections => {
                    const sectionSelect = document.getElementById('editSectionId');
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.section_id;
                        option.textContent = section.section_name;
                        sectionSelect.appendChild(option);
                    });
                });
        });

        // Close modals when clicking outside
        document.querySelectorAll('.fixed.inset-0').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    // Clear password if it's the verification modal
                    if (this.id === 'passwordVerificationModal') {
                        document.getElementById('passwordInput').value = '';
                    }
                }
            });
        });
    </script>

    @if (session('user_added'))
        <script>
            document.addEventListener('DOMContentLoaded', showUserSuccessModal);
        </script>
    @endif

    @if (session('section_added'))
        <script>
            document.addEventListener('DOMContentLoaded', showSectionSuccessModal);
        </script>
    @endif
@endsection
