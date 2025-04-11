<div id="addSectionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Add New Section</h3>
        <form id="addSectionForm" action="{{ route('admin.sections.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Class</label>
                    <select name="class_id" required
                        class="w-full px-3 py-2 border rounded-lg @error('class_id') border-red-500 @enderror">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->class_id }}">Grade {{ $class->grade_level }}</option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Section Name</label>
                    <input type="text" name="section_name" required
                        class="w-full px-3 py-2 border rounded-lg @error('section_name') border-red-500 @enderror">
                    @error('section_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleSectionModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Add Section
                </button>
            </div>
        </form>
    </div>
</div>
