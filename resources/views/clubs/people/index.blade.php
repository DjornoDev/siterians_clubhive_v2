@section('title', $club->club_name . ' - Members')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="w-full max-w-none mx-auto px-4 sm:px-6 lg:px-8" x-data="editMember()">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $club->club_name }} Members</h1>
        </div>



        <!-- Club Settings Row -->
        @if (auth()->user()->user_id === $club->club_adviser)
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Club Settings</h3>
                            <p class="text-xs text-gray-500">Manage club preferences and policies</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Membership Approval Toggle -->
                        <div class="flex items-center gap-3">
                            <div class="text-sm text-right">
                                <span class="font-medium text-gray-700">Membership Approval</span>
                                <p class="text-xs text-gray-500">Require approval for new members</p>
                            </div>
                            <label class="inline-flex relative items-center cursor-pointer">
                                <input type="checkbox" id="approval-toggle" {{ $club->requires_approval ? 'checked' : '' }}
                                    class="sr-only peer" onchange="toggleApprovalRequirement()">
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Member Management Actions -->
        @if (auth()->user()->user_id === $club->club_adviser)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Member Management</h2>
                            <p class="text-sm text-gray-600">Add new members or export existing member data</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Add Member Button -->
                            <div x-data="memberModal()">
                                <button @click="isModalOpen = true"
                                    class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
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
                                    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl"
                                        @click.outside="isModalOpen = false">
                                        <form @submit.prevent="submitForm" method="POST"
                                            action="{{ route('clubs.members.store', $club) }}">
                                            <div class="p-6">
                                                <h3 class="text-xl font-semibold mb-4">Add Members to {{ $club->club_name }}
                                                </h3>

                                                <!-- Search Input -->
                                                <div class="relative mb-4">
                                                    <div
                                                        class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                                                        <svg class="w-5 h-5 text-gray-500" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" x-model="searchTerm"
                                                        @input.debounce.300ms="searchStudents"
                                                        placeholder="Search students by name or email"
                                                        class="w-full pl-10 pr-10 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <!-- Loading Spinner -->
                                                    <div x-show="isLoading" class="absolute right-3 top-3">
                                                        <svg class="animate-spin h-5 w-5 text-blue-500"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
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
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
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

                            <!-- Export Members Button -->
                            <div x-data="exportModal()">
                                <button @click="isModalOpen = true"
                                    class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Export Members
                                </button>

                                <!-- Export Modal -->
                                <div x-cloak x-show="isModalOpen"
                                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                                    @click.self="isModalOpen = false">
                                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md"
                                        @click.outside="isModalOpen = false">
                                        <div class="p-6">
                                            <h3 class="text-xl font-semibold mb-4">Export Club Members</h3>
                                            <p class="text-sm text-gray-600 mb-6">Choose your preferred export format to
                                                download
                                                the club members data.</p>

                                            <div class="space-y-3">
                                                <a href="{{ route('clubs.export.membership', ['club' => $club, 'format' => 'csv']) }}"
                                                    class="flex items-center justify-between w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 text-green-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="font-medium">CSV Format</span>
                                                    </div>
                                                    <span class="text-sm text-gray-500">.csv</span>
                                                </a>

                                                <a href="{{ route('clubs.export.membership', ['club' => $club, 'format' => 'xlsx']) }}"
                                                    class="flex items-center justify-between w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 text-blue-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="font-medium">Excel Format</span>
                                                    </div>
                                                    <span class="text-sm text-gray-500">.xlsx</span>
                                                </a>

                                                <a href="{{ route('clubs.export.membership', ['club' => $club, 'format' => 'json']) }}"
                                                    class="flex items-center justify-between w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 text-yellow-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="font-medium">JSON Format</span>
                                                    </div>
                                                    <span class="text-sm text-gray-500">.json</span>
                                                </a>

                                                <a href="{{ route('clubs.export.membership', ['club' => $club, 'format' => 'pdf']) }}"
                                                    class="flex items-center justify-between w-full p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-center gap-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-6 w-6 text-red-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        <span class="font-medium">PDF Format</span>
                                                    </div>
                                                    <span class="text-sm text-gray-500">.pdf</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 px-6 py-4 flex justify-end rounded-b-xl">
                                            <button type="button" @click="isModalOpen = false"
                                                class="px-5 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Data Visualization Section (Clean & Simple Design) -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Club Analytics</h2>
                                <p class="text-sm text-gray-600">Overview of membership statistics</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        @php
                            $membersByGrade = $allMembers->groupBy(function ($member) {
                                return $member->section && $member->section->schoolClass
                                    ? $member->section->schoolClass->grade_level
                                    : 'N/A';
                            });

                            $totalMembers = $allMembers->count();
                            $activeMembers = $allMembers->where('status', 'ACTIVE')->count();
                            $inactiveMembers = $allMembers->where('status', 'INACTIVE')->count();
                            $activePercentage =
                                $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0;

                            // Monthly Engagement Data
                            $currentMonth = now()->format('Y-m');
                            $monthlyNewMembers = $allMembers->filter(function ($member) use ($currentMonth) {
                                return $member->joined_date && 
                                       \Carbon\Carbon::parse($member->joined_date)->format('Y-m') === $currentMonth;
                            })->count();
                            
                            $monthlyPosts = \App\Models\Post::where('club_id', $club->club_id)
                                ->whereYear('post_date', now()->year)
                                ->whereMonth('post_date', now()->month)
                                ->count();
                            
                            $monthlyEvents = \App\Models\Event::where('club_id', $club->club_id)
                                ->whereYear('event_date', now()->year)
                                ->whereMonth('event_date', now()->month)
                                ->count();
                            
                            $totalMonthlyEngagement = $monthlyNewMembers + $monthlyPosts + $monthlyEvents;
                        @endphp

                        <!-- Stats Cards Row - Compact Design -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <!-- Member Status Card - Enhanced with Total Members -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900">Member Status</h4>
                                    <div class="p-1.5 bg-green-100 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Chart.js Pie Chart -->
                                <div class="h-48 flex items-center justify-center mb-4">
                                    <canvas id="memberStatusChart"></canvas>
                                </div>

                                <!-- Summary Stats -->
                                <div class="grid grid-cols-2 gap-3 text-center">
                                    <div class="p-2 bg-green-50 rounded-lg">
                                        <div class="text-lg font-bold text-green-600">{{ $activeMembers }}</div>
                                        <div class="text-xs text-gray-600">Active</div>
                                    </div>
                                    <div class="p-2 bg-gray-50 rounded-lg">
                                        <div class="text-lg font-bold text-gray-500">{{ $inactiveMembers }}</div>
                                        <div class="text-xs text-gray-600">Inactive</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Grade Distribution Card - Pie Chart -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900">Grade Distribution</h4>
                                    <div class="p-1.5 bg-purple-100 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                        </svg>
                                    </div>
                                </div>

                                @if ($membersByGrade->count() > 0)
                                    @php
                                        $colors = ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4', '#84CC16'];
                                        $gradeData = [];
                                        $colorIndex = 0;
                                        foreach ($membersByGrade->sortKeys() as $grade => $gradeMembers) {
                                            $percentage = $totalMembers > 0 ? round(($gradeMembers->count() / $totalMembers) * 100, 1) : 0;
                                            $gradeData[] = [
                                                'grade' => $grade,
                                                'count' => $gradeMembers->count(),
                                                'percentage' => $percentage,
                                                'color' => $colors[$colorIndex % count($colors)]
                                            ];
                                            $colorIndex++;
                                        }
                                    @endphp
                                    
                                    <!-- Horizontal Bar Chart -->
                                    <div class="space-y-4 py-2">
                                        @foreach ($gradeData as $data)
                                            <div class="flex items-center justify-between">
                                                <!-- Grade Label -->
                                                <div class="flex items-center w-16">
                                                    <div class="w-4 h-4 rounded-sm mr-2" style="background-color: {{ $data['color'] }}"></div>
                                                    <span class="text-sm font-medium text-gray-700">G{{ $data['grade'] }}</span>
                                                </div>
                                                
                                                <!-- Progress Bar -->
                                                <div class="flex-1 mx-3">
                                                    <div class="w-full bg-gray-200 rounded-lg h-6 relative overflow-hidden">
                                                        <div class="h-6 rounded-lg transition-all duration-1000 flex items-center justify-end pr-3" 
                                                             style="width: {{ $data['percentage'] }}%; background-color: {{ $data['color'] }}">
                                                            @if($data['percentage'] > 20)
                                                                <span class="text-sm font-bold text-white">{{ $data['count'] }}</span>
                                                            @endif
                                                        </div>
                                                        @if($data['percentage'] <= 20)
                                                            <span class="absolute right-3 top-0 h-6 flex items-center text-sm font-bold text-gray-700">{{ $data['count'] }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Percentage -->
                                                <div class="w-12 text-right">
                                                    <span class="text-sm font-bold text-gray-900">{{ $data['percentage'] }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <!-- Bottom Summary -->
                                        <div class="mt-6 pt-4 border-t border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-medium text-gray-600 uppercase tracking-wide">Total Students</span>
                                                <span class="text-xl font-bold text-purple-600">{{ $totalMembers }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-300 mx-auto mb-1"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                        </svg>
                                        <p class="text-xs text-gray-500">No grade data available</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Monthly Engagement Card -->
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-semibold text-gray-900">Monthly Engagement</h4>
                                    <div class="p-1.5 bg-orange-100 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-orange-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Chart.js Line Chart -->
                                <div class="h-72">
                                    <canvas id="engagementChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @endif

        <!-- 3. Filters and Table Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- 3a. Filters Section -->
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Search & Filters</h3>
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

            <!-- 3b. Results Control Bar -->
            <div
                class="px-6 py-4 flex flex-col sm:flex-row justify-between items-center bg-gray-50 border-b border-gray-100">
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

            <!-- 3c. Members Table -->
            <div class="overflow-x-auto">
                @if (auth()->user()->user_id === $club->club_adviser)
                    <!-- Bulk Actions Toolbar (hidden by default) -->
                    <div id="bulk-actions-toolbar" class="hidden bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span id="selected-count" class="text-sm font-medium text-blue-800">0 members
                                    selected</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" id="deselect-all"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    Deselect All
                                </button>
                                <button type="button" id="bulk-delete-btn"
                                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Remove Selected
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            @if (auth()->user()->user_id === $club->club_adviser)
                                <th class="px-4 py-3 text-left w-12">
                                    <input type="checkbox" id="select-all"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                </th>
                            @endif
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">
                                Name
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                Role
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                Position
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                Status
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                Class
                            </th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                Section
                            </th>
                            @if (auth()->user()->user_id === $club->club_adviser)
                                <th
                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Actions
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($members as $member)
                            <tr class="hover:bg-gray-50 transition-colors">
                                @if (auth()->user()->user_id === $club->club_adviser)
                                    <td class="px-4 py-4 whitespace-nowrap w-12">
                                        <input type="checkbox"
                                            class="member-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            value="{{ $member->user_id }}" data-name="{{ $member->name }}">
                                    </td>
                                @endif
                                <td class="px-4 py-4 whitespace-nowrap w-1/4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                            <span class="font-medium text-sm">{{ substr($member->name, 0, 1) }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $member->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $member->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap w-20">
                                    <span
                                        class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                        {{ $member->pivot->club_role === 'Member' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $member->pivot->club_role }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap w-24 text-sm text-gray-900">
                                    {{ $member->pivot->club_position ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap w-20">
                                    @if (auth()->user()->user_id === $club->club_adviser ||
                                            auth()->user()->joinedClubs()->where('tbl_club_membership.club_id', $club->club_id)->first()?->pivot->club_role === 'Officer')
                                        <form action="{{ route('clubs.members.update-status', [$club, $member]) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="updateStatusColor(this); this.form.submit();"
                                                oninput="updateStatusColor(this);"
                                                class="status-select text-xs px-2 py-1 rounded-full border-0 focus:ring-2 focus:ring-blue-500"
                                                data-current-status="{{ $member->status }}">
                                                <option value="ACTIVE" class="bg-green-100 text-green-800"
                                                    {{ $member->status === 'ACTIVE' ? 'selected' : '' }}>Active</option>
                                                <option value="INACTIVE" class="bg-red-100 text-red-800"
                                                    {{ $member->status === 'INACTIVE' ? 'selected' : '' }}>Inactive
                                                </option>
                                            </select>
                                        </form>
                                    @else
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full
                                            {{ $member->status === 'ACTIVE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $member->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap w-20 text-sm text-gray-900">
                                    @if ($member->section && $member->section->schoolClass)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                            Grade {{ $member->section->schoolClass->grade_level }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap w-24 text-sm text-gray-500">
                                    {{ $member->section->section_name ?? 'N/A' }}
                                </td>
                                @if (auth()->user()->user_id === $club->club_adviser)
                                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium w-32">
                                        <div class="flex items-center justify-end space-x-1">
                                            <a href="{{ route('clubs.members.profile', [$club, $member]) }}"
                                                class="text-green-600 hover:text-green-900 transition-colors p-1 rounded hover:bg-green-50"
                                                title="View Profile">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <button @click="openEditModal(@js($member))"
                                                class="text-blue-600 hover:text-blue-900 transition-colors p-1 rounded hover:bg-blue-50"
                                                title="Edit Member">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828zM5 12V7a1 1 0 011-1h9a1 1 0 110 2H7v3a1 1 0 01-1 1H5z" />
                                                </svg>
                                            </button>
                                            <button @click="openRemoveModal(@js($member))"
                                                class="text-red-600 hover:text-red-900 transition-colors p-1 rounded hover:bg-red-50"
                                                title="Remove Member">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->user_id === $club->club_adviser ? '8' : '7' }}"
                                    class="px-4 py-10 text-center text-gray-500">
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
            <!-- Join Requests Section -->
            @if ($joinRequests->count() > 0)
                <div class="bg-white rounded-xl shadow-md overflow-hidden mt-6">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-900">Pending Join Requests</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $joinRequests->count() }} pending request(s)</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Student</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Class</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Section</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Request Date</th>
                                    <th
                                        class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($joinRequests as $request)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center">
                                                    <span
                                                        class="font-medium text-lg">{{ substr($request->user->name, 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $request->user->name }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $request->user->email ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            Grade
                                            {{ $request->user->section && $request->user->section->schoolClass ? $request->user->section->schoolClass->grade_level : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $request->user->section ? $request->user->section->section_name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ $request->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            @if ($request->questionAnswers->count() > 0)
                                                <a href="{{ route('clubs.join-requests.answers', [$club, $request->request_id]) }}"
                                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors inline-block">
                                                    View Answers
                                                </a>
                                            @endif
                                            <button onclick="approveJoinRequest({{ $request->request_id }})"
                                                class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition-colors">
                                                Approve
                                            </button>
                                            <button onclick="rejectJoinRequest({{ $request->request_id }})"
                                                class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition-colors">
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @include('clubs.people.partials.edit-member-modal')

            {{-- Remove Member Confirmation Modal --}}
            <div x-cloak x-show="isRemoveModalOpen"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
                @click.self="isRemoveModalOpen = false">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md" @click.outside="isRemoveModalOpen = false">
                    <form @submit.prevent="confirmRemoveMember" method="POST" :action="removeFormAction">
                        @csrf
                        @method('DELETE')
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div
                                    class="mr-3 flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">Remove Member</h3>
                            </div>
                            <p class="text-gray-600 mb-2">
                                Are you sure you want to remove <strong
                                    x-text="memberToRemove ? memberToRemove.name : ''"></strong> from
                                {{ $club->club_name }}?
                            </p>
                            <p class="text-sm text-gray-500">
                                This action will only remove their membership from this club. Their account, posts, and
                                events will not be deleted. This action cannot be undone.
                            </p>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                            <button type="button" @click="isRemoveModalOpen = false"
                                class="px-5 py-2.5 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Remove Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        // Function to update status select colors dynamically
        function updateStatusColor(selectElement) {
            const selectedValue = selectElement.value;

            // Remove existing color classes
            selectElement.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');

            // Add appropriate color classes based on selected value
            if (selectedValue === 'ACTIVE') {
                selectElement.classList.add('bg-green-100', 'text-green-800');
                selectElement.style.backgroundColor = '#dcfce7'; // green-100
                selectElement.style.color = '#166534'; // green-800
            } else if (selectedValue === 'INACTIVE') {
                selectElement.classList.add('bg-red-100', 'text-red-800');
                selectElement.style.backgroundColor = '#fecaca'; // red-100
                selectElement.style.color = '#991b1b'; // red-800
            }
        }

        // Initialize colors for all status selects on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Update colors for all status select elements
            document.querySelectorAll('.status-select').forEach(function(select) {
                updateStatusColor(select);
            });
        });

        // Class and Section filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class');
            const sectionSelect = document.getElementById('section');

            // Get all sections and classes data from backend
            const allSections = @json(\App\Models\Section::with('schoolClass')->get()->toArray());
            const currentSection = "{{ request('section') }}";

            // Handle class filter change
            classSelect.addEventListener('change', function() {
                const selectedClassId = this.value;
                updateSectionOptions(selectedClassId);
            });

            function updateSectionOptions(classId) {
                // Store current selection
                const currentSelectedSection = sectionSelect.value;

                // Clear current section options
                sectionSelect.innerHTML = '<option value="">All Sections</option>';

                // Filter sections based on selected class
                let filteredSections = allSections;
                if (classId) {
                    filteredSections = allSections.filter(section =>
                        section.class_id == classId
                    );
                }

                // Add filtered sections to dropdown
                filteredSections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.section_id;
                    option.textContent = section.section_name;
                    // Keep the current selection if it's still valid
                    if (currentSelectedSection == section.section_id) {
                        option.selected = true;
                    }
                    sectionSelect.appendChild(option);
                });

                // If the previously selected section is no longer available, clear selection
                if (currentSelectedSection && !filteredSections.some(s => s.section_id == currentSelectedSection)) {
                    sectionSelect.value = '';
                }
            }

            // Initialize sections on page load if a class is already selected
            if (classSelect.value) {
                updateSectionOptions(classSelect.value);
            }
        });

        @if (auth()->user()->user_id === $club->club_adviser)
            // Bulk delete functionality
            document.addEventListener('DOMContentLoaded', function() {
                const selectAllCheckbox = document.getElementById('select-all');
                const memberCheckboxes = document.querySelectorAll('.member-checkbox');
                const bulkActionsToolbar = document.getElementById('bulk-actions-toolbar');
                const selectedCountSpan = document.getElementById('selected-count');
                const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
                const deselectAllBtn = document.getElementById('deselect-all');

                // Select all functionality
                selectAllCheckbox.addEventListener('change', function() {
                    memberCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActionsUI();
                });

                // Individual checkbox change
                memberCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        updateSelectAllState();
                        updateBulkActionsUI();
                    });
                });

                // Deselect all button
                deselectAllBtn.addEventListener('click', function() {
                    selectAllCheckbox.checked = false;
                    memberCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    updateBulkActionsUI();
                });

                // Bulk delete button
                bulkDeleteBtn.addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll('.member-checkbox:checked');
                    const selectedIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                    const selectedNames = Array.from(selectedCheckboxes).map(cb => cb.dataset.name);

                    if (selectedIds.length === 0) return;

                    const confirmMessage = selectedIds.length === 1 ?
                        `Are you sure you want to remove ${selectedNames[0]} from the club?` :
                        `Are you sure you want to remove ${selectedIds.length} members from the club?`;

                    if (confirm(confirmMessage)) {
                        bulkDeleteMembers(selectedIds);
                    }
                });

                function updateSelectAllState() {
                    const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                    const totalCount = memberCheckboxes.length;

                    selectAllCheckbox.checked = checkedCount === totalCount;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
                }

                function updateBulkActionsUI() {
                    const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;

                    if (checkedCount > 0) {
                        bulkActionsToolbar.classList.remove('hidden');
                        selectedCountSpan.textContent =
                            `${checkedCount} member${checkedCount > 1 ? 's' : ''} selected`;
                    } else {
                        bulkActionsToolbar.classList.add('hidden');
                    }
                }

                async function bulkDeleteMembers(memberIds) {
                    try {
                        const response = await fetch(`{{ route('clubs.members.bulk-destroy', $club) }}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                member_ids: memberIds
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            alert(data.message);
                            location.reload(); // Refresh to update the members list
                        } else {
                            const errorData = await response.json();
                            throw new Error(errorData.message || 'Failed to remove members');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while removing members: ' + error.message);
                    }
                }
            });
        @endif

        document.addEventListener('alpine:init', () => {
            Alpine.data('exportModal', () => ({
                isModalOpen: false,
            }));

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
                    permissions: {} // Ensure permissions is an object
                },
                formAction: '',
                isRemoveModalOpen: false, // New state for remove modal
                memberToRemove: null, // New state for member to remove
                removeFormAction: '', // New state for remove form action

                openEditModal(member) {
                    // Corrected: member.pivot.club_accessibility is already an object if available
                    const permissions = member.pivot.club_accessibility || {};
                    this.currentMember = {
                        id: member.user_id,
                        name: member.name,
                        email: member.email,
                        role: member.pivot.club_role,
                        position: member.pivot.club_position || '',
                        permissions: {
                            can_manage_members: permissions.can_manage_members || false,
                            can_create_posts: permissions.can_create_posts || false,
                            manage_posts: permissions.manage_posts || false, // Corrected key
                            can_create_events: permissions.can_create_events || false,
                            manage_events: permissions.manage_events || false, // Corrected key
                            can_view_analytics: permissions.can_view_analytics || false,
                            can_customize_club: permissions.can_customize_club || false,
                        }
                    };
                    this.formAction =
                        `{{ url('clubs/' . $club->club_id . '/members') }}/${member.user_id}`;
                    this.isEditModalOpen = true;
                },

                // New methods for remove modal
                openRemoveModal(member) {
                    this.memberToRemove = member;
                    // Corrected route name to 'clubs.members.destroy'
                    this.removeFormAction =
                        `{{ route('clubs.members.destroy', ['club' => $club->club_id, 'user' => ':userId']) }}`
                        .replace(':userId', member.user_id);
                    this.isRemoveModalOpen = true;
                },

                async confirmRemoveMember() {
                    if (!this.memberToRemove) return;

                    const form = this.$el; // Get the form element
                    try {
                        const response = await fetch(this.removeFormAction, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content'), // Ensure CSRF token is present
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        });

                        if (response.ok) {
                            // Optionally, show a success message
                            this.isRemoveModalOpen = false;
                            this.memberToRemove = null;
                            window.location.reload(); // Reload to see changes
                        } else {
                            const errorData = await response.json();
                            console.error('Failed to remove member:', errorData.message ||
                                'Unknown error');
                            alert('Failed to remove member: ' + (errorData.message ||
                                'Please try again.'));
                        }
                    } catch (error) {
                        console.error('Error removing member:', error);
                        alert(
                            'An error occurred while removing the member. Please check the console for details.'
                        );
                    }
                },

                togglePermissions(value) {
                    // Reset specific permissions if position is cleared, without overwriting others
                    if (!value) {
                        if (this.currentMember && this.currentMember.permissions) {
                            this.currentMember.permissions.manage_posts = false;
                            this.currentMember.permissions.manage_events = false;
                        }
                    }
                    // Add any logic here if certain positions should enable these permissions
                },

                async submitForm(event) {
                    const form = event.target;
                    const formData = new FormData(form);

                    // Explicitly set boolean values for permissions
                    formData.set('manage_posts', this.currentMember.permissions.manage_posts ? '1' :
                        '0');
                    formData.set('manage_events', this.currentMember.permissions.manage_events ?
                        '1' : '0');

                    // Remove position if it's empty to avoid sending an empty string 
                    // if the backend expects it to be null or not present
                    if (!this.currentMember.position) {
                        formData.delete('club_position');
                    } else {
                        formData.set('club_position', this.currentMember.position);
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST', // Method is POST, but we use X-HTTP-Method-Override
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-HTTP-Method-Override': 'PUT', // Laravel uses this to treat POST as PUT
                                'Accept': 'application/json',
                                // 'Content-Type': 'application/x-www-form-urlencoded' or 'multipart/form-data' is set by FormData
                            },
                            body: formData
                        });

                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const error = await response.json();
                            // Display a more specific error if available
                            let errorMessage = 'An error occurred';
                            if (error && error.message) {
                                errorMessage = error.message;
                            }
                            if (error && error.errors) {
                                const firstErrorKey = Object.keys(error.errors)[0];
                                errorMessage += `: ${error.errors[firstErrorKey][0]}`;
                            }
                            alert(errorMessage);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while saving changes. Please check the console.');
                    }
                }
            }));
        });

        // Function to toggle approval requirement
        async function toggleApprovalRequirement() {
            try {
                const response = await fetch(`{{ route('clubs.toggle-approval', $club) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    alert(data.message);
                } else {
                    throw new Error('Failed to update approval requirement');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating approval requirement');
                // Reset the toggle
                document.getElementById('approval-toggle').checked = !document.getElementById('approval-toggle')
                    .checked;
            }
        }

        // Function to approve join request
        async function approveJoinRequest(requestId) {
            try {
                const response = await fetch(
                    `{{ route('clubs.join-requests.approve', ['club' => $club, 'joinRequest' => '__REQUEST_ID__']) }}`
                    .replace('__REQUEST_ID__', requestId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    });

                if (response.ok) {
                    const data = await response.json();
                    alert(data.message);
                    location.reload(); // Refresh to update the requests list
                } else {
                    throw new Error('Failed to approve join request');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while approving the request');
            }
        }

        // Function to reject join request
        async function rejectJoinRequest(requestId) {
            if (!confirm('Are you sure you want to reject this join request?')) {
                return;
            }

            try {
                const response = await fetch(
                    `{{ route('clubs.join-requests.reject', ['club' => $club, 'joinRequest' => '__REQUEST_ID__']) }}`
                    .replace('__REQUEST_ID__', requestId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    });

                if (response.ok) {
                    const data = await response.json();
                    alert(data.message);
                    location.reload(); // Refresh to update the requests list
                } else {
                    throw new Error('Failed to reject join request');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while rejecting the request');
            }
        }
    </script>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Member Status Pie Chart
        const memberStatusCtx = document.getElementById('memberStatusChart').getContext('2d');
        const memberStatusChart = new Chart(memberStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active Members', 'Inactive Members'],
                datasets: [{
                    data: [{{ $activeMembers }}, {{ $inactiveMembers }}],
                    backgroundColor: ['#10B981', '#9CA3AF'],
                    borderWidth: 0,
                    cutout: '60%'
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
                            label: function(context) {
                                const total = {{ $totalMembers }};
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Monthly Engagement Line Chart
        @php
            $months = [];
            $postsData = [];
            $eventsData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');
                
                // Get actual posts count for this month
                $monthPosts = \App\Models\Post::where('club_id', $club->club_id)
                    ->whereYear('post_date', $date->year)
                    ->whereMonth('post_date', $date->month)
                    ->count();
                $postsData[] = $monthPosts;
                
                // Get actual events count for this month
                $monthEvents = \App\Models\Event::where('club_id', $club->club_id)
                    ->where('approval_status', 'approved')
                    ->whereYear('event_date', $date->year)
                    ->whereMonth('event_date', $date->month)
                    ->count();
                $eventsData[] = $monthEvents;
            }
        @endphp

        const engagementCtx = document.getElementById('engagementChart').getContext('2d');
        const engagementChart = new Chart(engagementCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Posts',
                    data: {!! json_encode($postsData) !!},
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }, {
                    label: 'Events',
                    data: {!! json_encode($eventsData) !!},
                    borderColor: '#8B5CF6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    </script>
@endpush
