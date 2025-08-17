<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\ClubQuestion;
use App\Models\ClubJoinRequest;
use App\Models\ClubQuestionAnswer;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Hashids\Hashids;

class ClubQuestionController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request, Club $club)
    {
        // Check if user is authorized to view club questions
        $this->authorize('manage', $club);

        $questions = $club->questions()->orderBy('order')->get();

        return view('clubs.questions.index', compact('club', 'questions'));
    }

    public function create(Club $club)
    {
        $this->authorize('manage', $club);

        return view('clubs.questions.create', compact('club'));
    }

    public function store(Request $request, Club $club)
    {
        Log::info('ClubQuestionController@store called', [
            'club_id' => $club->club_id,
            'request_data' => $request->all()
        ]);

        $this->authorize('manage', $club);
        Log::info('Authorization passed in store');

        $request->validate([
            'question' => 'required|string|max:255',
            'question_type' => 'required|in:text,textarea,select,radio',
            'options' => 'nullable|array',
            'options.*' => 'required_if:question_type,select,radio|string|max:255',
            'is_required' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        $options = null;
        if (in_array($request->question_type, ['select', 'radio']) && $request->options) {
            // Filter out empty options
            $options = array_filter($request->options, function ($option) {
                return !is_null($option) && trim($option) !== '';
            });

            // Re-index the array to avoid gaps
            $options = array_values($options);

            Log::info('Processed options:', ['options' => $options]);
        }

        $question = ClubQuestion::create([
            'club_id' => $club->club_id,
            'question' => $request->question,
            'question_type' => $request->question_type,
            'options' => $options,
            'is_required' => $request->boolean('is_required', true),
            'order' => $request->order ?? 0,
            'is_active' => true
        ]);

        // Log question creation
        ActionLog::create_log(
            'club_management',
            'created',
            "Created club question for {$club->club_name}: {$question->question}",
            [
                'club_id' => $club->club_id,
                'club_name' => $club->club_name,
                'question_id' => $question->question_id,
                'question_type' => $question->question_type
            ]
        );

        Log::info('Question created successfully');

        return redirect()->route('clubs.questions.index', $club->getRouteKey())
            ->with('success', 'Question created successfully!');
    }

    public function edit(Club $club, ClubQuestion $question)
    {
        $this->authorize('manage', $club);

        // Check if question belongs to this club
        if ($question->club_id !== $club->club_id) {
            abort(403);
        }

        return view('clubs.questions.edit', compact('club', 'question'));
    }

    public function update(Request $request, Club $club, ClubQuestion $question)
    {
        $this->authorize('manage', $club);

        // Check if question belongs to this club
        if ($question->club_id !== $club->club_id) {
            abort(403);
        }

        $request->validate([
            'question' => 'required|string|max:255',
            'question_type' => 'required|in:text,textarea,select,radio',
            'options' => 'nullable|array',
            'options.*' => 'required_if:question_type,select,radio|string|max:255',
            'is_required' => 'boolean',
            'order' => 'integer|min:0',
            'is_active' => 'boolean'
        ]);

        $options = null;
        if (in_array($request->question_type, ['select', 'radio']) && $request->options) {
            $options = array_filter($request->options); // Remove empty options
        }

        $question->update([
            'question' => $request->question,
            'question_type' => $request->question_type,
            'options' => $options,
            'is_required' => $request->boolean('is_required'),
            'order' => $request->order ?? $question->order,
            'is_active' => $request->boolean('is_active')
        ]);

        return redirect()->route('clubs.questions.index', $club->getRouteKey())
            ->with('success', 'Question updated successfully!');
    }

    public function destroy(Club $club, ClubQuestion $question)
    {
        $this->authorize('manage', $club);

        // Check if question belongs to this club
        if ($question->club_id !== $club->club_id) {
            abort(403);
        }

        $question->delete();

        return redirect()->route('clubs.questions.index', $club->getRouteKey())
            ->with('success', 'Question deleted successfully!');
    }

    // Get questions for join request (API endpoint)
    public function getQuestionsForJoin(Club $club)
    {
        $questions = $club->questions()->active()->ordered()->get();

        return response()->json(['questions' => $questions]);
    }

    // Submit answers and create join request
    public function submitAnswersAndJoinRequest(Request $request, Club $club)
    {
        $questions = $club->activeQuestions;

        // Validate answers
        $rules = ['message' => 'nullable|string'];
        foreach ($questions as $question) {
            $fieldName = "answers.{$question->id}";
            if ($question->is_required) {
                $rules[$fieldName] = 'required';
            } else {
                $rules[$fieldName] = 'nullable';
            }
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $club, $questions) {
            // Create join request
            $joinRequest = ClubJoinRequest::create([
                'club_id' => $club->club_id,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'message' => $request->message
            ]);

            // Save answers
            foreach ($questions as $question) {
                $answer = $request->input("answers.{$question->id}");
                if ($answer !== null && $answer !== '') {
                    ClubQuestionAnswer::create([
                        'club_join_request_id' => $joinRequest->request_id,
                        'club_question_id' => $question->id,
                        'user_id' => Auth::id(),
                        'answer' => $answer
                    ]);
                }
            }
        });

        return response()->json(['success' => true, 'message' => 'Join request submitted successfully!']);
    }

    // View answers for a specific join request
    public function viewAnswers(Club $club, $request_id)
    {
        $this->authorize('manage', $club);

        $joinRequest = ClubJoinRequest::with(['user', 'questionAnswers.clubQuestion'])
            ->where('request_id', $request_id)
            ->where('club_id', $club->club_id)
            ->firstOrFail();

        return view('clubs.questions.answers', compact('club', 'joinRequest'));
    }
}
