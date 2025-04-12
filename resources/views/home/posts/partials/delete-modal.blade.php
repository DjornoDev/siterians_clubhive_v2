<div x-cloak>
    <!-- Backdrop -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-black/20 transition-opacity z-50"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200">
    </div>

    <!-- Modal Content -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @click.away="showDeleteModal = false">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md transform transition-all" @click.stop>
            <div class="p-6">
                <!-- Delete confirmation content -->
                <div class="flex items-center justify-center mb-4 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold mb-2 text-center">Delete Post</h2>
                <p class="text-gray-600 mb-6 text-center">Are you sure you want to delete this post? This action cannot
                    be undone.</p>
                <form
                    :action="`{{ route('clubs.posts.delete', ['club' => 'CLUB_ID', 'post' => 'POST_ID']) }}`
                    .replace('CLUB_ID', currentClubId)
                        .replace('POST_ID', currentPostId)"
                    method="POST">
                    @csrf @method('DELETE')
                    <!-- Form buttons -->
                    <div class="flex justify-center space-x-3">
                        <button type="button" @click="showDeleteModal = false"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                            Delete Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
