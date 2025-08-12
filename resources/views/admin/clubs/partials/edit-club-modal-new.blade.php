<!-- Edit Club Modal -->
<div id="editClubModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex justify-center items-center hidden backdrop-blur-sm transition-opacity duration-300 p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl max-h-[90vh] overflow-hidden shadow-2xl transform transition-all">
        <div class="flex flex-col h-full">
            <!-- Modal Header -->
            <div
                class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <h3 class="text-xl font-bold">Edit Club</h3>
                <button onclick="toggleEditClubModal()"
                    class="text-white hover:text-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-white rounded-lg p-1">
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

                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column: Basic Info -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Club
                                Information</h4>

                            <!-- Club Name -->
                            <div>
                                <label for="editClubName" class="block text-sm font-medium text-gray-700 mb-1">
                                    Club Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="editClubName" name="club_name" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>

                            <!-- Club Description -->
                            <div>
                                <label for="editClubDescription" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description <span class="text-red-500">*</span>
                                </label>
                                <textarea id="editClubDescription" name="club_description" rows="3" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                    placeholder="Enter club description..."></textarea>
                            </div>

                            <!-- Club Adviser -->
                            <div>
                                <label for="editClubAdviser" class="block text-sm font-medium text-gray-700 mb-1">
                                    Adviser <span class="text-red-500">*</span>
                                </label>
                                <select id="editClubAdviser" name="club_adviser" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="">Select Adviser</option>
                                    @foreach ($advisers ?? [] as $adviser)
                                        <option value="{{ $adviser->user_id }}">{{ $adviser->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Club Category -->
                            <div>
                                <label for="editCategory" class="block text-sm font-medium text-gray-700 mb-1">
                                    Club Category <span class="text-red-500">*</span>
                                </label>
                                <select id="editCategory" name="category" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                    <option value="">Select Category</option>
                                    <option value="academic">Academic</option>
                                    <option value="sports">Sports</option>
                                    <option value="service">Service</option>
                                </select>
                            </div>

                            <!-- Approval Requirement -->
                            <div>
                                <label class="flex items-center space-x-3">
                                    <input type="hidden" name="requires_approval" value="0">
                                    <input type="checkbox" id="editRequiresApproval" name="requires_approval"
                                        value="1"
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700">Require Approval for
                                            Members</span>
                                        <p class="text-xs text-gray-500">Students must request approval to join</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Right Column: Images -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Club Images
                            </h4>

                            <!-- Club Logo Section -->
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    Club Logo
                                </h5>

                                <!-- Current Logo -->
                                <div class="mb-3">
                                    <div class="text-xs text-gray-600 mb-1">Current Logo</div>
                                    <div class="w-20 h-20 rounded-full overflow-hidden bg-white border-2 border-gray-200 shadow mx-auto"
                                        id="currentLogoContainer">
                                        <img id="currentLogoDisplay" src="" alt="Current Logo"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <!-- Logo Upload -->
                                <label for="editClubLogo" class="block text-xs font-medium text-gray-700 mb-2">
                                    New Logo <span class="text-gray-400">(optional)</span>
                                </label>
                                <label for="editClubLogo"
                                    class="flex flex-col items-center justify-center w-full h-20 border-2 border-blue-300 border-dashed rounded-lg cursor-pointer bg-blue-50 hover:bg-blue-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 mb-1 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="text-xs text-blue-600 font-medium">Upload Logo</p>
                                    </div>
                                    <input id="editClubLogo" name="club_logo" type="file" accept="image/*"
                                        class="hidden" onchange="previewEditLogo(this)">
                                </label>

                                <!-- New Logo Preview -->
                                <div class="hidden mt-3" id="newLogoPreviewContainer">
                                    <div class="text-xs text-green-600 mb-1 font-medium">✓ New Logo Preview</div>
                                    <div
                                        class="w-20 h-20 rounded-full overflow-hidden bg-white border-2 border-green-300 shadow mx-auto">
                                        <img id="newLogoPreview" src="" alt="New Logo Preview"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>
                            </div>

                            <!-- Club Banner Section -->
                            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                <h5 class="font-medium text-gray-900 mb-3 flex items-center">
                                    <div
                                        class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z">
                                            </path>
                                        </svg>
                                    </div>
                                    Club Banner
                                </h5>

                                <!-- Current Banner -->
                                <div class="mb-3">
                                    <div class="text-xs text-gray-600 mb-1">Current Banner</div>
                                    <div class="h-24 w-full rounded-lg overflow-hidden bg-white border-2 border-gray-200 shadow"
                                        id="currentBannerContainer">
                                        <img id="currentBannerDisplay" src="" alt="Current Banner"
                                            class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <!-- Banner Upload -->
                                <label for="editClubBanner" class="block text-xs font-medium text-gray-700 mb-2">
                                    New Banner <span class="text-gray-400">(optional)</span>
                                </label>
                                <label for="editClubBanner"
                                    class="flex flex-col items-center justify-center w-full h-20 border-2 border-purple-300 border-dashed rounded-lg cursor-pointer bg-purple-50 hover:bg-purple-100 transition-colors">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-6 h-6 mb-1 text-purple-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="text-xs text-purple-600 font-medium">Upload Banner</p>
                                    </div>
                                    <input id="editClubBanner" name="club_banner" type="file" accept="image/*"
                                        class="hidden" onchange="previewEditBanner(this)">
                                </label>

                                <!-- New Banner Preview -->
                                <div class="hidden mt-3" id="newBannerPreviewContainer">
                                    <div class="text-xs text-green-600 mb-1 font-medium">✓ New Banner Preview</div>
                                    <div
                                        class="h-24 w-full rounded-lg overflow-hidden bg-white border-2 border-green-300 shadow">
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
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <p class="text-xs text-gray-600">
                        <span class="text-red-500">*</span> Required fields
                    </p>
                    <div class="flex space-x-3">
                        <button type="button" onclick="toggleEditClubModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" form="editClubForm"
                            class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Club
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Image Previews -->
<script>
    function previewEditLogo(input) {
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

    function previewEditBanner(input) {
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
