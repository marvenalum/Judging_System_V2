<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Lightweight healthcheck — no DB or session dependency
Route::get('/healthz', function () {
    return response('OK', 200);
});

Route::get('/', function () {
    return view('welcome');
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
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'mark_as_read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'mark_all_as_read'])->name('notifications.read-all');
    Route::get('/notifications/preferences', [NotificationController::class, 'preferences'])->name('notifications.preferences');
    Route::patch('/notifications/preferences', [NotificationController::class, 'update_preferences'])->name('notifications.preferences.update');
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

    // Scoring Rubrics management
    Route::resource('scoring-rubrics', \App\Http\Controllers\Admin\ScoringRubricController::class, [
        'names' => [
            'index' => 'admin.scoring-rubrics.index',
            'create' => 'admin.scoring-rubrics.create',
            'store' => 'admin.scoring-rubrics.store',
            'show' => 'admin.scoring-rubrics.show',
            'edit' => 'admin.scoring-rubrics.edit',
            'update' => 'admin.scoring-rubrics.update',
            'destroy' => 'admin.scoring-rubrics.destroy',
        ],
        'parameters' => [
            'scoring-rubrics' => 'scoringRubric',
        ],
    ]);

    // Result Publications management
    Route::resource('result-publications', \App\Http\Controllers\Admin\ResultPublicationController::class, [
        'names' => [
            'index' => 'admin.result-publications.index',
            'create' => 'admin.result-publications.create',
            'store' => 'admin.result-publications.store',
            'show' => 'admin.result-publications.show',
            'edit' => 'admin.result-publications.edit',
            'update' => 'admin.result-publications.update',
            'destroy' => 'admin.result-publications.destroy',
        ],
        'parameters' => [
            'result-publications' => 'resultPublication',
        ],
    ]);
    Route::post('/result-publications/{resultPublication}/publish', [\App\Http\Controllers\Admin\ResultPublicationController::class, 'publish'])->name('admin.result-publications.publish');
    Route::post('/result-publications/{resultPublication}/archive', [\App\Http\Controllers\Admin\ResultPublicationController::class, 'archive'])->name('admin.result-publications.archive');
    Route::post('/result-publications/{resultPublication}/refresh', [\App\Http\Controllers\Admin\ResultPublicationController::class, 'refreshResults'])->name('admin.result-publications.refresh');

    // Bulk Import/Export management
    Route::get('/bulk-import', [\App\Http\Controllers\Admin\BulkImportController::class, 'index'])->name('admin.bulk-import.index');
    Route::post('/bulk-import/export', [\App\Http\Controllers\Admin\BulkImportController::class, 'export'])->name('admin.bulk-import.export');
    Route::post('/bulk-import/import', [\App\Http\Controllers\Admin\BulkImportController::class, 'import'])->name('admin.bulk-import.import');
    Route::get('/bulk-import/template', [\App\Http\Controllers\Admin\BulkImportController::class, 'downloadTemplate'])->name('admin.bulk-import.template');
    Route::get('/bulk-import/categories', [\App\Http\Controllers\Admin\BulkImportController::class, 'getCategories'])->name('admin.bulk-import.categories');
    Route::get('/bulk-import/criteria', [\App\Http\Controllers\Admin\BulkImportController::class, 'getCriteria'])->name('admin.bulk-import.criteria');

    // Toggle user status (active/inactive)
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    
    // Participant management routes
    Route::get('/participants', [AdminController::class, 'participants'])->name('admin.participants.index');
    Route::post('/participants/{submission}/approve', [AdminController::class, 'approveParticipant'])->name('admin.participants.approve');
    Route::post('/participants/{submission}/decline', [AdminController::class, 'declineParticipant'])->name('admin.participants.decline');
    
    // Participant submission CRUD
    Route::get('/participants/{submission}/edit', [AdminController::class, 'editParticipant'])->name('admin.participants.edit');
    Route::patch('/participants/{submission}', [AdminController::class, 'updateParticipant'])->name('admin.participants.update');
    Route::delete('/participants/{submission}', [AdminController::class, 'destroyParticipant'])->name('admin.participants.destroy');
    Route::get('/participants/{submission}', [AdminController::class, 'showParticipant'])->name('admin.participants.show');
    
    // Judges management
    Route::get('/judges', [UserController::class, 'judges'])->name('admin.judge.index');
    
    // Judge Event Assignments
    Route::get('/judges/{judge}/assign-events', [UserController::class, 'assignEvents'])->name('admin.judges.assign-events');
    Route::post('/judges/{judge}/assign-events', [UserController::class, 'storeAssignment'])->name('admin.judges.assign-events.store');
    Route::get('/judges/{judge}/assignments', [UserController::class, 'assignments'])->name('admin.judges.assignments');
    Route::delete('/judges/{judge}/assignments/{assignment}', [UserController::class, 'removeAssignment'])->name('admin.judges.assignments.destroy');
    
    // Judge Participant Assignments
    Route::get('/judges/{judge}/assign-participants', [UserController::class, 'assignParticipants'])->name('admin.judges.assign-participants');
    Route::post('/judges/{judge}/assign-participants', [UserController::class, 'storeParticipantAssignment'])->name('admin.judges.assign-participants.store');
    Route::get('/judges/{judge}/participant-assignments', [UserController::class, 'participantAssignments'])->name('admin.judges.participant-assignments');
    Route::delete('/judges/{judge}/participant-assignments/{assignment}', [UserController::class, 'removeParticipantAssignment'])->name('admin.judges.participant-assignments.destroy');
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
    
    // Participant Profile
    Route::post('/profile', [ParticipantController::class, 'participantProfileStore'])->name('participant.profile.store');
    
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
    
    // Scores viewing
    Route::get('/scores', [ParticipantController::class, 'scoreIndex'])->name('participant.score.index');
    

});

