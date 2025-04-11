@extends('layouts.dashboard')

@section('title', 'Home Page | ClubHive')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- LEFT COLUMN: POSTS -->
            <div class="lg:col-span-2">
                {{-- POST CONTAINER --}}
                <div class="bg-white rounded-xl shadow-lg mb-6 overflow-hidden transform transition-all hover:shadow-xl">
                    <!-- Post Header -->
                    <div class="p-5 flex items-center justify-between border-b border-gray-200">
                        <div class="flex items-center space-x-3">
                            <div class="h-12 w-12 rounded-full overflow-hidden ring-2 ring-blue-400">
                                <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}"
                                    alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">John Doe</div>
                                <div class="text-xs text-gray-500 flex items-center flex-wrap">
                                    <span>2 hours ago</span>
                                    <span class="mx-1 text-blue-400">•</span>
                                    <span class="font-medium text-blue-600">Club Adviser</span>
                                    <span class="mx-1">|</span>
                                    <span class="font-medium text-indigo-600">SSLG</span>
                                </div>
                            </div>
                        </div>
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="text-gray-500 hover:text-blue-600 rounded-full p-2 hover:bg-gray-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200"
                                x-cloak>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Post
                                </a>
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Post
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="p-5 pb-4">
                        <p class="text-gray-800 leading-relaxed">Just had an amazing weekend with the team at our annual
                            retreat! Sharing some of the best moments from our trip. <span
                                class="text-blue-600 font-medium">#TeamBuilding</span> <span
                                class="text-blue-600 font-medium">#WeekendVibes</span></p>
                    </div>

                    <!-- Image Grid (5 images with improved layout) -->
                    <div class="grid grid-cols-12 gap-1 p-1">
                        <!-- First row: 1 large image -->
                        <div class="col-span-12 h-64 overflow-hidden">
                            <img src="{{ asset('images/red.jpg') }}" alt="Post image 1"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>

                        <!-- Second row: 2 medium images -->
                        <div class="col-span-6 h-48 overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="Post image 2"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>
                        <div class="col-span-6 h-48 overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="Post image 3"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>

                        <!-- Third row: 2 medium images -->
                        <div class="col-span-6 h-48 overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="Post image 4"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>
                        <div class="col-span-6 h-48 relative group overflow-hidden">
                            <img src="https://placehold.co/600x400" alt="Post image 5"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                    </div>
                </div>

                <!-- MORE POSTS WOULD GO HERE -->
            </div>

            <!-- RIGHT COLUMN: UPCOMING EVENTS & MY CLUBS -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Sticky container for the right column -->
                <div class="sticky top-0">
                    <!-- UPCOMING EVENTS -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-5 py-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">Upcoming Events</h2>
                                <a href="{{ route('events.index') }}"
                                    class="text-white hover:text-blue-100 text-sm font-medium flex items-center">
                                    View All
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Event Cards -->
                        <div class="p-5 space-y-4">
                            <!-- Event 1 -->
                            <div
                                class="border-l-4 border-indigo-500 pl-4 py-2 hover:bg-indigo-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">Annual Leadership Summit</h3>
                                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">SSLG</span>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">February 14, 2025</div>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>8:00AM - 5:00PM</span>
                                </div>
                            </div>

                            <!-- Event 2 -->
                            <div
                                class="border-l-4 border-purple-500 pl-4 py-2 hover:bg-purple-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">Charity Fundraiser Gala</h3>
                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">SSLG</span>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">May 9, 2025</div>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>6:00PM - 10:00PM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MY CLUBS SECTION -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-5 py-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">My Clubs</h2>
                                <a href="#"
                                    class="text-white hover:text-green-100 text-sm font-medium flex items-center">
                                    View All
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-5 space-y-3">
                            <!-- Club 1 -->
                            <div
                                class="flex items-center p-3 rounded-lg hover:bg-indigo-50 transition-all border border-transparent hover:border-indigo-200">
                                <div
                                    class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mr-4 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">Supreme Secondary Learner Government</div>
                                    <div class="text-sm text-indigo-600 font-medium">Officer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
