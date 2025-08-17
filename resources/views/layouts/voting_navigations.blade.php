@extends('layouts.dashboard')

@section('after_topbar')
    <!-- Voting Navigation placed right after the topbar -->
    <div class="bg-white border-b border-gray-200">
        <div class="container mx-auto px-4">
            <nav class="flex justify-between items-center">
                <!-- Left side: Navigation links -->
                <div class="flex space-x-12">
                    <a href="{{ route('voting.index') }}"
                        class="py-4 px-1 border-b-2 font-medium text-sm 
                      {{ request()->routeIs('voting.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Voting
                    </a>
                    <a href="{{ route('voting.responses') }}"
                        class="py-4 px-1 border-b-2 font-medium text-sm 
                      {{ request()->routeIs('voting.responses') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Responses
                    </a>
                </div>

                <!-- Right side: Voting Publish Toggle -->
                @if (auth()->user()->role === 'TEACHER')
                    @php
                        $election = \App\Models\Election::where('club_id', 1)->latest()->first();
                        $isPublished = $election ? $election->is_published : false;
                    @endphp
                    <div class="flex items-center space-x-2">
                        <span
                            class="text-sm text-gray-600">{{ $isPublished ? 'Voting Published' : 'Voting Unpublished' }}</span>
                        <form id="toggleVotingPublishedForm">
                            @csrf
                            <button type="button" onclick="toggleVotingPublished()"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 {{ $isPublished ? 'bg-blue-600' : 'bg-gray-200' }}">
                                <span class="sr-only">Toggle Voting Published</span>
                                <span
                                    class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 {{ $isPublished ? 'translate-x-6' : 'translate-x-1' }}"></span>
                            </button>
                        </form>
                    </div>
                @endif
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <div class="container mx-auto px-4">
        <!-- Content Section -->
        <div class="pb-6">
            @yield('voting_content')
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function toggleVotingPublished() {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch("/voting/toggle-published", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                });

                if (response.ok) {
                    const button = document.querySelector('#toggleVotingPublishedForm button');
                    const span = button.querySelector('span:not(.sr-only)');
                    const label = document.querySelector('#toggleVotingPublishedForm').previousElementSibling;

                    if (button.classList.contains('bg-gray-200')) {
                        button.classList.remove('bg-gray-200');
                        button.classList.add('bg-blue-600');
                        span.classList.remove('translate-x-1');
                        span.classList.add('translate-x-6');
                        label.textContent = 'Voting Published';
                    } else {
                        button.classList.remove('bg-blue-600');
                        button.classList.add('bg-gray-200');
                        span.classList.remove('translate-x-6');
                        span.classList.add('translate-x-1');
                        label.textContent = 'Voting Unpublished';
                    }

                    // After successful toggle, fetch updated checksum to invalidate all clients' checksums
                    await fetch('{{ route('voting.check-changes') }}');

                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className =
                        'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-up';
                    successAlert.textContent = 'Voting status updated successfully!';
                    document.body.appendChild(successAlert);

                    // Remove the success message after 3 seconds
                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
                } else {
                    console.error('Failed to toggle voting publication status');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    </script>
@endpush
