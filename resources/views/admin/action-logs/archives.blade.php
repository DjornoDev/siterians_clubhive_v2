@extends('layouts.dashboard')

@section('title', 'Action Log Archives | ClubHive')

@section('content')
    <!-- Include Archives Password Protection Modal -->
    @include('admin.action-logs.partials.archives-password-modal')

    <div class="p-4 sm:p-2">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-700 to-purple-800 rounded-xl shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Action Log Archives</h1>
                    <p class="text-indigo-100 mt-1">Download and manage archived action logs</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.action-logs.index') }}"
                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition duration-200 backdrop-blur-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Archive Information:</strong> Action logs older than 30 days are automatically archived to
                        JSON files.
                        You can download these files to keep historical records or for analysis purposes.
                    </p>
                </div>
            </div>
        </div>

        <!-- Archives Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-archive mr-2 text-indigo-600"></i>
                    Archived Files
                    <span class="ml-3 text-sm font-normal text-gray-500">({{ count($archives) }} files)</span>
                </h2>
            </div>

            @if (count($archives) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-file-archive mr-1"></i>Archive File
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>Archive Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-weight mr-1"></i>File Size
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($archives as $archive)
                                <tr class="hover:bg-gray-50 transition duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                                    <i class="fas fa-file-code text-indigo-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $archive['filename'] }}
                                                </div>
                                                <div class="text-sm text-gray-500">JSON Archive File</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($archive['date'])->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($archive['date'])->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $archive['size'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.action-logs.download-archive', $archive['filename']) }}"
                                            class="text-indigo-600 hover:text-indigo-900 transition duration-200 inline-flex items-center">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="mx-auto h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-archive text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Archives Found</h3>
                        <p class="text-gray-500 text-sm mb-4">
                            No archived action logs are available. Archives are created automatically when you run the
                            cleanup process.
                        </p>
                        <div class="bg-gray-50 rounded-lg p-4 text-left">
                            <h4 class="font-medium text-gray-900 mb-2">How archives are created:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Action logs older than 30 days are automatically archived</li>
                                <li>• Archives are created when you click "Cleanup Old Logs"</li>
                                <li>• Archived files are saved as JSON format</li>
                                <li>• Files are stored in: <code
                                        class="bg-gray-200 px-1 rounded">storage/app/action_logs_archives/</code></li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Storage Location Info -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-2 flex items-center">
                <i class="fas fa-folder mr-2 text-gray-600"></i>Storage Location
            </h3>
            <p class="text-sm text-gray-600 mb-2">
                Archive files are stored in: <code
                    class="bg-gray-200 px-2 py-1 rounded text-xs">storage/app/action_logs_archives/</code>
            </p>
            <p class="text-xs text-gray-500">
                You can also manually access these files from your server's file system if needed.
            </p>
        </div>
    </div>
@endsection
