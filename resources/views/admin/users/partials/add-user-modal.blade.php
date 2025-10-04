<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Add New User</h3>
        <form id="addUserForm" action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <!-- General form error messages will appear here -->
            <div id="formErrors" class="mb-4 hidden">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span id="errorMessageGeneral" class="block sm:inline"></span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h4>

                    <div>
                        <label class="block text-sm font-medium mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="userName" required
                            class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="userEmail" required
                            class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="roleSelect" required
                            class="w-full px-3 py-2 border rounded-lg @error('role') border-red-500 @enderror">
                            <option value="">Select Role</option>
                            <option value="TEACHER">Teacher</option>
                            <option value="STUDENT">Student</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Sex</label>
                        <select name="sex" id="sexSelect"
                            class="w-full px-3 py-2 border rounded-lg @error('sex') border-red-500 @enderror">
                            <option value="">Select Sex</option>
                            <option value="MALE">Male</option>
                            <option value="FEMALE">Female</option>
                        </select>
                        @error('sex')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Contact No.</label>
                        <input type="text" name="contact_no" id="contactNo" placeholder="09XXXXXXXXX"
                            class="w-full px-3 py-2 border rounded-lg @error('contact_no') border-red-500 @enderror">
                        @error('contact_no')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="contactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        <p class="text-gray-500 text-xs mt-1">Format: 09XXXXXXXXX (11 digits starting with 09)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-3 py-2 border rounded-lg @error('address') border-red-500 @enderror"></textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Parent Information & School Details -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Parent Information</h4>

                    <div>
                        <label class="block text-sm font-medium mb-1">Mother's Name</label>
                        <input type="text" name="mother_name" id="motherName"
                            class="w-full px-3 py-2 border rounded-lg @error('mother_name') border-red-500 @enderror">
                        @error('mother_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Mother's Contact No.</label>
                        <input type="text" name="mother_contact_no" id="motherContactNo" placeholder="09XXXXXXXXX"
                            class="w-full px-3 py-2 border rounded-lg @error('mother_contact_no') border-red-500 @enderror">
                        @error('mother_contact_no')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="motherContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                    </div>

                    <!-- Father's Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Father's Information</h4>

                        <div>
                            <label class="block text-sm font-medium mb-1">Father's Name</label>
                            <input type="text" name="father_name" id="fatherName"
                                class="w-full px-3 py-2 border rounded-lg @error('father_name') border-red-500 @enderror">
                            @error('father_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Father's Contact Number</label>
                            <input type="tel" name="father_contact_no" id="fatherContactNo"
                                class="w-full px-3 py-2 border rounded-lg @error('father_contact_no') border-red-500 @enderror"
                                placeholder="09XXXXXXXXX">
                            @error('father_contact_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="fatherContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                    <!-- Guardian's Information -->
                    <div class="space-y-4">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2">Guardian's Information</h4>

                        <div>
                            <label class="block text-sm font-medium mb-1">Guardian's Name</label>
                            <input type="text" name="guardian_name" id="guardianName"
                                class="w-full px-3 py-2 border rounded-lg @error('guardian_name') border-red-500 @enderror">
                            @error('guardian_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Guardian's Contact Number</label>
                            <input type="tel" name="guardian_contact_no" id="guardianContactNo"
                                class="w-full px-3 py-2 border rounded-lg @error('guardian_contact_no') border-red-500 @enderror"
                                placeholder="09XXXXXXXXX">
                            @error('guardian_contact_no')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p id="guardianContactNoError" class="text-red-500 text-xs mt-1 hidden"></p>
                        </div>
                    </div>

                    <div id="classSection" class="hidden">
                        <h4 class="text-lg font-semibold text-gray-700 border-b pb-2 mt-6">School Information</h4>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Class <span
                                        class="text-red-500">*</span></label>
                                <select name="class_id" id="class_id"
                                    class="w-full px-3 py-2 border rounded-lg @error('class_id') border-red-500 @enderror">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}">Grade {{ $class->grade_level }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Section <span
                                        class="text-red-500">*</span></label>
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

                    <div class="mt-6">
                        <label class="block text-sm font-medium mb-1">Password <span
                                class="text-red-500">*</span></label>
                        <input type="password" name="password" id="addPasswordInput" required
                            class="w-full px-3 py-2 border rounded-lg @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p id="addPasswordError" class="text-red-500 text-xs mt-1 hidden"></p>
                        <p class="text-gray-500 text-xs mt-1">Minimum 8 characters required</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleUserModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" id="addUserBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addUserForm');
        const nameInput = document.getElementById('userName');
        const emailInput = document.getElementById('userEmail');
        const passwordInput = document.getElementById('addPasswordInput');
        const contactNoInput = document.getElementById('contactNo');
        const motherContactNoInput = document.getElementById('motherContactNo');
        const fatherContactNoInput = document.getElementById('fatherContactNo');
        const guardianContactNoInput = document.getElementById('guardianContactNo');
        
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('addPasswordError');
        const contactNoError = document.getElementById('contactNoError');
        const motherContactNoError = document.getElementById('motherContactNoError');
        const fatherContactNoError = document.getElementById('fatherContactNoError');
        const guardianContactNoError = document.getElementById('guardianContactNoError');
        
        const formErrors = document.getElementById('formErrors');
        const errorMessageGeneral = document.getElementById('errorMessageGeneral');

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

        // Function to check if user exists
        async function checkUserExists(fieldName, value) {
            try {
                const response = await fetch(
                    `/admin/users/check-exists?field=${fieldName}&value=${encodeURIComponent(value)}`, {
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
                console.error('Error checking user existence:', error);
                return {
                    exists: false
                };
            }
        }


        // Validate email on blur
        emailInput.addEventListener('blur', async function() {
            if (this.value.trim()) {
                const result = await checkUserExists('email', this.value);
                if (result.exists) {
                    emailError.textContent = 'This email already exists in the database.';
                    emailError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    emailError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            }
        });

        // Validate password on input
        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (!validatePassword(this.value)) {
                    showValidationError(this, passwordError, 'Password must be at least 8 characters long.');
                } else {
                    hideValidationError(this, passwordError);
                }
            } else {
                hideValidationError(this, passwordError);
            }
        });

        // Validate contact numbers on blur
        function addContactValidation(input, errorElement) {
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
        addContactValidation(contactNoInput, contactNoError);
        addContactValidation(motherContactNoInput, motherContactNoError);
        addContactValidation(fatherContactNoInput, fatherContactNoError);
        addContactValidation(guardianContactNoInput, guardianContactNoError);

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Reset errors
            emailError.classList.add('hidden');
            passwordError.classList.add('hidden');
            contactNoError.classList.add('hidden');
            motherContactNoError.classList.add('hidden');
            fatherContactNoError.classList.add('hidden');
            guardianContactNoError.classList.add('hidden');
            formErrors.classList.add('hidden');

            let hasErrors = false;

            // Check email
            const emailResult = await checkUserExists('email', emailInput.value);
            if (emailResult.exists) {
                emailError.textContent = 'This email already exists in the database.';
                emailError.classList.remove('hidden');
                emailInput.classList.add('border-red-500');
                hasErrors = true;
            }

            // Validate password
            if (!validatePassword(passwordInput.value)) {
                showValidationError(passwordInput, passwordError, 'Password must be at least 8 characters long.');
                hasErrors = true;
            }

            // Validate contact numbers (only if they have values)
            const contactFields = [
                {input: contactNoInput, error: contactNoError},
                {input: motherContactNoInput, error: motherContactNoError},
                {input: fatherContactNoInput, error: fatherContactNoError},
                {input: guardianContactNoInput, error: guardianContactNoError}
            ];

            contactFields.forEach(field => {
                if (field.input.value.trim() && !validatePhilippineMobile(field.input.value)) {
                    showValidationError(field.input, field.error, 'Invalid format. Use 09XXXXXXXXX (11 digits starting with 09).');
                    hasErrors = true;
                }
            });

            if (hasErrors) {
                formErrors.classList.remove('hidden');
                errorMessageGeneral.textContent = 'Please fix the errors before submitting.';
                return;
            }

            // Submit the form if no errors
            this.submit();
        });

        // Handle role selection
        const roleSelect = document.getElementById('roleSelect');
        const classSectionDiv = document.getElementById('classSection');
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'STUDENT') {
                classSectionDiv.classList.remove('hidden');
                classSelect.setAttribute('required', '');
                sectionSelect.setAttribute('required', '');
            } else {
                classSectionDiv.classList.add('hidden');
                classSelect.removeAttribute('required');
                sectionSelect.removeAttribute('required');
                classSelect.value = '';
                sectionSelect.value = '';
            }
        });

        // Handle class selection to populate sections
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (classId) {
                // Find the selected class and populate its sections
                const classes = @json($classes);
                const selectedClass = classes.find(cls => cls.class_id == classId);

                if (selectedClass && selectedClass.sections) {
                    selectedClass.sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.section_id;
                        option.textContent = section.section_name;
                        sectionSelect.appendChild(option);
                    });
                }
            }
        });
    });
</script>
