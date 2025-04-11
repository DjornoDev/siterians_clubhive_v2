<!-- resources/views/clubs/posts/partials/create.blade.php -->
<div x-data="{ 
    showCreatePostModal: @json(session()->has('openCreatePostModal')), 
    isSubmitting: false,
    imagePreviewUrls: [],
    currentPage: 0,
    
    get totalPages() {
        return Math.ceil(this.imagePreviewUrls.length / 3);
    },
    
    get displayedImages() {
        const startIndex = this.currentPage * 3;
        return this.imagePreviewUrls.slice(startIndex, startIndex + 3);
    },
    
    nextPage() {
        if (this.currentPage < this.totalPages - 1) {
            this.currentPage++;
        }
    },
    
    prevPage() {
        if (this.currentPage > 0) {
            this.currentPage--;
        }
    },
    
    handleImageFiles(files) {
        if (!files || files.length === 0) return;
        
        // Process each file and create preview URLs
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviewUrls.push(e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }
    },
    
    removeImage(index) {
        // Calculate the actual index in the full array
        const actualIndex = this.currentPage * 3 + index;
        
        // Remove from preview array
        this.imagePreviewUrls.splice(actualIndex, 1);
        
        // Remove from file input
        const input = document.getElementById('images');
        const dt = new DataTransfer();
        
        // Add all files except the one to be removed
        for (let i = 0; i < input.files.length; i++) {
            if (i !== actualIndex) {
                dt.items.add(input.files[i]);
            }
        }
        
        // Update the file input
        input.files = dt.files;
        
        // Adjust current page if needed
        if (this.currentPage >= this.totalPages && this.currentPage > 0) {
            this.currentPage = this.totalPages - 1;
        }
    }
}" 
x-init="$watch('showCreatePostModal', value => {
    if (!value) {
        imagePreviewUrls = [];
        currentPage = 0;
    }
})">
    <button @click="showCreatePostModal = true"
        class="w-full flex items-center justify-center gap-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium px-5 py-3.5 rounded-lg text-center transition-colors focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create a post for {{ $club->club_name }}
    </button>

    <!-- Modal -->
    <div x-cloak>
        <!-- Backdrop with blur -->
        <div x-show="showCreatePostModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity z-50"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        <!-- Modal Content -->
        <div x-show="showCreatePostModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
            @keydown.escape.window="showCreatePostModal = false">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl transform transition-all" @click.stop
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95">
                
                <div class="flex justify-between items-center border-b border-gray-200 p-5">
                    <h2 class="text-xl font-bold text-gray-800">Create New Post</h2>
                    <button @click="showCreatePostModal = false" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('clubs.posts.store', $club) }}" method="POST" enctype="multipart/form-data"
                        @submit="isSubmitting = true">
                        @csrf
                        
                        <div class="mb-5">
                            <label for="post_caption" class="block text-sm font-medium text-gray-700 mb-1">Caption</label>
                            <textarea name="post_caption" id="post_caption" rows="4"
                                placeholder="Share what's on your mind..."
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 resize-none" required>{{ old('post_caption') }}</textarea>
                            @error('post_caption')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-5">
                            <label for="visibility" class="block text-sm font-medium text-gray-700 mb-1">Visibility</label>
                            <div class="relative">
                                <select name="visibility" id="visibility"
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10"
                                    required>
                                    <option value="CLUB_ONLY" {{ old('visibility') == 'CLUB_ONLY' ? 'selected' : '' }}>Club Members Only</option>
                                    <option value="PUBLIC" {{ old('visibility') == 'PUBLIC' ? 'selected' : '' }}>Public</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-5">
                            <label for="images" class="block text-sm font-medium text-gray-700 mb-1">Upload Images</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors"
                                x-on:dragover.prevent="$el.classList.add('border-blue-400')"
                                x-on:dragleave.prevent="$el.classList.remove('border-blue-400')"
                                x-on:drop.prevent="
                                    $el.classList.remove('border-blue-400');
                                    const fileInput = document.getElementById('images');
                                    fileInput.files = $event.dataTransfer.files;
                                    handleImageFiles($event.dataTransfer.files);
                                ">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload images</span>
                                            <input id="images" name="images[]" type="file" multiple accept="image/*" class="sr-only"
                                                @change="handleImageFiles($event.target.files)">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
                                </div>
                            </div>
                            
                            <!-- Image Preview Section with Navigation -->
                            <div x-show="imagePreviewUrls.length > 0" class="mt-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-sm font-medium text-gray-700">
                                        Image Preview 
                                        <span class="text-gray-500" x-text="`(${imagePreviewUrls.length} images)`"></span>
                                    </h3>
                                    <div class="flex items-center space-x-2" x-show="totalPages > 1">
                                        <button @click.prevent="prevPage" type="button" 
                                            class="p-1 rounded bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 0 }">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>
                                        <span class="text-sm text-gray-600" x-text="`${currentPage + 1}/${totalPages}`"></span>
                                        <button @click.prevent="nextPage" type="button" 
                                            class="p-1 rounded bg-gray-100 text-gray-600 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages - 1 }">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="(url, index) in displayedImages" :key="index">
                                        <div class="relative group">
                                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200">
                                                <img :src="url" class="w-full h-full object-cover" :alt="`Preview ${currentPage * 3 + index + 1}`">
                                            </div>
                                            <button @click.prevent="removeImage(index)" type="button" 
                                                class="absolute top-1 right-1 bg-black bg-opacity-50 rounded-full p-1 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            @error('images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="flex justify-end space-x-3 mt-8">
                            <button type="button" @click="showCreatePostModal = false"
                                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center"
                                :disabled="isSubmitting">
                                <span x-show="!isSubmitting">Post</span>
                                <span x-show="isSubmitting" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Posting...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>