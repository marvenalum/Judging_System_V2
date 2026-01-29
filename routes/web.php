<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\JudgeController;
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
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::resource('events', AdminController::class, [
        'names' => [
            'index' => 'admin.event.index',
            'create' => 'admin.event.create',
            'store' => 'admin.event.store',
            'show' => 'admin.event.show',
            'edit' => 'admin.event.edit',
            'update' => 'admin.event.update',
            'destroy' => 'admin.event.destroy',
        ],
        'parameters' => [
            'events' => 'event',
        ],
    ]);
    Route::resource('categories', CategoryController::class, [
        'names' => [
            'index' => 'admin.category.index',
            'create' => 'admin.category.create',
            'store' => 'admin.category.store',
            'show' => 'admin.category.show',
            'edit' => 'admin.category.edit',
            'update' => 'admin.category.update',
            'destroy' => 'admin.category.destroy',
        ],
        'parameters' => [
            'categories' => 'category',
        ],
    ]);
    Route::resource('users', UserController::class, [
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ],
        'parameters' => [
            'users' => 'user',
        ],
    ]);
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
