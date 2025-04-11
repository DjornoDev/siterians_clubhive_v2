<div id="delete-event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Delete Event</h3>

        <form method="POST" action="">
            @csrf
            @method('DELETE')
            <input type="hidden" name="event_id" id="delete_event_id">

            <p class="text-gray-600 mb-4">Are you sure you want to delete this event? This action cannot be undone.</p>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('delete-event-modal')"
                    class="px-4 py-2 border rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete Event
                </button>
            </div>
        </form>
    </div>
</div>
