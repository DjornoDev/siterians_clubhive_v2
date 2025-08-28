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

        <!-- Quick Actions Section - Moved to Top -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Quick Actions</h2>
                    <p class="text-sm text-gray-600 mt-1">Frequently used tools and shortcuts</p>
                </div>
                <div class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    <i class="fas fa-lightbulb mr-1 text-yellow-500"></i> Shortcuts
                </div>
            </div>
            <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
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
                </a>

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
        </div>

        <!-- Main Statistics Cards -->
        {{-- <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">System Statistics</h2>
                    <p class="text-sm text-gray-600 mt-1">Overview of your platform's key metrics</p>
                </div>
            </div>
            <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 mb-6">
                <!-- Total Clubs -->
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500 xl:col-span-2">
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
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500 xl:col-span-2">
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
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500 xl:col-span-2">
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
                <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500 xl:col-span-2">
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
        </div> --}}

        <!-- Quick Analytics Summary -->
        <div class="bg-white rounded-lg shadow-sm p-5 mb-6 border border-gray-200">
            <!-- Header with responsive layout -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Quick Analytics</h3>
                <!-- Time Filter -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                        <div class="relative">
                            <select id="chartTimeFilter" onchange="updateCharts()"
                                class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-10 text-sm font-medium text-gray-700 shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 w-full sm:w-auto min-w-[140px] cursor-pointer">
                                <option value="6">Last 6 months</option>
                                <option value="8" selected>Last 8 months</option>
                                <option value="12">Last 12 months</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ array_sum($userCounts) }}</div>
                    <div class="text-sm text-gray-600">Total Users</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600 mb-1">{{ $eventStatistics['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Total Events</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600 mb-1">{{ $postStatistics['total'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Total Posts</div>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600 mb-1">
                        @php
                            $totalMemberships = \DB::table('tbl_club_membership')->count();
                        @endphp
                        {{ $totalMemberships }}
                    </div>
                    <div class="text-sm text-gray-600">Club Memberships</div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
            <!-- User Distribution Chart -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">User Distribution</h3>
                    <div class="bg-blue-50 p-2 rounded-full">
                        <i class="fas fa-users text-blue-600"></i>
                    </div>
                </div>
                <div class="relative h-48 flex items-center justify-center">
                    <canvas id="userDistributionChart"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap justify-center gap-3 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Students ({{ $userCounts['STUDENT'] ?? 0 }})</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Teachers ({{ $userCounts['TEACHER'] ?? 0 }})</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Admins ({{ $userCounts['ADMIN'] ?? 0 }})</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Active Users -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Monthly Active Users</h3>
                    <div class="bg-green-50 p-2 rounded-full">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                </div>
                <div class="relative h-48">
                    <canvas id="growthTrendsChart"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap justify-center gap-3 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Unique users per month</span>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500 text-center">
                    Based on actual user actions: posts, club activities, events, and system logs
                </div>
            </div>

            <!-- Activity Overview -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300 lg:col-span-2 xl:col-span-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Weekly Activity</h3>
                    <div class="bg-yellow-50 p-2 rounded-full">
                        <i class="fas fa-chart-bar text-yellow-600"></i>
                    </div>
                </div>
                <div class="relative h-48">
                    <canvas id="activityOverviewChart"></canvas>
                </div>
                <div class="mt-4 flex flex-wrap justify-center gap-3 text-sm">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Posts</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">Events</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">New Members</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                        <span class="text-gray-600">New Users</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Club Performance Chart -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <!-- Top Clubs by Members -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Top Clubs by Members</h3>
                    <div class="bg-indigo-50 p-2 rounded-full">
                        <i class="fas fa-trophy text-indigo-600"></i>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="topClubsChart"></canvas>
                </div>
            </div>

            <!-- Event Timeline -->
            <div
                class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Events This Month</h3>
                    <div class="bg-pink-50 p-2 rounded-full">
                        <i class="fas fa-calendar-check text-pink-600"></i>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="eventsTimelineChart"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-500">Event creation dates for {{ now()->format('F Y') }}</span>
                    @php
                        $totalEventsThisMonth = \App\Models\Event::whereMonth('created_at', now()->month)
                            ->whereYear('created_at', now()->year)
                            ->count();
                    @endphp
                    <div class="mt-2">
                        <span class="text-lg font-semibold text-gray-800">{{ $totalEventsThisMonth }}</span>
                        <span class="text-sm text-gray-500 ml-1">events created this month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events and Recent Activities Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- LEFT SIDE: Upcoming Events -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Upcoming Events</h2>
                                <p class="text-sm text-gray-600 mt-1">Events scheduled for the coming days</p>
                            </div>
                            <a href="{{ route('admin.events.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors flex items-center">
                                View all <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        @if ($eventStatistics['upcoming'] > 0)
                            @php
                                $upcomingEvents = \App\Models\Event::with(['club', 'organizer'])
                                    ->where('event_date', '>', today())
                                    ->orderBy('event_date')
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if ($upcomingEvents->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($upcomingEvents as $event)
                                        <div
                                            class="flex items-center justify-between p-4 bg-gray-50 hover:bg-blue-50 rounded-lg border border-gray-100 hover:border-blue-200 transition-all duration-200">
                                            <div class="flex items-center space-x-4 flex-1 min-w-0">
                                                <div class="bg-blue-100 p-2.5 rounded-full">
                                                    <i class="fas fa-calendar text-blue-600"></i>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="font-medium text-gray-800 truncate">
                                                        {{ $event->event_name }}
                                                    </h4>
                                                    <p class="text-sm text-gray-600 truncate">
                                                        {{ $event->club->club_name ?? 'No Club' }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right ml-4 flex-shrink-0">
                                                <div class="text-sm font-medium text-gray-800">
                                                    {{ $event->event_date ? $event->event_date->format('M d, Y') : 'No Date' }}
                                                </div>
                                                @if ($event->event_time)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        {{ $event->event_time }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div
                                        class="bg-blue-50 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                        <i class="fas fa-calendar text-blue-500 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800 mb-2">No Upcoming Events</h3>
                                    <p class="text-gray-600">Events will appear here once scheduled.</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="bg-blue-50 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-calendar text-blue-500 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">No Upcoming Events</h3>
                                <p class="text-gray-600">Events will appear here once scheduled.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE: Recent Activities -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full">
                    <!-- Header -->
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Recent Activities</h2>
                                <p class="text-sm text-gray-600 mt-1">Latest system activities</p>
                            </div>
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full font-medium">
                                Live
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        @if (count($recentActivities ?? []) > 0)
                            <div class="space-y-4">
                                @foreach ($recentActivities as $activity)
                                    <div
                                        class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                                        <div class="bg-blue-100 p-2 rounded-full flex-shrink-0">
                                            <i class="fas {{ $activity['icon'] ?? 'fa-bell' }} text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 leading-relaxed">
                                                <span
                                                    class="font-medium text-blue-700">{{ $activity['user'] ?? 'Unknown User' }}</span>
                                                <span
                                                    class="text-gray-600">{{ $activity['action'] ?? 'performed an action' }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $activity['time'] ?? 'Recently' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div
                                    class="bg-blue-50 p-4 rounded-full w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-history text-blue-500 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-2">No Recent Activities</h3>
                                <p class="text-gray-600 text-center">Activities will appear here as users interact with the
                                    system.</p>
                            </div>
                        @endif
                    </div>
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
                        <button onclick="showFormatModal('users')" class="w-full flex items-center justify-between">
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
                        <button onclick="showFormatModal('clubs')" class="w-full flex items-center justify-between">
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
                        <button onclick="showFormatModal('action-logs')" class="w-full flex items-center justify-between">
                            <div class="flex items-center">
                                <div
                                    class="bg-purple-100 p-3 rounded-full mr-3 group-hover:bg-purple-200 transition-colors">
                                    <i class="fas fa-calendar-alt text-purple-600"></i>
                                </div>
                                <div class="text-left">
                                    <h4 class="font-medium text-gray-800">Action Logs Report</h4>
                                    <p class="text-sm text-gray-500">Export system activity logs</p>
                                </div>
                            </div>
                            <i class="fas fa-download text-gray-400 group-hover:text-purple-500 transition-colors"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Format Selection Modal -->
        <div id="formatSelectionModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <span class="bg-blue-100 p-2 rounded-full mr-2">
                            <i class="fas fa-file-export text-blue-600"></i>
                        </span>
                        Select Export Format
                    </h3>
                    <button onclick="closeFormatModal()"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-full p-1">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="space-y-3">
                    <button onclick="exportData('csv')"
                        class="w-full p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors duration-200 group text-left">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-3 rounded-full mr-3 group-hover:bg-green-200 transition-colors">
                                <i class="fas fa-file-csv text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">CSV Format</h4>
                                <p class="text-sm text-gray-500">Comma-separated values for spreadsheets</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="exportData('xlsx')"
                        class="w-full p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-colors duration-200 group text-left">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-3 rounded-full mr-3 group-hover:bg-blue-200 transition-colors">
                                <i class="fas fa-file-excel text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">Excel Format (.xlsx)</h4>
                                <p class="text-sm text-gray-500">Microsoft Excel workbook</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="exportData('pdf')"
                        class="w-full p-4 border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-colors duration-200 group text-left">
                        <div class="flex items-center">
                            <div class="bg-red-100 p-3 rounded-full mr-3 group-hover:bg-red-200 transition-colors">
                                <i class="fas fa-file-pdf text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">PDF Format</h4>
                                <p class="text-sm text-gray-500">Portable document with formatting</p>
                            </div>
                        </div>
                    </button>

                    <button onclick="exportData('json')"
                        class="w-full p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition-colors duration-200 group text-left">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-3 rounded-full mr-3 group-hover:bg-purple-200 transition-colors">
                                <i class="fas fa-code text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">JSON Format</h4>
                                <p class="text-sm text-gray-500">JavaScript Object Notation for APIs</p>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
            <!-- Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // Chart.js default configuration
                Chart.defaults.font.family = 'Inter, sans-serif';
                Chart.defaults.color = '#6B7280';
                Chart.defaults.plugins.legend.display = false;

                // User Distribution Pie Chart
                const userDistributionCtx = document.getElementById('userDistributionChart').getContext('2d');
                new Chart(userDistributionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Students', 'Teachers', 'Admins'],
                        datasets: [{
                            data: [
                                {{ $userCounts['STUDENT'] ?? 0 }},
                                {{ $userCounts['TEACHER'] ?? 0 }},
                                {{ $userCounts['ADMIN'] ?? 0 }}
                            ],
                            backgroundColor: ['#3B82F6', '#8B5CF6', '#6366F1'],
                            borderWidth: 2,
                            borderColor: '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        cutout: '50%'
                    }
                });

                // Monthly Active Users Line Chart
                const growthTrendsCtx = document.getElementById('growthTrendsChart').getContext('2d');
                @php
                    // Get monthly active users data - unique users who logged in or used the system per month
                    $months = [];
                    $monthlyActiveUsers = [];

                    for ($i = 7; $i >= 0; $i--) {
                        $date = now()->subMonths($i);
                        $months[] = $date->format('M');

                        // Count unique users who were active in this month using REAL data sources
                        // Primary: action_logs table (actual user actions)
                        // Fallback: posts, club memberships, events (user activities)
                        $activeUsersFromLogs = \App\Models\ActionLog::whereBetween('created_at', [$date->startOfMonth()->copy(), $date->endOfMonth()->copy()])
                            ->whereNotNull('user_id')
                            ->distinct('user_id')
                            ->pluck('user_id');

                        $activeUsersFromPosts = \App\Models\User::whereHas('posts', function ($q) use ($date) {
                            $q->whereBetween('created_at', [$date->startOfMonth()->copy(), $date->endOfMonth()->copy()]);
                        })->pluck('user_id');

                        $activeUsersFromMemberships = \App\Models\User::whereHas('clubMemberships', function ($q) use ($date) {
                            $q->whereBetween('created_at', [$date->startOfMonth()->copy(), $date->endOfMonth()->copy()]);
                        })->pluck('user_id');

                        $activeUsersFromEvents = \App\Models\User::whereHas('organizedEvents', function ($q) use ($date) {
                            $q->whereBetween('created_at', [$date->startOfMonth()->copy(), $date->endOfMonth()->copy()]);
                        })->pluck('user_id');

                        // Combine all unique user IDs and count them
                        $allActiveUserIds = $activeUsersFromLogs->concat($activeUsersFromPosts)->concat($activeUsersFromMemberships)->concat($activeUsersFromEvents)->unique();

                        $activeUsersThisMonth = $allActiveUserIds->count();

                        // Debug: Log the breakdown for this month (remove in production)
                        // \Log::info("Month: " . $date->format('M Y') . " - Logs: " . $activeUsersFromLogs->count() . ", Posts: " . $activeUsersFromPosts->count() . ", Memberships: " . $activeUsersFromMemberships->count() . ", Events: " . $activeUsersFromEvents->count() . ", Total Unique: " . $activeUsersThisMonth);

                        $monthlyActiveUsers[] = $activeUsersThisMonth;
                    }
                @endphp

                // Store chart data globally for filtering
                window.originalChartData = {
                    months: {!! json_encode($months) !!},
                    activeUsers: [{{ implode(',', $monthlyActiveUsers) }}]
                };

                window.growthChart = new Chart(growthTrendsCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($months) !!},
                        datasets: [{
                            label: 'Active Users',
                            data: [{{ implode(',', $monthlyActiveUsers) }}],
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label + ' - Active users';
                                    },
                                    label: function(context) {
                                        return context.dataset.label + ': ' + context.parsed.y + ' users';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                },
                                title: {
                                    display: true,
                                    text: 'Active users per month'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Filter function for charts
                function updateCharts() {
                    const months = parseInt(document.getElementById('chartTimeFilter').value);

                    // Update Monthly Active Users Chart with filtered data
                    const filteredMonths = window.originalChartData.months.slice(-months);
                    const filteredActiveUsers = window.originalChartData.activeUsers.slice(-months);

                    window.growthChart.data.labels = filteredMonths;
                    window.growthChart.data.datasets[0].data = filteredActiveUsers;
                    window.growthChart.update();
                }



                // Activity Overview Bar Chart - Real Data from Last 7 Days
                const activityOverviewCtx = document.getElementById('activityOverviewChart').getContext('2d');
                @php
                    // Get real activity data for the last 7 days
                    $weeklyActivityData = [];
                    $dayLabels = [];

                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i);
                        $dayLabels[] = $date->format('D'); // Mon, Tue, Wed, etc.

                        // Count posts created on this day
                        $postsCount = \App\Models\Post::whereDate('created_at', $date->format('Y-m-d'))->count();

                        // Count events created on this day
                        $eventsCount = \App\Models\Event::whereDate('created_at', $date->format('Y-m-d'))->count();

                        // Count new club memberships on this day
                        $membershipsCount = \DB::table('tbl_club_membership')->whereDate('created_at', $date->format('Y-m-d'))->count();

                        // Count new user registrations on this day
                        $usersCount = \App\Models\User::whereDate('created_at', $date->format('Y-m-d'))->count();

                        $weeklyActivityData[] = [
                            'posts' => $postsCount,
                            'events' => $eventsCount,
                            'memberships' => $membershipsCount,
                            'users' => $usersCount,
                        ];
                    }
                @endphp
                new Chart(activityOverviewCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($dayLabels) !!},
                        datasets: [{
                                label: 'Posts',
                                data: [
                                    @foreach ($weeklyActivityData as $data)
                                        {{ $data['posts'] }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                ],
                                backgroundColor: '#3B82F6',
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'Events',
                                data: [
                                    @foreach ($weeklyActivityData as $data)
                                        {{ $data['events'] }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                ],
                                backgroundColor: '#10B981',
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'New Members',
                                data: [
                                    @foreach ($weeklyActivityData as $data)
                                        {{ $data['memberships'] }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                ],
                                backgroundColor: '#8B5CF6',
                                borderRadius: 4,
                                borderSkipped: false,
                            },
                            {
                                label: 'New Users',
                                data: [
                                    @foreach ($weeklyActivityData as $data)
                                        {{ $data['users'] }}{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                ],
                                backgroundColor: '#F59E0B',
                                borderRadius: 4,
                                borderSkipped: false,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        const dayIndex = tooltipItems[0].dataIndex;
                                        const date = new Date();
                                        date.setDate(date.getDate() - (6 - dayIndex));
                                        return date.toLocaleDateString('en-US', {
                                            weekday: 'long',
                                            month: 'short',
                                            day: 'numeric'
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                },
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Top Clubs Horizontal Bar Chart
                const topClubsCtx = document.getElementById('topClubsChart').getContext('2d');
                @php
                    // Get top 5 clubs by member count
                    $topClubs = \App\Models\Club::withCount('members')->orderByDesc('members_count')->take(5)->get();

                    // Fallback data if no clubs exist
                    if ($topClubs->isEmpty()) {
                        $topClubs = collect([(object) ['club_name' => 'No clubs yet', 'members_count' => 0]]);
                    }
                @endphp
                new Chart(topClubsCtx, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach ($topClubs as $club)
                                '{{ Str::limit($club->club_name, 20) }}'
                                {{ !$loop->last ? ',' : '' }}
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Members',
                            data: [
                                @foreach ($topClubs as $club)
                                    {{ $club->members_count }}{{ !$loop->last ? ',' : '' }}
                                @endforeach
                            ],
                            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Events Timeline Chart - Real Data for Current Month
                const eventsTimelineCtx = document.getElementById('eventsTimelineChart').getContext('2d');
                @php
                    // Get event creation data for each day of current month
                    $daysInMonth = now()->daysInMonth;
                    $eventCreationByDay = [];
                    $monthDays = [];

                    for ($i = 1; $i <= $daysInMonth; $i++) {
                        $date = now()->day($i);
                        $monthDays[] = $i;

                        // Count events CREATED on this day this month (not scheduled for this day)
                        $count = \App\Models\Event::whereDate('created_at', $date->format('Y-m-d'))->count();
                        $eventCreationByDay[] = $count;
                    }
                @endphp
                new Chart(eventsTimelineCtx, {
                    type: 'line',
                    data: {
                        labels: [{{ implode(',', $monthDays) }}],
                        datasets: [{
                            label: 'Events Created',
                            data: [{{ implode(',', $eventCreationByDay) }}],
                            borderColor: '#EC4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#EC4899',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(tooltipItems) {
                                        const day = tooltipItems[0].label;
                                        const date = new Date();
                                        date.setDate(day);
                                        return date.toLocaleDateString('en-US', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric'
                                        });
                                    },
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        return value === 1 ? '1 event created' : `${value} events created`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#F3F4F6'
                                },
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Day of Month'
                                }
                            }
                        }
                    }
                });

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

                let currentExportType = '';

                function showFormatModal(type) {
                    currentExportType = type;
                    document.getElementById('exportDataModal').classList.add('hidden');
                    document.getElementById('formatSelectionModal').classList.remove('hidden');
                }

                function closeFormatModal() {
                    document.getElementById('formatSelectionModal').classList.add('hidden');
                    currentExportType = '';
                }

                function exportData(format) {
                    if (!currentExportType) {
                        alert('Please select an export type first.');
                        return;
                    }

                    // Store the export type before closing modal
                    const exportType = currentExportType;

                    closeFormatModal();

                    // Show loading indicator
                    const loadingToast = document.createElement('div');
                    loadingToast.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                    loadingToast.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing export...';
                    document.body.appendChild(loadingToast);

                    // Build correct URL based on export type
                    let exportUrl = '';
                    switch (exportType) {
                        case 'users':
                            exportUrl = `/admin/export/users?format=${format}`;
                            break;
                        case 'clubs':
                            exportUrl = `/admin/export/clubs?format=${format}`;
                            break;
                        case 'action-logs':
                            exportUrl = `/admin/export/action-logs?format=${format}`;
                            break;
                        default:
                            alert('Invalid export type: ' + exportType);
                            return;
                    }

                    // Redirect to export with format
                    window.location.href = exportUrl;

                    // Remove loading indicator after a delay
                    setTimeout(() => {
                        if (loadingToast.parentNode) {
                            loadingToast.parentNode.removeChild(loadingToast);
                        }
                    }, 3000);
                }
            </script>
        @endpush
    </div>
@endsection
