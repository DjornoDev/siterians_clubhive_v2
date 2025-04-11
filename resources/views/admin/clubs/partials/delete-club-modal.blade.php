<!-- Delete Club Modal -->
<div id="deleteClubModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg max-w-md w-full">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Confirm Deletion</h3>
                <button onclick="toggleDeleteClubModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-gray-700">Are you sure you want to delete <span id="deleteClubName"
                        class="font-semibold"></span>?</p>
                <p class="text-red-600 mt-2">This action cannot be undone.</p>
            </div>

            <form id="deleteClubForm" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" id="deleteClubId" name="club_id">

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="toggleDeleteClubModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                        Delete Club
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
