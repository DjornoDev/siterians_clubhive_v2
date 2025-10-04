@extends('layouts.dashboard')

@section('title', 'Manage Users | ClubHive')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Page Header with gradient background -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 mb-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">User Management</h1>
                    <p class="text-blue-100 mt-1">Manage users, roles and permissions</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="toggleUserModal()"
                        class="bg-white text-blue-700 px-5 py-2.5 rounded-lg hover:bg-blue-50 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Add User
                    </button>
                    <button onclick="toggleBulkModal()"
                        class="bg-white/20 backdrop-blur-sm text-white px-5 py-2.5 rounded-lg hover:bg-white/30 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Bulk Upload
                    </button>
                    <button onclick="toggleBulkDeleteModal()" id="bulkDeleteBtn"
                        class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 transition-all duration-200 font-medium shadow-lg flex items-center gap-2 hidden animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Delete Selected (<span id="selectedCount" class="font-bold">0</span>)
                    </button>
                    <a href="{{ route('admin.users.export', request()->query()) }}"
                        class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition-all duration-200 font-medium shadow-sm flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Export Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Users</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $users->total() }}</h3>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Admins</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">
                            {{ App\Models\User::where('role', 'ADMIN')->count() }}</h3>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Teachers</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">
                            {{ App\Models\User::where('role', 'TEACHER')->count() }}</h3>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path
                                d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Students</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">
                            {{ App\Models\User::where('role', 'STUDENT')->count() }}</h3>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-md mb-6">
            <!-- Filters -->
            <div class="mb-6">
                <form action="{{ route('admin.users.index') }}" method="GET"
                    class="flex flex-col md:flex-row gap-5 items-end">
                    <div class="w-full md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search User</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Name or email..."
                                value="{{ request('search') }}"
                                class="w-full pl-10 px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        </div>
                    </div>
                    <div class="w-full md:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                        <select name="role" id="filterRoleSelect"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%20%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.5rem_center] bg-no-repeat pr-10">
                            <option value="">All Roles</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="TEACHER" {{ request('role') === 'TEACHER' ? 'selected' : '' }}>Teacher</option>
                            <option value="STUDENT" {{ request('role') === 'STUDENT' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48" id="classFilterContainer">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                        <select name="class_id" id="filterClassId"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%20%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.5rem_center] bg-no-repeat pr-10">
                            <option value="">All Classes</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}"
                                    {{ request('class_id') == $class->class_id ? 'selected' : '' }}>Grade
                                    {{ $class->grade_level }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-48" id="sectionFilterContainer">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                        <select name="section_id" id="filterSectionId"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%20%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.5rem_center] bg-no-repeat pr-10">
                            <option value="">All Sections</option>
                            @isset($sections)
                                @foreach ($sections as $section)
                                    <option value="{{ $section->section_id }}"
                                        {{ request('section_id') == $section->section_id ? 'selected' : '' }}>
                                        {{ $section->section_name }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-5 py-2.5 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-200 w-full md:w-auto font-medium shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                    clip-rule="evenodd" />
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                            class="bg-white text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-50 transition-all duration-200 w-full md:w-auto text-center font-medium border border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            @if (session('bulk_errors'))
                <div class="mb-6 bg-red-50 p-4 rounded-lg border border-red-200 animate-pulse">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-red-800">Bulk Upload Errors:</h4>
                                <button type="button" onclick="toggleInstructionsModal()" 
                                    class="px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 flex items-center">
                                    <i class="fas fa-list mr-1"></i>
                                    View Available Classes
                                </button>
                            </div>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach (session('bulk_errors') as $row => $messages)
                                    <li><strong>{{ $row }}:</strong> {{ implode(', ', $messages) }}</li>
                                @endforeach
                            </ul>
                            <p class="text-xs text-red-600 mt-2 italic">
                                <i class="fas fa-info-circle mr-1"></i>
                                Click "View Available Classes" to see the exact class and section names to use.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Table - Enhanced -->
            <div class="overflow-hidden rounded-xl border border-gray-200 shadow-md bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <input type="checkbox" id="selectAllUsers"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 hover:border-blue-400 transition-colors">
                                        <span class="text-xs mt-1">Select All</span>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Name</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Email</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Role</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Class/Section</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100"
                                    data-user-id="{{ $user->user_id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="selectedUsers[]" value="{{ $user->user_id }}"
                                            class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500 hover:border-blue-400 transition-colors"
                                            data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 flex-shrink-0 mr-3">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-lg">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                            </div>
                                            <div class="text-sm font-medium text-gray-800">{{ $user->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @php
                                            $emailParts = explode('@', $user->email);
                                            $maskedLocal = substr($emailParts[0], 0, 3) . '*****';
                                            echo $maskedLocal . '@' . $emailParts[1];
                                        @endphp
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium shadow-sm
                                        @switch($user->role)
                                            @case('ADMIN') bg-purple-100 text-purple-800 border border-purple-200 @break
                                            @case('TEACHER') bg-blue-100 text-blue-800 border border-blue-200 @break
                                            @case('STUDENT') bg-emerald-100 text-emerald-800 border border-emerald-200 @break
                                        @endswitch">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if ($user->section)
                                            <div class="flex flex-col">
                                                <span class="font-medium">Grade
                                                    {{ $user->section->schoolClass->grade_level ?? 'N/A' }}</span>
                                                <span
                                                    class="text-xs text-gray-500">{{ $user->section->section_name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <button
                                                onclick="openViewModal('{{ $user->user_id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors duration-150 flex items-center gap-1 border border-indigo-200 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <button
                                                onclick="openEditModal('{{ $user->user_id }}', '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->sex ?? '' }}', '{{ addslashes($user->address ?? '') }}', '{{ $user->contact_no ?? '' }}', '{{ addslashes($user->mother_name ?? '') }}', '{{ $user->mother_contact_no ?? '' }}', '{{ addslashes($user->father_name ?? '') }}', '{{ $user->father_contact_no ?? '' }}', '{{ addslashes($user->guardian_name ?? '') }}', '{{ $user->guardian_contact_no ?? '' }}', '{{ $user->section->class_id ?? '' }}', '{{ $user->section_id ?? '' }}')"
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-150 flex items-center gap-1 border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $user->user_id }}', '{{ $user->name }}', '{{ $user->email }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors duration-150 flex items-center gap-1 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-gray-600">No users found matching your criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <div class="pagination-wrapper">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div> <!-- Add User Modal -->
            @include('admin.users.partials.add-user-modal')

            {{-- Add this modal partial include at the bottom near other modals --}}
            <!-- Bulk Upload Modal -->
            @include('admin.users.partials.bulk-upload-modal')

            <!-- Instructions Modal -->
            @include('admin.users.partials.instructions-modal')

            <!-- Edit User Modal -->
            @include('admin.users.partials.edit-user-modal')

            <!-- Delete User Modal -->
            @include('admin.users.partials.delete-user-modal')

            <!-- Add Section Modal -->
            @include('admin.users.partials.add-section-modal')

            <!-- Password Verification Modal -->
            @include('admin.users.partials.password-verification-modal')

            <!-- View Password Verification Modal -->
            @include('admin.users.partials.view-password-verification-modal')

            <!-- Edit Password Verification Modal -->
            @include('admin.users.partials.edit-password-verification-modal')

            <!-- User View Modal -->
            @include('admin.users.partials.view-user-modal')

            <!-- Bulk Delete Modal -->
            <div id="bulkDeleteModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto shadow-lg">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Bulk Delete Users</h3>
                        <button onclick="closeBulkDeleteModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Warning</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>You are about to permanently delete <strong id="bulkDeleteCount">0</strong>
                                            user(s). This action cannot be undone.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 mb-3">Selected users for deletion:</p>
                        <div id="bulkDeleteUserList"
                            class="max-h-32 overflow-y-auto bg-gray-50 p-3 rounded text-sm border border-gray-200">
                            <!-- User list will be populated here -->
                        </div>
                    </div>

                    <form id="bulkDeleteForm" class="space-y-4">
                        <div>
                            <label for="bulkDeletePassword" class="block text-sm font-medium text-gray-700 mb-2">
                                Enter your password to confirm deletion
                            </label>
                            <input type="password" id="bulkDeletePassword" name="password" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Your password">
                        </div>

                        <div id="bulkDeleteError" class="hidden text-sm text-red-600 bg-red-50 p-3 rounded"></div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeBulkDeleteModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit" id="bulkDeleteSubmitBtn"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete Users
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- User Success Modal -->
            <div id="userSuccessModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div
                    class="bg-white rounded-lg p-6 w-full max-w-sm mx-auto shadow-lg transform transition-all duration-300 opacity-0 translate-y-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">User Added Successfully</h3>
                        <p class="mt-2 text-sm text-gray-500">The new user has been added to the system.</p>
                    </div>
                </div>
            </div>

            <!-- Section Success Modal -->
            <div id="sectionSuccessModal"
                class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div
                    class="bg-white rounded-lg p-6 w-full max-w-sm mx-auto shadow-lg transform transition-all duration-300 opacity-0 translate-y-4">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Section Added Successfully</h3>
                        <p class="mt-2 text-sm text-gray-500">The new section has been added to the system.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            /**
             * Admin Users Management JavaScript
             * 
             * This script handles all user management functionality including:
             * - User CRUD operations (Create, Read, Update, Delete)
             * - Password verification for sensitive operations
             * - Modal management and state handling
             * - Dynamic form validation and submission
             * - Performance optimizations with DOM element caching
             * 
             * @author ClubHive Development Team
             * @version 2.0
             * @since 2024
             */

            // ========================================
            // UTILITY FUNCTIONS
            // ========================================

            /**
             * Logs messages only in development environment
             * @param {string} message - The message to log
             * @param {*} data - Optional data to log with the message
             */
            function devLog(message, data = null) {
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    if (data) {
                        console.log(message, data);
                    } else {
                        console.log(message);
                    }
                }
            }

            /**
             * Logs errors only in development environment
             * @param {string} message - The error message to log
             * @param {*} error - Optional error object to log with the message
             */
            function devError(message, error = null) {
                if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                    if (error) {
                        console.error(message, error);
                        devLog(message, error);
                    } else {
                        console.error(message);
                        devLog(message);
                    }
                }
            }

            // User Modal Interactions
            function toggleUserModal() {
                document.getElementById('addUserModal').classList.toggle('hidden');
            }

            // Add to your existing JavaScript
            // Bulk Upload Modal Interactions
            function toggleBulkModal() {
                document.getElementById('bulkUploadModal').classList.toggle('hidden');
            }

            function toggleSectionModal() {
                document.getElementById('addSectionModal').classList.toggle('hidden');
            }

            // Dynamic Class/Section Handling
            document.getElementById('roleSelect').addEventListener('change', function() {
                const classSection = document.getElementById('classSection');
                classSection.classList.toggle('hidden', this.value !== 'STUDENT');
            });

            // Dynamic Section Loading
            document.getElementById('class_id').addEventListener('change', function() {
                const classId = this.value;
                if (!classId) return;

                fetch(`/get-sections/${classId}`)
                    .then(response => response.json())
                    .then(sections => {
                        const sectionSelect = document.getElementById('section_id');
                        sectionSelect.innerHTML = '<option value="">Select Section</option>';
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            sectionSelect.appendChild(option);
                        });
                    });
            });

            // Filter section dropdown based on class selection
            document.getElementById('filterClassId').addEventListener('change', function() {
                const classId = this.value;
                const sectionSelect = document.getElementById('filterSectionId');

                // Clear current sections
                sectionSelect.innerHTML = '<option value="">All Sections</option>';

                if (!classId) return;

                // Fetch sections for the selected class
                fetch(`/get-sections/${classId}`)
                    .then(response => response.json())
                    .then(sections => {
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            sectionSelect.appendChild(option);
                        });
                    });
            });

            // Hide/show class and section filters based on role selection
            document.getElementById('filterRoleSelect').addEventListener('change', function() {
                const selectedRole = this.value;
                const classFilterContainer = document.getElementById('classFilterContainer');
                const sectionFilterContainer = document.getElementById('sectionFilterContainer');
                
                if (selectedRole === 'TEACHER' || selectedRole === 'ADMIN') {
                    // Hide class and section filters for TEACHER and ADMIN
                    classFilterContainer.style.display = 'none';
                    sectionFilterContainer.style.display = 'none';
                    
                    // Reset the filter values
                    document.getElementById('filterClassId').value = '';
                    document.getElementById('filterSectionId').value = '';
                } else {
                    // Show class and section filters for STUDENT or All Roles
                    classFilterContainer.style.display = 'block';
                    sectionFilterContainer.style.display = 'block';
                }
            });

            // Initialize filter visibility on page load
            document.addEventListener('DOMContentLoaded', function() {
                const roleSelect = document.getElementById('filterRoleSelect');
                const selectedRole = roleSelect.value;
                const classFilterContainer = document.getElementById('classFilterContainer');
                const sectionFilterContainer = document.getElementById('sectionFilterContainer');
                
                if (selectedRole === 'TEACHER' || selectedRole === 'ADMIN') {
                    classFilterContainer.style.display = 'none';
                    sectionFilterContainer.style.display = 'none';
                } else {
                    classFilterContainer.style.display = 'block';
                    sectionFilterContainer.style.display = 'block';
                }
            });

            // ========================================
            // DOM INITIALIZATION & EVENT HANDLERS
            // ========================================

            // Cache frequently used DOM elements for better performance
            let cachedElements = {};

            /**
             * Gets or caches a DOM element to reduce DOM queries
             * @param {string} elementId - The ID of the element to get
             * @returns {HTMLElement|null} The cached element or null if not found
             */
            function getCachedElement(elementId) {
                if (!cachedElements[elementId]) {
                    cachedElements[elementId] = document.getElementById(elementId);
                }
                return cachedElements[elementId];
            }

            /**
             * Clears the element cache (useful when DOM changes)
             */
            function clearElementCache() {
                cachedElements = {};
            }

            // Load sections on page load if class is already selected
            document.addEventListener('DOMContentLoaded', function() {
                const classId = getCachedElement('filterClassId')?.value;
                if (classId) {
                    // Set timeout to ensure the DOM is fully loaded
                    setTimeout(() => {
                        getCachedElement('filterClassId')?.dispatchEvent(new Event('change'));

                        // Set the correct section if it was previously selected
                        const urlParams = new URLSearchParams(window.location.search);
                        const sectionId = urlParams.get('section_id');

                        if (sectionId) {
                            setTimeout(() => {
                                const sectionElement = getCachedElement('filterSectionId');
                                if (sectionElement) {
                                    sectionElement.value = sectionId;
                                }
                            }, 300);
                        }
                    }, 100);
                }

                // Ensure modals are properly initialized
                const userViewModal = getCachedElement('userViewModal');
                const viewPasswordVerificationModal = getCachedElement('viewPasswordVerificationModal');

                if (userViewModal) {
                    // Ensure modal is hidden by default
                    userViewModal.classList.add('hidden');
                    userViewModal.style.display = 'none';
                }

                if (viewPasswordVerificationModal) {
                    // Ensure modal is hidden by default
                    viewPasswordVerificationModal.classList.add('hidden');
                }

                // Initialize bulk delete functionality
                initializeBulkDelete();
            });

            /**
             * Initializes bulk delete functionality
             */
            function initializeBulkDelete() {
                // Select all checkbox functionality
                const selectAllCheckbox = document.getElementById('selectAllUsers');
                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        const userCheckboxes = document.querySelectorAll('.user-checkbox');
                        userCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                        updateBulkDeleteButton();
                    });
                }

                // Individual checkbox change handlers
                document.addEventListener('change', function(e) {
                    if (e.target.classList.contains('user-checkbox')) {
                        updateBulkDeleteButton();
                        updateSelectAllCheckbox();
                    }
                });

                // Bulk delete form submission
                const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                if (bulkDeleteForm) {
                    bulkDeleteForm.addEventListener('submit', handleBulkDelete);
                }

                // Keyboard shortcuts
                document.addEventListener('keydown', function(e) {
                    // Ctrl/Cmd + A to select all (only when no modal is open)
                    if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                        // Check if any modal is currently open
                        const addModal = document.getElementById('addUserModal');
                        const editModal = document.getElementById('editUserModal');
                        const bulkDeleteModal = document.getElementById('bulkDeleteModal');
                        const bulkUploadModal = document.getElementById('bulkUploadModal');
                        const deleteUserModal = document.getElementById('deleteUserModal');
                        const viewModal = document.getElementById('userViewModal');
                        const addSectionModal = document.getElementById('addSectionModal');
                        const viewPasswordVerificationModal = document.getElementById('viewPasswordVerificationModal');
                        const editPasswordVerificationModal = document.getElementById('editPasswordVerificationModal');
                        const passwordVerificationModal = document.getElementById('passwordVerificationModal');
                        
                        const isModalOpen = (addModal && !addModal.classList.contains('hidden')) ||
                                          (editModal && !editModal.classList.contains('hidden')) ||
                                          (bulkDeleteModal && !bulkDeleteModal.classList.contains('hidden')) ||
                                          (bulkUploadModal && !bulkUploadModal.classList.contains('hidden')) ||
                                          (deleteUserModal && !deleteUserModal.classList.contains('hidden')) ||
                                          (viewModal && !viewModal.classList.contains('hidden')) ||
                                          (addSectionModal && !addSectionModal.classList.contains('hidden')) ||
                                          (viewPasswordVerificationModal && !viewPasswordVerificationModal.classList.contains('hidden')) ||
                                          (editPasswordVerificationModal && !editPasswordVerificationModal.classList.contains('hidden')) ||
                                          (passwordVerificationModal && !passwordVerificationModal.classList.contains('hidden'));
                        
                        // Only prevent default and trigger select all if no modal is open
                        if (!isModalOpen) {
                            e.preventDefault();
                            const selectAllCheckbox = document.getElementById('selectAllUsers');
                            if (selectAllCheckbox) {
                                selectAllCheckbox.checked = !selectAllCheckbox.checked;
                                selectAllCheckbox.dispatchEvent(new Event('change'));
                            }
                        }
                        // If modal is open, let the browser handle Ctrl+A normally (for text selection)
                    }

                    // Escape key to close any open modal
                    if (e.key === 'Escape') {
                        // Check and close each modal if it's open
                        const bulkDeleteModal = document.getElementById('bulkDeleteModal');
                        const bulkUploadModal = document.getElementById('bulkUploadModal');
                        const deleteUserModal = document.getElementById('deleteUserModal');
                        const addModal = document.getElementById('addUserModal');
                        const editModal = document.getElementById('editUserModal');
                        const viewModal = document.getElementById('userViewModal');
                        const addSectionModal = document.getElementById('addSectionModal');
                        const viewPasswordVerificationModal = document.getElementById('viewPasswordVerificationModal');
                        const editPasswordVerificationModal = document.getElementById('editPasswordVerificationModal');
                        const passwordVerificationModal = document.getElementById('passwordVerificationModal');

                        if (bulkDeleteModal && !bulkDeleteModal.classList.contains('hidden')) {
                            closeBulkDeleteModal();
                        } else if (bulkUploadModal && !bulkUploadModal.classList.contains('hidden')) {
                            toggleBulkModal();
                        } else if (deleteUserModal && !deleteUserModal.classList.contains('hidden')) {
                            closeDeleteModal();
                        } else if (addModal && !addModal.classList.contains('hidden')) {
                            toggleUserModal();
                        } else if (editModal && !editModal.classList.contains('hidden')) {
                            closeEditModal();
                        } else if (viewModal && !viewModal.classList.contains('hidden')) {
                            closeViewModal();
                        } else if (addSectionModal && !addSectionModal.classList.contains('hidden')) {
                            toggleSectionModal();
                        } else if (viewPasswordVerificationModal && !viewPasswordVerificationModal.classList.contains('hidden')) {
                            closeViewPasswordModal();
                        } else if (editPasswordVerificationModal && !editPasswordVerificationModal.classList.contains('hidden')) {
                            closeEditPasswordVerificationModal();
                        } else if (passwordVerificationModal && !passwordVerificationModal.classList.contains('hidden')) {
                            closePasswordModal();
                        }
                    }
                });

                // Add click-outside functionality to all modals
                function addClickOutsideListener(modalId, closeFunction) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.addEventListener('click', function(e) {
                            // Only close if clicking on the modal backdrop (not the modal content)
                            if (e.target === modal) {
                                closeFunction();
                            }
                        });
                    }
                }

                // Apply click-outside to all modals
                addClickOutsideListener('addUserModal', toggleUserModal);
                addClickOutsideListener('editUserModal', closeEditModal);
                addClickOutsideListener('userViewModal', closeViewModal);
                addClickOutsideListener('addSectionModal', toggleSectionModal);
                addClickOutsideListener('bulkDeleteModal', closeBulkDeleteModal);
                addClickOutsideListener('bulkUploadModal', toggleBulkModal);
                addClickOutsideListener('deleteUserModal', closeDeleteModal);
                addClickOutsideListener('viewPasswordVerificationModal', closeViewPasswordModal);
                addClickOutsideListener('editPasswordVerificationModal', closeEditPasswordVerificationModal);
                addClickOutsideListener('passwordVerificationModal', closePasswordModal);
            }

            /**
             * Updates the bulk delete button visibility and count
             */
            function updateBulkDeleteButton() {
                const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
                const selectedCount = document.getElementById('selectedCount');

                if (bulkDeleteBtn && selectedCount) {
                    const count = selectedCheckboxes.length;
                    selectedCount.textContent = count;

                    if (count > 0) {
                        bulkDeleteBtn.classList.remove('hidden');
                    } else {
                        bulkDeleteBtn.classList.add('hidden');
                    }
                }
            }

            /**
             * Updates the select all checkbox state
             */
            function updateSelectAllCheckbox() {
                const selectAllCheckbox = document.getElementById('selectAllUsers');
                const userCheckboxes = document.querySelectorAll('.user-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked');

                if (selectAllCheckbox) {
                    if (checkedCheckboxes.length === 0) {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = false;
                    } else if (checkedCheckboxes.length === userCheckboxes.length) {
                        selectAllCheckbox.checked = true;
                        selectAllCheckbox.indeterminate = false;
                    } else {
                        selectAllCheckbox.checked = false;
                        selectAllCheckbox.indeterminate = true;
                    }
                }
            }

            /**
             * Handles bulk delete form submission
             */
            async function handleBulkDelete(e) {
                e.preventDefault();

                const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                const password = document.getElementById('bulkDeletePassword').value;
                const errorElement = document.getElementById('bulkDeleteError');
                const submitBtn = document.getElementById('bulkDeleteSubmitBtn');

                if (selectedCheckboxes.length === 0) {
                    showBulkDeleteError('No users selected for deletion.');
                    return;
                }

                if (!password.trim()) {
                    showBulkDeleteError('Please enter your password to confirm deletion.');
                    return;
                }

                // Final confirmation
                const confirmMessage =
                    `Are you absolutely sure you want to delete ${selectedCheckboxes.length} user(s)? This action cannot be undone.`;
                if (!confirm(confirmMessage)) {
                    return;
                }

                // Disable submit button and show loading state
                submitBtn.disabled = true;
                submitBtn.textContent = 'Deleting...';

                try {
                    const userIds = Array.from(selectedCheckboxes).map(cb => cb.value);

                    const response = await fetch('/admin/users/bulk-delete', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            user_ids: userIds,
                            password: password
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Remove deleted users from the table
                        userIds.forEach(userId => {
                            const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                            if (row) {
                                row.remove();
                            }
                        });

                        // Reset checkboxes and close modal
                        document.querySelectorAll('.user-checkbox:checked').forEach(cb => cb.checked = false);
                        updateBulkDeleteButton();
                        closeBulkDeleteModal();

                        // Show success message
                        showBulkDeleteSuccess(`Successfully deleted ${userIds.length} user(s).`);

                        // Refresh page after a short delay to update counts
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showBulkDeleteError(data.error || 'Failed to delete users. Please try again.');
                    }
                } catch (error) {
                    devError('Bulk delete error:', error);
                    showBulkDeleteError('An error occurred while deleting users. Please try again.');
                } finally {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Delete Users';
                }
            }

            /**
             * Shows error message in bulk delete modal
             */
            function showBulkDeleteError(message) {
                const errorElement = document.getElementById('bulkDeleteError');
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            }

            /**
             * Shows success message for bulk delete
             */
            function showBulkDeleteSuccess(message) {
                // Create a temporary success message
                const successDiv = document.createElement('div');
                successDiv.className =
                    'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full';
                successDiv.innerHTML = `
                     <div class="flex items-center">
                         <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                         </svg>
                         ${message}
                     </div>
                 `;
                document.body.appendChild(successDiv);

                // Animate in
                setTimeout(() => {
                    successDiv.classList.remove('translate-x-full');
                }, 100);

                // Remove after 4 seconds with animation
                setTimeout(() => {
                    successDiv.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (successDiv.parentNode) {
                            successDiv.parentNode.removeChild(successDiv);
                        }
                    }, 300);
                }, 4000);
            }

            // Add this helper function
            function fetchSections(classId) {
                if (!classId) return;

                fetch(`/get-sections/${classId}`)
                    .then(response => response.json())
                    .then(sections => {
                        const sectionSelect = document.getElementById('section_id');
                        sectionSelect.innerHTML = '<option value="">Select Section</option>';
                        sections.forEach(section => {
                            const option = new Option(section.section_name, section.section_id);
                            sectionSelect.add(option);
                        });
                    });
            }

            // Add to your existing JavaScript
            document.getElementById('addSectionForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toggleSectionModal(); // Close section modal
                            showSectionSuccessModal(); // Show section success

                            // Refresh sections dropdown in Add User modal
                            const classSelect = document.getElementById('class_id');
                            if (classSelect.value) {
                                classSelect.dispatchEvent(new Event('change'));
                            }
                        } else {
                            // Handle validation errors
                            devError('Validation errors:', data.errors);
                        }
                    })
                    .catch(error => {
                        devError('Error:', error);
                    });
            });

            // Success modal display functions
            function showUserSuccessModal() {
                const modal = document.getElementById('userSuccessModal');
                showModal(modal);
            }

            function showSectionSuccessModal() {
                const modal = document.getElementById('sectionSuccessModal');
                showModal(modal);
            }

            function showModal(modal) {
                const content = modal.querySelector('.bg-white');
                modal.classList.remove('hidden');
                setTimeout(() => content.classList.remove('opacity-0', 'translate-y-4'), 10);
                setTimeout(() => modal.classList.add('hidden'), 3000);
            }

            // Edit Modal Functions
            function openEditModal(userId, name, email, role, sex, address, contactNo, motherName, motherContactNo, fatherName,
                fatherContactNo, guardianName, guardianContactNo, classId, sectionId) {
                const form = document.getElementById('editUserForm');
                form.action = `/admin/users/${userId}`;

                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editRole').value = role;
                document.getElementById('editSex').value = sex;
                document.getElementById('editAddress').value = address;
                document.getElementById('editContactNo').value = contactNo;
                document.getElementById('editMotherName').value = motherName;
                document.getElementById('editMotherContactNo').value = motherContactNo;
                document.getElementById('editFatherName').value = fatherName;
                document.getElementById('editFatherContactNo').value = fatherContactNo;
                document.getElementById('editGuardianName').value = guardianName;
                document.getElementById('editGuardianContactNo').value = guardianContactNo;

                const classSection = document.getElementById('editClassSection');
                if (role === 'STUDENT') {
                    classSection.classList.remove('hidden');
                    document.getElementById('editClassId').value = classId;
                    const event = new Event('change');
                    document.getElementById('editClassId').dispatchEvent(event);
                    setTimeout(() => {
                        document.getElementById('editSectionId').value = sectionId;
                    }, 500);
                } else {
                    classSection.classList.add('hidden');
                }

                document.getElementById('editUserModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editUserModal').classList.add('hidden');
            }

            // Delete Modal Functions
            function openDeleteModal(userId, userName, userEmail) {
                // Set the form action
                document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;

                // Set the user ID in the hidden field
                document.getElementById('userId').value = userId;

                // Set the user details in the modal
                document.getElementById('deleteUserName').textContent = userName;
                document.getElementById('deleteUserEmail').textContent = userEmail;

                // Show the modal
                document.getElementById('deleteUserModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteUserModal').classList.add('hidden');
            }

            function showPasswordModal() {
                document.getElementById('passwordVerificationModal').classList.remove('hidden');
            }

            function closePasswordModal() {
                document.getElementById('passwordVerificationModal').classList.add('hidden');
                // Clear password input when closing modal
                document.getElementById('passwordInput').value = '';
                // Clear any error message
                const errorElement = document.getElementById('generalPasswordError');
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
                // Remove any error styling
                document.getElementById('passwordInput').classList.remove('border-red-500');
            }

            function showSuccessModal() {
                const modal = document.getElementById('deleteSuccessModal');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 2000);
            }

            function closeSuccessModal() {
                const modal = document.getElementById('deleteSuccessModal');
                modal.classList.add('hidden');
                // Optional: Refresh the page to update the user list
                window.location.reload();
            }

            // Handle password verification
            document.getElementById('verifyPasswordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = document.getElementById('passwordInput').value;
                const deleteForm = document.getElementById('deleteUserForm');
                const userId = document.getElementById('userId').value;
                const errorElement = document.getElementById('generalPasswordError');

                // Hide any previous error
                errorElement.classList.add('hidden');
                errorElement.textContent = '';

                fetch(deleteForm.action, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: password
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeDeleteModal();
                            closePasswordModal();
                            showSuccessModal();
                            // Remove user row from table
                            document.querySelector(`tr[data-user-id="${userId}"]`).remove();
                        } else {
                            // Show error message in the form
                            errorElement.textContent = data.error || 'Password verification failed';
                            errorElement.classList.remove('hidden');
                            // Optional: Add a shake animation to the password input
                            document.getElementById('passwordInput').classList.add('border-red-500');
                        }
                    })
                    .catch(error => {
                        // Log error for debugging (only in development)
                        devError('Error:', error);

                        errorElement.textContent = 'An error occurred. Please try again.';
                        errorElement.classList.remove('hidden');
                    });
            });

            // Toggle Class Section in Edit Modal
            function toggleEditClassSection() {
                const roleSelect = document.getElementById('editRole');
                const classSection = document.getElementById('editClassSection');
                classSection.classList.toggle('hidden', roleSelect.value !== 'STUDENT');
            }

            // Load Sections for Edit Modal
            document.getElementById('editClassId').addEventListener('change', function() {
                const classId = this.value;
                if (!classId) return;

                fetch(`/get-sections/${classId}`)
                    .then(response => response.json())
                    .then(sections => {
                        const sectionSelect = document.getElementById('editSectionId');
                        sectionSelect.innerHTML = '<option value="">Select Section</option>';
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.section_id;
                            option.textContent = section.section_name;
                            sectionSelect.appendChild(option);
                        });
                    });
            });

            // ========================================
            // BULK DELETE FUNCTIONS
            // ========================================

            /**
             * Toggles the bulk delete modal
             */
            function toggleBulkDeleteModal() {
                const modal = document.getElementById('bulkDeleteModal');
                if (modal) {
                    modal.classList.toggle('hidden');
                    if (!modal.classList.contains('hidden')) {
                        populateBulkDeleteModal();
                    }
                }
            }

            /**
             * Closes the bulk delete modal
             */
            function closeBulkDeleteModal() {
                const modal = document.getElementById('bulkDeleteModal');
                if (modal) {
                    modal.classList.add('hidden');
                    // Clear form
                    document.getElementById('bulkDeleteForm').reset();
                    document.getElementById('bulkDeleteError').classList.add('hidden');
                }
            }

            /**
             * Populates the bulk delete modal with selected users
             */
            function populateBulkDeleteModal() {
                const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
                const count = selectedCheckboxes.length;

                document.getElementById('bulkDeleteCount').textContent = count;

                const userList = document.getElementById('bulkDeleteUserList');
                userList.innerHTML = '';

                selectedCheckboxes.forEach(checkbox => {
                    const userName = checkbox.getAttribute('data-user-name');
                    const userEmail = checkbox.getAttribute('data-user-email');

                    const userItem = document.createElement('div');
                    userItem.className =
                        'flex justify-between items-center py-1 border-b border-gray-200 last:border-b-0';
                    userItem.innerHTML = `
                         <span class="font-medium">${userName}</span>
                         <span class="text-gray-500 text-xs">${userEmail}</span>
                     `;
                    userList.appendChild(userItem);
                });
            }

            // ========================================
            // VIEW MODAL FUNCTIONS
            // ========================================

            /**
             * Opens the password verification modal for viewing user details
             * @param {string} userId - The user ID to view
             * @param {string} userName - The user's name
             * @param {string} userEmail - The user's email
             * @param {string} userRole - The user's role
             */
            function openViewModal(userId, userName, userEmail, userRole) {
                // Store the user ID for later
                document.getElementById('viewUserId').value = userId;

                // Reset any previous state
                const errorElement = document.getElementById('viewPasswordError');
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }

                // Clear password input
                const passwordInput = document.getElementById('viewPasswordInput');
                if (passwordInput) {
                    passwordInput.value = '';
                    passwordInput.classList.remove('border-red-500');
                }

                // Show password verification modal
                document.getElementById('viewPasswordVerificationModal').classList.remove('hidden');
            }

            function closeViewModal() {
                const userViewModal = document.getElementById('userViewModal');
                if (userViewModal) {
                    userViewModal.classList.add('hidden');
                    // Reset display style
                    userViewModal.style.display = 'none';
                }

                // Reset any dynamic content to prevent stale data
                const resetElements = [
                    'userName', 'userEmail', 'userCreated', 'userUpdated',
                    'userCredName', 'userCredEmail', 'userAvatar', 'userRoleBadge',
                    'userGradeLevel', 'userSection', 'userPostsCount', 'userEventsCount',
                    'userMotherName', 'userMotherContact', 'userFatherName', 'userFatherContact',
                    'userGuardianName', 'userGuardianContact'
                ];

                resetElements.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.textContent = '';
                    }
                });
            }

            function closeViewPasswordModal() {
                document.getElementById('viewPasswordVerificationModal').classList.add('hidden');
                // Clear password input when closing modal
                document.getElementById('viewPasswordInput').value = '';
                // Clear any error message
                const errorElement = document.getElementById('viewPasswordError');
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
                // Remove any error styling
                document.getElementById('viewPasswordInput').classList.remove('border-red-500');
            }

            /**
             * Displays user details in the view modal
             * @param {Object} userData - The user data object containing all user information
             */
            function showUserDetails(userData) {
                try {
                    // Check if the main modal exists
                    const userViewModal = document.getElementById('userViewModal');
                    if (!userViewModal) {
                        throw new Error('User view modal not found');
                    }

                    // Ensure the modal is in the DOM and accessible
                    if (!document.body.contains(userViewModal)) {
                        throw new Error('User view modal is not accessible');
                    }

                    // Check if document is ready
                    if (document.readyState !== 'complete') {
                        // Only retry once to avoid infinite loops
                        setTimeout(() => {
                            if (document.readyState === 'complete') {
                                showUserDetails(userData);
                            } else {
                                throw new Error('Document not ready after timeout');
                            }
                        }, 200);
                        return;
                    }

                    // Check if modal is accessible
                    const testElement = userViewModal.querySelector('#userName');
                    if (!testElement) {
                        throw new Error('Modal content not accessible');
                    }

                    // Fill in user basic details
                    const userName = document.getElementById('userName');
                    const userEmail = document.getElementById('userEmail');
                    const userCreated = document.getElementById('userCreated');
                    const userUpdated = document.getElementById('userUpdated');

                    if (userName) userName.textContent = userData.name || 'N/A';
                    if (userEmail) userEmail.textContent = userData.email || 'N/A';
                    if (userCreated) userCreated.textContent = formatDate(userData.created_at);
                    if (userUpdated) userUpdated.textContent = formatDate(userData.updated_at);

                    // Fill in user credentials section
                    const userCredName = document.getElementById('userCredName');
                    const userCredEmail = document.getElementById('userCredEmail');
                    const userCredContactNo = document.getElementById('userCredContactNo');

                    if (userCredName) userCredName.textContent = userData.name || 'N/A';
                    if (userCredEmail) userCredEmail.textContent = userData.email || 'N/A';
                    if (userCredContactNo) userCredContactNo.textContent = userData.contact_no || 'N/A';

                    // Set avatar initial
                    const avatar = document.getElementById('userAvatar');
                    if (avatar && userData.name) {
                        avatar.textContent = userData.name.charAt(0).toUpperCase();
                    }

                    // Set role badge
                    const roleBadge = document.getElementById('userRoleBadge');
                    if (roleBadge) {
                        let badgeClass = '';
                        switch (userData.role) {
                            case 'ADMIN':
                                badgeClass = 'bg-purple-100 text-purple-800 border border-purple-200';
                                break;
                            case 'TEACHER':
                                badgeClass = 'bg-blue-100 text-blue-800 border border-blue-200';
                                break;
                            case 'STUDENT':
                                badgeClass = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                                break;
                            default:
                                badgeClass = 'bg-gray-100 text-gray-800 border border-gray-200';
                        }
                        roleBadge.innerHTML =
                            `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium shadow-sm ${badgeClass}">${userData.role || 'N/A'}</span>`;
                    }

                    // Set class and section info
                    const userGradeLevel = document.getElementById('userGradeLevel');
                    const userSection = document.getElementById('userSection');
                    if (userGradeLevel && userSection) {
                        if (userData.section && userData.section.school_class) {
                            userGradeLevel.textContent = `Grade ${userData.section.school_class.grade_level || 'N/A'}`;
                            userSection.textContent = userData.section.section_name || 'N/A';
                        } else {
                            userGradeLevel.textContent = 'N/A';
                            userSection.textContent = 'N/A';
                        }
                    }

                    // Set activity statistics
                    const userPostsCount = document.getElementById('userPostsCount');
                    const userEventsCount = document.getElementById('userEventsCount');
                    if (userPostsCount) {
                        userPostsCount.textContent = userData.posts ? userData.posts.length : '0';
                    }
                    if (userEventsCount) {
                        userEventsCount.textContent = userData.organized_events ? userData.organized_events.length : '0';
                    }

                    // Set parent/guardian information
                    const userMotherName = document.getElementById('userMotherName');
                    const userMotherContact = document.getElementById('userMotherContact');
                    const userFatherName = document.getElementById('userFatherName');
                    const userFatherContact = document.getElementById('userFatherContact');
                    const userGuardianName = document.getElementById('userGuardianName');
                    const userGuardianContact = document.getElementById('userGuardianContact');

                    if (userMotherName) userMotherName.textContent = userData.mother_name || 'N/A';
                    if (userMotherContact) userMotherContact.textContent = userData.mother_contact_no || 'N/A';
                    if (userFatherName) userFatherName.textContent = userData.father_name || 'N/A';
                    if (userFatherContact) userFatherContact.textContent = userData.father_contact_no || 'N/A';
                    if (userGuardianName) userGuardianName.textContent = userData.guardian_name || 'N/A';
                    if (userGuardianContact) userGuardianContact.textContent = userData.guardian_contact_no || 'N/A';

                    // Handle Club Section based on user role
                    const studentClubSection = document.getElementById('studentClubSection');
                    const teacherClubSection = document.getElementById('teacherClubSection');
                    const adminClubSection = document.getElementById('adminClubSection');

                    // Hide all sections first
                    if (studentClubSection) studentClubSection.classList.add('hidden');
                    if (teacherClubSection) teacherClubSection.classList.add('hidden');
                    if (adminClubSection) adminClubSection.classList.add('hidden');

                    // Update section title based on role
                    const clubSectionTitle = document.getElementById('clubSectionTitle');
                    if (clubSectionTitle) {
                        const titleSpan = clubSectionTitle.querySelector('span');
                        if (titleSpan) {
                            // Show appropriate section based on role
                            switch (userData.role) {
                                case 'STUDENT':
                                    // For students, show club memberships
                                    titleSpan.textContent = 'Club Memberships';
                                    if (studentClubSection) studentClubSection.classList.remove('hidden');

                                    const clubMembershipsContainer = document.getElementById('clubMemberships');
                                    const noClubsMessage = document.getElementById('noClubsMessage');

                                    if (clubMembershipsContainer && noClubsMessage) {
                                        clubMembershipsContainer.innerHTML = ''; // Clear existing content

                                        if (userData.club_memberships && userData.club_memberships.length > 0) {
                                            noClubsMessage.style.display = 'none';

                                            userData.club_memberships.forEach(membership => {
                                                const membershipElement = document.createElement('div');
                                                membershipElement.className =
                                                    'p-2 bg-gray-50 rounded-lg flex justify-between items-center';
                                                membershipElement.innerHTML = `
                                                    <div>
                                                        <span class="font-medium text-sm">${membership.club.club_name}</span>
                                                        <p class="text-xs text-gray-500">Joined: ${formatDate(membership.joined_date)}</p>
                                                    </div>
                                                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">${membership.club_role}</span>
                                                `;
                                                clubMembershipsContainer.appendChild(membershipElement);
                                            });
                                        } else {
                                            noClubsMessage.style.display = 'block';
                                        }
                                    }
                                    break;

                                case 'TEACHER':
                                    // For teachers, show clubs advised
                                    titleSpan.textContent = 'Clubs Advised';
                                    if (teacherClubSection) teacherClubSection.classList.remove('hidden');

                                    const clubsAdvisedContainer = document.getElementById('clubsAdvised');
                                    const noAdvisedClubsMessage = document.getElementById('noAdvisedClubsMessage');

                                    if (clubsAdvisedContainer && noAdvisedClubsMessage) {
                                        clubsAdvisedContainer.innerHTML = ''; // Clear existing content

                                        if (userData.advised_clubs && userData.advised_clubs.length > 0) {
                                            noAdvisedClubsMessage.style.display = 'none';

                                            userData.advised_clubs.forEach(club => {
                                                const clubElement = document.createElement('div');
                                                clubElement.className =
                                                    'p-2 bg-gray-50 rounded-lg flex justify-between items-center';
                                                clubElement.innerHTML = `
                                                    <div>
                                                        <span class="font-medium text-sm">${club.club_name}</span>
                                                    </div>
                                                    <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full">${club.memberships_count} Members</span>
                                                `;
                                                clubsAdvisedContainer.appendChild(clubElement);
                                            });
                                        } else {
                                            noAdvisedClubsMessage.style.display = 'block';
                                        }
                                    }
                                    break;

                                case 'ADMIN':
                                    // For admins, show admin message
                                    titleSpan.textContent = 'Club Information';
                                    if (adminClubSection) adminClubSection.classList.remove('hidden');
                                    break;
                            }
                        }
                    }

                    // Show the modal - ensure it's visible
                    if (userViewModal) {
                        // Remove hidden class and ensure visibility
                        userViewModal.classList.remove('hidden');

                        // Set explicit display style to ensure visibility
                        userViewModal.style.display = 'flex';
                        userViewModal.style.zIndex = '50';
                    } else {
                        throw new Error('User view modal not found');
                    }

                } catch (error) {
                    // Log error for debugging (only in development)
                    devError('Error in showUserDetails:', error);

                    // Show user-friendly error message
                    const errorMessage = 'Unable to display user details. Please try again.';

                    // Try to show error in the password verification modal
                    const errorElement = document.getElementById('viewPasswordError');
                    if (errorElement) {
                        errorElement.textContent = errorMessage;
                        errorElement.classList.remove('hidden');
                    } else {
                        // Fallback to alert if error element not found
                        alert(errorMessage);
                    }

                    // Close any open modals
                    closeViewPasswordModal();
                }
            }

            // Helper function to format dates
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            }

            // Handle view password verification
            document.getElementById('viewVerifyPasswordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = document.getElementById('viewPasswordInput').value;
                const userId = document.getElementById('viewUserId').value;
                const errorElement = document.getElementById('viewPasswordError');

                // Validate inputs
                if (!password.trim()) {
                    errorElement.textContent = 'Please enter your password';
                    errorElement.classList.remove('hidden');
                    return;
                }

                if (!userId) {
                    errorElement.textContent = 'User ID is missing. Please try again.';
                    errorElement.classList.remove('hidden');
                    return;
                }

                // Hide any previous error
                errorElement.classList.add('hidden');
                errorElement.textContent = '';

                // Disable the form during submission
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                submitButton.disabled = true;
                submitButton.textContent = 'Verifying...';

                // Make AJAX request to verify password and fetch user details
                fetch(`/admin/users/${userId}/details`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            password: password
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close password verification modal first
                            closeViewPasswordModal();

                            // Show user details immediately after closing password modal
                            try {
                                showUserDetails(data.user);
                            } catch (error) {
                                // Log error for debugging (only in development)
                                devError('Error showing user details:', error);

                                // Reopen password modal if there's an error
                                document.getElementById('viewPasswordVerificationModal').classList
                                    .remove('hidden');
                                errorElement.textContent =
                                    'Error displaying user details. Please try again.';
                                errorElement.classList.remove('hidden');
                            }
                        } else {
                            // Show error message in the form
                            errorElement.textContent = data.error || 'Password verification failed';
                            errorElement.classList.remove('hidden');
                            // Add a shake animation to the password input
                            document.getElementById('viewPasswordInput').classList.add('border-red-500');
                        }
                    })
                    .catch(error => {
                        // Log error for debugging (only in development)
                        devError('Error:', error);

                        errorElement.textContent = 'An error occurred. Please try again.';
                        errorElement.classList.remove('hidden');
                    })
                    .finally(() => {
                        // Re-enable the form
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    });
            });

            // Edit Password Verification Form Handling
            document.getElementById('editVerifyPasswordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = document.getElementById('editPasswordVerificationInput').value;
                const userId = document.getElementById('editVerifiedUserId').value;
                const formDataStr = document.getElementById('editOriginalFormData').value;
                const errorElement = document.getElementById('editPasswordVerificationError');
                let formData;

                try {
                    formData = JSON.parse(formDataStr);
                } catch (error) {
                    // Log error for debugging (only in development)
                    devError('Error parsing form data:', error);

                    errorElement.textContent = 'An error occurred with the form data. Please try again.';
                    errorElement.classList.remove('hidden');
                    return;
                }

                // Hide any previous error
                errorElement.classList.add('hidden');
                errorElement.textContent = '';

                // Create a new FormData object with the admin's password
                const submitData = new FormData();

                // Add form data
                for (const key in formData) {
                    if (Object.hasOwnProperty.call(formData, key)) {
                        submitData.append(key, formData[key]);
                    }
                }

                // Add admin's password for verification
                submitData.append('admin_password', password);
                submitData.append('_method', 'PUT'); // For method spoofing

                // Make AJAX request to update user with password verification
                fetch(`/admin/users/${userId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: submitData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close verification modal
                            closeEditPasswordVerificationModal();

                            // Close edit modal
                            closeEditModal();

                            // Show success modal
                            document.getElementById('updateSuccessModal').classList.remove('hidden');
                        } else {
                            // Show error message
                            errorElement.textContent = data.error || 'Invalid password. Please try again.';
                            errorElement.classList.remove('hidden');
                            document.getElementById('editPasswordVerificationInput').classList.add(
                                'border-red-500');
                        }
                    })
                    .catch(error => {
                        // Log error for debugging (only in development)
                        devError('Error:', error);

                        errorElement.textContent = 'An error occurred. Please try again.';
                        errorElement.classList.remove('hidden');
                    });
            });

            function closeEditPasswordVerificationModal() {
                document.getElementById('editPasswordVerificationModal').classList.add('hidden');
                // Clear password input when closing modal
                document.getElementById('editPasswordVerificationInput').value = '';
                // Clear any error message
                const errorElement = document.getElementById('editPasswordVerificationError');
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
                // Remove any error styling
                document.getElementById('editPasswordVerificationInput').classList.remove('border-red-500');
            }

            function closeUpdateSuccessModal() {
                document.getElementById('updateSuccessModal').classList.add('hidden');
                // Refresh page to show updated data
                window.location.reload();
            }
        </script>

        @if (session('user_added'))
            <script>
                document.addEventListener('DOMContentLoaded', showUserSuccessModal);
            </script>
        @endif

        @if (session('section_added'))
            <script>
                document.addEventListener('DOMContentLoaded', showSectionSuccessModal);
            </script>
        @endif

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('updateSuccessModal').classList.remove('hidden');
                });
            </script>
        @endif
    @endsection
