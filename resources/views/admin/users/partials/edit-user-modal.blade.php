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
                    <p id="editEmailError" class="text-red-500 text-xs mt-1 hidden"></p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editEmailInput = document.getElementById('editEmail');
        const editEmailError = document.getElementById('editEmailError');
        const editUserForm = document.getElementById('editUserForm');

        // Function to check if email exists (excluding current user)
        async function checkEmailExists(email, userId) {
            try {
                const response = await fetch(
                    `/admin/users/check-exists?field=email&value=${encodeURIComponent(email)}&exclude=${userId}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                    });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                return await response.json();
            } catch (error) {
                console.error('Error checking email existence:', error);
                return {
                    exists: false
                };
            }
        }

        // Store original email to check if it was changed
        let originalEmail = '';
        // When the edit modal is opened, store the original email
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const modal = document.getElementById('editUserModal');
                    if (!modal.classList.contains('hidden')) {
                        // Modal just became visible
                        originalEmail = document.getElementById('editEmail').value;
                    }
                }
            });
        });

        // Start observing the edit modal for class changes
        observer.observe(document.getElementById('editUserModal'), {
            attributes: true,
            attributeFilter: ['class']
        });

        // Validate email on blur only if it has changed from original
        editEmailInput.addEventListener('blur', async function() {
            if (this.value.trim() && this.value !== originalEmail) {
                const userId = editUserForm.action.split('/').pop();
                const result = await checkEmailExists(this.value, userId);

                if (result.exists) {
                    editEmailError.textContent = 'This email already exists in the database.';
                    editEmailError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    editEmailError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            } else {
                editEmailError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        }); // Form submission validation
        editUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = editEmailInput.value;
            let canSubmit = true;

            // Only validate if email has changed
            if (email !== originalEmail) {
                const userId = this.action.split('/').pop();
                const result = await checkEmailExists(email, userId);

                if (result.exists) {
                    editEmailError.textContent = 'This email already exists in the database.';
                    editEmailError.classList.remove('hidden');
                    editEmailInput.classList.add('border-red-500');
                    canSubmit = false;
                } else {
                    editEmailError.classList.add('hidden');
                    editEmailInput.classList.remove('border-red-500');
                }
            }

            // If validation passes, check if we need password verification
            if (canSubmit) {
                const emailChanged = email !== originalEmail;
                const passwordChanged = document.getElementById('editPassword').value.trim() !== '';

                // If either email or password is being changed, show password verification modal
                if (emailChanged || passwordChanged) {
                    // Store form data in hidden field
                    const formData = new FormData(this);
                    document.getElementById('editVerifiedUserId').value = this.action.split('/')
                        .pop();
                    document.getElementById('editOriginalFormData').value = JSON.stringify(Object
                        .fromEntries(formData));

                    // Show password verification modal
                    document.getElementById('editPasswordVerificationModal').classList.remove(
                        'hidden');
                } else {
                    // If no sensitive fields are being changed, submit form normally
                    this.submit();
                }
            }
        });
    });
</script>
