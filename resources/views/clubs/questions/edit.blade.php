@section('title', $club->club_name . ' - Edit Question')
@extends('clubs.layouts.navigation')

@section('club_content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('clubs.questions.index', $club) }}" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Questions</span>
                            <svg class="flex-shrink-0 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z" />
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <a href="{{ route('clubs.questions.index', $club) }}"
                                class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                                Questions
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ml-4 text-sm font-medium text-gray-900">Edit Question</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Edit Question</h1>
                <p class="mt-1 text-sm text-gray-600">Modify your registration question</p>
            </div>

            <form action="{{ route('clubs.questions.update', [$club, $question]) }}" method="POST"
                class="px-6 py-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Question Text -->
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700 mb-2">
                        Question <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="question" name="question" value="{{ old('question', $question->question) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Enter your question..." required>
                    @error('question')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Question Type -->
                <div>
                    <label for="question_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Question Type <span class="text-red-500">*</span>
                    </label>
                    <select id="question_type" name="question_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        onchange="toggleOptionsSection()" required>
                        <option value="">Select question type</option>
                        <option value="text"
                            {{ old('question_type', $question->question_type) === 'text' ? 'selected' : '' }}>Short Text
                        </option>
                        <option value="textarea"
                            {{ old('question_type', $question->question_type) === 'textarea' ? 'selected' : '' }}>Long Text
                        </option>
                        <option value="select"
                            {{ old('question_type', $question->question_type) === 'select' ? 'selected' : '' }}>Dropdown
                        </option>
                        <option value="radio"
                            {{ old('question_type', $question->question_type) === 'radio' ? 'selected' : '' }}>Multiple
                            Choice</option>
                    </select>
                    @error('question_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options Section (for select/radio) -->
                <div id="optionsSection" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Options <span class="text-red-500">*</span>
                    </label>
                    <div id="optionsContainer">
                        @php
                            $options = old('options', $question->options ?? []);
                        @endphp
                        @if (!empty($options))
                            @foreach ($options as $index => $option)
                                <div class="flex items-center gap-2 mb-2 option-row">
                                    <input type="text" name="options[]" value="{{ $option }}"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter option...">
                                    <button type="button" onclick="removeOption(this)"
                                        class="text-red-600 hover:text-red-800 p-2">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center gap-2 mb-2 option-row">
                                <input type="text" name="options[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter option...">
                                <button type="button" onclick="removeOption(this)"
                                    class="text-red-600 hover:text-red-800 p-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <div class="flex items-center gap-2 mb-2 option-row">
                                <input type="text" name="options[]"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Enter option...">
                                <button type="button" onclick="removeOption(this)"
                                    class="text-red-600 hover:text-red-800 p-2">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" onclick="addOption()"
                        class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Option
                    </button>
                    @error('options')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('options.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Required Toggle -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_required" name="is_required" value="1"
                        {{ old('is_required', $question->is_required) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_required" class="ml-2 block text-sm text-gray-700">
                        Required question (students must answer this question)
                    </label>
                </div>

                <!-- Order -->
                <div>
                    <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                        Display Order
                    </label>
                    <input type="number" id="order" name="order" value="{{ old('order', $question->order) }}"
                        min="0"
                        class="w-32 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="0">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first (0 = first)</p>
                    @error('order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active (show this question to students)
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('clubs.questions.index', $club) }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleOptionsSection() {
            const questionType = document.getElementById('question_type').value;
            const optionsSection = document.getElementById('optionsSection');

            if (questionType === 'select' || questionType === 'radio') {
                optionsSection.classList.remove('hidden');
            } else {
                optionsSection.classList.add('hidden');
            }
        }

        function addOption() {
            const container = document.getElementById('optionsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'flex items-center gap-2 mb-2 option-row';
            newRow.innerHTML = `
        <input type="text"
               name="options[]"
               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
               placeholder="Enter option...">
        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 p-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    `;
            container.appendChild(newRow);
        }

        function removeOption(button) {
            const container = document.getElementById('optionsContainer');
            const rows = container.querySelectorAll('.option-row');

            // Don't allow removing if only one option remains
            if (rows.length > 1) {
                button.closest('.option-row').remove();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleOptionsSection();
        });
    </script>
@endsection
