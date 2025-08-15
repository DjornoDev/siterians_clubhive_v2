<!-- resources/views/clubs/posts/partials/edit.blade.php -->
<div x-cloak>
    <!-- Backdrop -->
    <div x-show="showEditModal" class="fixed inset-0 bg-black/20 transition-opacity z-50"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200">
    </div>

    <!-- Modal Content -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.away="showEditModal = false">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl transform transition-all max-h-[90vh] flex flex-col"
            @click.stop x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">

            <!-- Header -->
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold">Edit Post</h2>
            </div>

            <!-- Scrollable Content Area -->
            <div class="p-6 overflow-y-auto flex-grow">
                <form id="edit-club-post-form"
                    :action="`{{ route('clubs.posts.update', [$club, 'POST_ID']) }}`.replace('POST_ID', currentPostId)"
                    method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label for="post_caption" class="block text-sm font-medium text-gray-700">Caption</label>
                        <textarea name="post_caption" x-model="editPostCaption"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        @error('post_caption')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                        <select x-model="editPostVisibility" name="visibility"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            required>
                            <option value="CLUB_ONLY">Club Only</option>
                            <option value="PUBLIC">Public</option>
                        </select>
                    </div>

                    <!-- Images Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                        <p class="text-sm text-gray-500 mb-2">Click the checkbox to remove an image.</p>

                        <template x-if="currentPostImages.length > 0">
                            <div
                                class="grid grid-cols-2 sm:grid-cols-3 gap-3 max-h-48 overflow-y-auto p-2 border rounded-md">
                                <template x-for="image in currentPostImages" :key="image.id">
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

                    <!-- File Attachment Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Attachment</label>

                        <!-- Current File Attachment -->
                        <template x-if="currentPostFileAttachment">
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

                        <!-- Upload New File -->
                        <div>
                            <label for="file_attachment" class="block text-sm font-medium text-gray-600 mb-2">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                Upload New File
                            </label>
                            <input type="file" name="file_attachment" id="file_attachment"
                                accept=".pdf,.doc,.docx,.txt,.ppt,.pptx,.xls,.xlsx,.zip,.rar"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC, TXT, PPT, XLS, ZIP files up to 10MB</p>
                            @error('file_attachment')
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
                    <button type="submit" form="edit-club-post-form"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Update Post
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
