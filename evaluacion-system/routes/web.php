<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationPeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

/* ------------------------------------------------------------------
   Public routes
------------------------------------------------------------------ */
Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/* ------------------------------------------------------------------
   Auth routes (Breeze / Jetstream style)
------------------------------------------------------------------ */
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

/* ------------------------------------------------------------------
   Authenticated routes – all require the user to be logged in
------------------------------------------------------------------ */
Route::middleware('auth')->group(function () {

    // ---------- Profile ----------
    // (profile.index is kept for backward compatibility)
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.index');
    // The route the navigation actually uses
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // ---------- Support ----------
    Route::get('/support', [SupportController::class, 'index'])
        ->name('support.index');
    Route::post('/support/send', [SupportController::class, 'send'])
        ->name('support.send');

    // ---------- Workers (Admins only) ----------
    Route::middleware(['role:Administrador'])->group(function () {
        Route::resource('workers', WorkerController::class)->except(['show']);
        Route::post('workers/import', [WorkerController::class, 'importCsv'])
            ->name('workers.import');
        Route::patch('workers/{worker}/toggle', [WorkerController::class, 'toggleStatus'])
            ->name('workers.toggle');
    });

    // ---------- Evaluation periods (Admins) ----------
    Route::middleware(['role:Administrador'])->group(function () {
        Route::resource('evaluation-periods', EvaluationPeriodController::class)
            ->except(['show']);
        // <-- nuevo recurso para los periodos
        Route::resource('periods', EvaluationPeriodController::class)
            ->except(['show']);
        // Toggle active/inactive status of a period
        Route::patch('periods/{period}/toggle', [EvaluationPeriodController::class, 'toggleStatus'])->name('periods.toggle');
    });

    // ---------- Evaluations (Admins & Supervisors) ----------
    Route::middleware(['role:Administrador'])->group(function () {
        // Index route for evaluations
        Route::get('evaluations', [EvaluationController::class, 'index'])
            ->name('evaluations.index');

        // Crear evaluación para un trabajador
        Route::get('workers/{worker}/create-evaluation', [EvaluationController::class, 'createForWorker'])
            ->name('evaluations.createForWorker');

        // Evaluaciones propias (trabajador)
        Route::get('workers/{worker}/evaluate', [EvaluationController::class, 'evaluate'])
            ->name('evaluations.evaluate');
        Route::post('workers/{worker}/evaluate', [EvaluationController::class, 'storeEvaluation'])
            ->name('evaluations.store');

        // Evaluación de supervisor
        Route::get('workers/{worker}/supervisor-evaluation', [EvaluationController::class, 'supervisorEvaluate'])
            ->name('evaluations.supervisorEvaluate');
        Route::post('workers/{worker}/supervisor-evaluation', [EvaluationController::class, 'storeSupervisorEvaluation'])
            ->name('evaluations.storeSupervisor');

        // Ver y editar evaluaciones
        Route::get('evaluations/{evaluation}', [EvaluationController::class, 'show'])
            ->name('evaluations.show');
        Route::get('evaluations/{evaluation}/edit', [EvaluationController::class, 'edit'])
            ->name('evaluations.edit');
        Route::put('evaluations/{evaluation}', [EvaluationController::class, 'update'])
            ->name('evaluations.update');
            Route::get('evaluations/export/{period}', [EvaluationController::class, 'exportCsv'])
                ->name('evaluations.export');
    });

    // ---------- Admin‑only roles ----------
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    });

    // ---------- Super‑Admin ----------
    Route::get('/users/superadmin', [UserController::class, 'createSuperadmin'])
        ->name('users.createSuperadmin');
    Route::post('/users/superadmin', [UserController::class, 'storeSuperadmin'])
        ->name('users.storeSuperadmin');
});

require __DIR__.'/auth.php';
//