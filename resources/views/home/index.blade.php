@extends('layouts.dashboard')

@section('title', 'Home | ClubHive')

@section('content')
    <div x-data="{
        showEditModal: false,
        showDeleteModal: false,
        currentPostId: null,
        currentClubId: null,
        editPostCaption: '',
        editPostVisibility: 'CLUB_ONLY',
        currentPostImages: [],
        showFullGallery: null,
        currentImageIndex: 0,
        postLastChecksum: '{{ md5(json_encode($publicPosts->pluck('post_id')->merge($publicPosts->pluck('updated_at')))) }}',
        eventLastChecksum: '{{ md5(json_encode($todayEvents->pluck('event_id')->merge($todayEvents->pluck('updated_at'))->merge($upcomingEvents->pluck('event_id'))->merge($upcomingEvents->pluck('updated_at')))) }}',
    
        checkForPostChanges() {
            // Skip refresh check if a modal is open
            if (this.showEditModal || this.showDeleteModal || this.showFullGallery) return;
    
            fetch('{{ route('home.check-post-changes') }}?checksum=' + this.postLastChecksum)
                .then(response => response.json())
                .then(data => {
                    if (data.hasChanges) {
                        window.location.reload();
                    }
                });
        },
    
        checkForEventChanges() {
            // Skip refresh check if a modal is open
            if (this.showEditModal || this.showDeleteModal || this.showFullGallery) return;
    
            fetch('{{ route('home.check-event-changes') }}?checksum=' + this.eventLastChecksum)
                .then(response => response.json())
                .then(data => {
                    if (data.hasChanges) {
                        window.location.reload();
                    }
                });
        },
    
        init() {
            // Check for post changes every 15 seconds
            setInterval(() => this.checkForPostChanges(), 15000);
    
            // Check for event changes every 30 seconds
            setInterval(() => this.checkForEventChanges(), 30000);
        }
    }" class="container mx-auto px-4 py-6">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg mb-8 overflow-hidden relative">
            <div class="absolute right-0 top-0 opacity-10">
                <svg width="200" height="200" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 5H5v14h14V5zM2 3v18h20V3H2zm10 14h-2v-6h2v6zm4 0h-2V7h2v10zm-8 0H6v-2h2v2z"
                        fill="currentColor" />
                </svg>
            </div>
            <div class="p-6 md:p-8 relative z-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Welcome, {{ auth()->user()->name }}!</h1>
                        <p class="text-blue-100 mt-1 max-w-2xl">Stay updated with the latest club activities and upcoming
                            events.</p>
                    </div>
                    @if (auth()->user()->role === 'STUDENT')
                        <div class="mt-4 md:mt-0">
                            <a href="{{ route('clubs.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white text-blue-700 rounded-lg font-medium shadow-sm hover:bg-blue-50 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Explore Clubs
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- LEFT COLUMN: POSTS -->
            <div class="lg:col-span-2">
                <!-- Posts Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Recent Posts
                    </h2>
                    <div class="text-sm bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-medium flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Latest Updates
                    </div>
                </div>

                @if ($publicPosts->isEmpty())
                    <!-- No Posts State with Enhanced UI -->
                    <div
                        class="bg-white rounded-xl shadow-md p-8 text-center border border-dashed border-gray-300 animate-pulse">
                        <div class="flex flex-col items-center justify-center gap-4">
                            <div class="bg-blue-50 text-blue-500 p-4 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-medium text-gray-900 mb-1">No club posts yet</h3>
                                <p class="text-gray-500 mb-4">Check back later for updates from your clubs!</p>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach ($publicPosts as $post)
                        <div
                            class="bg-white rounded-xl shadow-md mb-6 overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <!-- Post Header with enhanced styling -->
                            <div
                                class="p-5 flex items-center justify-between border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full overflow-hidden ring-2 ring-blue-400 shadow-md">
                                        @if ($post->author->profile_picture)
                                            <img src="{{ asset('storage/profile_pictures/' . $post->author->profile_picture) }}"
                                                alt="{{ $post->author->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div
                                                class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold">
                                                {{ strtoupper(substr($post->author->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $post->author->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center flex-wrap">
                                            <span>{{ $post->post_date->diffForHumans() }}</span>
                                            <span class="mx-1 text-blue-400">•</span>
                                            <span class="font-medium text-blue-600">{{ $post->author->role }}</span>
                                            <span class="mx-1">|</span>
                                            <a href="{{ route('clubs.show', $post->club) }}"
                                                class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $post->club->club_name }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @can('update', $post)
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="text-gray-500 hover:text-blue-600 rounded-full p-2 hover:bg-gray-100 transition-all focus:outline-none focus:ring-2 focus:ring-blue-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200 animate-fade-in-down"
                                            x-cloak>
                                            <!-- Edit Button -->
                                            <button
                                                @click="                                                    showEditModal = true;
                                                    currentPostId = {{ $post->post_id }};
                                                    currentClubId = {{ $post->club->club_id }};
                                                    // Set post caption directly instead of trying to access hidden element
                                                    editPostCaption = `{{ $post->post_caption }}`;
                                                    editPostVisibility = '{{ $post->post_visibility }}';
                                                    currentPostImages = {{ $post->images->map(fn($img) => ['id' => $img->image_id, 'url' => Storage::url($img->image_path)])->toJson() }};
                                                "
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-800 rounded-t-md">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Post
                                                </div>
                                            </button>
                                            <!-- Delete Button -->
                                            <button
                                                @click="
                                                    showDeleteModal = true;
                                                    currentPostId = {{ $post->post_id }};
                                                    currentClubId = {{ $post->club->club_id }};
                                                "
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100 hover:text-red-800 rounded-b-md">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete Post
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                            <!-- Post Content with better typography -->
                            <div class="p-6 pb-4">
                                <p class="text-gray-800 leading-relaxed whitespace-pre-line text-[15px]">
                                    {{ $post->post_caption }}</p>
                            </div>

                            @if ($post->images->count() > 0)
                                <div class="relative">
                                    <!-- Images container with improved styling -->
                                    <div class="grid grid-cols-12 gap-1 p-1 bg-gray-50">
                                        @php
                                            $totalImages = $post->images->count();
                                            $displayImages = $totalImages > 5 ? 5 : $totalImages;
                                        @endphp

                                        @if ($totalImages == 1)
                                            <!-- Single image layout -->
                                            <div class="col-span-12 h-96 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post content"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                        @elseif ($totalImages == 2)
                                            <!-- Two images layout -->
                                            @foreach ($post->images->take(2) as $index => $image)
                                                <div class="col-span-6 h-64 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post content"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index }};">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 3)
                                            <!-- Three images layout -->
                                            <div class="col-span-12 h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            @foreach ($post->images->slice(1, 2) as $index => $image)
                                                <div class="col-span-6 h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index + 1 }};">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 4)
                                            <!-- Four images layout -->
                                            <div class="col-span-12 h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            @foreach ($post->images->slice(1, 3) as $index => $image)
                                                <div class="col-span-4 h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index + 1 }};">
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Five or more images layout -->
                                            <div class="col-span-8 h-80 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            <div class="col-span-4 grid grid-rows-2 gap-1">
                                                @foreach ($post->images->slice(1, 2) as $index => $image)
                                                    <div class="h-40 overflow-hidden">
                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                            alt="Post image"
                                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                            @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index + 1 }};">
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Last two images (or one if there are only 5 total) -->
                                            @php
                                                $lastImages = $post->images->slice(3, 2)->values(); // Add ->values() to reset indices
                                                $lastImageIndex = count($lastImages) - 1;
                                            @endphp

                                            @foreach ($lastImages as $key => $image)
                                                <div class="col-span-6 h-36 overflow-hidden relative">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $key + 3 }};">

                                                    @if ($key == $lastImageIndex && $totalImages > 5)
                                                        <!-- Show more overlay on the last visible image -->
                                                        <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center cursor-pointer"
                                                            @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                                            <div class="text-white text-center">
                                                                <span
                                                                    class="text-xl font-bold">+{{ $totalImages - 5 }}</span>
                                                                <p class="text-sm">View all</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Image Carousel Modal with enhanced styling -->
                                <template x-if="showFullGallery === {{ $post->post_id }}">
                                    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center"
                                        @keydown.left="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : {{ $totalImages - 1 }}"
                                        @keydown.right="currentImageIndex = currentImageIndex < {{ $totalImages - 1 }} ? currentImageIndex + 1 : 0"
                                        @keydown.escape="showFullGallery = null" tabindex="0">

                                        <!-- Close button -->
                                        <button @click="showFullGallery = null"
                                            class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 transition-transform hover:scale-110">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <!-- Image counter -->
                                        <div
                                            class="absolute top-4 left-4 text-white font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full">
                                            <span x-text="currentImageIndex + 1"></span> /
                                            <span>{{ $totalImages }}</span>
                                        </div>

                                        <!-- Previous button -->
                                        <button
                                            @click="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : {{ $totalImages - 1 }}"
                                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-transform hover:scale-110 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>

                                        <!-- Main image container -->
                                        <div class="h-full w-full flex items-center justify-center p-8">
                                            @foreach ($post->images as $index => $image)
                                                <div x-show="currentImageIndex === {{ $index }}"
                                                    class="h-full max-h-full flex items-center justify-center transform transition-opacity"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="max-h-full max-w-full object-contain shadow-2xl">
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Next button -->
                                        <button
                                            @click="currentImageIndex = currentImageIndex < {{ $totalImages - 1 }} ? currentImageIndex + 1 : 0"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-transform hover:scale-110 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>

                                        <!-- Thumbnail navigation -->
                                        <div
                                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 overflow-x-auto px-4 py-2 bg-black bg-opacity-50 rounded-lg max-w-3xl">
                                            @foreach ($post->images as $index => $image)
                                                <button @click="currentImageIndex = {{ $index }}"
                                                    class="h-16 w-16 overflow-hidden rounded-md border-2 transition-all duration-200 hover:opacity-90"
                                                    :class="currentImageIndex === {{ $index }} ?
                                                        'border-blue-500 scale-110' :
                                                        'border-transparent'">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Thumbnail"
                                                        class="h-full w-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </template>
                            @endif

                            <!-- Post Footer with metadata and engagement stats -->
                            <div
                                class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-sm text-gray-500 flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center"> <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-4 mr-1 {{ $post->post_visibility === 'CLUB_ONLY' ? 'text-purple-500' : 'text-blue-500' }}"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd"
                                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        @include('home.partials.post-visibility-badge')
                                    </span>
                                </div>
                                <div>
                                    @if ($post->images->count() > 0)
                                        <span class="inline-flex items-center mr-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-indigo-500"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            {{ $post->images->count() }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400"
                                            viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ $post->post_date->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <!-- Improved pagination styling -->
                    <div class="mt-8">
                        {{ $publicPosts->links() }}
                    </div>
                @endif
                <!-- Include Modals -->
                @include('home.posts.partials.edit-modal')
                @include('home.posts.partials.delete-modal')
            </div>

            <!-- RIGHT COLUMN: UPCOMING EVENTS & MY CLUBS -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Sticky container -->
                <div class="sticky top-0">
                    <!-- Today's Events with Enhanced UI -->
                    @if ($todayEvents->isNotEmpty())
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white">Today's Events</h2>
                                    <span class="bg-white/20 text-white text-sm px-3 py-1 rounded-full backdrop-blur-sm">
                                        {{ now()->format('M d') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 space-y-4">
                                @foreach ($todayEvents as $event)
                                    <div
                                        class="group bg-white border border-gray-100 rounded-lg p-4 hover:border-blue-200 hover:bg-blue-50 transition-all duration-200 shadow-sm hover:shadow">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h3
                                                    class="font-semibold text-gray-800 group-hover:text-blue-700 transition-colors">
                                                    {{ $event->event_name }}</h3>
                                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 mr-1 text-blue-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        @if ($event->event_time)
                                                            {{ $event->event_time }}
                                                        @else
                                                            All day
                                                        @endif
                                                    </span>
                                                    <span class="mx-2">•</span>
                                                    <span class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 mr-1 text-blue-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $event->event_location }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full inline-block mt-2 group-hover:bg-blue-100 transition-colors">
                                                    {{ $event->club->club_name }}
                                                </div>
                                            </div>
                                            <a href="{{ route('events.index') }}"
                                                class="text-white bg-blue-600 hover:bg-blue-700 transition-colors p-2 rounded-full flex items-center justify-center shadow-sm group-hover:shadow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upcoming Events with Enhanced UI -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-4">
                            <div class="flex items-center justify-between">
                                <h2 class="text-xl font-bold text-white">Upcoming Events</h2>
                                <a href="{{ route('events.index') }}"
                                    class="text-white hover:text-blue-100 text-sm font-medium flex items-center bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                                    View All
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="p-5 space-y-4">
                            @forelse($upcomingEvents as $event)
                                <div
                                    class="group bg-white border border-gray-100 rounded-lg p-4 hover:border-indigo-200 hover:bg-indigo-50 transition-all duration-200 shadow-sm hover:shadow">
                                    <div class="flex items-center space-x-4">
                                        <!-- Event Date Badge -->
                                        <div
                                            class="bg-indigo-100 text-indigo-800 rounded-lg p-2 text-center w-14 h-14 flex flex-col items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                            <span class="text-xl font-bold">{{ $event->event_date->format('d') }}</span>
                                            <span class="text-xs uppercase">{{ $event->event_date->format('M') }}</span>
                                        </div>

                                        <!-- Event Details -->
                                        <div class="flex-1">
                                            <h3
                                                class="font-semibold text-gray-800 group-hover:text-indigo-700 transition-colors">
                                                {{ $event->event_name }}</h3>
                                            <div class="flex flex-wrap items-center text-xs text-gray-500 mt-1">
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3 w-3 mr-1 text-indigo-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    @if ($event->event_time)
                                                        {{ $event->event_time }}
                                                    @else
                                                        All day
                                                    @endif
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-3 w-3 mr-1 text-indigo-500" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $event->event_location }}
                                                </span>
                                            </div>
                                            <div
                                                class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full inline-block mt-2 group-hover:bg-indigo-100 transition-colors">
                                                {{ $event->club->club_name }}
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        <a href="{{ route('events.index') }}"
                                            class="text-white bg-indigo-600 hover:bg-indigo-700 transition-colors p-2 rounded-full flex items-center justify-center shadow-sm group-hover:shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <div class="bg-indigo-100 text-indigo-500 p-3 rounded-full mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-800 mb-1">No upcoming events</h3>
                                    <p class="text-gray-500 mb-4">Check back later for new events</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- My Clubs Section - Only visible to STUDENT role -->
                    @if (auth()->user()->role === 'STUDENT')
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white">My Clubs</h2>
                                    <a href="{{ route('clubs.index') }}"
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

                            <div class="p-5">
                                @if (isset($myClubs) && count($myClubs) > 0)
                                    <div class="space-y-4">
                                        @foreach ($myClubs as $club)
                                            <a href="{{ route('clubs.show', $club) }}"
                                                class="flex items-center p-3 rounded-lg border border-gray-100 hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 group">
                                                <div
                                                    class="h-12 w-12 rounded-full overflow-hidden border-2 border-purple-200 flex-shrink-0 bg-purple-100">
                                                    @if ($club->club_logo)
                                                        <img src="{{ asset(Storage::url($club->club_logo)) }}"
                                                            alt="{{ $club->club_name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full bg-purple-100 flex items-center justify-center text-purple-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3
                                                        class="text-gray-800 font-medium group-hover:text-purple-700 transition-colors">
                                                        {{ $club->club_name }}</h3>
                                                    <p class="text-xs text-gray-500">{{ $club->members_count }} members
                                                    </p>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 group-hover:text-purple-600 transition-colors"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-center">
                                        <div class="bg-purple-100 text-purple-500 p-3 rounded-full mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-800 mb-1">Not a member of any club yet
                                        </h3>
                                        <p class="text-gray-500 mb-4">Join clubs to see them listed here</p>
                                        <a href="{{ route('clubs.index') }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg font-medium shadow hover:bg-purple-700 transition-colors">
                                            Browse Available Clubs
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Advised Clubs Section - Only visible to TEACHER role -->
                    @if (auth()->user()->role === 'TEACHER')
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                            <div class="bg-gradient-to-r from-green-500 to-teal-600 px-5 py-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-white">My Advised Clubs</h2>
                                    <a href="{{ route('clubs.index') }}"
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

                            <div class="p-5">
                                @php
                                    $advisedClubs = auth()->user()->advisedClubs()->withCount('members')->get();
                                @endphp

                                @if (isset($advisedClubs) && count($advisedClubs) > 0)
                                    <div class="space-y-4">
                                        @foreach ($advisedClubs as $club)
                                            <a href="{{ route('clubs.show', $club) }}"
                                                class="flex items-center p-3 rounded-lg border border-gray-100 hover:bg-green-50 hover:border-green-200 transition-all duration-200 group">
                                                <div
                                                    class="h-12 w-12 rounded-full overflow-hidden border-2 border-green-200 flex-shrink-0 bg-green-100">
                                                    @if ($club->club_logo)
                                                        <img src="{{ asset(Storage::url($club->club_logo)) }}"
                                                            alt="{{ $club->club_name }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <div
                                                            class="w-full h-full bg-green-100 flex items-center justify-center text-green-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3
                                                        class="text-gray-800 font-medium group-hover:text-green-700 transition-colors">
                                                        {{ $club->club_name }}</h3>
                                                    <p class="text-xs text-gray-500">{{ $club->members_count }} members
                                                    </p>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5 text-gray-400 group-hover:text-green-600 transition-colors"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-8 text-center">
                                        <div class="bg-green-100 text-green-500 p-3 rounded-full mb-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-800 mb-1">Not advising any clubs yet</h3>
                                        <p class="text-gray-500 mb-4">You'll see clubs you advise listed here</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
