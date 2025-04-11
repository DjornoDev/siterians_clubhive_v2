<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostController;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use App\Models\Club;
use App\Models\Event;

Route::get('/', function () {
    return view('welcome'); //or auth.login to redirect to login page
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
    Route::get('/admin/dashboard', function () {
        $clubCount = Club::count();
        $userCounts = User::groupBy('role')
            ->selectRaw('role, count(*) as count')
            ->pluck('count', 'role')
            ->toArray();
        return view('admin.dashboard', compact('clubCount', 'userCounts'));
    })->middleware('role:ADMIN')->name('admin.dashboard');

    // Place your new admin routes inside the existing admin middleware group
    Route::middleware(['role:ADMIN'])->prefix('admin')->group(function () {
        // Users
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

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
        Route::post('/clubs', [App\Http\Controllers\ClubController::class, 'store'])->name('admin.clubs.store');
    });

    // AJAX Route for sections
    Route::get('/get-sections/{classId}', function ($classId) {
        $sections = App\Models\Section::where('class_id', $classId)->get();
        return response()->json($sections);
    });
    
    Route::middleware(['role:TEACHER,STUDENT'])->group(function () {
        // Home page with all clubs/posts
        Route::get('/home', [HomeController::class, 'index'])->name('home.index');

        // Global events list
        Route::get('/events', function () {
            $events = Event::with('club')->latest()->paginate(10);
            return view('events.index', compact('events'));
        })->name('events.index');

        // Club-specific routes - Consolidated group
        Route::prefix('clubs/{club}')->group(function () {
            Route::get('/', [ClubController::class, 'show'])
                ->middleware('can:club-access,club')
                ->name('clubs.show');
            Route::get('/events', [ClubController::class, 'events'])->name('clubs.events.index');
            Route::get('/people', [ClubController::class, 'people'])->name('clubs.people.index');
            Route::get('/voting', [ClubController::class, 'voting'])->name('clubs.voting.index');
            Route::get('/about', [ClubController::class, 'about'])->name('clubs.about.index');

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
        });
    });

    // Shared Club Routes (Teacher + Student)
    // Route::middleware(['role:TEACHER,STUDENT'])->group(function () {
    //     // Home page with all clubs/posts
    //     Route::get('/home', [HomeController::class, 'index'])->name('home.index');

    //     // Global events list
    //     Route::get('/events', function () {
    //         $events = Event::with('club')->latest()->paginate(10);
    //         return view('events.index', compact('events'));
    //     })->name('events.index');

    //     // Club-specific routes
    //     Route::prefix('clubs/{club}')->group(function () {
    //         Route::get('/', [ClubController::class, 'show'])
    //             ->middleware('can:club-access,club')
    //             ->name('clubs.show');
    //         Route::get('/events', [ClubController::class, 'events'])->name('clubs.events.index');
    //         Route::get('/people', [ClubController::class, 'people'])->name('clubs.people.index');
    //         Route::get('/voting', [ClubController::class, 'voting'])->name('clubs.voting.index');
    //         Route::get('/about', [ClubController::class, 'about'])->name('clubs.about.index');
    //     });
    // });

    // Route::middleware(['role:TEACHER,STUDENT'])->group(function () {
    //     Route::prefix('clubs/{club}')->group(function () {
    //         // Posts
    //         Route::get('/posts/create', [PostController::class, 'create'])->name('clubs.posts.create');
    //         Route::post('/posts', [PostController::class, 'store'])->name('clubs.posts.store');
    //         Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('clubs.posts.edit');
    //         Route::put('/posts/{post}', [PostController::class, 'update'])->name('clubs.posts.update');
    //         Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('clubs.posts.delete');

    //         // Events
    //         Route::get('/events/create', [EventController::class, 'create'])->name('clubs.events.create');
    //         Route::post('/events', [EventController::class, 'store'])->name('clubs.events.store');
    //         Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('clubs.events.edit');
    //         Route::put('/events/{event}', [EventController::class, 'update'])->name('clubs.events.update');
    //         Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('clubs.events.delete');
    //     });
    // });

    // TEACHER-only routes
    Route::middleware(['auth', 'role:TEACHER'])->group(function () {
        Route::get('/clubs/{club}/non-members', [ClubController::class, 'getNonMembers'])
            ->name('clubs.non-members');

        Route::post('/clubs/{club}/members', [ClubController::class, 'addMembers'])
            ->name('clubs.members.store');

        Route::put('/clubs/{club}/members/{user}', [ClubController::class, 'updateMember'])
            ->name('clubs.members.update');
    });

    // STUDENT-only routes
    Route::middleware(['role:STUDENT'])->group(function () {
        Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
    });

    Route::middleware(['role:STUDENT'])->group(function () {
        Route::post('/clubs/{club}/join', [ClubController::class, 'join'])->name('clubs.join');
    });

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
