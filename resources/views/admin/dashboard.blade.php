@extends('layouts.dashboard')

@section('title', 'Admin Dashboard | ClubHive')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Statistics Summary -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Dashboard Overview</h1>
                    <p class="text-blue-100 mt-1">Welcome back, {{ auth()->user()->name }}</p>
                </div>
                <div class="text-sm bg-white/20 py-2 px-4 rounded-lg backdrop-blur-sm">
                    <span class="opacity-80">Last updated:</span>
                    <span class="font-medium ml-1">{{ now()->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Cards with consistent styling matching other pages -->
        <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-6">
            <!-- Total Clubs -->
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Clubs</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $clubCount }}</h3>
                        <div class="flex items-center mt-1 text-sm">
                            @if ($clubTrend > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i> {{ $clubTrend }}%
                                </span>
                            @elseif ($clubTrend < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i> {{ abs($clubTrend) }}%
                                </span>
                            @else
                                <span class="text-gray-500 flex items-center">
                                    <i class="fas fa-equals mr-1"></i> 0%
                                </span>
                            @endif
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Events -->
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Events</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $eventStatistics['total'] ?? 0 }}</h3>
                        <div class="flex items-center mt-1 text-sm">
                            <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full text-xs">
                                {{ $eventStatistics['upcoming'] ?? 0 }} upcoming
                            </span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <i class="fas fa-calendar text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Teachers</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $userCounts['TEACHER'] ?? 0 }}</h3>
                        <div class="flex items-center mt-1 text-sm">
                            @if ($userTrends['TEACHER'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i> {{ $userTrends['TEACHER'] }}%
                                </span>
                            @elseif ($userTrends['TEACHER'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i> {{ abs($userTrends['TEACHER']) }}%
                                </span>
                            @else
                                <span class="text-gray-500 flex items-center">
                                    <i class="fas fa-equals mr-1"></i> 0%
                                </span>
                            @endif
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $userCounts['STUDENT'] ?? 0 }}</h3>
                        <div class="flex items-center mt-1 text-sm">
                            @if ($userTrends['STUDENT'] > 0)
                                <span class="text-green-500 flex items-center">
                                    <i class="fas fa-arrow-up mr-1"></i> {{ $userTrends['STUDENT'] }}%
                                </span>
                            @elseif ($userTrends['STUDENT'] < 0)
                                <span class="text-red-500 flex items-center">
                                    <i class="fas fa-arrow-down mr-1"></i> {{ abs($userTrends['STUDENT']) }}%
                                </span>
                            @else
                                <span class="text-gray-500 flex items-center">
                                    <i class="fas fa-equals mr-1"></i> 0%
                                </span>
                            @endif
                            <span class="text-gray-500 ml-1">from last month</span>
                        </div>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <i class="fas fa-user-graduate text-yellow-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area with Quick Actions and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- LEFT SIDE: Quick Actions -->
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Quick Actions</h2>
                    <div class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        <i class="fas fa-lightbulb mr-1 text-yellow-500"></i> Frequently used tools
                    </div>
                </div>
                <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center space-x-3 border border-gray-100">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 rounded-full text-white shadow-sm">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Add User</h3>
                            <p class="text-sm text-gray-500">Create new account</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.clubs.index') }}"
                        class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center space-x-3 border border-gray-100">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-full text-white shadow-sm">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Create Club</h3>
                            <p class="text-sm text-gray-500">Add new club</p>
                        </div>
                    </a>

                    <a href="#" onclick="toggleClubHuntingDay(event)"
                        class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center space-x-3 border border-gray-100">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 rounded-full text-white shadow-sm">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Club Hunting Day</h3>
                            <p class="text-sm text-gray-500" id="clubHuntingStatus">
                                {{ $clubHuntingDay ? 'Currently Active' : 'Currently Inactive' }}</p>
                        </div>
                    </a>

                    <a href="#" onclick="document.getElementById('csvUploadModal').classList.remove('hidden')"
                        class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center space-x-3 border border-gray-100">
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-full text-white shadow-sm">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Bulk Upload</h3>
                            <p class="text-sm text-gray-500">Import users via CSV</p>
                        </div>
                    </a> <!-- Announcements Button Removed -->

                    <a href="#" onclick="document.getElementById('exportDataModal').classList.remove('hidden')"
                        class="bg-white rounded-xl shadow-md p-5 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 flex items-center space-x-3 border border-gray-100">
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-3 rounded-full text-white shadow-sm">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Export Data</h3>
                            <p class="text-sm text-gray-500">Download reports</p>
                        </div>
                    </a>
                </div>

                <!-- Upcoming Events Section -->
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Upcoming Events</h2>
                        <a href="{{ route('admin.events.index') }}"
                            class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                            View all <i class="fas fa-chevron-right ml-1"></i>
                        </a>
                    </div>
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        @if ($eventStatistics['upcoming'] > 0)
                            <div class="space-y-4">
                                @php
                                    $upcomingEvents = \App\Models\Event::with(['club', 'organizer'])
                                        ->where('event_date', '>', today())
                                        ->orderBy('event_date')
                                        ->take(5)
                                        ->get();
                                @endphp

                                @foreach ($upcomingEvents as $event)
                                    <div
                                        class="flex items-start border-l-4 border-blue-500 pl-4 py-3 hover:bg-blue-50 rounded-r-lg transition-all">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-800">{{ $event->event_name }}</h3>
                                            <div class="flex items-center text-sm text-gray-500 mt-1">
                                                <span
                                                    class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium mr-2">
                                                    {{ $event->event_date->format('M d, Y') }}
                                                </span>
                                                @if ($event->event_time)
                                                    <span class="mr-2">{{ $event->event_time }}</span>
                                                @endif
                                                <span class="flex items-center">
                                                    <i class="fas fa-users text-xs mr-1 text-gray-400"></i>
                                                    {{ $event->club->club_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event->event_visibility === 'PUBLIC' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ $event->event_visibility === 'PUBLIC' ? 'Public' : 'Club Only' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="bg-blue-50 p-3 rounded-full mb-3">
                                    <i class="fas fa-calendar-alt text-blue-500 text-xl"></i>
                                </div>
                                <p class="text-gray-500 mb-1">No upcoming events found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Recent Activities -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Recent Activities</h2>
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        Last 10 activities
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 h-full border border-gray-100">
                    @if (count($recentActivities ?? []) > 0)
                        <div class="space-y-5">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-start">
                                    <div
                                        class="bg-gradient-to-br from-blue-100 to-blue-200 p-3 rounded-full mr-4 shadow-sm">
                                        <i class="fas {{ $activity['icon'] }} text-blue-600"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm">
                                            <span class="font-medium text-gray-800">{{ $activity['user'] }}</span>
                                            <span class="text-gray-600">{{ $activity['action'] }}</span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center">
                                            <i class="fas fa-clock mr-1 text-gray-400"></i>
                                            {{ $activity['time'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="bg-gray-100 p-3 rounded-full mb-3">
                                <i class="fas fa-history text-gray-500 text-xl"></i>
                            </div>
                            <p class="text-gray-500">No recent activities found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- CSV Upload Modal -->
        <div id="csvUploadModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 border border-gray-200 transform transition-all">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-yellow-100 p-2 rounded-full mr-3">
                            <i class="fas fa-file-import text-yellow-600"></i>
                        </span>
                        Bulk Upload Users
                    </h3>
                    <button onclick="document.getElementById('csvUploadModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-full p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.bulk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="users_file" class="block text-sm font-medium text-gray-700 mb-1">Upload CSV
                            File</label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors">
                            <input type="file" name="users_file" id="users_file" accept=".csv"
                                class="w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium
                                file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 focus:outline-none">
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                CSV Format: name, email, role, password, class_id, section_id
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('csvUploadModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg text-sm font-medium text-white hover:from-blue-700 hover:to-blue-800 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-upload mr-1"></i> Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Export Data Modal -->
        <div id="exportDataModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden backdrop-blur-sm transition-all duration-300">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 border border-gray-200 transform transition-all">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center">
                        <span class="bg-indigo-100 p-2 rounded-full mr-3">
                            <i class="fas fa-file-export text-indigo-600"></i>
                        </span>
                        Export Data
                    </h3>
                    <button onclick="document.getElementById('exportDataModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-full p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <div
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-colors duration-200 group">
                        <button onclick="exportData('users')" class="w-full flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-3 rounded-full mr-3 group-hover:bg-blue-200 transition-colors">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-medium text-gray-800">Users Report</h4>
                                    <p class="text-sm text-gray-500">Export all users data</p>
                                </div>
                            </div>
                            <i class="fas fa-download text-gray-400 group-hover:text-blue-500 transition-colors"></i>
                        </button>
                    </div>
                    <div
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-green-300 transition-colors duration-200 group">
                        <button onclick="exportData('clubs')" class="w-full flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-3 rounded-full mr-3 group-hover:bg-green-200 transition-colors">
                                    <i class="fas fa-users-cog text-green-600"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-medium text-gray-800">Clubs Report</h4>
                                    <p class="text-sm text-gray-500">Export clubs and memberships</p>
                                </div>
                            </div>
                            <i class="fas fa-download text-gray-400 group-hover:text-green-500 transition-colors"></i>
                        </button>
                    </div>
                    <div
                        class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 hover:border-purple-300 transition-colors duration-200 group">
                        <button onclick="exportData('events')" class="w-full flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="bg-purple-100 p-3 rounded-full mr-3 group-hover:bg-purple-200 transition-colors">
                                    <i class="fas fa-calendar-alt text-purple-600"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-medium text-gray-800">Events Report</h4>
                                    <p class="text-sm text-gray-500">Export events data</p>
                                </div>
                            </div>
                            <i class="fas fa-download text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                function toggleClubHuntingDay(e) {
                    e.preventDefault();

                    // Show a simple confirmation dialog
                    if (!confirm('Are you sure you want to toggle the Club Hunting Day status?')) {
                        return;
                    }

                    // Get CSRF token from meta tag
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    // Assumes the first club (ID 1) is the SSG club with hunting day control
                    fetch('/clubs/1/toggle-hunting-day', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            // Add empty body for POST request
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 403) {
                                    throw new Error('Permission denied. Only administrators can toggle Club Hunting Day.');
                                }
                                throw new Error('Request failed with status: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.status === 'success') {
                                const statusElement = document.getElementById('clubHuntingStatus');
                                const currentStatus = statusElement.textContent.includes('Active');
                                statusElement.textContent = currentStatus ? 'Currently Inactive' : 'Currently Active';

                                // Show a notification
                                alert('Club Hunting Day status has been updated!');
                            } else {
                                alert('Error: ' + (data.message || 'Unknown error occurred'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(error.message || 'An error occurred while toggling Club Hunting Day status.');
                        });
                } // Send Announcement Function Removed

                function exportData(type) {
                    window.location.href = `/admin/export/${type}`;
                }
            </script>
        @endpush
    </div>
@endsection
