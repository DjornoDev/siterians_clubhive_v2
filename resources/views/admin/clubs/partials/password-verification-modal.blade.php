<!-- Password Verification Modal -->
<div id="passwordVerificationModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Security Verification</h3>
                <button onclick="togglePasswordVerificationModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-700 mb-3">For security reasons, please enter your password to confirm deletion of <span id="verifyDeleteClubName" class="font-semibold"></span>.</p>
                <div class="mt-4">
                    <label for="security-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="security-password" name="password" autocomplete="current-password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Enter your password">
                    <div id="password-error" class="text-red-500 text-sm mt-1 hidden">Invalid password. Please try again.</div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="togglePasswordVerificationModal()"
                    class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Cancel
                </button>
                <button type="button" onclick="verifyPasswordAndDeleteClub()"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                    Verify & Delete
                </button>
            </div>
        </div>
    </div>
</div>
