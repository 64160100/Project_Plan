<?php
use App\Http\Controllers\strategicController;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('strategic/{Id_Strategic}/edit', [strategicController::class, 'editStrategic'])->name('strategic.edit');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
