<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Story;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if the user is authenticated and if they are an admin
    if (Auth::check() && Auth::user()->isAdmin) {
        $recentStories = Story::latest()->take(5)->get();
        return view('dashboard', compact('recentStories')); // Admin goes to the dashboard
    }

    // If not admin, redirect to the story page
    return redirect()->route('story.index'); // Adjust the route name for the story page
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('stories', [StoryController::class, 'index'])->name('stories.index');
    Route::get('stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('stories/{story}', [StoryController::class, 'show'])->name('stories.show');
    Route::get('stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
    Route::put('stories/{story}', [StoryController::class, 'update'])->name('stories.update');
    Route::delete('stories/{story}', [StoryController::class, 'destroy'])->name('stories.destroy');
    Route::get('/users/{user}/stories', [StoryController::class, 'userStories'])->name('users.stories')->middleware(AdminMiddleware::class);

    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware(AdminMiddleware::class);
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware(AdminMiddleware::class);
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware(AdminMiddleware::class);
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware(AdminMiddleware::class);
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware(AdminMiddleware::class);
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware(AdminMiddleware::class);
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show')->middleware(AdminMiddleware::class);

    Route::prefix('stories/{story}')->group(function () {
        Route::get('/branches', [SectionController::class, 'index'])->name('stories.branches');

        Route::get('/sections/create/{parent_id?}', [SectionController::class, 'create'])->name('sections.create');
        Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');

        Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
        Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');

        Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
        Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');
        Route::get('/sections/{section}/branches', [SectionController::class, 'showBranches'])->name('sections.branches');
    })->middleware(AdminMiddleware::class);
});

Route::get('/story', [FrontendController::class, 'index'])->name('story.index');
require __DIR__.'/auth.php';
