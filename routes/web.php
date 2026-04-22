<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\CriteriaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/healthz', function () {
    return response()->json(['status' => 'ok']);
});

Route::get('/dashboard', function (): \Illuminate\Http\RedirectResponse|\Illuminate\View\View {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return view('admin.dashboard');
    } elseif ($user->role === 'judge') {
        return redirect()->route('judge.dashboard');
    } else {
        return redirect()->route('participant.dashboard');
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
    
    // Toggle criteria status (activate/deactivate)
    Route::post('/criteria/{criterion}/toggle-status', [CriteriaController::class, 'toggleStatus'])->name('admin.criteria.toggleStatus');
    
    // Participant management routes
    Route::get('/participants', [AdminController::class, 'participants'])->name('admin.participants.index');
    Route::post('/participants/{submission}/approve', [AdminController::class, 'approveParticipant'])->name('admin.participants.approve');
    Route::post('/participants/{submission}/decline', [AdminController::class, 'declineParticipant'])->name('admin.participants.decline');
    
    // Participant submission CRUD
    Route::get('/participants/{submission}/edit', [AdminController::class, 'editParticipant'])->name('admin.participants.edit');
    Route::patch('/participants/{submission}', [AdminController::class, 'updateParticipant'])->name('admin.participants.update');
    Route::delete('/participants/{submission}', [AdminController::class, 'destroyParticipant'])->name('admin.participants.destroy');
});

// Participant Dashboard Routes
Route::prefix('participant')->middleware(['auth', 'verified', 'role:participant'])->group(function () {
    Route::get('/dashboard', [ParticipantController::class, 'dashboard'])->name('participant.dashboard');
    Route::get('/settings', [ParticipantController::class, 'settings'])->name('participant.settings');
    
    // Events CRUD
    Route::get('/events', [ParticipantController::class, 'eventIndex'])->name('participant.event.index');
    Route::get('/events/create', [ParticipantController::class, 'eventCreate'])->name('participant.event.create');
    Route::post('/events', [ParticipantController::class, 'eventStore'])->name('participant.event.store');
    Route::get('/events/{event}', [ParticipantController::class, 'eventShow'])->name('participant.event.show');
    Route::get('/events/{event}/edit', [ParticipantController::class, 'eventEdit'])->name('participant.event.edit');
    Route::put('/events/{event}', [ParticipantController::class, 'eventUpdate'])->name('participant.event.update');
    Route::delete('/events/{event}', [ParticipantController::class, 'eventDestroy'])->name('participant.event.destroy');
    
    // Apply to Event
    Route::post('/events/{event}/apply', [ParticipantController::class, 'eventApply'])->name('participant.event.apply');
    
    // Categories CRUD
    Route::get('/categories', [ParticipantController::class, 'categoryIndex'])->name('participant.category.index');
    Route::get('/categories/create', [ParticipantController::class, 'categoryCreate'])->name('participant.category.create');
    Route::post('/categories', [ParticipantController::class, 'categoryStore'])->name('participant.category.store');
    Route::get('/categories/{category}', [ParticipantController::class, 'categoryShow'])->name('participant.category.show');
    Route::get('/categories/{category}/edit', [ParticipantController::class, 'categoryEdit'])->name('participant.category.edit');
    Route::put('/categories/{category}', [ParticipantController::class, 'categoryUpdate'])->name('participant.category.update');
    Route::delete('/categories/{category}', [ParticipantController::class, 'categoryDestroy'])->name('participant.category.destroy');
    
    // Criteria CRUD
    Route::get('/criteria', [ParticipantController::class, 'criteriaIndex'])->name('participant.criteria.index');
    Route::get('/criteria/create', [ParticipantController::class, 'criteriaCreate'])->name('participant.criteria.create');
    Route::post('/criteria', [ParticipantController::class, 'criteriaStore'])->name('participant.criteria.store');
    Route::get('/criteria/{criterion}', [ParticipantController::class, 'criteriaShow'])->name('participant.criteria.show');
    Route::get('/criteria/{criterion}/edit', [ParticipantController::class, 'criteriaEdit'])->name('participant.criteria.edit');
    Route::put('/criteria/{criterion}', [ParticipantController::class, 'criteriaUpdate'])->name('participant.criteria.update');
    Route::delete('/criteria/{criterion}', [ParticipantController::class, 'criteriaDestroy'])->name('participant.criteria.destroy');
    
    // Users (View Only)
    Route::get('/users', [ParticipantController::class, 'userIndex'])->name('participant.users.index');
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
    Route::get('/scoring/by-category', [JudgeController::class, 'scoringByCategory'])->name('judge.scoring.category');
    Route::get('/scoring/by-category/{category}/participant/{participant}', [JudgeController::class, 'scoreParticipantByCategory'])->name('judge.scoring.category.participant');
    Route::post('/scoring/by-category/{category}/participant/{participant}', [JudgeController::class, 'storeScoreByCategory'])->name('judge.scoring.category.participant.store');
    Route::get('/scoring/participants', [JudgeController::class, 'scoringParticipantsTable'])->name('judge.scoring.participants');
    Route::get('/scores/by-category', [JudgeController::class, 'scoresByCategory'])->name('judge.scores.by-category');
    
    // Bulk scoring routes
    Route::get('/scoring/bulk/{category}', [JudgeController::class, 'bulkScoreCategory'])->name('judge.scoring.bulk');
    Route::post('/scoring/bulk/{category}', [JudgeController::class, 'storeBulkScores'])->name('judge.scoring.bulk.store');
    
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
    
    // Score entry routes for criteria
    Route::get('/criteria/{criterion}/score/create', [CriteriaController::class, 'createScore'])->name('judge.criteria.createScore');
    Route::post('/criteria/{criterion}/score', [CriteriaController::class, 'storeScore'])->name('judge.criteria.storeScore');
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
