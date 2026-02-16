<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\CriteriaController;
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
        return redirect()->route('judge.dashboard');
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
    Route::resource('criteria', CriteriaController::class, [
        'names' => [
            'index' => 'admin.criteria.index',
            'create' => 'admin.criteria.create',
            'store' => 'admin.criteria.store',
            'show' => 'admin.criteria.show',
            'edit' => 'admin.criteria.edit',
            'update' => 'admin.criteria.update',
            'destroy' => 'admin.criteria.destroy',
        ],
        'parameters' => [
            'criteria' => 'criterion',
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
    Route::get('/assigned-events', [JudgeController::class, 'assignedEvents'])->name('judge.assigned-events');
    Route::get('/my-events', [JudgeController::class, 'myEvents'])->name('judge.my-events');
    Route::get('/participants', [JudgeController::class, 'participants'])->name('judge.participants');
    Route::get('/manage-participants', [JudgeController::class, 'manageParticipants'])->name('judge.manage_participants.index');
    Route::get('/review-scores', [JudgeController::class, 'reviewScores'])->name('judge.review-scores');
    Route::get('/scoring', [JudgeController::class, 'reviewScores'])->name('judge.scoring.index');
    Route::get('/scoring/edit/{scoreId}', [JudgeController::class, 'scoringEdit'])->name('judge.scoring.edit');
    Route::put('/scoring/update/{scoreId}', [JudgeController::class, 'scoringUpdate'])->name('judge.scoring.update');
    Route::get('/profile', [JudgeController::class, 'profile'])->name('judge.profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('judge.profile.edit');
    Route::resource('events', AdminController::class, [
        'names' => [
            'index' => 'judge.event.index',
            'create' => 'judge.event.create',
            'store' => 'judge.event.store',
            'show' => 'judge.event.show',
            'edit' => 'judge.event.edit',
            'update' => 'judge.event.update',
            'destroy' => 'judge.event.destroy',
        ],
        'parameters' => [
            'events' => 'event',
        ],
    ]);
    Route::resource('categories', CategoryController::class, [
        'names' => [
            'index' => 'judge.category.index',
            'create' => 'judge.category.create',
            'store' => 'judge.category.store',
            'show' => 'judge.category.show',
            'edit' => 'judge.category.edit',
            'update' => 'judge.category.update',
            'destroy' => 'judge.category.destroy',
        ],
        'parameters' => [
            'categories' => 'category',
        ],
    ]);
    Route::resource('criteria', CriteriaController::class, [
        'names' => [
            'index' => 'judge.criteria.index',
            'create' => 'judge.criteria.create',
            'store' => 'judge.criteria.store',
            'show' => 'judge.criteria.show',
            'edit' => 'judge.criteria.edit',
            'update' => 'judge.criteria.update',
            'destroy' => 'judge.criteria.destroy',
        ],
        'parameters' => [
            'criteria' => 'criterion',
        ],
    ]);
    Route::resource('users', UserController::class, [
        'names' => [
            'index' => 'judge.users.index',
            'create' => 'judge.users.create',
            'store' => 'judge.users.store',
            'show' => 'judge.users.show',
            'edit' => 'judge.users.edit',
            'update' => 'judge.users.update',
            'destroy' => 'judge.users.destroy',
        ],
        'parameters' => [
            'users' => 'user',
        ],
    ]);
});

require __DIR__.'/auth.php';
