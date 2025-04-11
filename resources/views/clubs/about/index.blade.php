@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto">
        <div class="relative mb-8">
            <!-- Club Banner -->
            <div class="h-64 w-full overflow-hidden rounded-lg">
                <img src="{{ asset('storage/' . $club->club_banner) }}" alt="{{ $club->club_name }} Banner"
                    class="w-full h-full object-cover">
            </div>

            <!-- Club Logo Overlay -->
            <div
                class="absolute -bottom-8 left-8 w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white">
                <img src="{{ asset('storage/' . $club->club_logo) }}" alt="{{ $club->club_name }} Logo"
                    class="w-full h-full object-cover">
            </div>
        </div>

        <div class="pt-8">
            <!-- Club Name and Basic Info -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $club->club_name }}</h1>
                    @if ($club->adviser)
                        <p class="text-gray-600 mt-1">Adviser: {{ $club->adviser->name }}</p>
                    @endif
                </div>
            </div>

            <!-- Club Description -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">About Us</h2>

                <div class="prose max-w-none">
                    @if ($club->club_description)
                        {!! nl2br(e($club->club_description)) !!}
                    @else
                        <p class="text-gray-500 italic">No description available.</p>
                    @endif
                </div>
            </div>

            <!-- Club Mission & Vision (if available) -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Our Mission</h2>
                        <p class="text-gray-600">
                            @if (isset($club->club_mission))
                                {{ $club->club_mission }}
                            @else
                                To provide a platform for students to develop their skills and interests in
                                {{ $club->club_name }}.
                            @endif
                        </p>
                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Our Vision</h2>
                        <p class="text-gray-600">
                            @if (isset($club->club_vision))
                                {{ $club->club_vision }}
                            @else
                                To foster a community of passionate individuals who excel in {{ $club->club_name }}
                                activities and contribute positively to the school environment.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
