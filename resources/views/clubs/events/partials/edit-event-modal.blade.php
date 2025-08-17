<!-- Edit Event Modal -->
<div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showEditModal = false"></div>

    <!-- Modal Container -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-4xl bg-white rounded-lg shadow-xl transform transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-100 translate-y-4 sm:translate-y-0 sm:scale-95">

            <!-- Modal Header -->
            <div class="bg-green-600 px-6 py-4 rounded-t-lg">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Event
                    </h3>
                    <button @click="showEditModal = false" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[calc(100vh-12rem)] overflow-y-auto">
                <form
                    x-bind:action="editingEvent.id ? '{{ route('clubs.events.index', $club) }}/' + editingEvent.id : '#'"
                    method="POST" enctype="multipart/form-data" class="space-y-6" x-ref="editForm">
                    @csrf
                    @method('PUT')

                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Left Column - Event Details -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Event
                                Information</h4>

                            <!-- Event Title -->
                            <div>
                                <label for="edit_event_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_name" id="edit_event_name" required
                                    :value="editingEvent.name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter event title">
                            </div>

                            <!-- Event Description -->
                            <div>
                                <label for="edit_event_description"
                                    class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Description <span class="text-red-500">*</span>
                                </label>
                                <textarea name="event_description" id="edit_event_description" rows="4" required x-text="editingEvent.description"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                    placeholder="Describe your event in detail"></textarea>
                            </div>

                            <!-- Event Visibility -->
                            <div>
                                <label for="edit_event_visibility" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Visibility <span class="text-red-500">*</span>
                                </label>
                                <select name="event_visibility" id="edit_event_visibility" required
                                    :value="editingEvent.visibility"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
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
                                <label for="edit_event_date" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="event_date" id="edit_event_date" required
                                    :value="editingEvent.date"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                            <!-- Event Time -->
                            <div>
                                <label for="edit_event_time" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Time <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_time" id="edit_event_time" required
                                    :value="editingEvent.time"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="e.g. 9:00 AM - 5:00 PM">
                            </div>

                            <!-- Event Location -->
                            <div>
                                <label for="edit_event_location" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="event_location" id="edit_event_location" required
                                    :value="editingEvent.location"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Enter event location">
                            </div>

                            <!-- Current Documents -->
                            <div id="current-documents-section">
                                <h4 class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Documents
                                </h4>
                                <div id="current-documents-list" class="space-y-2 mb-3">
                                    <!-- Current documents will be loaded here -->
                                </div>
                            </div>

                            <!-- File Upload Section -->
                            <div>
                                <label for="edit_event_documents" class="block text-sm font-medium text-gray-700 mb-2">
                                    Add New Documents
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-blue-400 transition-colors"
                                    id="editDropZone" ondrop="dropHandlerEdit(event);"
                                    ondragover="dragOverHandlerEdit(event);"
                                    ondragleave="dragLeaveHandlerEdit(event);">
                                    <input type="file" name="event_documents[]" id="edit_event_documents" multiple
                                        class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.txt"
                                        onchange="handleFileSelectEdit(event)">
                                    <div class="text-gray-600">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor"
                                            fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="text-sm">
                                            <button type="button"
                                                class="text-blue-600 hover:text-blue-500 font-medium"
                                                onclick="document.getElementById('edit_event_documents').click()">
                                                Click to upload
                                            </button>
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG, GIF, TXT up to
                                            10MB each</p>
                                    </div>
                                </div>
                                <div id="editFileList" class="mt-3 space-y-2"></div>
                                <div id="editUploadError" class="mt-2 text-red-600 text-sm hidden"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200 mt-6">
                        <button type="button" @click="showEditModal = false"
                            class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Update Event
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedEditFiles = [];

    function validateFileEdit(file) {
        const allowedTypes = ['application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'text/plain'
        ];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: `${file.name}: Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG, GIF, TXT`
            };
        }

        if (file.size > maxSize) {
            return {
                valid: false,
                message: `${file.name}: File too large. Maximum size is 10MB`
            };
        }

        return {
            valid: true
        };
    }

    function handleFileSelectEdit(event) {
        const files = Array.from(event.target.files);
        processEditFiles(files);
    }

    function processEditFiles(files) {
        const errorDiv = document.getElementById('editUploadError');
        const fileListDiv = document.getElementById('editFileList');

        errorDiv.classList.add('hidden');
        errorDiv.innerHTML = '';

        const errors = [];
        const validFiles = [];

        files.forEach(file => {
            const validation = validateFileEdit(file);
            if (validation.valid) {
                validFiles.push(file);
            } else {
                errors.push(validation.message);
            }
        });

        if (errors.length > 0) {
            errorDiv.innerHTML = errors.join('<br>');
            errorDiv.classList.remove('hidden');
        }

        if (validFiles.length > 0) {
            selectedEditFiles = [...selectedEditFiles, ...validFiles];
            updateEditFileList();
            updateEditFileInput();
        }
    }

    function updateEditFileList() {
        const fileListDiv = document.getElementById('editFileList');
        fileListDiv.innerHTML = '';

        selectedEditFiles.forEach((file, index) => {
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
        <button type="button" onclick="removeEditFile(${index})" class="text-red-500 hover:text-red-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
            fileListDiv.appendChild(fileItem);
        });
    }

    function updateEditFileInput() {
        const input = document.getElementById('edit_event_documents');
        const dt = new DataTransfer();

        selectedEditFiles.forEach(file => {
            dt.items.add(file);
        });

        input.files = dt.files;
    }

    function removeEditFile(index) {
        selectedEditFiles.splice(index, 1);
        updateEditFileList();
        updateEditFileInput();
    }

    function dropHandlerEdit(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('editDropZone');
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = Array.from(ev.dataTransfer.files);
        processEditFiles(files);
    }

    function dragOverHandlerEdit(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('editDropZone');
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    }

    function dragLeaveHandlerEdit(ev) {
        ev.preventDefault();
        const dropZone = document.getElementById('editDropZone');
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    }

    function closeModal(modalId) {
        // This function is deprecated - we now use Alpine.js methods
        console.log('closeModal function called - using Alpine.js instead');
    }
</script>
