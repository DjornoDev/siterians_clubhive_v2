@extends('layouts.dashboard')

@section('title', 'Admin Dashboard | ClubHive')

@section('content')
    <!-- Responsive content area -->
    <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-6">
        <!-- Total Clubs -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Clubs</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $clubCount }}</h3>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Total Admins -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Admins</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $userCounts['ADMIN'] ?? 0 }}</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user-cog text-green-500"></i>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Teachers</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $userCounts['TEACHER'] ?? 0 }}</h3>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chalkboard-teacher text-purple-500"></i>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-500 text-sm">Total Students</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $userCounts['STUDENT'] ?? 0 }}</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-user-graduate text-yellow-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content area with responsive design -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left column (2/3 on large screens) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                    <h3 class="font-semibold text-lg text-gray-800">Recent Activities</h3>
                    <button class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                        View All
                    </button>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3 flex-shrink-0">
                                <i class="fas fa-user-plus text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">Science Club</span> added 5 new members
                                </p>
                                <p class="text-xs text-gray-500">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-green-100 p-2 rounded-full mr-3 flex-shrink-0">
                                <i class="fas fa-calendar-plus text-green-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">Chess Club</span> scheduled a new tournament
                                </p>
                                <p class="text-xs text-gray-500">Yesterday at 3:45 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-purple-100 p-2 rounded-full mr-3 flex-shrink-0">
                                <i class="fas fa-file-alt text-purple-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">Drama Club</span> submitted budget proposal
                                </p>
                                <p class="text-xs text-gray-500">Yesterday at 10:30 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="bg-yellow-100 p-2 rounded-full mr-3 flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">System</span> detected unusual login activity
                                </p>
                                <p class="text-xs text-gray-500">2 days ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column (1/3 on large screens) -->
        <div class="space-y-6">
            <!-- Upcoming Events -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 p-4">
                    <h3 class="font-semibold text-lg text-gray-800">Upcoming Events</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3 text-center">
                                <div class="bg-blue-50 rounded-lg p-2 w-12">
                                    <p class="text-xs text-blue-700">MAR</p>
                                    <p class="text-lg font-bold text-blue-700">31</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Science Fair Showcase</h4>
                                <p class="text-xs text-gray-500">10:00 AM - 2:00 PM</p>
                                <p class="text-xs text-gray-600 mt-1">Main Hall</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3 text-center">
                                <div class="bg-green-50 rounded-lg p-2 w-12">
                                    <p class="text-xs text-green-700">APR</p>
                                    <p class="text-lg font-bold text-green-700">02</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Chess Tournament</h4>
                                <p class="text-xs text-gray-500">3:30 PM - 6:00 PM</p>
                                <p class="text-xs text-gray-600 mt-1">Room 103</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0 mr-3 text-center">
                                <div class="bg-purple-50 rounded-lg p-2 w-12">
                                    <p class="text-xs text-purple-700">APR</p>
                                    <p class="text-lg font-bold text-purple-700">05</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Drama Performance</h4>
                                <p class="text-xs text-gray-500">7:00 PM - 9:00 PM</p>
                                <p class="text-xs text-gray-600 mt-1">Auditorium</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="border-b border-gray-200 p-4">
                    <h3 class="font-semibold text-lg text-gray-800">Quick Actions</h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-2 gap-3">
                        <button class="bg-blue-50 hover:bg-blue-100 p-3 rounded-lg text-center transition">
                            <i class="fas fa-plus-circle text-blue-500 text-lg mb-2"></i>
                            <p class="text-xs font-medium text-gray-700">New Club</p>
                        </button>
                        <button class="bg-green-50 hover:bg-green-100 p-3 rounded-lg text-center transition">
                            <i class="fas fa-calendar-plus text-green-500 text-lg mb-2"></i>
                            <p class="text-xs font-medium text-gray-700">Add Event</p>
                        </button>
                        <button class="bg-purple-50 hover:bg-purple-100 p-3 rounded-lg text-center transition">
                            <i class="fas fa-user-plus text-purple-500 text-lg mb-2"></i>
                            <p class="text-xs font-medium text-gray-700">New User</p>
                        </button>
                        <button class="bg-yellow-50 hover:bg-yellow-100 p-3 rounded-lg text-center transition">
                            <i class="fas fa-pencil text-yellow-500 text-lg mb-2"></i>
                            <p class="text-xs font-medium text-gray-700">Post</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
