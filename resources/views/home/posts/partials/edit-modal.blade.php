<div x-cloak>
    <!-- Backdrop -->
    <div x-show="showEditModal" class="fixed inset-0 bg-black/20 transition-opacity z-[9999]"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200">
    </div>

    <!-- Modal Content -->
    <div x-show="showEditModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        @click.away="showEditModal = false">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl transform transition-all max-h-[90vh] flex flex-col"
            @click.stop>
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold">Edit Post</h2>
            </div>

            <!-- Scrollable Content Area -->
            <div class="p-6 overflow-y-auto flex-grow">
                <form id="edit-post-form"
                    :action="`{{ route('clubs.posts.update', ['club' => 'CLUB_ID', 'post' => 'POST_ID']) }}`
                    .replace('CLUB_ID', currentClubId)
                        .replace('POST_ID', currentPostId)"
                    method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label for="post_caption" class="block text-sm font-medium text-gray-700">Caption</label>
                        <textarea name="post_caption" id="post_caption" x-model="editPostCaption" rows="8"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        @error('post_caption')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                        <select x-model="editPostVisibility" name="visibility" id="visibility"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="CLUB_ONLY">Club Only</option>
                            <option value="PUBLIC">Public</option>
                        </select>
                    </div>

                    <!-- Images Section -->
                    <div class="mb-4">
                        <h3 class="block text-sm font-medium text-gray-700 mb-2">Current Images</h3>
                        <p class="text-sm text-gray-500 mb-2">Click the checkbox to remove an image.</p>

                        <template x-if="currentPostImages.length > 0">
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-48 overflow-y-auto p-2 border rounded-md">
                                <template x-for="(image, imageIndex) in currentPostImages"
                                    :key="`image-${image.id || imageIndex}`">
                                    <div class="relative group">
                                        <img :src="image.url" class="w-full h-20 object-cover rounded">
                                        <div class="absolute bottom-0 right-0 bg-white/80 rounded-tl p-1">
                                            <input type="checkbox" name="delete_images[]" :value="image.id"
                                                class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="mb-4">
                        <label for="images" class="block text-sm font-medium text-gray-700">Add More Images</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*"
                            class="mt-1 block w-full">
                        <p class="text-sm text-gray-500 mt-1">You can upload multiple images
                            (max 5MB each).</p>
                        @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Attachments Section -->
                    <div class="mb-4">
                        <h3 class="block text-sm font-medium text-gray-700 mb-2">File Attachments</h3>

                        <!-- Current Multiple Documents -->
                        <template x-if="currentPostDocuments && currentPostDocuments.length > 0">
                            <div class="mb-3">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current Documents (<span
                                        x-text="currentPostDocuments.length"></span>)</h4>
                                <div class="space-y-2">
                                    <template x-for="(document, docIndex) in currentPostDocuments"
                                        :key="`doc-${document.id || docIndex}`">
                                        <div
                                            class="flex items-center justify-between bg-gray-50 rounded-md p-3 border border-gray-200">
                                            <div class="flex items-center min-w-0 flex-1">
                                                <div class="bg-blue-100 rounded p-1 mr-3">
                                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 truncate"
                                                        x-text="document.original_name"></p>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="formatFileSize(document.file_size)"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <a :href="document.url"
                                                    class="text-blue-600 hover:text-blue-700 text-sm mr-3"
                                                    target="_blank">Download</a>
                                                <label class="flex items-center">
                                                    <input type="checkbox"
                                                        :name="'remove_documents[' + document.id + ']'" value="1"
                                                        class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                                    <span class="ml-1 text-sm text-red-600">Remove</span>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Current Single File Attachment (Legacy) -->
                        <template
                            x-if="currentPostFileAttachment && (!currentPostDocuments || currentPostDocuments.length === 0)">
                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-gray-900"
                                                x-text="currentPostFileAttachment.original_name"></span>
                                            <p class="text-xs text-gray-500"
                                                x-text="formatFileSize(currentPostFileAttachment.size)"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <a :href="currentPostFileAttachment.url"
                                            class="text-blue-600 hover:text-blue-700 text-sm mr-3"
                                            target="_blank">Download</a>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="remove_file_attachment" value="1"
                                                class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                                            <span class="ml-1 text-sm text-red-600">Remove</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Upload New Multiple Files -->
                        <div>
                            <label for="file_attachments_home_edit"
                                class="block text-sm font-medium text-gray-600 mb-2">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                Upload New Documents (Max 5 files)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-md p-4 text-center hover:border-blue-400 transition-colors"
                                id="homeEditDropZone" ondrop="dropHomeEditHandler(event);"
                                ondragover="dragOverHomeEditHandler(event);"
                                ondragleave="dragLeaveHomeEditHandler(event);">
                                <input type="file" name="file_attachments[]" id="file_attachments_home_edit"
                                    class="hidden" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                                    multiple onchange="handleHomeEditFileSelect(event)">
                                <div class="text-gray-600">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" stroke="currentColor"
                                        fill="none" viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-sm">
                                        <button type="button" class="text-blue-600 hover:text-blue-500 font-medium"
                                            onclick="document.getElementById('file_attachments_home_edit').click()">
                                            Click to upload files
                                        </button>
                                        or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">PDF, DOC, TXT, PPT, XLS, ZIP up to 10MB each
                                        (Maximum 5 files)</p>
                                </div>
                            </div>
                            <div id="homeEditFileList" class="mt-3 space-y-2"></div>
                            <div id="homeEditUploadError" class="mt-2 text-red-600 text-sm hidden"></div>
                            @error('file_attachments.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            <!-- Fixed Footer -->
            <div class="p-4 border-t bg-gray-50 rounded-b-xl">
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showEditModal = false"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" form="edit-post-form"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Post
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Multiple file upload handling for home post edit
    let selectedHomeEditFiles = [];

    function validateHomeEditFile(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/x-rar-compressed'
        ];

        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: `${file.name}: Invalid file type. Allowed: PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, XLSX, ZIP, RAR`
            };
        }

        if (file.size > maxSize) {
            return {
                valid: false,
                message: `${file.name}: File too large. Maximum size is 10MB`
            };
        }

        return {
            valid: true,
            message: ''
        };
    }

    function handleHomeEditFileSelect(event) {
        const files = Array.from(event.target.files);
        processHomeEditFiles(files);
    }

    function processHomeEditFiles(files) {
        const errorDiv = document.getElementById('homeEditUploadError');
        let errors = [];

        // Check if adding these files would exceed the limit
        if (selectedHomeEditFiles.length + files.length > 5) {
            errors.push(
                `Cannot upload more than 5 files. Currently selected: ${selectedHomeEditFiles.length}, trying to add: ${files.length}`
            );
        }

        // Validate each file
        files.forEach(file => {
            const validation = validateHomeEditFile(file);
            if (!validation.valid) {
                errors.push(validation.message);
            }
        });

        if (errors.length > 0) {
            errorDiv.textContent = errors.join('. ');
            errorDiv.classList.remove('hidden');
            return;
        }

        // Clear any previous errors
        errorDiv.classList.add('hidden');

        // Add valid files
        files.forEach(file => {
            selectedHomeEditFiles.push(file);
        });

        updateHomeEditFileList();
    }

    function updateHomeEditFileList() {
        const fileList = document.getElementById('homeEditFileList');
        fileList.innerHTML = '';

        selectedHomeEditFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className =
                'flex items-center justify-between bg-blue-50 border border-blue-200 rounded-md p-3';

            fileItem.innerHTML = `
            <div class="flex items-center min-w-0 flex-1">
                <div class="bg-blue-100 rounded p-1 mr-3">
                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                </div>
            </div>
            <button type="button" onclick="removeHomeEditFile(${index})" class="ml-3 text-red-600 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

            fileList.appendChild(fileItem);
        });
    }

    function removeHomeEditFile(index) {
        selectedHomeEditFiles.splice(index, 1);
        updateHomeEditFileList();

        // Update the file input
        const input = document.getElementById('file_attachments_home_edit');
        const dt = new DataTransfer();
        selectedHomeEditFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
    }

    // Drag and drop handlers for home edit
    function dragOverHomeEditHandler(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
    }

    function dragLeaveHomeEditHandler(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
    }

    function dropHomeEditHandler(ev) {
        ev.preventDefault();
        ev.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');

        const files = Array.from(ev.dataTransfer.files);
        processHomeEditFiles(files);

        // Update the file input
        const input = document.getElementById('file_attachments_home_edit');
        const dt = new DataTransfer();
        selectedHomeEditFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
    }
</script>
