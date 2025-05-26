<div id="addClubModal"
    class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Add New Club</h3>
        <form id="addClubForm" action="{{ route('admin.clubs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="club_name" class="block text-sm font-medium mb-1">Club Name</label>
                    <input type="text" id="club_name" name="club_name" required
                        class="w-full px-3 py-2 border rounded-lg @error('club_name') border-red-500 @enderror">
                    @error('club_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p id="club_name_error" class="text-red-500 text-xs mt-1 hidden"></p>
                </div>

                <div>
                    <label for="club_adviser" class="block text-sm font-medium mb-1">Club Adviser</label>
                    <select id="club_adviser" name="club_adviser" required
                        class="w-full px-3 py-2 border rounded-lg @error('club_adviser') border-red-500 @enderror">
                        <option value="">Select Adviser</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->user_id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('club_adviser')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="club_description" class="block text-sm font-medium mb-1">Description (Optional)</label>
                    <textarea id="club_description" name="club_description"
                        class="w-full px-3 py-2 border rounded-lg @error('club_description') border-red-500 @enderror"></textarea>
                    @error('club_description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="club_logo" class="block text-sm font-medium mb-1">Club Logo</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-full">
                            <label for="club_logo"
                                class="cursor-pointer flex items-center justify-center w-full px-3 py-2 border border-dashed rounded-lg hover:bg-gray-50">
                                <span id="logoFileName" class="text-gray-500">Choose file</span>
                                <input type="file" id="club_logo" name="club_logo" accept="image/*" class="hidden"
                                    onchange="previewLogo(this)">
                            </label>
                            @error('club_logo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <!-- Add custom error for client-side validation -->
                            <div id="club_logo_error" class="text-red-500 text-xs mt-1 hidden">Please select a logo
                                file.</div>
                        </div>                        <div id="logoPreviewContainer"
                            class="hidden h-16 w-16 rounded-full border overflow-hidden bg-gray-100">
                            <img id="logoPreview" src="#" alt="Logo preview" class="h-full w-full object-cover">
                        </div>
                    </div>
                </div>

                <div>
                    <label for="club_banner" class="block text-sm font-medium mb-1">Club Banner</label>
                    <div>
                        <label for="club_banner"
                            class="cursor-pointer flex items-center justify-center w-full px-3 py-2 border border-dashed rounded-lg hover:bg-gray-50">
                            <span id="bannerFileName" class="text-gray-500">Choose file</span>
                            <input type="file" id="club_banner" name="club_banner" accept="image/*" class="hidden"
                                onchange="previewBanner(this)">
                        </label>
                        @error('club_banner')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <!-- Add custom error for client-side validation -->
                        <div id="club_banner_error" class="text-red-500 text-xs mt-1 hidden">Please select a banner
                            file.</div>
                    </div>
                    <div id="bannerPreviewContainer"
                        class="hidden mt-2 h-32 rounded-lg border overflow-hidden bg-gray-100">
                        <img id="bannerPreview" src="#" alt="Banner preview" class="h-full w-full object-cover">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="closeAddClubModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Club
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Form submission handler with full validation
    document.addEventListener('DOMContentLoaded', function() {
        const clubNameInput = document.getElementById('club_name');
        const clubNameError = document.getElementById('club_name_error');
        const addClubForm = document.getElementById('addClubForm');
        
        // Function to check if club name exists
        async function checkClubNameExists(name) {
            try {
                const response = await fetch(
                    `/admin/clubs/check-name-exists?value=${encodeURIComponent(name)}`, {
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
                console.error('Error checking club name existence:', error);
                return {
                    exists: false
                };
            }
        }
        
        // Validate club name on blur
        clubNameInput.addEventListener('blur', async function() {
            if (this.value.trim()) {
                const result = await checkClubNameExists(this.value);

                if (result.exists) {
                    clubNameError.textContent = 'This club name already exists.';
                    clubNameError.classList.remove('hidden');
                    this.classList.add('border-red-500');
                } else {
                    clubNameError.classList.add('hidden');
                    this.classList.remove('border-red-500');
                }
            } else {
                clubNameError.classList.add('hidden');
                this.classList.remove('border-red-500');
            }
        });

        // Form submission handler with full validation
        addClubForm.addEventListener('submit', async function(e) {
            // Prevent default form submission first to allow for validation
            e.preventDefault();
            
            let isValid = true;
            const logoInput = document.getElementById('club_logo');
            const bannerInput = document.getElementById('club_banner');
            const logoError = document.getElementById('club_logo_error');
            const bannerError = document.getElementById('club_banner_error');
            const submitButton = this.querySelector('button[type="submit"]');
            const clubName = clubNameInput.value.trim();

            // Reset errors
            logoError.classList.add('hidden');
            bannerError.classList.add('hidden');
            clubNameError.classList.add('hidden');
            
            // Validate club name uniqueness
            if (clubName) {
                const result = await checkClubNameExists(clubName);
                if (result.exists) {
                    isValid = false;
                    clubNameError.textContent = 'This club name already exists.';
                    clubNameError.classList.remove('hidden');
                    clubNameInput.classList.add('border-red-500');
                } else {
                    clubNameError.classList.add('hidden');
                    clubNameInput.classList.remove('border-red-500');
                }
            }

            // Validate logo
            const logoValidation = validateFile(logoInput, 5); // 5MB max
            if (logoValidation !== true) {
                isValid = false;
                logoError.textContent = typeof logoValidation === 'string' ? logoValidation :
                    'Please select a logo file.';
                logoError.classList.remove('hidden');
            }

            // Validate banner
            const bannerValidation = validateFile(bannerInput, 5); // 5MB max
            if (bannerValidation !== true) {
                isValid = false;
                bannerError.textContent = typeof bannerValidation === 'string' ? bannerValidation :
                    'Please select a banner file.';
                bannerError.classList.remove('hidden');
            }

            if (!isValid) {
                // Keep modal open
                document.getElementById('addClubModal').classList.remove('hidden');
                // Scroll to first error
                const firstError = document.querySelector('.text-red-500:not(.hidden)');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            } else {
                // Add loading state
                submitButton.innerHTML = 'Adding...';
                submitButton.disabled = true;
                // Submit the form
                this.submit();
            }
        });
    });

    // File validation helper
    function validateFile(input, maxSizeMB) {
        if (!input.files || input.files.length === 0) return 'Please select a file.';

        const file = input.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        const maxSizeBytes = maxSizeMB * 1024 * 1024;

        if (!validTypes.includes(file.type)) {
            return 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP.';
        }

        if (file.size > maxSizeBytes) {
            return `File too large (max ${maxSizeMB}MB).`;
        }

        return true;
    }

    // Preview functions with error handling
    function previewLogo(input) {
        const container = document.getElementById('logoPreviewContainer');
        const preview = document.getElementById('logoPreview');
        const fileName = document.getElementById('logoFileName');
        const error = document.getElementById('club_logo_error');

        resetPreview(input, container, preview, fileName, error);

        if (input.files && input.files[0]) {
            const validation = validateFile(input, 2);
            if (validation !== true) {
                error.textContent = validation;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }

            previewFile(input.files[0], preview, container, fileName);
        }
    }

    function previewBanner(input) {
        const container = document.getElementById('bannerPreviewContainer');
        const preview = document.getElementById('bannerPreview');
        const fileName = document.getElementById('bannerFileName');
        const error = document.getElementById('club_banner_error');

        resetPreview(input, container, preview, fileName, error);

        if (input.files && input.files[0]) {
            const validation = validateFile(input, 5);
            if (validation !== true) {
                error.textContent = validation;
                error.classList.remove('hidden');
                input.value = '';
                return;
            }

            previewFile(input.files[0], preview, container, fileName);
        }
    }

    // Generic preview handler
    function previewFile(file, previewElement, container, fileNameElement) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewElement.src = e.target.result;
            container.classList.remove('hidden');
            fileNameElement.textContent = file.name;
        };

        reader.onerror = function(error) {
            console.error('Error reading file:', error);
            fileNameElement.textContent = 'Error loading file';
        };

        reader.readAsDataURL(file);
    }

    // Reset preview and errors
    function resetPreview(input, container, preview, fileName, error) {
        if (!input.files || input.files.length === 0) {
            container.classList.add('hidden');
            preview.src = '#';
            fileName.textContent = 'Choose file';
            error.classList.add('hidden');
        }
    }

    // Modal close handler
    function closeAddClubModal() {
        document.getElementById('addClubModal').classList.add('hidden');
        const form = document.getElementById('addClubForm');
        form.reset();

        // Reset all previews and errors
        ['logo', 'banner'].forEach(type => {
            document.getElementById(`${type}PreviewContainer`).classList.add('hidden');
            document.getElementById(`${type}Preview`).src = '#';
            document.getElementById(`${type}FileName`).textContent = 'Choose file';
            document.getElementById(`club_${type}_error`).classList.add('hidden');
        });

        // Reset submit button
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.innerHTML = 'Add Club';
        submitButton.disabled = false;
    }
</script>
