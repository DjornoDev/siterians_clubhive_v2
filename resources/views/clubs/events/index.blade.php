@section('title', $club->club_name . ' - Events')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div tabindex="-1" x-data="{
        activeTab: '{{ request()->get('tab', 'today') }}',
        showCreateModal: false,
        showEditModal: false,
        showDeleteModal: false,
        editingEvent: {},
        deletingEvent: {},
        init() {
            // Update URL when tab changes
            this.$watch('activeTab', (value) => {
                const url = new URL(window.location);
                url.searchParams.set('tab', value);
                window.history.pushState({}, '', url);
            });
    
            // Listen for modal events
            window.addEventListener('open-create-modal', () => {
                this.openCreateModal();
            });
        },
        openCreateModal() {
            this.showEditModal = false;
            this.showDeleteModal = false;
            this.showCreateModal = true;
        },
        openEditModal(eventData) {
            this.showCreateModal = false;
            this.showDeleteModal = false;
            this.editingEvent = eventData;
            this.$nextTick(() => {
                this.showEditModal = true;
            });
        },
        openDeleteModal(eventData) {
            console.log('openDeleteModal called with:', eventData);
            this.showCreateModal = false;
            this.showEditModal = false;
            this.deletingEvent = eventData;
            this.$nextTick(() => {
                this.showDeleteModal = true;
                console.log('Delete modal should be visible now:', this.showDeleteModal);
            });
        },
        closeAllModals() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.showDeleteModal = false;
            // Don't clear event data here to avoid timing issues
        }
    }" class="max-w-7xl mx-auto px-4 sm:px-6 py-8">


        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Club Events</h1>
                <p class="text-gray-600 mt-2">Manage and view all {{ $club->club_name }} events</p>
            </div>

            @can('create', [App\Models\Event::class, $club])
                <button type="button" @click="openCreateModal()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors
                       flex items-center gap-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Create Event
                </button>
            @endcan
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" onclick="this.parentElement.parentElement.parentElement.style.display='none'"
                            class="inline-flex bg-green-50 rounded-md p-1.5 text-green-400 hover:bg-green-100">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200 mb-6 sm:mb-8">
            <nav class="-mb-px flex overflow-x-auto scrollbar-hide space-x-4 sm:space-x-6 lg:space-x-8 pb-1" aria-label="Tabs">
                <button type="button" @click="activeTab = 'today'"
                    :class="activeTab === 'today' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                    Today's Events
                    <span :class="activeTab === 'today' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900'"
                        class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                        {{ $todayCount }}
                    </span>
                </button>

                @if ($isClubMember || $isClubAdviser)
                    <button type="button" @click="activeTab = 'pending'"
                        :class="activeTab === 'pending' ? 'border-orange-500 text-orange-600' :
                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="flex-shrink-0 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                        Pending Events
                        <span
                            :class="activeTab === 'pending' ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-900'"
                            class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                            {{ $pendingCount ?? 0 }}
                        </span>
                    </button>
                @endif

                <button type="button" @click="activeTab = 'upcoming'"
                    :class="activeTab === 'upcoming' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                    Upcoming Events
                    <span :class="activeTab === 'upcoming' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900'"
                        class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                        {{ $upcomingCount }}
                    </span>
                </button>

                <button type="button" @click="activeTab = 'past'"
                    :class="activeTab === 'past' ? 'border-blue-500 text-blue-600' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="flex-shrink-0 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm transition-colors">
                    Past Events
                    <span :class="activeTab === 'past' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-900'"
                        class="ml-2 py-0.5 px-2.5 rounded-full text-xs font-medium">
                        {{ $pastCount }}
                    </span>
                </button>
            </nav>
        </div>

        {{-- Filters and Search --}}
        <div class="mb-6 bg-white rounded-lg border border-gray-200 p-3 sm:p-4">
            <div class="flex flex-col gap-3 sm:gap-4">
                <!-- Search Bar -->
                <div class="w-full">
                    <input type="text" id="search-events" placeholder="Search events..."
                        class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Filters Row -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <select id="filter-visibility"
                        class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Visibility</option>
                        <option value="PUBLIC">Public</option>
                        <option value="CLUB_ONLY">Club Only</option>
                    </select>
                    <select id="filter-status"
                        class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button type="button" onclick="clearFilters()"
                        class="px-3 sm:px-4 py-2 text-sm sm:text-base text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        <span class="hidden sm:inline">Clear Filters</span>
                        <span class="sm:hidden">Clear</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Events Content --}}
        <div class="space-y-8">
            {{-- Today's Events --}}
            <div x-show="activeTab === 'today'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                @if ($todayEvents->count() > 0)
                    <div class="space-y-4" id="events-container-today">
                        @foreach ($todayEvents as $event)
                            <div class="event-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200"
                                data-name="{{ strtolower($event->event_name) }}"
                                data-visibility="{{ $event->event_visibility }}"
                                data-status="{{ $event->approval_status }}">
                                @include('clubs.events.partials.event-card-content', ['event' => $event])
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $todayEvents->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No events today</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back tomorrow or view upcoming events.</p>
                    </div>
                @endif
            </div>

            @if ($isClubMember || $isClubAdviser)
                {{-- Pending Events --}}
                <div x-show="activeTab === 'pending'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0">

                    @if (isset($pendingEvents) && $pendingEvents->count() > 0)
                        <div class="space-y-4" id="events-container-pending">
                            @foreach ($pendingEvents as $event)
                                <div class="event-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-orange-200"
                                    data-name="{{ strtolower($event->event_name) }}"
                                    data-visibility="{{ $event->event_visibility }}"
                                    data-status="{{ $event->approval_status }}">
                                    @include('clubs.events.partials.event-card-content', [
                                        'event' => $event,
                                    ])
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ isset($pendingEvents) ? $pendingEvents->appends(request()->query())->links() : '' }}
                        </div>
                    @else
                        <div class="text-center py-12 bg-orange-50 rounded-lg border border-orange-200">
                            <svg class="mx-auto h-12 w-12 text-orange-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No pending events</h3>
                            <p class="mt-1 text-sm text-gray-500">All events are either approved or there are no events
                                awaiting approval.</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Upcoming Events --}}
            <div x-show="activeTab === 'upcoming'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                @if ($upcomingEvents->count() > 0)
                    <div class="space-y-4" id="events-container-upcoming">
                        @foreach ($upcomingEvents as $event)
                            <div class="event-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200"
                                data-name="{{ strtolower($event->event_name) }}"
                                data-visibility="{{ $event->event_visibility }}"
                                data-status="{{ $event->approval_status }}">
                                @include('clubs.events.partials.event-card-content', ['event' => $event])
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $upcomingEvents->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming events</h3>
                        <p class="mt-1 text-sm text-gray-500">Create an event to get started.</p>
                    </div>
                @endif
            </div>

            {{-- Past Events --}}
            <div x-show="activeTab === 'past'" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0">
                @if ($pastEvents->count() > 0)
                    <div class="space-y-4" id="events-container-past">
                        @foreach ($pastEvents as $event)
                            <div class="event-card bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200 opacity-75"
                                data-name="{{ strtolower($event->event_name) }}"
                                data-visibility="{{ $event->event_visibility }}"
                                data-status="{{ $event->approval_status }}">
                                @include('clubs.events.partials.event-card-content', ['event' => $event])
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $pastEvents->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No past events</h3>
                        <p class="mt-1 text-sm text-gray-500">Past events will appear here after they're completed.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Modals --}}
        @include('clubs.events.partials.create-event-modal')
        @include('clubs.events.partials.edit-event-modal')
        @include('clubs.events.partials.delete-event-modal')

        {{-- JavaScript --}}
        <script>
            // Auto-show create modal if there are validation errors
            @if ($errors->any())
                document.addEventListener('DOMContentLoaded', function() {
                    // Use Alpine.js to open the modal
                    window.dispatchEvent(new CustomEvent('open-create-modal'));
                });
            @endif

            // Search and filter functionality
            function filterEvents() {
                const searchTerm = document.getElementById('search-events').value.toLowerCase();
                const visibilityFilter = document.getElementById('filter-visibility').value;
                const statusFilter = document.getElementById('filter-status').value;

                // Get all event containers
                const containers = ['today', 'pending', 'upcoming', 'past'];

                containers.forEach(container => {
                    const events = document.querySelectorAll(`#events-container-${container} .event-card`);

                    events.forEach(event => {
                        const name = event.getAttribute('data-name');
                        const visibility = event.getAttribute('data-visibility');
                        const status = event.getAttribute('data-status');

                        const matchesSearch = !searchTerm || name.includes(searchTerm);
                        const matchesVisibility = !visibilityFilter || visibility === visibilityFilter;
                        const matchesStatus = !statusFilter || status === statusFilter;

                        if (matchesSearch && matchesVisibility && matchesStatus) {
                            event.style.display = 'block';
                        } else {
                            event.style.display = 'none';
                        }
                    });
                });
            }

            function clearFilters() {
                document.getElementById('search-events').value = '';
                document.getElementById('filter-visibility').value = '';
                document.getElementById('filter-status').value = '';
                filterEvents();
            }

            // Add event listeners for real-time filtering
            document.getElementById('search-events').addEventListener('input', filterEvents);
            document.getElementById('filter-visibility').addEventListener('change', filterEvents);
            document.getElementById('filter-status').addEventListener('change', filterEvents);

            // Form submission handler
            function handleFormSubmit(event) {
                console.log('Form submission started');

                const form = event.target;
                const formData = new FormData(form);

                // Validate required fields
                const eventName = form.querySelector('#event_name').value.trim();
                const eventDate = form.querySelector('#event_date').value;
                const eventVisibility = form.querySelector('#event_visibility').value;

                if (!eventName || !eventDate || !eventVisibility) {
                    alert('Please fill in all required fields.');
                    return false;
                }

                // Check file validation
                const fileInput = form.querySelector('#supporting_documents');
                if (fileInput && fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    console.log(`File selected: ${file.name} (${file.size} bytes)`);

                    if (file.size > 10485760) {
                        alert('File is too large. Maximum size is 10MB.');
                        return false;
                    }

                    if (!confirm(
                            `Are you sure you want to upload "${file.name}" (${(file.size / 1024 / 1024).toFixed(2)} MB)?`)) {
                        return false;
                    }
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating Event...
            `;

                // Reset button after timeout
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                }, 30000);

                return true;
            }

            // File validation functions
            function validateFile(input) {
                const file = input.files[0];
                const errorDiv = document.getElementById('file-error');
                const successDiv = document.getElementById('file-success');
                const errorMessage = document.getElementById('file-error-message');
                const successMessage = document.getElementById('file-success-message');
                const submitBtn = document.querySelector('button[type="submit"]');

                errorDiv.classList.add('hidden');
                successDiv.classList.add('hidden');

                if (!file) {
                    submitBtn.disabled = false;
                    return;
                }

                const maxSize = 10485760; // 10MB
                const allowedTypes = [
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg', 'image/jpg', 'image/png', 'text/plain',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/zip', 'application/x-rar-compressed'
                ];

                const allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png', '.txt', '.ppt', '.pptx', '.xls',
                    '.xlsx', '.zip', '.rar'
                ];
                const fileName = file.name.toLowerCase();
                const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (file.size > maxSize) {
                    errorMessage.textContent =
                        `File is too large. Maximum size is 10MB. Your file is ${(file.size / 1024 / 1024).toFixed(2)} MB.`;
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = true;
                    return false;
                }

                if (!hasValidExtension && !allowedTypes.includes(file.type)) {
                    errorMessage.textContent =
                        `Invalid file type. Only PDF, DOC, DOCX, JPG, PNG, TXT, PPT, PPTX, XLS, XLSX, ZIP, and RAR files are allowed.`;
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = true;
                    return false;
                }

                successMessage.textContent =
                    `✓ File "${file.name}" (${(file.size / 1024 / 1024).toFixed(2)} MB) is ready to upload.`;
                successDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                return true;
            }

            function validateFileEdit(input) {
                const file = input.files[0];
                const errorDiv = document.getElementById('file-error-edit');
                const successDiv = document.getElementById('file-success-edit');
                const errorMessage = document.getElementById('file-error-message-edit');
                const successMessage = document.getElementById('file-success-message-edit');
                const submitBtn = document.querySelector('#edit-event-modal button[type="submit"]');

                errorDiv.classList.add('hidden');
                successDiv.classList.add('hidden');

                if (!file) {
                    submitBtn.disabled = false;
                    return;
                }

                const maxSize = 10485760; // 10MB
                const allowedTypes = [
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg', 'image/jpg', 'image/png', 'text/plain',
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'application/zip', 'application/x-rar-compressed'
                ];

                const allowedExtensions = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png', '.txt', '.ppt', '.pptx', '.xls',
                    '.xlsx', '.zip', '.rar'
                ];
                const fileName = file.name.toLowerCase();
                const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (file.size > maxSize) {
                    errorMessage.textContent =
                        `File is too large. Maximum size is 10MB. Your file is ${(file.size / 1024 / 1024).toFixed(2)} MB.`;
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = true;
                    return false;
                }

                if (!hasValidExtension && !allowedTypes.includes(file.type)) {
                    errorMessage.textContent =
                        `Invalid file type. Only PDF, DOC, DOCX, JPG, PNG, TXT, PPT, PPTX, XLS, XLSX, ZIP, and RAR files are allowed.`;
                    errorDiv.classList.remove('hidden');
                    submitBtn.disabled = true;
                    return false;
                }

                successMessage.textContent =
                    `✓ File "${file.name}" (${(file.size / 1024 / 1024).toFixed(2)} MB) is ready to upload.`;
                successDiv.classList.remove('hidden');
                submitBtn.disabled = false;
                return true;
            }

            // File validation helper (still needed for file uploads)
            function resetFileValidation() {
                const elements = [
                    'file-error', 'file-success', 'file-error-edit', 'file-success-edit'
                ];

                elements.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.classList.add('hidden');
                });

                const buttons = document.querySelectorAll('button[type="submit"]');
                buttons.forEach(btn => btn.disabled = false);
            }

            // Close modal on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    // Use Alpine.js to close modals
                    const alpineComponent = document.querySelector('[x-data]').__x?.$data;
                    if (alpineComponent) {
                        alpineComponent.closeAllModals();
                    }
                }
            });
        </script>
    </div>
@endsection
