@extends('clubs.layouts.navigation')

@section('club_content')
    <div x-data="{
        showEditModal: false,
        showDeleteModal: false,
        currentPostId: null,
        editPostCaption: '',
        editPostVisibility: 'CLUB_ONLY',
        currentPostImages: [],
        showFullGallery: null,
        currentImageIndex: 0
    }" class="max-w-7xl mx-auto px-4 py-6">
        <!-- Club Header Section -->
        <div class="mb-8">
            <!-- Club Banner -->
            <div class="h-80 w-full rounded-xl overflow-hidden shadow-lg mb-4">
                <img src="{{ Storage::url($club->club_banner) }}" alt="Club Banner" class="w-full h-full object-cover">
            </div>

            <!-- Club Logo and Name -->
            <div class="flex items-center space-x-6">
                <div class="h-32 w-32 rounded-full shadow-lg border-4 border-white -mt-12 overflow-hidden ml-4">
                    <img src="{{ Storage::url($club->club_logo) }}" alt="Club Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $club->club_name }}</h1>
                    <p class="text-gray-600">{{ $club->members_count }} Members</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Events -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Today's Events -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-4">
                        <h2 class="text-xl font-bold text-white">Today's Events</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse ($todayEvents as $event)
                            <div class="border-l-4 border-blue-500 pl-4 py-2 hover:bg-blue-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $event->event_name }}</h3>
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $event->event_time }} • {{ $event->event_location }}
                                        </div>
                                    </div>
                                    <a href="{{ route('clubs.events.index', [$club, $event]) }}"
                                        class="text-blue-600 hover:text-blue-800 ml-4">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="border-l-4 border-blue-500 pl-4 py-2 hover:bg-blue-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">No events today</h3>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-5 py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-white">Upcoming Events</h2>
                            <a href="{{ route('clubs.events.index', $club) }}"
                                class="text-white hover:text-purple-100 text-sm font-medium flex items-center">
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
                        @forelse ($upcomingEvents as $event)
                            <div
                                class="border-l-4 border-purple-500 pl-4 py-2 hover:bg-purple-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $event->event_name }}</h3>
                                        <div class="text-sm text-gray-600 mt-1">
                                            {{ $event->event_date->format('M d, Y') }} • {{ $event->event_location }}
                                        </div>
                                    </div>
                                    <a href="{{ route('clubs.events.index', [$club, $event]) }}"
                                        class="text-purple-600 hover:text-purple-800 ml-4">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div
                                class="border-l-4 border-purple-500 pl-4 py-2 hover:bg-purple-50 rounded-r-lg transition-all">
                                <div class="text-sm text-gray-600 mt-1">No upcoming events</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column: Posts -->
            <div class="lg:col-span-2">
                <!-- Create Post Card -->
                @can('create', [App\Models\Post::class, $club])
                    <div class="bg-white rounded-xl shadow-lg p-5 mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 rounded-full overflow-hidden ring-2 ring-blue-400">
                                <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}"
                                    alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                            </div>
                            <!-- Create Post Button/Modal -->

                            <div class="flex-1">
                                @include('clubs.posts.partials.create')
                            </div>

                        </div>
                    </div>
                @endcan

                @if ($posts->isEmpty())
                    <div
                        class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg mb-6 p-10 text-center transition-all duration-300 hover:shadow-xl">
                        <div class="flex flex-col items-center justify-center">
                            <!-- Animated SVG with subtle pulse effect -->
                            <div class="animate-pulse-slow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 text-blue-400 mb-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>

                            <!-- Improved typography and messaging -->
                            <h3 class="text-2xl font-bold text-gray-800 mb-3">No Posts Yet</h3>
                            <p class="text-gray-600 max-w-md">Looks like there aren't any posts available right now.</p>
                            <p>You can only create posts if you have permissions.</p>
                        </div>
                    </div>

                    <!-- Add this to your CSS file -->
                    <style>
                        @keyframes pulse-slow {

                            0%,
                            100% {
                                opacity: 1;
                                transform: scale(1);
                            }

                            50% {
                                opacity: 0.9;
                                transform: scale(1.03);
                            }
                        }

                        .animate-pulse-slow {
                            animation: pulse-slow 3s infinite ease-in-out;
                        }
                    </style>
                @else
                    {{-- POST CONTAINER --}}
                    @foreach ($posts as $post)
                        <div
                            class="post-container bg-white rounded-xl shadow-lg mb-6 overflow-hidden transform transition-all hover:shadow-xl">
                            <!-- Post Header -->
                            <div class="p-5 flex items-center justify-between border-b border-gray-200">
                                <div class="flex items-center space-x-3">
                                    <div class="h-12 w-12 rounded-full overflow-hidden ring-2 ring-blue-400">
                                        <img src="{{ asset('storage/profile_pictures/' . $post->author->profile_picture) }}"
                                            alt="{{ $post->author->name }}" class="h-full w-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $post->author->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center flex-wrap">
                                            <span>{{ $post->post_date->diffForHumans() }}</span>
                                            <span class="mx-1 text-blue-400">•</span>
                                            <span class="font-medium text-blue-600">{{ $post->author->role }}</span>
                                            <span class="mx-1">|</span>
                                            <span class="font-medium text-indigo-600">{{ $club->club_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                @can('update', $post)
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="text-gray-500 hover:text-blue-600 rounded-full p-2 hover:bg-gray-100 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200"
                                            x-cloak>
                                            <button
                                                @click="
                                            showEditModal = true;
                                            currentPostId = {{ $post->post_id }};
                                            editPostCaption = document.getElementById('post-caption-{{ $post->post_id }}').value;
                                            editPostVisibility = '{{ $post->post_visibility }}';
                                            currentPostImages = {{ $post->images->map(
                                                    fn($img) => [
                                                        'id' => $img->image_id,
                                                        'url' => Storage::url($img->image_path),
                                                    ],
                                                )->toJson() }};
                                        "
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-800">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Post
                                                </div>
                                            </button>
                                            <button
                                                @click="
                                            showDeleteModal = true;
                                            currentPostId = {{ $post->post_id }};
                                            "
                                                class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100 hover:text-red-800">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2"
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

                            <!-- Post Content -->
                            <div class="p-6 pb-4">
                                <p class="text-gray-800 leading-relaxed whitespace-pre-line">{{ $post->post_caption }}</p>
                            </div>

                            @if ($post->images->count() > 0)
                                <div class="relative">
                                    <!-- Images container -->
                                    <div class="grid grid-cols-12 gap-1 p-1">
                                        @php
                                            $totalImages = $post->images->count();
                                            $displayImages = $totalImages > 5 ? 5 : $totalImages;
                                        @endphp

                                        @if ($totalImages == 1)
                                            <!-- Single image layout -->
                                            <div class="col-span-12 h-96 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            </div>
                                        @elseif ($totalImages == 2)
                                            <!-- Two images layout -->
                                            @foreach ($post->images->take(2) as $image)
                                                <div class="col-span-6 h-64 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 3)
                                            <!-- Three images layout -->
                                            <div class="col-span-12 h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            </div>
                                            @foreach ($post->images->slice(1, 2) as $image)
                                                <div class="col-span-6 h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 4)
                                            <!-- Four images layout -->
                                            <div class="col-span-12 h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            </div>
                                            @foreach ($post->images->slice(1, 3) as $image)
                                                <div class="col-span-4 h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Five or more images layout -->
                                            <div class="col-span-8 h-80 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post image"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                            </div>
                                            <div class="col-span-4 grid grid-rows-2 gap-1">
                                                @foreach ($post->images->slice(1, 2) as $image)
                                                    <div class="h-40 overflow-hidden">
                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                            alt="Post image"
                                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
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
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">

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

                                    <!-- Hidden element to store post content -->
                                    <input type="hidden" id="post-caption-{{ $post->post_id }}"
                                        value="{{ $post->post_caption }}">
                                </div>

                                <!-- Image Carousel Modal -->
                                <template x-if="showFullGallery === {{ $post->post_id }}">
                                    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center"
                                        @keydown.left="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : {{ $totalImages - 1 }}"
                                        @keydown.right="currentImageIndex = currentImageIndex < {{ $totalImages - 1 }} ? currentImageIndex + 1 : 0"
                                        @keydown.escape="showFullGallery = null" tabindex="0">

                                        <!-- Close button -->
                                        <button @click="showFullGallery = null"
                                            class="absolute top-4 right-4 text-white hover:text-gray-300 z-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>

                                        <!-- Image counter -->
                                        <div class="absolute top-4 left-4 text-white font-medium">
                                            <span x-text="currentImageIndex + 1"></span> /
                                            <span>{{ $totalImages }}</span>
                                        </div>

                                        <!-- Previous button -->
                                        <button
                                            @click="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : {{ $totalImages - 1 }}"
                                            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70">
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
                                                    class="h-full max-h-full flex items-center justify-center">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post image"
                                                        class="max-h-full max-w-full object-contain">
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Next button -->
                                        <button
                                            @click="currentImageIndex = currentImageIndex < {{ $totalImages - 1 }} ? currentImageIndex + 1 : 0"
                                            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70">
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
                                                    class="h-16 w-16 overflow-hidden rounded border-2 transition-all duration-200"
                                                    :class="currentImageIndex === {{ $index }} ? 'border-blue-500' :
                                                        'border-transparent'">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Thumbnail"
                                                        class="h-full w-full object-cover">
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </template>
                            @endif
                        </div>
                    @endforeach
                @endif
                {{ $posts->links() }}
            </div>
        </div>

        <!-- Post Modals -->
        @include('clubs.posts.partials.edit-modal')
        @include('clubs.posts.partials.delete-modal')
    </div>
@endsection
