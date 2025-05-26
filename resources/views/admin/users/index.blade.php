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
                        <select name="role"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2020%2020%22%20fill%3D%22%236b7280%22%3E%3Cpath%20fill-rule%3D%22evenodd%22%20d%3D%22M5.293%207.293a1%201%200%20011.414%200L10%2010.586l3.293-3.293a1%201%200%20111.414%201.414l-4%204a1%201%200%2001-1.414%200l-4-4a1%201%200%20010-1.414z%22%20clip-rule%3D%22evenodd%22%20%2F%3E%3C%2Fsvg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.5rem_center] bg-no-repeat pr-10">
                            <option value="">All Roles</option>
                            <option value="ADMIN" {{ request('role') === 'ADMIN' ? 'selected' : '' }}>Admin</option>
                            <option value="TEACHER" {{ request('role') === 'TEACHER' ? 'selected' : '' }}>Teacher</option>
                            <option value="STUDENT" {{ request('role') === 'STUDENT' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="w-full md:w-48">
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

                    <div class="w-full md:w-48">
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
                        <div class="ml-3">
                            <h4 class="font-semibold text-red-800 mb-2">Bulk Upload Errors:</h4>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach (session('bulk_errors') as $row => $messages)
                                    <li><strong>{{ $row }}:</strong> {{ implode(', ', $messages) }}</li>
                                @endforeach
                            </ul>
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
                                                onclick="openEditModal('{{ $user->user_id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->section->class_id ?? '' }}', '{{ $user->section_id ?? '' }}')"
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

            // Load sections on page load if class is already selected
            document.addEventListener('DOMContentLoaded', function() {
                const classId = document.getElementById('filterClassId').value;
                if (classId) {
                    // Set timeout to ensure the DOM is fully loaded
                    setTimeout(() => {
                        document.getElementById('filterClassId').dispatchEvent(new Event('change'));

                        // Set the correct section if it was previously selected
                        const urlParams = new URLSearchParams(window.location.search);
                        const sectionId = urlParams.get('section_id');

                        if (sectionId) {
                            setTimeout(() => {
                                document.getElementById('filterSectionId').value = sectionId;
                            }, 300);
                        }
                    }, 100);
                }
            });

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
                            console.error(data.errors);
                        }
                    })
                    .catch(error => console.error('Error:', error));
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
            function openEditModal(userId, name, email, role, classId, sectionId) {
                const form = document.getElementById('editUserForm');
                form.action = `/admin/users/${userId}`;

                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editRole').value = role;

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
                const errorElement = document.getElementById('passwordError');
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

            // Handle password verification
            document.getElementById('verifyPasswordForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const password = document.getElementById('passwordInput').value;
                const deleteForm = document.getElementById('deleteUserForm');
                const userId = document.getElementById('userId').value;
                const errorElement = document.getElementById('passwordError');

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
                        console.error('Error:', error);
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

            // View Modal Functions
            function openViewModal(userId, userName, userEmail, userRole) {
                // Store the user ID for later
                document.getElementById('viewUserId').value = userId;

                // Show password verification modal
                document.getElementById('viewPasswordVerificationModal').classList.remove('hidden');
            }

            function closeViewModal() {
                document.getElementById('userViewModal').classList.add('hidden');
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

            function showUserDetails(userData) {
                // Fill in user basic details
                document.getElementById('userName').textContent = userData.name;
                document.getElementById('userEmail').textContent = userData.email;
                document.getElementById('userCreated').textContent = formatDate(userData.created_at);
                document.getElementById('userUpdated').textContent = formatDate(userData.updated_at);

                // Fill in user credentials section
                document.getElementById('userCredName').textContent = userData.name;
                document.getElementById('userCredEmail').textContent = userData.email;

                // Set avatar initial
                const avatar = document.getElementById('userAvatar');
                avatar.textContent = userData.name.charAt(0).toUpperCase();

                // Set role badge
                const roleBadge = document.getElementById('userRoleBadge');
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
                }
                roleBadge.innerHTML =
                    `<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium shadow-sm ${badgeClass}">${userData.role}</span>`;

                // Set class and section info
                if (userData.section) {
                    document.getElementById('userGradeLevel').textContent =
                    `Grade ${userData.section.school_class.grade_level}`;
                    document.getElementById('userSection').textContent = userData.section.section_name;
                } else {
                    document.getElementById('userGradeLevel').textContent = 'N/A';
                    document.getElementById('userSection').textContent = 'N/A';
                }

                // Set activity statistics
                document.getElementById('userPostsCount').textContent = userData.posts ? userData.posts.length : '0';
                document.getElementById('userEventsCount').textContent = userData.organized_events ? userData.organized_events
                    .length : '0';

                // Handle Club Section based on user role
                const studentClubSection = document.getElementById('studentClubSection');
                const teacherClubSection = document.getElementById('teacherClubSection');
                const adminClubSection = document.getElementById('adminClubSection');

                // Hide all sections first
                studentClubSection.classList.add('hidden');
                teacherClubSection.classList.add('hidden');
                adminClubSection.classList.add('hidden');

                // Update section title based on role
                const clubSectionTitle = document.getElementById('clubSectionTitle').querySelector('span');

                // Show appropriate section based on role
                switch (userData.role) {
                    case 'STUDENT':
                        // For students, show club memberships
                        clubSectionTitle.textContent = 'Club Memberships';
                        studentClubSection.classList.remove('hidden');

                        const clubMembershipsContainer = document.getElementById('clubMemberships');
                        const noClubsMessage = document.getElementById('noClubsMessage');

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
                        break;

                    case 'TEACHER':
                        // For teachers, show clubs advised
                        clubSectionTitle.textContent = 'Clubs Advised';
                        teacherClubSection.classList.remove('hidden');

                        const clubsAdvisedContainer = document.getElementById('clubsAdvised');
                        const noAdvisedClubsMessage = document.getElementById('noAdvisedClubsMessage');

                        clubsAdvisedContainer.innerHTML = ''; // Clear existing content

                        if (userData.advised_clubs && userData.advised_clubs.length > 0) {
                            noAdvisedClubsMessage.style.display = 'none';

                            userData.advised_clubs.forEach(club => {
                                const clubElement = document.createElement('div');
                                clubElement.className = 'p-2 bg-gray-50 rounded-lg flex justify-between items-center';
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
                        break;

                    case 'ADMIN':
                        // For admins, show admin message
                        clubSectionTitle.textContent = 'Club Information';
                        adminClubSection.classList.remove('hidden');
                        break;
                }

                // Show the modal
                document.getElementById('userViewModal').classList.remove('hidden');
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

                // Hide any previous error
                errorElement.classList.add('hidden');
                errorElement.textContent = '';

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
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeViewPasswordModal();
                            showUserDetails(data.user);
                        } else {
                            // Show error message in the form
                            errorElement.textContent = data.error || 'Password verification failed';
                            errorElement.classList.remove('hidden');
                            // Add a shake animation to the password input
                            document.getElementById('viewPasswordInput').classList.add('border-red-500');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        errorElement.textContent = 'An error occurred. Please try again.';
                        errorElement.classList.remove('hidden');
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
                    console.error('Error parsing form data:', error);
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
                        console.error('Error:', error);
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
