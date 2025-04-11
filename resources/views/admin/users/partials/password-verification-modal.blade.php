<!-- Password Verification Modal -->
<div id="passwordVerificationModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 scale-100">
        <div class="flex items-center mb-5">
            <div class="bg-blue-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Verify Identity</h3>
        </div>

        <p class="text-gray-700 mb-4">Please enter your password to confirm this action.</p>

        <form id="verifyPasswordForm">
            <div class="mb-4">
                <label for="passwordInput" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="relative">
                    <input type="password" id="passwordInput" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="Enter your password">
                </div>
                <!-- Error message container (hidden by default) -->
                <div id="passwordError" class="mt-2 text-sm text-red-600 hidden"></div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closePasswordModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Verify
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="deleteSuccessModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 scale-100">
        <div class="flex flex-col items-center text-center">
            <div class="bg-green-100 p-4 rounded-full mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">User Deleted Successfully</h3>
            <p class="text-gray-600 mb-6">The user has been permanently removed from the system.</p>

            <button type="button" onclick="closeSuccessModal()"
                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Done
            </button>
        </div>
    </div>
</div>
