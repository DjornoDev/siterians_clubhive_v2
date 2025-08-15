@section('title', $club->club_name . ' - Questions')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Registration Questions</h1>
                <p class="text-gray-600 mt-2">Manage questions that students must answer before joining your club</p>
            </div>
            <a href="{{ route('clubs.questions.create', $club) }}"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition-colors duration-200 
                   flex items-center gap-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Create Question
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($questions->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Current Questions ({{ $questions->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach ($questions as $question)
                        <div class="p-6 flex items-start justify-between hover:bg-gray-50">
                            <div class="flex-1">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                                            {{ $question->order + 1 }}
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ $question->question }}</h4>
                                        <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $question->question_type === 'text'
                                                       ? 'bg-blue-100 text-blue-800'
                                                       : ($question->question_type === 'textarea'
                                                           ? 'bg-green-100 text-green-800'
                                                           : ($question->question_type === 'select'
                                                               ? 'bg-purple-100 text-purple-800'
                                                               : 'bg-orange-100 text-orange-800')) }}">
                                                {{ ucfirst($question->question_type) }}
                                            </span>
                                            @if ($question->is_required)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Required
                                                </span>
                                            @endif
                                            @if (!$question->is_active)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </div>
                                        @if ($question->options && in_array($question->question_type, ['select', 'radio']))
                                            <div class="text-sm text-gray-600">
                                                <strong>Options:</strong> {{ implode(', ', $question->options) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('clubs.questions.edit', [$club, $question]) }}"
                                    class="text-blue-600 hover:text-blue-800 p-2 rounded-md hover:bg-blue-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <button
                                    onclick="confirmDelete('{{ $question->id }}', '{{ addslashes($question->question) }}')"
                                    class="text-red-600 hover:text-red-800 p-2 rounded-md hover:bg-red-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No questions yet</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first registration question.</p>
                <div class="mt-6">
                    <a href="{{ route('clubs.questions.create', $club) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Create Question
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="mt-2 px-7 py-3">
                    <h4 class="text-lg font-medium text-gray-900">Delete Question</h4>
                    <p class="text-sm text-gray-500 mt-1">
                        Are you sure you want to delete this question: "<span id="deleteQuestionText"></span>"?
                        This action cannot be undone and will also delete all student answers to this question.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <div class="flex gap-3 justify-end">
                        <button onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(questionId, questionText) {
            document.getElementById('deleteQuestionText').textContent = questionText;
            document.getElementById('deleteForm').action =
                `{{ route('clubs.questions.destroy', [$club, '']) }}/${questionId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection
