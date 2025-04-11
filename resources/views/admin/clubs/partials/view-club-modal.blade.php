<!-- View Club Modal -->
<div id="viewClubModal"
    class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex justify-center items-center hidden backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-xl transform transition-all">
        <div class="flex flex-col h-full">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white z-10">
                <h3 class="text-2xl font-bold text-gray-900">Club Details</h3>
                <button onclick="toggleViewClubModal()"
                    class="text-gray-500 hover:text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="overflow-y-auto flex-grow">
                <div id="clubDetails" class="p-6">
                    <!-- Banner Image -->
                    <div class="relative h-72 bg-gray-100 rounded-lg overflow-hidden shadow group"
                        id="clubBannerContainer">
                        <img src="" alt="Club Banner" class="w-full h-full object-cover" id="clubBannerImage">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                    </div>

                    <!-- Club Identity -->
                    <div class="flex items-center space-x-4 mt-6">
                        <div class="w-20 h-20 rounded-full bg-gray-100 overflow-hidden border-4 border-white shadow-md"
                            id="clubLogoContainer">
                            <img src="" alt="Club Logo" class="w-full h-full object-cover" id="clubLogoImage">
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900" id="clubNameDisplay"></h4>
                    </div>

                    <!-- Club Information -->
                    <div class="mt-8 space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-lg font-semibold mb-2 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Description
                            </h5>
                            <p class="text-gray-700" id="clubDescriptionDisplay">No description available</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h5 class="text-lg font-semibold mb-2 text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Adviser
                            </h5>
                            <p class="text-gray-700" id="clubAdviserDisplay">No adviser assigned</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3 sticky bottom-0 bg-white z-10">
                <button onclick="toggleViewClubModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                    Close
                </button>
                <button onclick="openEditClubModal()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                        </path>
                    </svg>
                    Edit Club
                </button>
                <button onclick="openDeleteClubModal()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Delete Club
                </button>
            </div>
        </div>
    </div>
</div>
