<div id="deleteUserModal"
    class="hidden fixed inset-0 bg-gray-900 bg-opacity-70 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all duration-300 scale-100">
        <div class="flex items-center mb-5">
            <div class="bg-red-100 p-2 rounded-full mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Delete User</h3>
        </div>

        <p class="text-gray-700 mb-4">Are you sure you want to delete this user? This action cannot be undone.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-200">
            <p class="text-gray-700 font-medium mb-2">You're about to delete:</p>
            <div class="ml-4 space-y-1">
                <p class="text-gray-600">Name: <span id="deleteUserName" class="font-semibold text-gray-800"></span></p>
                <p class="text-gray-600">Email: <span id="deleteUserEmail" class="font-semibold text-gray-800"></span>
                </p>
            </div>
        </div>

        <form id="deleteUserForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" id="userId" name="user_id">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button type="button" onclick="showPasswordModal()"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    Delete User
                </button>
            </div>
        </form>
    </div>
</div>
