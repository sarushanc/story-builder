<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Check if the user is authenticated and if they are an admin
    if (Auth::check() && Auth::user()->isAdmin) {
        return view('dashboard'); // Admin goes to the dashboard
    }

    // If not admin, redirect to the story page
    return redirect()->route('story.index'); // Adjust the route name for the story page
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/stories', [FrontendController::class, 'index'])->name('story.index');
require __DIR__.'/auth.php';
