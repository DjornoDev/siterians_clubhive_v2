<div id="edit-event-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">Edit Event</h3>

        <form method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="event_id" id="event_id">

            <div class="space-y-4">
                <div>
                    <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name</label>
                    <input type="text" name="event_name" id="event_name" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="event_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="event_description" id="event_description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" name="event_date" id="event_date" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label for="event_time" class="block text-sm font-medium text-gray-700">Time (optional)</label>
                    <input type="text" name="event_time" id="event_time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="e.g., 8am onwards">
                </div>

                <div>
                    <label for="event_visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                    <select name="event_visibility" id="event_visibility" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="CLUB_ONLY" selected>Club Members Only</option>
                        <option value="PUBLIC">Public</option>
                    </select>
                </div>

                <div>
                    <label for="event_location" class="block text-sm font-medium text-gray-700">Location</label>
                    <input type="text" name="event_location" id="event_location"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeModal('edit-event-modal')"
                    class="px-4 py-2 border rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</div>
