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
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl transform transition-all" @click.stop
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Edit Post</h2>
                <form
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

                    <!-- Dynamic Images Section -->
                    <template x-if="currentPostImages.length > 0">
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="image in currentPostImages" :key="image.id">
                                <div class="relative group">
                                    <img :src="image.url" class="w-full h-24 object-cover rounded">
                                    <input type="checkbox" name="delete_images[]" :value="image.id">
                                </div>
                            </template>
                        </div>
                    </template>

                    <h1>Click the checkbox to remove an image.</h1>

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
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="showEditModal = false"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
