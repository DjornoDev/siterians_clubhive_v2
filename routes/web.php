<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubQuestionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeUpdateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\PostController;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Post;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Club;
use App\Models\Event;
use App\Http\Controllers\VotingController;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index']);

// Sitero Infographics Route
Route::get('/sitero-infographics', function () {
    return view('sitero-infographics.index');
})->name('sitero.infographics');

Route::get('/clubs/{id}', function ($id) {
    $club = Club::findOrFail($id);
    return redirect()->route('clubs.show', $club, 301);
})->where('id', '[0-9]+'); // Add this numeric constraint

// Debug route to test club resolution
Route::get('/debug/club/{club}', function ($club) {
    return response()->json([
        'club_id' => $club->club_id,
        'club_name' => $club->club_name,
        'resolved' => true
    ]);
});

// Remove 'verified' middleware and add role-based routes
Route::middleware(['auth'])->group(function () {
    // Dynamic dashboard redirection
    Route::get('/dashboard', function () {
        return auth()->user()->role === 'ADMIN'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home.index');
    })->name('dashboard');

    // Role-specific dashboards
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->middleware('role:ADMIN')->name('admin.dashboard');

    // Place your new admin routes inside the existing admin middleware group
    Route::middleware(['role:ADMIN'])->prefix('admin')->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/check-exists', [UserController::class, 'checkExists'])->name('admin.users.check-exists');
        Route::get('/users/export', [UserController::class, 'export'])->name('admin.users.export');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/users/{user}/details', [UserController::class, 'getUserDetails'])->name('admin.users.details');
        Route::post('/users/bulk-delete', [UserController::class, 'bulkDestroy'])->name('admin.users.bulk-destroy');

        // Sections
        Route::post('/sections', [App\Http\Controllers\SectionController::class, 'store'])->name('admin.sections.store');

        // Clubs
        Route::get('/clubs', function () {
            $teachers = User::where('role', 'TEACHER')
                ->whereNotIn('user_id', Club::pluck('club_adviser'))
                ->get();
            $advisers = User::where('role', 'TEACHER')->get();
            $clubs = Club::all();
            return view('admin.clubs.index', compact('clubs', 'teachers', 'advisers'));
        })->name('admin.clubs.index');

        Route::put('/clubs/{club}', [ClubController::class, 'update'])->name('admin.clubs.update');
        Route::delete('/clubs/{club}', [ClubController::class, 'destroy'])->name('admin.clubs.destroy');
        // Separate route for password verification before deletion
        Route::post('/clubs/{club}/verify-and-delete', [ClubController::class, 'verifyAndDelete'])->name('admin.clubs.verify-delete');
        Route::post('/clubs', [App\Http\Controllers\ClubController::class, 'store'])->name('admin.clubs.store');
        Route::get('/clubs/check-name-exists', [ClubController::class, 'checkClubNameExists'])->name('admin.clubs.check-name-exists');

        // Action Logs
        Route::get('/action-logs', [App\Http\Controllers\Admin\ActionLogController::class, 'index'])->name('admin.action-logs.index');
        Route::get('/action-logs/archives', [App\Http\Controllers\Admin\ActionLogController::class, 'archives'])->name('admin.action-logs.archives');
        Route::get('/action-logs/archives/download/{filename}', [App\Http\Controllers\Admin\ActionLogController::class, 'downloadArchive'])->name('admin.action-logs.download-archive');
        Route::post('/action-logs/cleanup', [App\Http\Controllers\Admin\ActionLogController::class, 'cleanup'])->name('admin.action-logs.cleanup');
        Route::get('/action-logs/{actionLog}', [App\Http\Controllers\Admin\ActionLogController::class, 'show'])->name('admin.action-logs.show');
        Route::get('/api/users/suggestions', [App\Http\Controllers\Admin\ActionLogController::class, 'getUserSuggestions'])->name('admin.action-logs.user-suggestions');
        Route::get('/api/actions/suggestions', [App\Http\Controllers\Admin\ActionLogController::class, 'getActionSuggestions'])->name('admin.action-logs.action-suggestions');

        // Password verification routes for action logs
        Route::post('/action-logs/verify-password', [App\Http\Controllers\Admin\ActionLogController::class, 'verifyPassword'])->name('admin.action-logs.verify-password');
        Route::post('/action-logs/verify-archives-password', [App\Http\Controllers\Admin\ActionLogController::class, 'verifyArchivesPassword'])->name('admin.action-logs.verify-archives-password');

        // Enhanced Export Routes
        Route::prefix('export')->group(function () {
            Route::get('/users', [App\Http\Controllers\ExportController::class, 'exportUsers'])->name('admin.export.users');
            Route::get('/clubs', [App\Http\Controllers\ExportController::class, 'exportClubs'])->name('admin.export.clubs');
            Route::get('/action-logs', [App\Http\Controllers\ExportController::class, 'exportActionLogs'])->name('admin.export.action-logs');
        });

        // Admin posts and events management routes
        Route::get('/posts', function (Request $request) {
            $query = Post::with(['club', 'author'])->orderBy('created_at', 'desc');

            // Apply filters if they exist
            if ($request->filled('club')) {
                $query->where('club_id', $request->club);
            }

            if ($request->filled('visibility')) {
                $query->where('post_visibility', $request->visibility);
            }

            $posts = $query->paginate(15)->withQueryString();
            return view('admin.posts.index', compact('posts'));
        })->name('admin.posts.index');

        Route::get('/events', function (Request $request) {
            $query = Event::with(['club', 'organizer'])->orderBy('event_date', 'desc');

            // Apply filters if they exist
            if ($request->filled('club')) {
                $query->where('club_id', $request->club);
            }

            if ($request->filled('visibility')) {
                $query->where('event_visibility', $request->visibility);
            }

            if ($request->filled('date_filter')) {
                switch ($request->date_filter) {
                    case 'upcoming':
                        $query->where('event_date', '>=', today());
                        break;
                    case 'past':
                        $query->where('event_date', '<', today());
                        break;
                    case 'today':
                        $query->whereDate('event_date', today());
                        break;
                }
            }

            $events = $query->paginate(15)->withQueryString();
            return view('admin.events.index', compact('events'));
        })->name('admin.events.index');
    });

    // Route removed: admin.clubs.send-announcement

    Route::post('/admin/users/bulk', [UserController::class, 'bulkStore'])
        ->name('admin.users.bulk.store')
        ->middleware('role:ADMIN');

    // AJAX Route for sections
    Route::get('/get-sections/{classId}', function ($classId) {
        $sections = App\Models\Section::where('class_id', $classId)->get();
        return response()->json($sections);
    });

    Route::middleware(['role:TEACHER,STUDENT'])->group(function () {
        // Home page with all clubs/posts
        Route::get('/home', [HomeController::class, 'index'])->name('home.index');
        Route::get('/home/check-post-changes', [HomeUpdateController::class, 'checkPostChanges'])->name('home.check-post-changes');
        Route::get('/home/check-event-changes', [HomeUpdateController::class, 'checkEventChanges'])->name('home.check-event-changes');

        // Global events list
        Route::get('/events', [EventController::class, 'globalIndex'])->name('events.index');
        Route::get('/events/check-changes', [EventController::class, 'checkGlobalEventChanges'])->name('events.check-changes');

        // Event approval routes (for SSLG adviser only)
        Route::get('/events/pending', [EventController::class, 'pendingEvents'])->name('events.pending');
        Route::get('/events/{event}/approval', [EventController::class, 'showForApproval'])->name('events.approval.show');
        Route::post('/events/{event}/approve', [EventController::class, 'approve'])->name('events.approve');
        Route::post('/events/{event}/reject', [EventController::class, 'reject'])->name('events.reject');
        Route::get('/events/{event}/download-document', [EventController::class, 'downloadSupportingDocument'])->name('events.download-document');
        Route::get('/event-documents/{document}/download', [EventController::class, 'downloadDocument'])->name('events.documents.download');
        Route::get('/post-documents/{document}/download', [PostController::class, 'downloadDocument'])->name('posts.documents.download');

        // Club-specific routes - Consolidated group
        Route::prefix('clubs/{club}')->group(function () {
            Route::get('/', [ClubController::class, 'show'])
                ->middleware('can:view,club')
                ->name('clubs.show');

            Route::get('/check-post-changes', [ClubController::class, 'checkPostChanges'])
                ->middleware('can:view,club')
                ->name('clubs.check-post-changes');

            Route::get('/check-event-changes', [ClubController::class, 'checkEventChanges'])
                ->middleware('can:view,club')
                ->name('clubs.check-event-changes');

            Route::middleware('can:view,club')->group(function () {
                Route::get('/events', [ClubController::class, 'events'])->name('clubs.events.index');
                Route::get('/people', [ClubController::class, 'people'])->name('clubs.people.index');
                Route::get('/about', [ClubController::class, 'about'])->name('clubs.about.index');
            });

            // Move posts and events routes here
            Route::get('/posts/create', [PostController::class, 'create'])->name('clubs.posts.create');
            Route::post('/posts', [PostController::class, 'store'])->name('clubs.posts.store');
            Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('clubs.posts.edit');
            Route::put('/posts/{post}', [PostController::class, 'update'])->name('clubs.posts.update');
            Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('clubs.posts.delete');

            Route::get('/events/create', [EventController::class, 'create'])->name('clubs.events.create');
            Route::post('/events', [EventController::class, 'store'])->name('clubs.events.store');
            Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('clubs.events.edit');
            Route::put('/events/{event}', [EventController::class, 'update'])->name('clubs.events.update');
            Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('clubs.events.delete');

            Route::get('/events/{event}', [EventController::class, 'show'])
                ->name('clubs.events.show');

            // Club Questions Management (for club advisers) - no middleware here, handled in controller
            Route::get('/questions', [ClubQuestionController::class, 'index'])->name('clubs.questions.index');
            Route::get('/questions/create', [ClubQuestionController::class, 'create'])->name('clubs.questions.create');
            Route::post('/questions', [ClubQuestionController::class, 'store'])->name('clubs.questions.store');
            Route::get('/questions/{question}/edit', [ClubQuestionController::class, 'edit'])->name('clubs.questions.edit');
            Route::put('/questions/{question}', [ClubQuestionController::class, 'update'])->name('clubs.questions.update');
            Route::delete('/questions/{question}', [ClubQuestionController::class, 'destroy'])->name('clubs.questions.destroy');
            Route::get('/join-requests/{request_id}/answers', [ClubQuestionController::class, 'viewAnswers'])->name('clubs.join-requests.answers');

            // API routes for questions (for students joining)
            Route::get('/api/questions', [ClubQuestionController::class, 'getQuestionsForJoin'])->name('clubs.api.questions');
            Route::post('/api/join-with-answers', [ClubQuestionController::class, 'submitAnswersAndJoinRequest'])->name('clubs.api.join-with-answers');
        });
    });

    // TEACHER-only routes
    Route::middleware(['auth', 'role:TEACHER'])->group(function () {
        Route::get('/clubs/{club}/non-members', [ClubController::class, 'getNonMembers'])
            ->name('clubs.non-members');

        Route::post('/clubs/{club}/members', [ClubController::class, 'addMembers'])
            ->name('clubs.members.store');

        Route::put('/clubs/{club}/members/{user}', [ClubController::class, 'updateMember'])
            ->name('clubs.members.update');

        Route::patch('/clubs/{club}/members/{user}/status', [ClubController::class, 'updateMemberStatus'])
            ->name('clubs.members.update-status');

        Route::delete('/clubs/{club}/members/{user}', [ClubController::class, 'removeMember'])
            ->name('clubs.members.destroy');

        Route::delete('/clubs/{club}/members', [ClubController::class, 'removeBulkMembers'])
            ->name('clubs.members.bulk-destroy');

        Route::get('/clubs/{club}/members/{user}/profile', [ClubController::class, 'showMemberProfile'])
            ->name('clubs.members.profile');

        Route::put('/clubs/{club}/update-settings', [ClubController::class, 'updateSettings'])
            ->name('clubs.update-settings');

        // Join request management routes
        Route::post('/clubs/{club}/join-requests/{joinRequest}/approve', [ClubController::class, 'approveJoinRequest'])
            ->name('clubs.join-requests.approve');

        Route::post('/clubs/{club}/join-requests/{joinRequest}/reject', [ClubController::class, 'rejectJoinRequest'])
            ->name('clubs.join-requests.reject');

        Route::post('/clubs/{club}/toggle-approval', [ClubController::class, 'toggleApprovalRequirement'])
            ->name('clubs.toggle-approval');

        // Teacher Export Routes
        Route::prefix('clubs/{club}/export')->group(function () {
            Route::get('/membership', [App\Http\Controllers\ExportController::class, 'exportClubMembership'])->name('clubs.export.membership');
            Route::get('/events', [App\Http\Controllers\ExportController::class, 'exportClubEvents'])->name('clubs.export.events');
            Route::get('/voting-results', [App\Http\Controllers\ExportController::class, 'exportVotingResults'])->name('clubs.export.voting-results');
        });
    });

    // Add a route that both ADMIN and TEACHER can access for the toggle hunting day feature
    Route::middleware(['auth'])->group(function () {
        Route::post('/clubs/{club}/toggle-hunting-day', [ClubController::class, 'toggleHuntingDay'])
            ->middleware('role:ADMIN,TEACHER')
            ->name('clubs.toggle-hunting-day');
    });

    // STUDENT-only routes
    Route::middleware(['role:STUDENT'])->group(function () {
        Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    });

    Route::middleware(['role:STUDENT'])->group(function () {
        Route::post('/clubs/{club}/join', [ClubController::class, 'join'])->name('clubs.join');
    });

    // Club-specific voting routes
    Route::middleware(['auth'])->group(function () {
        Route::prefix('clubs/{club}/voting')->name('clubs.voting.')->group(function () {
            // Main voting index
            Route::get('/', [App\Http\Controllers\VotingController::class, 'index'])->name('index');

            // Teacher/Adviser routes
            Route::middleware(['auth'])->group(function () {
                // Store route for creating new voting
                Route::post('/store', [App\Http\Controllers\VotingController::class, 'store'])->name('store');

                // Toggle published status
                Route::post('/toggle-published', [App\Http\Controllers\VotingController::class, 'togglePublished'])->name('toggle-published');

                // Search for students
                Route::get('/search-students', [App\Http\Controllers\VotingController::class, 'searchStudents'])->name('search-students');

                // Save candidates
                Route::post('/save-candidate', [App\Http\Controllers\VotingController::class, 'saveCandidate'])->name('save-candidate');

                // Edit candidate
                Route::get('/edit-candidate/{candidateId}', [App\Http\Controllers\VotingController::class, 'editCandidate'])->name('edit-candidate');

                // Update candidate
                Route::post('/update-candidate/{candidateId}', [App\Http\Controllers\VotingController::class, 'updateCandidate'])->name('update-candidate');

                // Delete candidate
                Route::delete('/delete-candidate/{candidateId}', [App\Http\Controllers\VotingController::class, 'deleteCandidate'])->name('delete-candidate');

                // Reset voting data route - only for club advisers
                Route::post('/reset', [App\Http\Controllers\VotingController::class, 'resetVotingData'])->name('reset');

                // Route for checking changes in voting data (for real-time updates)
                Route::get('/check-changes', [App\Http\Controllers\VotingController::class, 'checkVotingChanges'])->name('check-changes');

                // Get candidates for student voting
                Route::get('/candidates', [App\Http\Controllers\VotingController::class, 'getCandidates'])->name('candidates');

                // Submit vote
                Route::post('/submit', [App\Http\Controllers\VotingController::class, 'submitVote'])->name('submit');

                // Check if user has voted
                Route::get('/check-voted/{electionId}', [App\Http\Controllers\VotingController::class, 'checkVoted'])->name('check-voted');

                // Get user's vote details
                Route::get('/my-vote/{electionId}', [App\Http\Controllers\VotingController::class, 'getMyVote'])->name('my-vote');

                // Teacher-specific routes
                Route::get('/elections', [App\Http\Controllers\VotingController::class, 'getTeacherElections'])->name('elections');
                Route::get('/results/{electionId}', [App\Http\Controllers\VotingController::class, 'getElectionResults'])->name('results');

                // Update member positions after election
                Route::post('/update-positions/{electionId}', [App\Http\Controllers\VotingController::class, 'updateMemberPositions'])->name('update-positions');

                // Voting responses
                Route::get('/responses', [App\Http\Controllers\VotingController::class, 'responses'])->name('responses');
            });
        });
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
