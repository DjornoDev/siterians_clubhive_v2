@section('title', $student->name . ' - Profile')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-6xl mx-auto">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('clubs.people.index', $club) }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Back to Members
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Profile Picture & Club Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Picture Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 text-center">
                        <!-- Profile Picture -->
                        <div class="mx-auto mb-6">
                            @if ($student->profile_picture)
                                <img src="{{ asset('storage/profile_pictures/' . $student->profile_picture) }}"
                                    alt="{{ $student->name }}"
                                    class="w-36 h-36 rounded-full object-cover border-3 border-blue-200 mx-auto">
                            @else
                                <div
                                    class="w-36 h-36 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-3xl font-bold border-3 border-blue-200 mx-auto">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Basic Info -->
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $student->name }}</h1>
                        @if ($student->email)
                            <p class="text-gray-600 mb-4">{{ $student->email }}</p>
                        @endif

                        <!-- Club Role Badge -->
                        @if ($membership)
                            <div class="flex justify-center mb-4">
                                <span
                                    class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $membership->pivot->club_role }}
                                </span>
                            </div>
                        @endif

                        <!-- Position -->
                        @if ($membership && $membership->pivot->club_position)
                            <p class="text-sm text-gray-600 mb-4">
                                <span class="font-medium">Position:</span> {{ $membership->pivot->club_position }}
                            </p>
                        @endif

                        <!-- Join Date -->
                        @if ($membership)
                            <p class="text-xs text-gray-500">
                                Member since {{ $membership->pivot->created_at->format('M d, Y') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Academic Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Academic Info</h3>
                    </div>
                    <div class="p-6">
                        @if ($student->section && $student->section->schoolClass)
                            <div class="space-y-3">
                                <div class="text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-2 rounded-full text-base font-semibold bg-green-100 text-green-800">
                                        Grade {{ $student->section->schoolClass->grade_level }}
                                    </span>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                        {{ $student->section->section_name }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 text-center">No academic information available</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Personal & Contact Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Gender</h4>
                                <p class="text-lg text-gray-900">{{ $student->sex ?: 'Not specified' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Contact Number</h4>
                                @if ($student->contact_no)
                                    <p class="text-lg">
                                        <a href="tel:{{ $student->contact_no }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                            {{ $student->contact_no }}
                                        </a>
                                    </p>
                                @else
                                    <p class="text-lg text-gray-500">Not provided</p>
                                @endif
                            </div>
                            <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Address</h4>
                                <p class="text-lg text-gray-900">{{ $student->address ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contacts Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-red-50 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-red-800">Emergency Contacts</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mother's Information -->
                            <div class="bg-pink-50 rounded-lg p-5 border border-pink-200">
                                <h4 class="font-semibold text-pink-800 mb-4 text-lg">Mother</h4>
                                <div class="space-y-3">
                                    <div>
                                        <h5 class="text-sm font-medium text-pink-700 mb-1">Name</h5>
                                        <p class="text-lg text-pink-900">
                                            {{ $student->mother_name ?: 'Not provided' }}
                                        </p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-pink-700 mb-1">Contact Number</h5>
                                        @if ($student->mother_contact_no)
                                            <p class="text-lg font-bold">
                                                <a href="tel:{{ $student->mother_contact_no }}"
                                                    class="text-pink-700 hover:text-pink-900">
                                                    {{ $student->mother_contact_no }}
                                                </a>
                                            </p>
                                        @else
                                            <p class="text-lg text-gray-500">Not provided</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Father's Information -->
                            <div class="bg-blue-50 rounded-lg p-5 border border-blue-200">
                                <h4 class="font-semibold text-blue-800 mb-4 text-lg">Father</h4>
                                <div class="space-y-3">
                                    <div>
                                        <h5 class="text-sm font-medium text-blue-700 mb-1">Name</h5>
                                        <p class="text-lg text-blue-900">
                                            {{ $student->father_name ?: 'Not provided' }}
                                        </p>
                                    </div>
                                    <div>
                                        <h5 class="text-sm font-medium text-blue-700 mb-1">Contact Number</h5>
                                        @if ($student->father_contact_no)
                                            <p class="text-lg font-bold">
                                                <a href="tel:{{ $student->father_contact_no }}"
                                                    class="text-blue-700 hover:text-blue-900">
                                                    {{ $student->father_contact_no }}
                                                </a>
                                            </p>
                                        @else
                                            <p class="text-lg text-gray-500">Not provided</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Club Information -->
                @if ($student->clubMemberships && $student->clubMemberships->count() > 1)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Other Club Memberships</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($student->clubMemberships as $otherMembership)
                                    @if ($otherMembership->club_id !== $club->club_id)
                                        <div
                                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $otherMembership->club->club_name }}</p>
                                                <p class="text-sm text-gray-600">{{ $otherMembership->club_role }}</p>
                                            </div>
                                            @if ($otherMembership->club_position)
                                                <span
                                                    class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">
                                                    {{ $otherMembership->club_position }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