// Judge Dashboard Routes
Route::prefix('judge')->middleware(['auth', 'verified', 'role:judge'])->group(function () {
    Route::get('/dashboard', [JudgeController::class, 'dashboard'])->name('judge.dashboard');
    Route::get('/assigned-events', [JudgeController::class, 'assignedEvents'])->name('judge.assigned-events');
    Route::get('/my-events', [JudgeController::class, 'myEvents'])->name('judge.my-events');
    Route::get('/participants', [JudgeController::class, 'participants'])->name('judge.participants');
    Route::get('/manage-participants', [JudgeController::class, 'manageParticipants'])->name('judge.manage_participants.index');
    Route::get('/review-scores', [JudgeController::class, 'reviewScores'])->name('judge.review-scores');
    Route::get('/scoring', [JudgeController::class, 'index'])->name('judge.scoring.index');
    Route::get('/scoring/by-category', [JudgeController::class, 'scoringByCategory'])->name('judge.scoring.category');
    Route::get('/scoring/by-category/{category}/participant/{participant}', [JudgeController::class, 'scoreParticipantByCategory'])->name('judge.scoring.category.participant');
    Route::post('/scoring/by-category/{category}/participant/{participant}', [JudgeController::class, 'storeScoreByCategory'])->name('judge.scoring.category.participant.store');
    Route::get('/scoring/participants', [JudgeController::class, 'scoringParticipantsTable'])->name('judge.scoring.participants');
    Route::get('/scores/by-category', [JudgeController::class, 'scoresByCategory'])->name('judge.scores.by-category');
    Route::post('/scores/participant/{participant}', [JudgeController::class, 'saveParticipantScores'])->name('judge.scores.participant.save');
    
    // New routes for judge.scoring.score-participant (matches blade template)
    Route::get('/scoring/score-participant/{category}/{participant}', [JudgeController::class, 'scoreParticipantByCategory'])->name('judge.scoring.score-participant');
    Route::post('/scoring/score-participant/{category}/{participant}', [JudgeController::class, 'storeScoreByCategory'])->name('judge.scoring.score-participant.store');
    
    // Bulk scoring routes
    Route::get('/scoring/bulk/{category}', [JudgeController::class, 'bulkScoreCategory'])->name('judge.scoring.bulk');
    Route::post('/scoring/bulk/{category}', [JudgeController::class, 'storeBulkScores'])->name('judge.scoring.bulk.store');
    
    Route::get('/scoring/edit/{scoreId}', [JudgeController::class, 'scoringEdit'])->name('judge.scoring.edit');
    Route::put('/scoring/update/{scoreId}', [JudgeController::class, 'scoringUpdate'])->name('judge.scoring.update');
    Route::delete('/scoring/{score}', [JudgeController::class, 'destroyScore'])->name('judge.scoring.destroy');
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

// Public results sharing
Route::get('/results/{code}', [\App\Http\Controllers\Public\ResultController::class, 'show'])->name('public.results.show');

require __DIR__.'/auth.php';

