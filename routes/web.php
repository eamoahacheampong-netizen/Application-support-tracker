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
    // 1. Start a fresh query, ensuring we load relationships and lock it to the current logged-in user
    $query = Activity::with('comments.user')->where('user_id', auth()->id())->latest();

    // 2. If the user typed in the search box, filter by title OR description
    if (request()->filled('search')) {
        $query->where(function($q) {
            $q->where('title', 'like', '%' . request('search') . '%')
              ->orWhere('description', 'like', '%' . request('search') . '%');
        });
    }

    // 3. If the user selected a severity from the dropdown, filter by it
    if (request()->filled('severity')) {
        $query->where('severity', request('severity'));
    }

    // 4. Execute the query and grab the results
    $activities = $query->get();

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