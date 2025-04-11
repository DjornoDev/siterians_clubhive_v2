<!-- Edit Club Modal -->
<div id="editClubModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex justify-center items-center hidden backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-xl transform transition-all">
        <div class="flex flex-col h-full">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-2xl font-bold text-gray-900">Edit Club</h3>
                <button onclick="toggleEditClubModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="overflow-y-auto flex-grow">
                <form id="editClubForm" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editClubId" name="club_id">

                    <div class="space-y-6">
                        <!-- Club Name -->
                        <div>
                            <label for="editClubName" class="block text-sm font-medium text-gray-700 mb-1">Club
                                Name</label>
                            <input type="text" id="editClubName" name="club_name"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                        </div>

                        <!-- Club Description -->
                        <div>
                            <label for="editClubDescription"
                                class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="editClubDescription" name="club_description" rows="4"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"></textarea>
                        </div>

                        <!-- Club Adviser -->
                        <div>
                            <label for="editClubAdviser"
                                class="block text-sm font-medium text-gray-700 mb-1">Adviser</label>
                            <select id="editClubAdviser" name="club_adviser"
                                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                <option value="">Select Adviser</option>
                                @foreach ($advisers ?? [] as $adviser)
                                    <option value="{{ $adviser->user_id }}">{{ $adviser->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Images Section -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <!-- Club Logo -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Club Logo
                                </h4>

                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-2">Current Logo</label>
                                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-md mx-auto"
                                        id="currentLogoContainer">
                                        <img id="currentLogoDisplay" src="" alt="Current Logo"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <label for="editClubLogo" class="block text-sm text-gray-700 mb-2">New Logo
                                    (optional)</label>
                                <input type="file" id="editClubLogo" name="club_logo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 focus:outline-none"
                                    onchange="previewEditLogo(this)">

                                <!-- New Logo Preview -->
                                <div class="mt-4 hidden" id="newLogoPreviewContainer">
                                    <label class="block text-sm text-gray-700 mb-2">New Logo Preview</label>
                                    <div
                                        class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-4 border-white shadow-md mx-auto">
                                        <img id="newLogoPreview" src="" alt="New Logo Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>

                            <!-- Club Banner -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                                        </path>
                                    </svg>
                                    Club Banner
                                </h4>

                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-2">Current Banner</label>
                                    <div class="h-32 w-full rounded-lg overflow-hidden bg-gray-200 shadow"
                                        id="currentBannerContainer">
                                        <img id="currentBannerDisplay" src="" alt="Current Banner"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <label for="editClubBanner" class="block text-sm text-gray-700 mb-2">New Banner
                                    (optional)</label>
                                <input type="file" id="editClubBanner" name="club_banner" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 focus:outline-none"
                                    onchange="previewEditBanner(this)">

                                <!-- New Banner Preview -->
                                <div class="mt-4 hidden" id="newBannerPreviewContainer">
                                    <label class="block text-sm text-gray-700 mb-2">New Banner Preview</label>
                                    <div class="h-32 w-full rounded-lg overflow-hidden bg-gray-200 shadow">
                                        <img id="newBannerPreview" src="" alt="New Banner Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white z-10">
                <button type="button" onclick="toggleEditClubModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    Cancel
                </button>
                <button type="submit" form="editClubForm"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Update Club
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Image Previews -->
<script>
    function previewEditLogo(input) { // Renamed
        const previewContainer = document.getElementById('newLogoPreviewContainer');
        const preview = document.getElementById('newLogoPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.onerror = function(error) {
                console.error('Error reading logo:', error);
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            previewContainer.classList.add('hidden');
        }
    }

    function previewEditBanner(input) { // Renamed
        const previewContainer = document.getElementById('newBannerPreviewContainer');
        const preview = document.getElementById('newBannerPreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.onerror = function(error) {
                console.error('Error reading banner:', error);
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            previewContainer.classList.add('hidden');
        }
    }
</script>
