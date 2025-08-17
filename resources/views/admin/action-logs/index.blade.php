@extends('layouts.dashboard')

@section('title', 'Action Logs | ClubHive')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Action Logs</h1>
                    <p class="text-blue-100 mt-1">Monitor system activities and user actions</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.action-logs.archives') }}"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                        <i class="fas fa-archive mr-2"></i>View Archives
                    </a>
                    <form action="{{ route('admin.action-logs.cleanup') }}" method="POST"
                        onsubmit="return confirm('This will archive and delete logs older than 30 days. Continue?')">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Cleanup Old Logs
                        </button>
                    </form>
                    <button onclick="resetFilters()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                        <i class="fas fa-undo mr-2"></i>Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Filters & Search</h2>

            <form method="GET" action="{{ route('admin.action-logs.index') }}" class="space-y-4">
                <!-- Search Filters Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by User Name</label>
                        <input type="text" name="user_search" value="{{ request('user_search') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Type user name..." autocomplete="off">
                        <div id="user-suggestions"
                            class="hidden absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search by Action Keyword</label>
                        <input type="text" name="action_search" value="{{ request('action_search') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="login, logout, created..." autocomplete="off">
                        <div id="action-suggestions"
                            class="hidden absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto">
                        </div>
                    </div>
                </div>

                <!-- Date Range Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Filter Dropdowns Row -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Roles</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="TEACHER" {{ request('role') === 'TEACHER' ? 'selected' : '' }}>Teacher</option>
                            <option value="STUDENT" {{ request('role') === 'STUDENT' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Action Category</label>
                        <select name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            <option value="authentication"
                                {{ request('category') === 'authentication' ? 'selected' : '' }}>
                                Authentication</option>
                            <option value="user_management"
                                {{ request('category') === 'user_management' ? 'selected' : '' }}>User Management</option>
                            <option value="club_management"
                                {{ request('category') === 'club_management' ? 'selected' : '' }}>Club Management</option>
                            <option value="club_membership"
                                {{ request('category') === 'club_membership' ? 'selected' : '' }}>Club Membership</option>
                            <option value="event_management"
                                {{ request('category') === 'event_management' ? 'selected' : '' }}>Event Management
                            </option>
                            <option value="post_management"
                                {{ request('category') === 'post_management' ? 'selected' : '' }}>Post Management</option>
                            <option value="voting_management"
                                {{ request('category') === 'voting_management' ? 'selected' : '' }}>Voting Management
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success
                            </option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <select name="sort"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest First
                            </option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest First
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">
                        Action Logs
                        @if ($logs->total() > 0)
                            <span class="text-sm text-gray-500 font-normal">({{ $logs->total() }} entries)</span>
                        @endif
                    </h2>
                    @if (request()->hasAny(['user_search', 'action_search', 'start_date', 'end_date', 'role', 'category', 'status']))
                        <span class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                            <i class="fas fa-filter mr-1"></i>Filtered Results
                        </span>
                    @endif
                </div>
            </div>

            @if ($logs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $log->user_name ?? 'System' }}
                                        </div>
                                        @if ($log->user_id)
                                            <div class="text-xs text-gray-500">ID: {{ $log->user_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($log->user_role)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $log->user_role === 'ADMIN' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $log->user_role === 'TEACHER' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $log->user_role === 'STUDENT' ? 'bg-green-100 text-green-800' : '' }}">
                                                {{ $log->user_role }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            <span
                                                class="font-medium">{{ ucfirst(str_replace('_', ' ', $log->action_category)) }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $log->action_type }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate"
                                            title="{{ $log->action_description }}">
                                            {{ $log->action_description }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $log->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i
                                                class="fas {{ $log->status === 'success' ? 'fa-check' : 'fa-times' }} mr-1"></i>
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.action-logs.show', $log) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-search text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No logs found</h3>
                    <p class="text-gray-500 mb-4">
                        @if (request()->hasAny(['user_search', 'action_search', 'start_date', 'end_date', 'role', 'category', 'status']))
                            Try adjusting your filters to see more results.
                        @else
                            No action logs have been recorded yet.
                        @endif
                    </p>
                    @if (request()->hasAny(['user_search', 'action_search', 'start_date', 'end_date', 'role', 'category', 'status']))
                        <button onclick="resetFilters()" class="text-blue-600 hover:text-blue-800 font-medium">
                            Clear all filters
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function resetFilters() {
                window.location.href = '{{ route('admin.action-logs.index') }}';
            }

            // Auto-suggest functionality for user search
            let userSearchTimeout;
            const userSearchInput = document.querySelector('input[name="user_search"]');
            const userSuggestions = document.getElementById('user-suggestions');

            if (userSearchInput) {
                userSearchInput.addEventListener('input', function() {
                    clearTimeout(userSearchTimeout);
                    const query = this.value;

                    if (query.length < 2) {
                        userSuggestions.classList.add('hidden');
                        return;
                    }

                    userSearchTimeout = setTimeout(() => {
                        fetch(
                                `{{ route('admin.action-logs.user-suggestions') }}?q=${encodeURIComponent(query)}`
                                )
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    userSuggestions.innerHTML = data.map(user =>
                                        `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer user-suggestion" data-name="${user.name}">
                                        ${user.name}
                                    </div>`
                                    ).join('');
                                    userSuggestions.classList.remove('hidden');
                                } else {
                                    userSuggestions.classList.add('hidden');
                                }
                            });
                    }, 300);
                });

                userSearchInput.addEventListener('blur', function() {
                    setTimeout(() => userSuggestions.classList.add('hidden'), 200);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('user-suggestion')) {
                        userSearchInput.value = e.target.getAttribute('data-name');
                        userSuggestions.classList.add('hidden');
                    }
                });
            }

            // Auto-suggest functionality for action search
            let actionSearchTimeout;
            const actionSearchInput = document.querySelector('input[name="action_search"]');
            const actionSuggestions = document.getElementById('action-suggestions');

            if (actionSearchInput) {
                actionSearchInput.addEventListener('input', function() {
                    clearTimeout(actionSearchTimeout);
                    const query = this.value;

                    if (query.length < 2) {
                        actionSuggestions.classList.add('hidden');
                        return;
                    }

                    actionSearchTimeout = setTimeout(() => {
                        fetch(
                                `{{ route('admin.action-logs.action-suggestions') }}?q=${encodeURIComponent(query)}`
                                )
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    actionSuggestions.innerHTML = data.map(action =>
                                        `<div class="px-4 py-2 hover:bg-gray-100 cursor-pointer action-suggestion" data-action="${action}">
                                        ${action}
                                    </div>`
                                    ).join('');
                                    actionSuggestions.classList.remove('hidden');
                                } else {
                                    actionSuggestions.classList.add('hidden');
                                }
                            });
                    }, 300);
                });

                actionSearchInput.addEventListener('blur', function() {
                    setTimeout(() => actionSuggestions.classList.add('hidden'), 200);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('action-suggestion')) {
                        actionSearchInput.value = e.target.getAttribute('data-action');
                        actionSuggestions.classList.add('hidden');
                    }
                });
            }
        </script>
    @endpush
@endsection
