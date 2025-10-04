<!-- User View Modal -->
<div id="userViewModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
    <div
        class="bg-white rounded-lg shadow-xl p-6 w-full max-w-4xl transform transition-all duration-300 scale-100 overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-2 rounded-full mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800" id="viewUserName">User Details</h3>
            </div>
            <button onclick="closeViewModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- User Profile Section -->
            <div class="col-span-1 bg-gradient-to-b from-blue-50 to-indigo-50 p-5 rounded-xl border border-blue-100">
                <div class="flex flex-col items-center mb-4">
                    <div id="userAvatar"
                        class="h-24 w-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-3xl mb-3">
                        <!-- Initial will be inserted here by JS -->
                    </div>
                    <h4 id="userName" class="text-lg font-bold text-gray-800"></h4>
                    <p id="userEmail" class="text-gray-500 mt-1"></p>
                    <div id="userRoleBadge" class="mt-2">
                        <!-- Role badge will be inserted here by JS -->
                    </div>                </div>
                <div class="space-y-2 border-t border-blue-100 pt-4">
                    <p class="text-sm text-gray-500">Account Details</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Created:</span>
                        <span id="userCreated" class="text-sm font-medium"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Last Updated:</span>
                        <span id="userUpdated" class="text-sm font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- Class & Section Information -->
            <div class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        Academic Information
                    </h4>
                    <div class="space-y-3">
                        <div id="classInfo" class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Grade Level</p>
                            <p id="userGradeLevel" class="text-gray-800 font-medium mt-1">N/A</p>
                        </div>
                        <div id="sectionInfo" class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Section</p>
                            <p id="userSection" class="text-gray-800 font-medium mt-1">N/A</p>
                        </div>
                    </div>
                </div>
                
                <!-- Club Information -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 id="clubSectionTitle" class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Club Memberships</span>
                    </h4>
                    
                    <!-- For Students: Club memberships -->
                    <div id="studentClubSection" class="hidden">
                        <div id="clubMemberships" class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            <div class="text-center text-gray-500 italic py-2" id="noClubsMessage">
                                No club memberships found.
                            </div>
                        </div>
                    </div>
                    
                    <!-- For Teachers: Clubs advised -->
                    <div id="teacherClubSection" class="hidden">
                        <div id="clubsAdvised" class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            <div class="text-center text-gray-500 italic py-2" id="noAdvisedClubsMessage">
                                Not advising any clubs.
                            </div>
                        </div>
                    </div>
                    
                    <!-- For Admins: Message -->
                    <div id="adminClubSection" class="hidden">
                        <div class="text-center text-gray-500 italic py-2">
                            Admin account - No club association.
                        </div>
                    </div>
                </div>

                <!-- Activity Stats -->
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Activity Statistics
                    </h4>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Posts</p>
                            <p id="userPostsCount" class="text-gray-800 font-medium mt-1">0</p>
                        </div>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Events</p>
                            <p id="userEventsCount" class="text-gray-800 font-medium mt-1">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Credentials Section -->
        <div class="mt-6 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                User Credentials
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Name</p>
                    <p id="userCredName" class="text-gray-800 font-medium mt-1"></p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Email Address</p>
                    <p id="userCredEmail" class="text-gray-800 font-medium mt-1"></p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Contact no.</p>
                    <p id="userCredContactNo" class="text-gray-800 font-medium mt-1"></p>
                </div>
            </div>
        </div>

        <!-- Parent/Guardian Information Section -->
        <div class="mt-6 bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Parent/Guardian Information
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Mother's Information -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Mother's Name</p>
                    <p id="userMotherName" class="text-gray-800 font-medium mt-1">N/A</p>
                    <p class="text-xs text-gray-500 uppercase mt-2">Contact Number</p>
                    <p id="userMotherContact" class="text-gray-800 font-medium mt-1">N/A</p>
                </div>
                
                <!-- Father's Information -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Father's Name</p>
                    <p id="userFatherName" class="text-gray-800 font-medium mt-1">N/A</p>
                    <p class="text-xs text-gray-500 uppercase mt-2">Contact Number</p>
                    <p id="userFatherContact" class="text-gray-800 font-medium mt-1">N/A</p>
                </div>
                
                <!-- Guardian's Information -->
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 uppercase">Guardian's Name</p>
                    <p id="userGuardianName" class="text-gray-800 font-medium mt-1">N/A</p>
                    <p class="text-xs text-gray-500 uppercase mt-2">Contact Number</p>
                    <p id="userGuardianContact" class="text-gray-800 font-medium mt-1">N/A</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button onclick="closeViewModal()"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-gray-300">
                Close
            </button>
        </div>
    </div>
</div>
