<div id="clubSettingsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 z-50">
    <div class="modal-content bg-white rounded-lg p-6 max-w-2xl mx-auto mt-20">
        <form action="{{ route('clubs.update-settings', $club) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Club Name</label>
                <input type="text" name="club_name" value="{{ $club->club_name }}" class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Description</label>
                <textarea name="club_description" rows="6" class="w-full p-2 border rounded">{{ $club->club_description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Club Logo</label>
                <input type="file" name="club_logo" class="w-full">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Club Banner</label>
                <input type="file" name="club_banner" class="w-full">
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeClubSettingsModal()"
                    class="px-4 py-2 bg-gray-500 text-white rounded">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@push('club-settings-scripts')
    <script>
        function openClubSettingsModal() {
            document.getElementById('clubSettingsModal').classList.remove('hidden');
        }

        function closeClubSettingsModal() {
            document.getElementById('clubSettingsModal').classList.add('hidden');
        }
    </script>
@endpush
