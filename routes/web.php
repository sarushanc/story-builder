<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\NonAdmin;
use App\Models\Story;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->middleware('guest');

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->isAdmin) {
        $recentStories = Story::latest()->take(5)->get();
        return view('dashboard', compact('recentStories'));
    }

    return redirect()->route('story.index');
})->middleware(['auth', 'verified', AdminMiddleware::class])->name('dashboard');

// Route::middleware('auth', AdminMiddleware::class)->group(function () {
//     Route::get('stories', [StoryController::class, 'index'])->name('stories.index');
//     Route::get('stories/create', [StoryController::class, 'create'])->name('stories.create');
//     Route::post('stories', [StoryController::class, 'store'])->name('stories.store');
//     Route::get('stories/{story}', [StoryController::class, 'show'])->name('stories.show');
//     Route::get('stories/{story}/edit', [StoryController::class, 'edit'])->name('stories.edit');
//     Route::put('stories/{story}', [StoryController::class, 'update'])->name('stories.update');


//     Route::prefix('stories/{story}')->group(function () {
    //         Route::get('/branches', [SectionController::class, 'index'])->name('stories.branches');

    //         Route::get('/sections/create/{parent_id?}', [SectionController::class, 'create'])->name('sections.create');
    //         Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');

    //         Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');

    //         Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    //         Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');
    //         Route::get('/sections/{section}/branches', [SectionController::class, 'showBranches'])->name('sections.branches');
    //         Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    //     });
    // });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/frontend', [FrontendController::class, 'index'])->name('story.index');
        Route::get('/frontend/stories', [FrontendController::class, 'stories'])->name('story.stories');
        Route::get('/frontend/create', [FrontendController::class, 'create'])->name('story.create');
        Route::post('/frontend/store', [FrontendController::class, 'store'])->name('story.store');
        Route::post('/frontend/section/store', [FrontendController::class, 'sectionStore'])->name('section.store');
        Route::get('/frontend/show/{story}', [FrontendController::class, 'show'])->name('story.show');
        Route::post('/frontend/{section}/like', [FrontendController::class, 'like'])->name('section.like');
        Route::get('/frontend/{id}/download-ebook', [FrontendController::class, 'downloadEbook'])->name('story.downloadEbook');
        Route::delete('stories/{story}', [FrontendController::class, 'destroyStory'])->name('story.destroy');
        Route::post('/multimedia/store', [FrontendController::class, 'storyMultimedia'])->name('multimedia.store');
        Route::post('/section/{id}/add-multimedia', [FrontendController::class, 'sectionMultimedia'])->name('section.addMultimedia');
        Route::get('/rankings', [FrontendController::class, 'rankings'])->name('rankings');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

        Route::get('/contactus', [UserController::class, 'contactUs'])->name('contact.us');
    Route::get('/users/{user}/stories', [UserController::class, 'userStories'])->name('users.stories');
});
require __DIR__.'/auth.php';
