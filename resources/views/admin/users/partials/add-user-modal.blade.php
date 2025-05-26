<div id="addUserModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
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
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" id="userName" required
                        class="w-full px-3 py-2 border rounded-lg @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p id="nameError" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" id="userEmail" required
                        class="w-full px-3 py-2 border rounded-lg @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p id="emailError" class="text-red-500 text-xs mt-1 hidden"></p>
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
            </div>            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleUserModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" id="addUserBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
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
    const nameError = document.getElementById('nameError');
    const emailError = document.getElementById('emailError');
    const formErrors = document.getElementById('formErrors');
    const errorMessageGeneral = document.getElementById('errorMessageGeneral');
    
    // Function to check if user exists
    async function checkUserExists(fieldName, value) {
        try {
            const response = await fetch(`/admin/users/check-exists?field=${fieldName}&value=${encodeURIComponent(value)}`, {
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
            return { exists: false };
        }
    }
    
    // Validate name on blur
    nameInput.addEventListener('blur', async function() {
        if (this.value.trim()) {
            const result = await checkUserExists('name', this.value);
            if (result.exists) {
                nameError.textContent = 'This name already exists in the database.';
                nameError.classList.remove('hidden');
                this.classList.add('border-red-500');
            } else {
                nameError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        }
    });
    
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
    
    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset errors
        nameError.classList.add('hidden');
        emailError.classList.add('hidden');
        formErrors.classList.add('hidden');
        
        let hasErrors = false;
        
        // Check name
        const nameResult = await checkUserExists('name', nameInput.value);
        if (nameResult.exists) {
            nameError.textContent = 'This name already exists in the database.';
            nameError.classList.remove('hidden');
            nameInput.classList.add('border-red-500');
            hasErrors = true;
        }
        
        // Check email
        const emailResult = await checkUserExists('email', emailInput.value);
        if (emailResult.exists) {
            emailError.textContent = 'This email already exists in the database.';
            emailError.classList.remove('hidden');
            emailInput.classList.add('border-red-500');
            hasErrors = true;
        }
        
        if (hasErrors) {
            formErrors.classList.remove('hidden');
            errorMessageGeneral.textContent = 'Please fix the errors before submitting.';
            return;
        }
        
        // Submit the form if no errors
        this.submit();
    });
});
</script>
