@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto" x-data="editMember()">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ $club->club_name }} Members</h1>
            @if (auth()->user()->user_id === $club->club_adviser)
                <div x-data="memberModal()">
                    <button @click="isModalOpen = true"
                        class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Add Member
                    </button>

                    {{-- Add Member Modal --}}
                    <div x-cloak x-show="isModalOpen"
                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                        @click.self="isModalOpen = false">
                        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl" @click.outside="isModalOpen = false">
                            <form @submit.prevent="submitForm" method="POST"
                                action="{{ route('clubs.members.store', $club) }}">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-4">Add Members to {{ $club->club_name }}</h3>

                                    <!-- Search Input -->
                                    <div class="relative mb-4">
                                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" x-model="searchTerm" @input.debounce.300ms="searchStudents"
                                            placeholder="Search students by name or email"
                                            class="w-full pl-10 pr-10 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <!-- Loading Spinner -->
                                        <div x-show="isLoading" class="absolute right-3 top-3">
                                            <svg class="animate-spin h-5 w-5 text-blue-500"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Suggestions -->
                                    <div x-show="suggestions.length > 0"
                                        class="border rounded-lg mb-4 max-h-60 overflow-auto">
                                        <template x-for="student in suggestions" :key="student.user_id">
                                            <div @click="selectStudent(student)"
                                                class="p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0 transition-colors">
                                                <div class="font-medium" x-text="student.name"></div>
                                                <div class="text-sm text-gray-600" x-text="student.email"></div>
                                                <div class="text-sm text-gray-500">
                                                    Grade <span x-text="student.display_grade"></span>
                                                    -
                                                    <span x-text="student.display_section"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Selected Students -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <template x-for="student in selectedStudents" :key="student.user_id">
                                            <div
                                                class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full flex items-center gap-2 transition-colors hover:bg-blue-200">
                                                <span x-text="student.name"></span>
                                                <button @click="removeStudent(student)" type="button"
                                                    class="text-blue-600 hover:text-blue-800 transition-colors focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                                    <button type="button" @click="isModalOpen = false"
                                        class="px-5 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2"
                                        :disabled="selectedStudents.length === 0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                                        </svg>
                                        Add Selected Members
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Filters Section -->
            <div class="p-6 border-b">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="col-span-1 md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search"
                                    placeholder="Search by name or email" value="{{ request('search') }}"
                                    class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Class</label>
                            <select name="class" id="class"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->class_id }}"
                                        {{ request('class') == $class->class_id ? 'selected' : '' }}>
                                        Grade {{ $class->grade_level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                            <select name="section" id="section"
                                class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->section_id }}"
                                        {{ request('section') == $section->section_id ? 'selected' : '' }}>
                                        {{ $section->section_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ url()->current() }}"
                            class="flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Clear
                        </a>
                        <button type="submit"
                            class="flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Control Bar -->
            <div class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center bg-gray-50 border-b">
                <div class="mb-3 sm:mb-0">
                    <p class="text-sm text-gray-600">
                        Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} of
                        {{ $members->total() }} members
                    </p>
                </div>

                <form method="GET" class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Show</span>
                    <select name="per_page" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-md text-sm shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="10" {{ $members->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ $members->perPage() == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ $members->perPage() == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                    <!-- Hidden fields to preserve filters -->
                    @foreach (request()->except('per_page', 'page') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                </form>
            </div>

            <!-- Members Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Position</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Section</th>
                            @if (auth()->user()->user_id === $club->club_adviser)
                                <th
                                    class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                            <span class="font-medium text-lg">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $member->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $member->pivot->club_role === 'Member' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $member->pivot->club_role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $member->pivot->club_position ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if ($member->section && $member->section->schoolClass)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                            Grade {{ $member->section->schoolClass->grade_level }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $member->section->section_name ?? 'N/A' }}
                                </td>
                                @if (auth()->user()->user_id === $club->club_adviser)
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <button @click="openEditModal(@js($member))"
                                            class="text-blue-600 hover:text-blue-900 transition-colors flex items-center gap-1 ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                            Edit
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-lg font-medium">No members found</p>
                                        <p class="text-sm mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-white border-t">
                {{ $members->appends(request()->query())->links() }}
            </div>
        </div>

        @if (auth()->user()->user_id === $club->club_adviser)
            @include('clubs.people.partials.edit-member-modal')
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('memberModal', () => ({
                isModalOpen: false,
                searchTerm: '',
                suggestions: [],
                selectedStudents: [],
                isLoading: false,

                async searchStudents() {
                    if (this.searchTerm.length < 2) {
                        this.suggestions = [];
                        return;
                    }

                    this.isLoading = true;
                    try {
                        const response = await fetch(
                            `{{ route('clubs.non-members', $club) }}?search=${this.searchTerm}`
                        );
                        this.suggestions = await response.json();
                    } catch (error) {
                        console.error('Error fetching students:', error);
                    }
                    this.isLoading = false;
                },

                selectStudent(student) {
                    if (!this.selectedStudents.some(s => s.user_id === student.user_id)) {
                        this.selectedStudents.push(student);
                        this.searchTerm = '';
                        this.suggestions = [];
                    }
                },

                removeStudent(student) {
                    this.selectedStudents = this.selectedStudents.filter(
                        s => s.user_id !== student.user_id
                    );
                },

                async submitForm(event) {
                    const form = event.target; // Get the form from the event
                    const formData = new FormData(form);
                    formData.append('user_ids', this.selectedStudents.map(s => s.user_id).join(
                        ','));

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const error = await response.json();
                            alert(error.message || 'An error occurred');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while submitting the form');
                    }
                }
            }));

            Alpine.data('editMember', () => ({
                isEditModalOpen: false,
                currentMember: {
                    id: null,
                    name: '',
                    email: '',
                    role: '',
                    position: '',
                    permissions: {
                        manage_posts: false,
                        manage_events: false
                    }
                },
                formAction: '',

                openEditModal(member) {
                    const permissions = member.pivot.club_accessibility || {};
                    this.currentMember = {
                        id: member.user_id,
                        name: member.name,
                        email: member.email,
                        role: member.pivot.club_role,
                        position: member.pivot.club_position || '',
                        permissions: {
                            manage_posts: Boolean(permissions.manage_posts),
                            manage_events: Boolean(permissions.manage_events)
                        }
                    };
                    this.formAction = `/clubs/{{ $club->club_id }}/members/${member.user_id}`;
                    this.isEditModalOpen = true;
                },

                togglePermissions(value) {
                    // Reset permissions if position is cleared
                    if (!value) {
                        this.currentMember.permissions = {
                            manage_posts: false,
                            manage_events: false
                        };
                    }
                },

                async submitForm(event) {
                    const form = event.target;
                    const formData = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-HTTP-Method-Override': 'PUT',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const error = await response.json();
                            alert(error.message || 'An error occurred');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while saving changes');
                    }
                }
            }));
        });
    </script>
@endpush
