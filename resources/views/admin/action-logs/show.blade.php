@extends('layouts.dashboard')

@section('title', 'Action Log Details | ClubHive')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Action Log Details</h1>
                    <p class="text-blue-100 mt-1">Detailed view of action log entry</p>
                </div>
                <a href="{{ route('admin.action-logs.index') }}"
                    class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition duration-200 backdrop-blur-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Logs
                </a>
            </div>
        </div>

        <!-- Log Details -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Log Entry #{{ $actionLog->id }}</h2>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $actionLog->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <i class="fas {{ $actionLog->status === 'success' ? 'fa-check' : 'fa-times' }} mr-2"></i>
                        {{ ucfirst($actionLog->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">User Information</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">User Name</label>
                            <p class="text-gray-900 font-medium">{{ $actionLog->user_name ?? 'System' }}</p>
                        </div>

                        @if ($actionLog->user_id)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">User ID</label>
                                <p class="text-gray-900">{{ $actionLog->user_id }}</p>
                            </div>
                        @endif

                        @if ($actionLog->user_role)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Role</label>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $actionLog->user_role === 'ADMIN' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $actionLog->user_role === 'TEACHER' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $actionLog->user_role === 'STUDENT' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $actionLog->user_role }}
                                </span>
                            </div>
                        @endif

                        @if ($actionLog->user && $actionLog->user->email)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">Email</label>
                                <p class="text-gray-900">{{ $actionLog->user->email }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Action Information
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Category</label>
                            <p class="text-gray-900 font-medium">
                                {{ ucfirst(str_replace('_', ' ', $actionLog->action_category)) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Action Type</label>
                            <p class="text-gray-900">{{ $actionLog->action_type }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Description</label>
                            <p class="text-gray-900">{{ $actionLog->action_description }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600">Date & Time</label>
                            <p class="text-gray-900">
                                {{ $actionLog->created_at->format('F d, Y \a\t h:i A') }}
                                <span class="text-sm text-gray-500">({{ $actionLog->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Technical Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Technical Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($actionLog->ip_address)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">IP Address</label>
                                <p class="text-gray-900 font-mono">{{ $actionLog->ip_address }}</p>
                            </div>
                        @endif

                        @if ($actionLog->user_agent)
                            <div>
                                <label class="block text-sm font-medium text-gray-600">User Agent</label>
                                <p class="text-gray-900 text-sm break-all">{{ $actionLog->user_agent }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Details (User-Friendly) -->
                @if ($actionLog->action_details)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">Additional
                            Details</h3>

                        <div class="bg-gray-50 rounded-lg p-4">
                            @php
                                $formattedDetails = $actionLog->getFormattedDetailsAttribute();
                            @endphp

                            @if (!empty($formattedDetails))
                                <div class="space-y-3">
                                    @foreach ($formattedDetails as $key => $value)
                                        <div class="flex">
                                            <span class="font-medium text-gray-600 w-1/3">{{ $key }}:</span>
                                            <span class="text-gray-900 w-2/3">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">No additional details available</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Logged {{ $actionLog->created_at->diffForHumans() }}
                    </div>

                    <div class="flex space-x-3">
                        @if ($actionLog->user)
                            <a href="{{ route('admin.users.index') }}?search={{ $actionLog->user->name }}"
                                class="text-blue-600 hover:text-blue-800 text-sm transition duration-200">
                                <i class="fas fa-user mr-1"></i>View User
                            </a>
                        @endif

                        <a href="{{ route('admin.action-logs.index') }}?action_search={{ $actionLog->action_type }}"
                            class="text-green-600 hover:text-green-800 text-sm transition duration-200">
                            <i class="fas fa-search mr-1"></i>Similar Actions
                        </a>

                        <button onclick="window.print()"
                            class="text-gray-600 hover:text-gray-800 text-sm transition duration-200">
                            <i class="fas fa-print mr-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    background: white !important;
                }

                .bg-gradient-to-r {
                    background: #1e40af !important;
                }
            }
        </style>
    @endpush
@endsection
