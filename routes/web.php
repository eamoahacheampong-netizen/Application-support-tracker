<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CommentController;
use App\Models\Activity;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // We added "with('comments.user')" so the database fetches the comments and the engineer's name efficiently
    $activities = Activity::with('comments.user')->where('user_id', auth()->id())->latest()->get();
    return view('dashboard', compact('activities'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Activity Routes
    Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
    Route::patch('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
    
    // NEW: Comment Route
    Route::post('/activities/{activity}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';