<!-- Club Settings Modal -->
<div id="clubSettingsModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[9999] overflow-y-auto">
    <div class="flex min-h-screen items-center justify-center p-2 sm:p-4">
        <div class="bg-white rounded-lg sm:rounded-xl shadow-xl w-full max-w-md sm:max-w-lg lg:max-w-2xl max-h-[95vh] flex flex-col transform transition-all">
            
            <!-- Header -->
            <div class="flex justify-between items-center border-b border-gray-200 p-4 sm:p-6 flex-shrink-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Club Settings</h2>
                <button type="button" onclick="closeClubSettingsModal()"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable Content -->
            <div class="p-4 sm:p-6 overflow-y-auto flex-1">
                <form id="club-settings-form" action="{{ route('clubs.update-settings', $club) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Club Name -->
                    <div>
                        <label for="club_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Club Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="club_name" id="club_name" value="{{ $club->club_name }}" required
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="club_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="club_description" id="club_description" rows="4" 
                            placeholder="Describe your club's mission, activities, and goals..."
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ $club->club_description }}</textarea>
                    </div>

                    <!-- File Uploads -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Club Logo -->
                        <div>
                            <label for="club_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                Club Logo
                            </label>
                            <div class="relative">
                                <input type="file" name="club_logo" id="club_logo" accept="image/*"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                        </div>

                        <!-- Club Banner -->
                        <div>
                            <label for="club_banner" class="block text-sm font-medium text-gray-700 mb-2">
                                Club Banner
                            </label>
                            <div class="relative">
                                <input type="file" name="club_banner" id="club_banner" accept="image/*"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 5MB</p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Buttons -->
            <div class="border-t border-gray-200 p-4 sm:p-6 flex-shrink-0 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                    <button type="button" onclick="closeClubSettingsModal()"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" form="club-settings-form"
                        class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('club-settings-scripts')
    <script>
        function openClubSettingsModal() {
            document.getElementById('clubSettingsModal').classList.remove('hidden');
        }

        function closeClubSettingsModal() {
            document.getElementById('clubSettingsModal').classList.add('hidden');
        }
    </script>
@endpush
