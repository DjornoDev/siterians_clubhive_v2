<div x-data="{
    show: false,
    previewLogo: '{{ Storage::url($club->club_logo) }}',
    previewBanner: '{{ Storage::url($club->club_banner) }}',
}" x-show="show" x-cloak
    @open-club-settings.window="show = true"
    @close-modal.window="show = false"
    class="fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal content -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <form action="{{ route('admin.clubs.update', $club) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- Club Name -->
                    <div>
                        <label for="club_name" class="block text-sm font-medium text-gray-700">Club Name</label>
                        <input type="text" name="club_name" id="club_name" value="{{ old('club_name', $club->club_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Club Description -->
                    <div>
                        <label for="club_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="club_description" id="club_description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('club_description', $club->club_description) }}</textarea>
                    </div>

                    <!-- Club Banner -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Club Banner</label>
                        <div class="mt-1 flex items-center space-x-4">
                            <img :src="previewBanner" alt="Banner preview" class="h-32 w-full object-cover rounded-lg" x-show="previewBanner">
                            <input type="file" name="club_banner" id="club_banner" accept="image/*"
                                @change="previewBanner = URL.createObjectURL($event.target.files[0])"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Club Logo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Club Logo</label>
                        <div class="mt-1 flex items-center space-x-4">
                            <img :src="previewLogo" alt="Logo preview" class="h-24 w-24 rounded-full" x-show="previewLogo">
                            <input type="file" name="club_logo" id="club_logo" accept="image/*"
                                @change="previewLogo = URL.createObjectURL($event.target.files[0])"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button @click.prevent="$dispatch('close-modal')" type="button"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>