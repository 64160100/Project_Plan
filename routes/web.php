<?php
use App\Http\Controllers\strategicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('strategic/{Id_Strategic}/edit', [strategicController::class, 'editStrategic'])->name('strategic.edit');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/test-session', function () {
    session(['test_key' => 'test_value']);
    return session()->all();
});