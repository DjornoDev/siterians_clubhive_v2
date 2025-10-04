<div id="editUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Edit User</h3>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h4>

                    <div>
                        <label class="block text-sm font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="editName" required
                            class="w-full px-3 py-2 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="editEmail" required
                            class="w-full px-3 py-2 border rounded-lg">
                        <p id="editEmailError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="editRole" required class="w-full px-3 py-2 border rounded-lg"
                            onchange="toggleEditClassSection()">
                            <option value="">Select Role</option>
                            <option value="TEACHER">Teacher</option>
                            <option value="STUDENT">Student</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Sex</label>
                        <select name="sex" id="editSex" class="w-full px-3 py-2 border rounded-lg">
                            <option value="">Select Sex</option>
                            <option value="MALE">Male</option>
                            <option value="FEMALE">Female</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Contact No.</label>
                        <input type="text" name="contact_no" id="editContactNo" placeholder="09XXXXXXXXX"
                            class="w-full px-3 py-2 border rounded-lg">
                        <p id="editContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        <p class="text-gray-500 text-xs mt-1">Format: 09XXXXXXXXX (11 digits starting with 09)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Address</label>
                        <textarea name="address" id="editAddress" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>
                </div>

                <!-- Parent Information & School Details -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Parent Information</h4>

                    <div>
                        <label class="block text-sm font-medium mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" id="editMotherName"
                            class="w-full px-3 py-2 border rounded-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Mother's Contact No.</label>
                        <input type="text" name="mother_contact_no" id="editMotherContactNo" placeholder="09XXXXXXXXX"
                            class="w-full px-3 py-2 border rounded-lg">
                        <p id="editMotherContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Father's Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Father's Information</h4>

                        <div>
                            <label class="block text-sm font-medium mb-1">Father's Name</label>
                            <input type="text" name="father_name" id="editFatherName"
                                class="w-full px-3 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Father's Contact Number</label>
                            <input type="tel" name="father_contact_no" id="editFatherContactNo"
                                class="w-full px-3 py-2 border rounded-lg" placeholder="09XXXXXXXXX">
                            <p id="editFatherContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                    <!-- Guardian's Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Guardian's Information</h4>

                        <div>
                            <label class="block text-sm font-medium mb-1">Guardian's Name</label>
                            <input type="text" name="guardian_name" id="editGuardianName"
                                class="w-full px-3 py-2 border rounded-lg">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Guardian's Contact Number</label>
                            <input type="tel" name="guardian_contact_no" id="editGuardianContactNo"
                                class="w-full px-3 py-2 border rounded-lg" placeholder="09XXXXXXXXX">
                            <p id="editGuardianContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                    <div id="editClassSection" class="hidden">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mt-6">School Information</h4>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Class <span
                                        class="text-red-500">*</span></label>
                                <select name="class_id" id="editClassId" class="w-full px-3 py-2 border rounded-lg">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}">Grade {{ $class->grade_level }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Section <span
                                        class="text-red-500">*</span></label>
                                <select name="section_id" id="editSectionId"
                                    class="w-full px-3 py-2 border rounded-lg">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium mb-1">Password</label>
                        <input type="password" name="password" id="editPassword"
                            class="w-full px-3 py-2 border rounded-lg" placeholder="Leave blank to keep current">
                        <p id="editPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                        <p class="text-gray-500 text-xs mt-1">Minimum 8 characters required (leave blank to keep current)</p>
                    </div>
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
        const editPasswordInput = document.getElementById('editPassword');
        const editContactNoInput = document.getElementById('editContactNo');
        const editMotherContactNoInput = document.getElementById('editMotherContactNo');
        const editFatherContactNoInput = document.getElementById('editFatherContactNo');
        const editGuardianContactNoInput = document.getElementById('editGuardianContactNo');
        
        const editEmailError = document.getElementById('editEmailError');
        const editPasswordError = document.getElementById('editPasswordError');
        const editContactNoError = document.getElementById('editContactNoError');
        const editMotherContactNoError = document.getElementById('editMotherContactNoError');
        const editFatherContactNoError = document.getElementById('editFatherContactNoError');
        const editGuardianContactNoError = document.getElementById('editGuardianContactNoError');
        
        const editUserForm = document.getElementById('editUserForm');

        // Philippine mobile number validation
        function validatePhilippineMobile(number) {
            const pattern = /^09\d{9}$/;
            return pattern.test(number);
        }

        // Password validation
        function validatePassword(password) {
            return password.length >= 8;
        }

        // Generic function to show/hide validation error
        function showValidationError(input, errorElement, message) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
            input.classList.add('border-red-500');
        }

        function hideValidationError(input, errorElement) {
            errorElement.classList.add('hidden');
            input.classList.remove('border-red-500');
        }

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
        });

        // Validate password on input (only if not empty)
        editPasswordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (!validatePassword(this.value)) {
                    showValidationError(this, editPasswordError, 'Password must be at least 8 characters long.');
                } else {
                    hideValidationError(this, editPasswordError);
                }
            } else {
                hideValidationError(this, editPasswordError);
            }
        });

        // Validate contact numbers on blur
        function addEditContactValidation(input, errorElement) {
            input.addEventListener('blur', function() {
                if (this.value.trim()) {
                    if (!validatePhilippineMobile(this.value)) {
                        showValidationError(this, errorElement, 'Invalid format. Use 09XXXXXXXXX (11 digits starting with 09).');
                    } else {
                        hideValidationError(this, errorElement);
                    }
                } else {
                    hideValidationError(this, errorElement);
                }
            });
        }
        // Add validation to all contact number fields
        addEditContactValidation(editContactNoInput, editContactNoError);
        addEditContactValidation(editMotherContactNoInput, editMotherContactNoError);
        addEditContactValidation(editFatherContactNoInput, editFatherContactNoError);
        addEditContactValidation(editGuardianContactNoInput, editGuardianContactNoError);        // Form submission validation
        editUserForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = editEmailInput.value;
            const password = editPasswordInput.value;
            let canSubmit = true;

            // Reset all errors
            editEmailError.classList.add('hidden');
            editPasswordError.classList.add('hidden');
            editContactNoError.classList.add('hidden');
            editMotherContactNoError.classList.add('hidden');
            editFatherContactNoError.classList.add('hidden');
            editGuardianContactNoError.classList.add('hidden');

            // Only validate if email has changed
            if (email !== originalEmail) {
                const userId = this.action.split('/').pop();
                const result = await checkEmailExists(email, userId);

                if (result.exists) {
                    showValidationError(editEmailInput, editEmailError, 'This email already exists in the database.');
                    canSubmit = false;
                } else {
                    hideValidationError(editEmailInput, editEmailError);
                }
            }

            // Validate password if provided
            if (password.trim() && !validatePassword(password)) {
                showValidationError(editPasswordInput, editPasswordError, 'Password must be at least 8 characters long.');
                canSubmit = false;
            }

            // Validate contact numbers (only if they have values)
            const editContactFields = [
                {input: editContactNoInput, error: editContactNoError},
                {input: editMotherContactNoInput, error: editMotherContactNoError},
                {input: editFatherContactNoInput, error: editFatherContactNoError},
                {input: editGuardianContactNoInput, error: editGuardianContactNoError}
            ];

            editContactFields.forEach(field => {
                if (field.input.value.trim() && !validatePhilippineMobile(field.input.value)) {
                    showValidationError(field.input, field.error, 'Invalid format. Use 09XXXXXXXXX (11 digits starting with 09).');
                    canSubmit = false;
                }
            });

            // If validation passes, check if we need password verification
            if (canSubmit) {
                const emailChanged = email !== originalEmail;
                const passwordChanged = password.trim() !== '';

                // If either email or password is being changed, show password verification modal
                if (emailChanged || passwordChanged) {
                    // Store form data in hidden field
                    const formData = new FormData(this);
                    document.getElementById('editVerifiedUserId').value = this.action.split('/').pop();
                    document.getElementById('editOriginalFormData').value = JSON.stringify(Object.fromEntries(formData));

                    // Show password verification modal
                    document.getElementById('editPasswordVerificationModal').classList.remove('hidden');
                } else {
                    // If no sensitive fields are being changed, submit form normally
                    this.submit();
                }
            }
        });

        // Handle role selection for edit modal
        const editRoleSelect = document.getElementById('editRole');
        const editClassSectionDiv = document.getElementById('editClassSection');
        const editClassSelect = document.getElementById('editClassId');
        const editSectionSelect = document.getElementById('editSectionId');

        editRoleSelect.addEventListener('change', function() {
            if (this.value === 'STUDENT') {
                editClassSectionDiv.classList.remove('hidden');
                editClassSelect.setAttribute('required', '');
                editSectionSelect.setAttribute('required', '');
            } else {
                editClassSectionDiv.classList.add('hidden');
                editClassSelect.removeAttribute('required');
                editSectionSelect.removeAttribute('required');
                editClassSelect.value = '';
                editSectionSelect.value = '';
            }
        });

        // Handle class selection to populate sections for edit modal
        editClassSelect.addEventListener('change', function() {
            const classId = this.value;
            editSectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (classId) {
                // Find the selected class and populate its sections
                const classes = @json($classes);
                const selectedClass = classes.find(cls => cls.class_id == classId);

                if (selectedClass && selectedClass.sections) {
                    selectedClass.sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.section_id;
                        option.textContent = section.section_name;
                        editSectionSelect.appendChild(option);
                    });
                }
            }
        });
    });
</script>
