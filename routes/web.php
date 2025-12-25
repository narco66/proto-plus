<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/documentation', [\App\Http\Controllers\HomeController::class, 'documentation'])->name('documentation');
Route::get('/faq', [\App\Http\Controllers\HomeController::class, 'faq'])->name('faq');

    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::get('demandes/{demande}/letter', [\App\Http\Controllers\LetterController::class, 'download'])
        ->middleware(['auth'])
        ->name('demandes.letter');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Demandes
    Route::resource('demandes', \App\Http\Controllers\DemandeController::class);
    Route::post('demandes/{demande}/submit', [\App\Http\Controllers\DemandeController::class, 'submit'])
        ->name('demandes.submit');

    // Ayants droit
    Route::resource('ayants-droit', \App\Http\Controllers\AyantDroitController::class)
        ->parameters(['ayants-droit' => 'ayantDroit']);

    // Workflow
    Route::get('workflow', [\App\Http\Controllers\WorkflowController::class, 'index'])->name('workflow.index');
    Route::get('workflow/{demande}', [\App\Http\Controllers\WorkflowController::class, 'show'])->name('workflow.show');
    Route::post('workflow/{demande}/validate', [\App\Http\Controllers\WorkflowController::class, 'validateDemande'])->name('workflow.validate');

    // Exports
    Route::get('exports', [\App\Http\Controllers\ExportController::class, 'index'])->name('exports.index');
    Route::get('exports/demandes/excel', [\App\Http\Controllers\ExportController::class, 'exportExcel'])->name('exports.demandes.excel');
    Route::get('exports/demandes/pdf', [\App\Http\Controllers\ExportController::class, 'exportPDF'])->name('exports.demandes.pdf');

    // Notifications
    Route::get('notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])
        ->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])
        ->name('notifications.mark-all-read');
    Route::delete('notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});

require __DIR__.'/auth.php';
