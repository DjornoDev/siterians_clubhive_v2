<div x-cloak x-show="isEditModalOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <form @submit.prevent="submitForm" method="POST"
            x-bind:action="`/clubs/{{ $club->club_id }}/members/${currentMember.id}`">
            @csrf
            @method('PUT')

            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Edit Member Position</h3>

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" x-model="currentMember.name" readonly
                        class="w-full bg-gray-100 rounded px-3 py-2 cursor-not-allowed">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" x-model="currentMember.email" readonly
                        class="w-full bg-gray-100 rounded px-3 py-2 cursor-not-allowed">
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <input type="text" x-model="currentMember.role" readonly
                        class="w-full bg-gray-100 rounded px-3 py-2 cursor-not-allowed">
                </div>

                <!-- Position -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="club_position" x-model="currentMember.position"
                        @input="togglePermissions($event.target.value)"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter position">
                </div>

                <!-- Permissions -->
                <div class="space-y-4" x-show="currentMember.position">
                    <h4 class="font-medium text-gray-900">Club Member Permissions</h4>

                    <!-- Manage Posts -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-900">Manage Posts</p>
                            <p class="text-xs text-gray-500">Can create, edit/update, and delete posts for the club.</p>
                        </div>
                        <input type="hidden" name="manage_posts" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manage_posts" x-model="currentMember.permissions.manage_posts"
                                value="1" :disabled="!currentMember.position" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 
                                peer-disabled:bg-gray-100 peer-disabled:cursor-not-allowed 
                                peer-checked:after:translate-x-full peer-checked:after:border-white 
                                after:content-[''] after:absolute after:top-0.5 after:left-[2px] 
                                after:bg-white after:border-gray-300 after:border after:rounded-full 
                                after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>

                    <!-- Manage Events -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-900">Manage Events</p>
                            <p class="text-xs text-gray-500">Can schedule/create, edit/update, and/or delete events for
                                the club.</p>
                        </div>
                        <input type="hidden" name="manage_events" value="0">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="manage_events"
                                x-model="currentMember.permissions.manage_events" value="1"
                                :disabled="!currentMember.position" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-600 
                                peer-disabled:bg-gray-100 peer-disabled:cursor-not-allowed 
                                peer-checked:after:translate-x-full peer-checked:after:border-white 
                                after:content-[''] after:absolute after:top-0.5 after:left-[2px] 
                                after:bg-white after:border-gray-300 after:border after:rounded-full 
                                after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button type="button" @click="isEditModalOpen = false"
                    class="px-4 py-2 text-gray-700 hover:text-gray-900">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
