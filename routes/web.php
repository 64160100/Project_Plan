<?php
use App\Http\Controllers\strategicController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [strategicController::class, 'strategic'])->name('account.strategic');
Route::get('strategic/{Id_Strategic}/edit', [strategicController::class, 'editStrategic'])->name('strategic.edit');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
