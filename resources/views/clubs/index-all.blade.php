@extends('layouts.dashboard')

@section('title', 'All Clubs | ClubHive')

@push('styles')
<style>
    /* Custom animation for fading in elements */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
      /* Pulse effect for the membership badge */
    .pulse-shadow {
        box-shadow: 0 0 0 rgba(52, 211, 153, 0.4);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(52, 211, 153, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(52, 211, 153, 0);
        }
    }
      /* Confetti particles */
    .confetti {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 2px;
        opacity: 0;
    }
    
    .confetti-animation {
        animation-name: confetti-fall, confetti-shake;
        animation-duration: 3s, 2s;
        animation-timing-function: ease-out, ease-in-out;
        animation-iteration-count: 1, infinite;
        animation-fill-mode: forwards;
        animation-play-state: running;
    }
    
    @keyframes confetti-fall {
        0% {
            opacity: 1;
            transform: translateY(-10px);
        }
        100% {
            opacity: 0;
            transform: translateY(150px);
        }
    }
      @keyframes confetti-shake {
        0% {
            transform: translateX(0) rotate(0deg);
        }
        25% {
            transform: translateX(15px) rotate(90deg);
        }
        50% {
            transform: translateX(-10px) rotate(180deg);
        }
        75% {
            transform: translateX(15px) rotate(270deg);
        }
        100% {
            transform: translateX(-10px) rotate(360deg);
        }
    }
</style>
@endpush

@push('styles')
<style>
    /* Custom animation for fading in elements */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }
    
    /* Pulse effect for the membership badge */
    @keyframes gentlePulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    
    .pulse-effect {
        animation: gentlePulse 2s infinite;
    }
</style>
@endpush

