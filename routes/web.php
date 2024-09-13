<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SetLocale;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

    Route::get('/', function () {
        return redirect()->route('login', ['locale' => 'en']);
    });

    Route::prefix('{locale}')->middleware(['auth', 'setlocale'])->group(function () {
        
        Route::post('logout_custom', [AuthenticatedSessionController::class, 'logout'])
        ->name('logout.custom');
        
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/edit/{task}', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update_status');
        Route::get('/archives', [ArchiveController::class, 'index'])->name('archives.index');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware(['guest'])->group(function () {
        require __DIR__.'/auth.php';
    });
