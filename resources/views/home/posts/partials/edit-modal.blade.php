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
            @click.stop>
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold">Edit Post</h2>
            </div>

            <!-- Scrollable Content Area -->
            <div class="p-6 overflow-y-auto flex-grow">
                <form
                    :action="`{{ route('clubs.posts.update', ['club' => 'CLUB_ID', 'post' => 'POST_ID']) }}`
                    .replace('CLUB_ID', currentClubId)
                        .replace('POST_ID', currentPostId)"
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
                            (max 2MB each).</p>
                        @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