@section('content')
    @php
        $isHuntingActive = \App\Models\Club::find(1)?->is_club_hunting_day ?? false;
    @endphp
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Explore All Clubs</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-6">
            @forelse ($clubs as $club)
                <div
                    class="bg-white rounded-lg shadow-md hover:shadow-xl overflow-hidden transform transition-all duration-300 hover:scale-105 flex flex-col h-full border border-gray-100">
                    <!-- Banner Section -->
                    <div class="relative h-40 bg-gray-100">
                        @if ($club->club_banner)
                            <img src="{{ asset(Storage::url($club->club_banner)) }}" alt="{{ $club->club_name }} Banner"
                                class="w-full h-full object-cover">
                        @endif

                        <!-- Logo Overlay -->
                        <div class="absolute -bottom-10 left-4">
                            <div
                                class="w-20 h-20 rounded-full border-4 border-white shadow-md bg-white flex items-center justify-center overflow-hidden">
                                @if ($club->club_logo)
                                    <img src="{{ asset(Storage::url($club->club_logo)) }}" alt="{{ $club->club_name }} Logo"
                                        class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Club Details -->
                    <div class="pt-14 px-4 pb-4 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-800 mb-1 truncate">{{ $club->club_name }}</h3>

                        @if ($club->club_description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex-grow">{{ $club->club_description }}</p>
                        @else
                            <div class="flex-grow mb-2"></div>
                        @endif

                        <div class="border-t border-gray-100 pt-3 mt-auto">
                            <div class="flex items-center mb-3 text-sm">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-gray-700 font-medium truncate">Adviser:
                                    {{ $club->adviser->name ?? 'No Adviser Assigned' }}
                                </span>
                            </div>

                            <!-- View Details Button -->
                            <button
                                class="view-details-btn w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-md transition-colors duration-200 flex items-center justify-center"
                                data-club-id="{{ $club->club_id }}" data-club-name="{{ $club->club_name }}"
                                data-club-adviser="{{ $club->adviser->name ?? 'No Adviser Assigned' }}"
                                data-club-description="{{ $club->club_description }}"
                                data-club-logo="{{ $club->club_logo ? Storage::url($club->club_logo) : '' }}"
                                data-club-banner="{{ $club->club_banner ? Storage::url($club->club_banner) : '' }}"
                                data-is-member="{{ $club->members->isNotEmpty() ? 'true' : 'false' }}">
                                <span>View Details</span>
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10">
                    <div class="text-gray-500 text-lg">No clubs found.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('modals')
    <div id="clubDetailsModal"
        class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative max-w-2xl w-full rounded-lg shadow-2xl bg-white overflow-hidden">
            <!-- Close button -->
            <button onclick="closeModal()"
                class="absolute top-3 right-3 z-10 bg-white bg-opacity-70 rounded-full p-1 hover:bg-opacity-100 transition-all duration-200 shadow-md">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Banner Section -->
            <div class="relative h-48 bg-gradient-to-r from-blue-500 to-purple-600">
                <img id="modalClubBanner" src="" alt="Club Banner" class="w-full h-full object-cover">
            </div>

            <!-- Content Area -->
            <div class="relative bg-white px-6 pb-6">
                <!-- Logo positioned at the top of content area, overlapping the banner -->
                <div class="absolute -top-12 left-6">
                    <div class="w-24 h-24 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden">
                        <img id="modalClubLogo" src="" alt="Club Logo" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Club Header Info -->
                <div class="pt-16 pb-4">
                    <h2 id="modalClubName" class="text-2xl font-bold text-gray-800"></h2>
                    <p id="modalClubAdviser" class="text-sm font-medium text-gray-600 mt-1"></p>
                </div>

                <!-- Divider -->
                <div class="border-b border-gray-200 my-4"></div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">About this club</h3>
                    <p id="modalClubDescription" class="text-gray-600 leading-relaxed"></p>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 mt-8">                    <!-- Already joined club status -->
                    <div id="alreadyJoinedMessage" 
                        class="relative text-center w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium py-4 px-5 rounded-lg shadow-md hidden border border-green-400 transition-all duration-300 transform pulse-shadow overflow-hidden">                        <!-- Confetti elements -->
                        <div id="confettiContainer" class="absolute inset-0 -top-20 -bottom-20 overflow-hidden pointer-events-none z-0"></div>
                          <!-- Badge and Text -->
                        <div class="flex flex-col sm:flex-row items-center justify-center sm:space-x-3 relative z-10">
                            <!-- Badge/Icon -->
                            <div class="bg-white bg-opacity-20 p-2 rounded-full mb-2 sm:mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            
                            <!-- Text Content -->
                            <div>
                                <h3 class="text-lg font-semibold">You're already a member of this club!</h3>
                                <p class="text-green-100 text-sm mt-1">Access club activities, events and posts</p>
                            </div>
                        </div>
                          <!-- Action Button -->
                        <div class="flex justify-center mt-3 relative z-10">
                            <a id="visitClubLink" href="#" class="bg-white bg-opacity-10 hover:bg-opacity-20 px-4 py-2 rounded-full text-white font-medium text-sm inline-flex items-center transition-colors duration-200 border border-white border-opacity-20 hover:border-opacity-30">
                                <span>Go to club page</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <button id="joinClubButton"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg shadow-sm transition-colors duration-200 hidden">
                        <div class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Join Club
                        </div>
                    </button>
                    <button onclick="closeModal()"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 rounded-lg transition-colors duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        const isHuntingActive = @json($isHuntingActive);

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = document.getElementById('clubDetailsModal');
                    const isMember = this.dataset.isMember === 'true';

                    // Populate modal content
                    document.getElementById('modalClubName').textContent = this.dataset.clubName;
                    document.getElementById('modalClubAdviser').textContent =
                        `Adviser: ${this.dataset.clubAdviser}`;
                    document.getElementById('modalClubDescription').textContent = this.dataset
                        .clubDescription;
                    document.getElementById('modalClubLogo').src = this.dataset.clubLogo;
                    document.getElementById('modalClubBanner').src = this.dataset.clubBanner;   // Handle Join button and already joined message
                    const joinButton = document.getElementById('joinClubButton');
                    const alreadyJoinedMessage = document.getElementById('alreadyJoinedMessage');
                      // Show Join button only if hunting is active AND user is not already a member
                    joinButton.classList.toggle('hidden', isMember || !isHuntingActive);
                    joinButton.onclick = () => handleJoinClub(this.dataset.clubId);
                      // If user is already a member, show "already joined" message with animation
                    if (isMember) {
                        // First remove hidden class
                        alreadyJoinedMessage.classList.remove('hidden');                        // Set the club page link
                        document.getElementById('visitClubLink').href = `/clubs/${this.dataset.clubId}`;
                        // Create confetti effect
                        createConfetti();
                        // Then add animation classes after a small delay
                        setTimeout(() => {
                            alreadyJoinedMessage.classList.add('animate-fadeIn', 'scale-105');
                            setTimeout(() => {
                                alreadyJoinedMessage.classList.remove('scale-105');
                            }, 300);
                        }, 10);
                    } else {
                        alreadyJoinedMessage.classList.add('hidden');
                    }

                    modal.classList.remove('hidden');
                });
            });
        });

        async function toggleHuntingDay() {
            try {
                const response = await fetch("{{ route('clubs.toggle-hunting-day', \App\Models\Club::find(1)) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    location.reload(); // Refresh to update all Join buttons
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }        function closeModal() {
            document.getElementById('clubDetailsModal').classList.add('hidden');
        }
          // Function to create confetti effect
        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            if (!container) return;
            
            // Clear previous confetti
            container.innerHTML = '';
            
            // Create confetti particles
            const colors = ['#10B981', '#34D399', '#6EE7B7', '#A7F3D0', '#ECFDF5', '#FFFFFF'];
            const confettiCount = 40;
            
            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                
                // Random color
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                
                // Random position
                confetti.style.left = `${Math.random() * 100}%`;
                
                // Random size (slightly larger to be more visible)
                const size = Math.random() * 10 + 5;
                confetti.style.width = `${size}px`;
                confetti.style.height = `${size}px`;
                
                // Random initial rotation
                const rotation = Math.random() * 360;
                confetti.style.transform = `rotate(${rotation}deg)`;
                
                // Random animation delay for cascade effect
                const delay = i * 50;
                confetti.style.animationDelay = `${delay}ms, ${delay}ms`;
                
                // Random animation duration to create varied movement
                const duration = (Math.random() * 2) + 2; // 2-4 seconds
                confetti.style.animationDuration = `${duration}s, ${duration * 0.8}s`;
                
                // Add to container
                container.appendChild(confetti);
                
                // Start animation after a slight delay
                setTimeout(() => {
                    confetti.classList.add('confetti-animation');
                }, 10);
            }
        }
        
        function handleJoinClub(clubId) {
            fetch(`/clubs/${clubId}/join`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {                        // If user is already a member, show the already joined message and hide join button
                        if (data.message && data.message.includes('already a member')) {
                            const joinButton = document.getElementById('joinClubButton');
                            const alreadyJoinedMessage = document.getElementById('alreadyJoinedMessage');
                            
                            joinButton.classList.add('hidden');                            // Show with animation
                            alreadyJoinedMessage.classList.remove('hidden');                            // Set the club page link
                            document.getElementById('visitClubLink').href = `/clubs/${clubId}`;
                            // Create confetti effect
                            createConfetti();
                            setTimeout(() => {
                                alreadyJoinedMessage.classList.add('animate-fadeIn', 'scale-105');
                                setTimeout(() => {
                                    alreadyJoinedMessage.classList.remove('scale-105');
                                }, 300);
                            }, 10);
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to join club');
                });
        }
    </script>
@endpush
