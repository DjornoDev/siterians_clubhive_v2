@section('title', $club->club_name . ' - Home')
@extends('clubs.layouts.navigation')

@section('club_content')
    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.95;
                transform: scale(1.01);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s infinite ease-in-out;
        }
    </style>
    <div x-data="{
        showEditModal: false,
        showDeleteModal: false,
        currentPostId: null,
        editPostCaption: '',
        editPostVisibility: 'CLUB_ONLY',
        currentPostImages: [],
        showFullGallery: null,
        currentImageIndex: 0,
        lastChecksum: '{{ md5(json_encode($posts->pluck('post_id')->merge($posts->pluck('updated_at')))) }}',
        checkForPostChanges() {
            // Skip refresh check if a modal is open (creating, editing, or deleting a post)
            if (this.showEditModal || this.showDeleteModal || this.showFullGallery) return;
    
            fetch('{{ route('clubs.check-post-changes', $club) }}?checksum=' + this.lastChecksum)
                .then(response => response.json())
                .then(data => {
                    if (data.hasChanges) {
                        window.location.reload();
                    }
                });
        },
        init() {
            // Check for post changes every 10 seconds
            setInterval(() => this.checkForPostChanges(), 10000);
        }
    }" class="max-w-7xl mx-auto px-4 py-6">
        @php
        // Check if club hunting day is active
        $isHuntingActive = \App\Models\Club::find(1)?->is_club_hunting_day ?? false;

        // Check if there's an active election
        $activeElection = \App\Models\Election::where('club_id', 1)
            ->where('is_published', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->latest()
            ->first(); // Determine if user can access clubs page
        $canAccessClubsPage = auth()->user()->role === 'STUDENT';

        // Determine if user can access voting page - only students and the adviser of club ID 1
        $canAccessVotingPage =
            auth()->user()->role === 'STUDENT' ||
            (auth()->user()->role === 'TEACHER' &&
                            auth()->user()->user_id === \App\Models\Club::find(1)?->club_adviser);
        @endphp @if ($isHuntingActive || $activeElection)

            <!-- Notification Section -->
            <div class="mb-5 sm:mb-6">
                @if ($isHuntingActive)
                    <div
                        class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg p-3 sm:p-4 mb-3 shadow-sm animate-pulse-slow">
                        <div class="flex items-start sm:items-center">
                            <div class="flex-shrink-0 mt-0.5 sm:mt-0">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-green-800">Club Hunting Day is ACTIVE!</p>
                                @if ($canAccessClubsPage)
                                    <p class="text-xs text-green-700 mt-0.5 sm:mt-1">You can now join clubs of your choice.
                                        Visit the
                                        <a href="{{ route('clubs.index') }}" class="underline font-medium">Clubs page</a> to
                                        explore all available clubs.
                                    </p>
                                @else
                                    <p class="text-xs text-green-700 mt-0.5 sm:mt-1">Students can now join clubs of their
                                        choice.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if ($activeElection)
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 border-l-4 border-blue-500 rounded-lg p-3 sm:p-4 shadow-sm animate-pulse-slow">
                        <div class="flex items-start sm:items-center">
                            <div class="flex-shrink-0 mt-0.5 sm:mt-0">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-2 sm:ml-3">
                                <p class="text-sm font-medium text-blue-800">Election Voting is ONGOING!</p>
                                @if ($canAccessVotingPage)
                                    @if (auth()->user()->role === 'TEACHER' && auth()->user()->user_id === \App\Models\Club::find(1)?->club_adviser)
                                        <p class="text-xs text-blue-700 mt-0.5 sm:mt-1">{{ $activeElection->title }} is now
                                            active.
                                            As the club adviser, you can monitor the voting process at the <a
                                                href="{{ route('voting.index') }}" class="underline font-medium">Voting
                                                page</a>.</p>
                                    @else
                                        <p class="text-xs text-blue-700 mt-0.5 sm:mt-1">{{ $activeElection->title }} is now
                                            active.
                                            Cast your vote at the <a href="{{ route('voting.index') }}"
                                                class="underline font-medium">Voting page</a>.</p>
                                    @endif
                                @else
                                    <p class="text-xs text-blue-700 mt-0.5 sm:mt-1">{{ $activeElection->title }} is now
                                        active.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif        <!-- Club Header Section -->
        <div class="mb-8">
            <!-- Club Banner -->
            <div class="h-60 sm:h-80 w-full rounded-xl overflow-hidden shadow-lg mb-4">
                <img src="{{ Storage::url($club->club_banner) }}" alt="Club Banner" class="w-full h-full object-cover cursor-pointer transition-transform duration-500 hover:scale-105" @click="showFullGallery = 'club_banner'; currentImageIndex = 0;">
            </div>

            <!-- Club Logo and Name -->
            <div class="flex flex-col sm:flex-row items-center sm:space-x-6 text-center sm:text-left">
                <div
                    class="h-24 w-24 sm:h-32 sm:w-32 rounded-full shadow-lg border-4 border-white -mt-12 overflow-hidden sm:ml-4 mb-4 sm:mb-0">
                    <img src="{{ Storage::url($club->club_logo) }}" alt="Club Logo" class="w-full h-full object-cover cursor-pointer transition-all duration-300 hover:scale-110" @click="showFullGallery = 'club_logo'; currentImageIndex = 0;">
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $club->club_name }}</h1>
                    <p class="text-gray-600">{{ $club->members_count }} Members</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Left Column: Events - Order changes on mobile (appears below posts) -->
            <div class="lg:col-span-1 space-y-5 sm:space-y-6 order-2 lg:order-1">
                <!-- Events Navigation for mobile -->
                <div class="block lg:hidden">
                    <div class="flex border-b border-gray-200 mb-4">
                        <button type="button"
                            class="flex-1 py-3 px-2 text-center border-b-2 border-blue-500 text-blue-600 font-medium">
                            Events
                        </button>
                        <button type="button" class="flex-1 py-3 px-2 text-center text-gray-500"
                            onclick="document.getElementById('posts-section').scrollIntoView({behavior: 'smooth'})">
                            Posts
                        </button>
                    </div>
                </div>

                <!-- Today's Events -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 sm:px-5 py-3 sm:py-4">
                        <h2 class="text-lg sm:text-xl font-bold text-white">Today's Events</h2>
                    </div>
                    <div class="p-4 sm:p-5 space-y-3 sm:space-y-4">
                        @forelse ($todayEvents as $event)
                            <div
                                class="border-l-4 border-blue-500 pl-3 sm:pl-4 py-2 hover:bg-blue-50 rounded-r-lg transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="mb-2 sm:mb-0">
                                        <h3 class="font-semibold text-gray-800">{{ $event->event_name }}</h3>
                                        <div class="text-xs sm:text-sm text-gray-600 mt-1">
                                            {{ $event->event_time }} • {{ $event->event_location }}
                                        </div>
                                    </div>
                                    <a href="{{ route('clubs.events.index', [$club, $event]) }}"
                                        class="text-blue-600 hover:text-blue-800 text-sm sm:ml-4">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div
                                class="border-l-4 border-blue-500 pl-3 sm:pl-4 py-2 hover:bg-blue-50 rounded-r-lg transition-all">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-800">No events today</h3>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div> 
                
                <!-- Upcoming Events -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-4 sm:px-5 py-3 sm:py-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg sm:text-xl font-bold text-white">Upcoming Events</h2>
                            <a href="{{ route('clubs.events.index', $club) }}"
                                class="text-white hover:text-purple-100 text-xs sm:text-sm font-medium flex items-center">
                                View All
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 ml-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5 space-y-3 sm:space-y-4">
                        @forelse ($upcomingEvents as $event)
                            <div
                                class="border-l-4 border-purple-500 pl-3 sm:pl-4 py-2 hover:bg-purple-50 rounded-r-lg transition-all">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                    <div class="mb-2 sm:mb-0">
                                        <h3 class="font-semibold text-gray-800">{{ $event->event_name }}</h3>
                                        <div class="text-xs sm:text-sm text-gray-600 mt-1">
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
            <div id="posts-section" class="lg:col-span-2 order-1 lg:order-2">
                <!-- Posts Navigation for mobile -->
                <div class="block lg:hidden">
                    <div class="flex border-b border-gray-200 mb-4">
                        <button type="button" class="flex-1 py-3 px-2 text-center text-gray-500"
                            onclick="document.querySelector('.order-2').scrollIntoView({behavior: 'smooth'})">
                            Events
                        </button>
                        <button type="button"
                            class="flex-1 py-3 px-2 text-center border-b-2 border-blue-500 text-blue-600 font-medium">
                            Posts
                        </button>
                    </div>
                </div>

                <!-- Create Post Card -->
                @can('create', [App\Models\Post::class, $club])
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-5 mb-5 sm:mb-6 border border-gray-100">
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <div
                                class="h-12 w-12 sm:h-12 sm:w-12 rounded-full overflow-hidden ring-2 ring-blue-400 flex-shrink-0 shadow-md">
                                @if (auth()->user()->profile_picture)
                                    <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}"
                                        alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                @else
                                    <div
                                        class="h-full w-full flex items-center justify-center bg-blue-100 text-blue-600 font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-28 w-28 text-blue-400 mb-6"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                            class="post-container bg-white rounded-xl shadow-lg mb-5 sm:mb-6 overflow-hidden transform transition-all hover:shadow-xl">
                            <!-- Post Header -->
                            <div class="p-3 sm:p-5 flex items-center justify-between border-b border-gray-200">
                                <div class="flex items-center space-x-2 sm:space-x-3">
                                    <div
                                        class="h-10 w-10 sm:h-12 sm:w-12 rounded-full overflow-hidden ring-2 ring-blue-400 flex-shrink-0">
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
                                    <div class="overflow-hidden">
                                        <div class="font-bold text-gray-800 truncate">{{ $post->author->name }}</div>
                                        <div class="text-xs text-gray-500 flex flex-wrap gap-1 sm:gap-0">
                                            <span>{{ $post->post_date->diffForHumans() }}</span>
                                            <span class="hidden sm:inline mx-1 text-blue-400">•</span>
                                            <span class="font-medium text-blue-600">{{ $post->author->role }}</span>
                                            <span class="hidden sm:inline mx-1">|</span>
                                            <span
                                                class="block sm:inline font-medium text-indigo-600 truncate max-w-[120px] sm:max-w-none">{{ $club->club_name }}</span>
                                            <span class="hidden sm:inline mx-1">|</span>
                                            <span class="block sm:inline">
                                                @include('clubs.partials.post-visibility-badge', [
                                                    'post' => $post,
                                                ])</span>
                                        </div>
                                    </div>
                                </div> 
                                
                                @can('update', $post)
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="text-gray-500 hover:text-blue-600 rounded-full p-1 sm:p-2 hover:bg-gray-100 transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 mt-2 w-40 sm:w-48 bg-white rounded-md shadow-lg z-10 border border-gray-200"
                                            x-cloak>
                                            <button
                                                @click="
                                            showEditModal = true;
                                            currentPostId = {{ $post->post_id }};
                                            // Set post caption directly instead of trying to access hidden element
                                            editPostCaption = `{{ $post->post_caption }}`;
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
                            <div class="px-4 py-3 sm:p-6 sm:pb-4">
                                <p class="text-gray-800 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                                    {{ $post->post_caption }}</p>
                            </div>

                            @if ($post->images->count() > 0)
                                <div class="relative">
                                    <!-- Images container -->
                                    <div class="grid grid-cols-12 gap-1 p-1">
                                        @php
                                            $totalImages = $post->images->count();
                                            $displayImages = $totalImages > 5 ? 5 : $totalImages;
                                        @endphp @if ($totalImages == 1)
                                            <!-- Single image layout -->
                                            <div class="col-span-12 h-60 sm:h-96 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post content"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                        @elseif ($totalImages == 2)
                                            <!-- Two images layout -->
                                            @foreach ($post->images->take(2) as $index => $image)
                                                <div class="col-span-6 h-48 sm:h-64 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post content"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer""
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index }};">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 3)
                                            <!-- Three images layout -->
                                            <div class="col-span-12 h-48 sm:h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post content"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            @foreach ($post->images->slice(1, 2) as $index => $image)
                                                <div class="col-span-6 h-36 sm:h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post content"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer""
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index + 1 }};">
                                                </div>
                                            @endforeach
                                        @elseif ($totalImages == 4)
                                            <!-- Four images layout -->
                                            <div class="col-span-12 h-48 sm:h-64 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post content"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            @foreach ($post->images->slice(1, 3) as $index => $image)
                                                <div class="col-span-4 h-32 sm:h-48 overflow-hidden">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post content"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer""
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $index + 1 }};">
                                                </div>
                                            @endforeach
                                        @else
                                            <!-- Five or more images layout -->
                                            <div class="col-span-12 sm:col-span-8 h-48 sm:h-80 overflow-hidden">
                                                <img src="{{ Storage::url($post->images[0]->image_path) }}"
                                                    alt="Post content"
                                                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer"
                                                    @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 grid grid-cols-2 sm:grid-cols-1 sm:grid-rows-2 gap-1">
                                                @foreach ($post->images->slice(1, 2) as $index => $image)
                                                    <div class="h-32 sm:h-40 overflow-hidden">
                                                        <img src="{{ Storage::url($image->image_path) }}"
                                                            alt="Post content"
                                                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer""
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
                                                <div class="col-span-6 h-28 sm:h-36 overflow-hidden relative">
                                                    <img src="{{ Storage::url($image->image_path) }}" alt="Post content"
                                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 cursor-pointer""
                                                        @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = {{ $key + 3 }};">

                                                    @if ($key == $lastImageIndex && $totalImages > 5)
                                                        <!-- Show more overlay on the last visible image -->
                                                        <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center cursor-pointer"
                                                            @click="showFullGallery = {{ $post->post_id }}; currentImageIndex = 0;">
                                                            <div class="text-white text-center">
                                                                <span
                                                                    class="text-lg sm:text-xl font-bold">+{{ $totalImages - 5 }}</span>
                                                                <p class="text-xs sm:text-sm">View all</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
                <div class="mt-4 sm:mt-6">
                    {{ $posts->links() }}
                </div>
            </div>
        </div> 
        
        <!-- Global Image Carousel Modal -->
        <template x-if="showFullGallery !== null">
            <div class="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center"
                @keydown.escape="showFullGallery = null"
                @keydown.left="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : (document.querySelectorAll(`[data-post-gallery='${showFullGallery}']`).length - 1)"
                @keydown.right="currentImageIndex = currentImageIndex < (document.querySelectorAll(`[data-post-gallery='${showFullGallery}']`).length - 1) ? currentImageIndex + 1 : 0"
                tabindex="0">

                <!-- Close button -->
                <button @click="showFullGallery = null"
                    class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 transition-transform hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Image counter -->
                <div class="absolute top-4 left-4 text-white font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full">
                    <span x-text="currentImageIndex + 1"></span> /
                    <span x-text="document.querySelectorAll(`[data-post-gallery='${showFullGallery}']`).length"></span>
                </div>

                <!-- Previous button -->
                <button
                    @click="currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : (document.querySelectorAll(`[data-post-gallery='${showFullGallery}']`).length - 1)"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-transform hover:scale-110 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>                <!-- Main image container -->
                <div class="h-full w-full flex items-center justify-center p-8">
                    <!-- Club banner image -->
                    <div x-show="showFullGallery === 'club_banner'" data-post-gallery="club_banner"
                        class="h-full max-h-full flex items-center justify-center transform transition-opacity"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95">
                        <img src="{{ Storage::url($club->club_banner) }}" alt="Club Banner"
                            class="max-h-full max-w-full object-contain shadow-2xl">
                    </div>
                    
                    <!-- Club logo image -->
                    <div x-show="showFullGallery === 'club_logo'" data-post-gallery="club_logo"
                        class="h-full max-h-full flex items-center justify-center transform transition-opacity"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95">
                        <img src="{{ Storage::url($club->club_logo) }}" alt="Club Logo"
                            class="max-h-full max-w-full object-contain shadow-2xl">
                    </div>
                    
                    <!-- Post images -->
                    @foreach ($posts as $post)
                        @foreach ($post->images as $index => $image)
                            <div x-show="showFullGallery === {{ $post->post_id }} && currentImageIndex === {{ $index }}"
                                data-post-gallery="{{ $post->post_id }}"
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
                    @endforeach
                </div>

                <!-- Next button -->
                <button
                    @click="currentImageIndex = currentImageIndex < (document.querySelectorAll(`[data-post-gallery='${showFullGallery}']`).length - 1) ? currentImageIndex + 1 : 0"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-transform hover:scale-110 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <!-- Thumbnail navigation -->
                <div
                    class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 overflow-x-auto px-4 py-2 bg-black bg-opacity-50 rounded-lg max-w-3xl">
                    @foreach ($posts as $post)
                        <template x-if="showFullGallery === {{ $post->post_id }}">
                            <div class="flex space-x-2">
                                @foreach ($post->images as $index => $image)
                                    <button @click="currentImageIndex = {{ $index }}"
                                        class="h-16 w-16 overflow-hidden rounded-md border-2 transition-all duration-200 hover:opacity-90"
                                        :class="currentImageIndex === {{ $index }} ? 'border-blue-500 scale-110' :
                                            'border-transparent'">
                                        <img src="{{ Storage::url($image->image_path) }}" alt="Thumbnail"
                                            class="h-full w-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    @endforeach
                </div>
            </div>
        </template>

        <!-- Post Modals -->
        @include('clubs.posts.partials.edit-modal')
        @include('clubs.posts.partials.delete-modal')
    </div>
@endsection
<style>
    @keyframes pulse-slow {

        0%,
        100% {
            opacity: 1;
            transform: scale(1);
        }

        50% {
            opacity: 0.95;
            transform: scale(1.01);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s infinite ease-in-out;
    }
</style>
