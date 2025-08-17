<!-- Create Event Modal -->
<div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showCreateModal = false"></div>

    <!-- Modal Container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl transform transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-100 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Modal Header -->
            <div class="bg-blue-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create New Event
                    </h3>
                    <button @click="showCreateModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[calc(100vh-12rem)] overflow-y-auto">
                <form action="{{ route('clubs.events.store', $club) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6" x-ref="createForm">
                    @csrf

                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Left Column - Event Details -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Event
                                Information</h4>

                            <!-- Event Title -->
                            <div>
                                <label for="event_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_name" id="event_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter event title">
                            </div>

                            <!-- Event Description -->
                            <div>
                                <label for="event_description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="event_description" id="event_description" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                    placeholder="Describe your event in detail"></textarea>
                            </div>

                            <!-- Event Visibility -->
                            <div>
                                <label for="event_visibility" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Visibility <span class="text-red-500">*</span>
                                </label>
                                <select name="event_visibility" id="event_visibility" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select visibility</option>
                                    <option value="PUBLIC">Public (Visible to all users)</option>
                                    <option value="CLUB_ONLY">Club Only (Visible to club members only)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Right Column - Date, Location & Files -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Event Details
                            </h4>

                            <!-- Event Date -->
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="event_date" id="event_date" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    min="{{ date('Y-m-d') }}">
                            </div>

                            <!-- Event Time -->
                            <div>
                                <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Time <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_time" id="event_time" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="e.g. 9:00 AM - 5:00 PM">
                            </div>

                            <!-- Event Location -->
                            <div>
                                <label for="event_location" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_location" id="event_location" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter event location">
                            </div>

                            <!-- File Upload Section -->
                            <div>
                                <label for="event_documents" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Documents (Max 5 files)
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-blue-400 transition-colors"
                                    id="dropZone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);"
                                    ondragleave="dragLeaveHandler(event);">
                                    <input type="file" name="supporting_documents[]" id="event_documents"
                                        class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt" multiple
                                        onchange="handleFileSelect(event)">
                                    <div class="text-gray-600">
                                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm">
                                            <button type="button"
                                                class="text-blue-600 hover:text-blue-500 font-medium"
                                                onclick="document.getElementById('event_documents').click()">
                                                Click to upload files
                                            </button>
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG, GIF, TXT up to
                                            10MB each (Maximum 5 files)</p>
                                    </div>
                                </div>
                                <div id="fileList" class="mt-3 space-y-2"></div>
                                <div id="uploadError" class="mt-2 text-red-600 text-sm hidden"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" @click="showCreateModal = false"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Create Event
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedFiles = [];
    const MAX_FILES = 5;
    const MAX_SIZE = 10 * 1024 * 1024; // 10MB

    function validateCreateFile(file) {
        const allowedTypes = ['application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'text/plain',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip', 'application/x-rar-compressed'
        ];

        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: `${file.name}: Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG, GIF, TXT, PPT, PPTX, XLS, XLSX, ZIP, RAR`
            };
        }

        if (file.size > MAX_SIZE) {
            return {
                valid: false,
                message: `${file.name}: File too large. Maximum size is 10MB`
            };
        }

        return {
            valid: true
        };
    }

    function handleFileSelect(event) {
        const files = Array.from(event.target.files);
        processFiles(files);
    }

    function processFiles(files) {
        const errorDiv = document.getElementById('uploadError');
        const fileListDiv = document.getElementById('fileList');

        // Clear previous errors
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        // Check if adding these files would exceed the limit
        if (selectedFiles.length + files.length > MAX_FILES) {
            errorDiv.innerHTML =
                `Too many files selected. Maximum ${MAX_FILES} files allowed. You currently have ${selectedFiles.length} files selected.`;
            errorDiv.classList.remove('hidden');
            return;
        }

        const errors = [];
        const validFiles = [];

        files.forEach(file => {
            // Check if file already exists
            const isDuplicate = selectedFiles.some(existingFile =>
                existingFile.name === file.name && existingFile.size === file.size
            );

            if (isDuplicate) {
                errors.push(`${file.name}: File already selected`);
                return;
            }

            const validation = validateCreateFile(file);
            if (!validation.valid) {
                errors.push(validation.message);
            } else {
                validFiles.push(file);
            }
        });

        if (errors.length > 0) {
            errorDiv.innerHTML = errors.join('<br>');
            errorDiv.classList.remove('hidden');
        }

        // Add valid files to selection
        selectedFiles.push(...validFiles);
        updateFileDisplay();
        updateFileInput();
    }

    function updateFileDisplay() {
        const fileListDiv = document.getElementById('fileList');
        fileListDiv.innerHTML = '';

        if (selectedFiles.length === 0) {
            return;
        }

        // Show file count
        const countDiv = document.createElement('div');
        countDiv.className = 'text-sm text-gray-600 mb-2';
        countDiv.innerHTML = `${selectedFiles.length} of ${MAX_FILES} files selected`;
        fileListDiv.appendChild(countDiv);

        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm text-gray-700">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / (1024 * 1024)).toFixed(2)} MB)</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            fileListDiv.appendChild(fileItem);
        });
    }

    function updateFileInput() {
        const input = document.getElementById('event_documents');
        const dt = new DataTransfer();

        selectedFiles.forEach(file => {
            dt.items.add(file);
        });

        input.files = dt.files;
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileDisplay();
        updateFileInput();
    }

    function clearFileSelection() {
        selectedFiles = [];
        updateFileDisplay();
        updateFileInput();
        clearErrorDisplay();
    }

    function clearErrorDisplay() {
        const errorDiv = document.getElementById('uploadError');
        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';
    }

    function dropHandler(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('dropZone');
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = Array.from(ev.dataTransfer.files);
        processFiles(files);
    }

    function dragOverHandler(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('dropZone');
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    function dragLeaveHandler(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('dropZone');
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }
</script>
