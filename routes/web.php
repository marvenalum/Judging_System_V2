<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return view('admin.dashboard');
    } elseif ($user->role === 'judge') {
        return view('judge.dashboard');
    } else {
        return view('participant.dashboard', ['submissions' => [], 'competitions' => []]);
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dashboard Routes
Route::prefix('admin')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
});

// Participant Dashboard Routes
Route::prefix('participant')->middleware(['auth', 'verified', 'role:participant'])->group(function () {
    Route::get('/dashboard', [ParticipantController::class, 'dashboard'])->name('participant.dashboard');
});

// Judge Dashboard Routes
Route::prefix('judge')->middleware(['auth', 'verified', 'role:judge'])->group(function () {
    Route::get('/dashboard', [JudgeController::class, 'dashboard'])->name('judge.dashboard');
});

require __DIR__.'/auth.php';
