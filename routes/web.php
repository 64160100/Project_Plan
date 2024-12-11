<?php
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategyController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [strategicController::class, 'strategic'])->name('account.strategic');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';

// แผนยุทศาสตร์
Route::get('/strategic',[StrategicController::class, 'index'])->name('strategic.index');
Route::post('/AddStrategic', [StrategicController::class, 'addStrategic'])->name('strategic.add');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');
Route::delete('/strategic/{Id_Strategic}', [StrategicController::class, 'deleteStrategic'])->name('strategic.destroy');

// กลยุทธ์
Route::get('/strategy/{Id_Strategic}',[StrategyController::class, 'index'])->name('strategy.index');
Route::post('/AddStrategy', [StrategyController::class, 'addStrategy'])->name('strategy.add');
// Route::post('/strategic/{Id_Strategic}/strategy/add', [StrategyController::class, 'addStrategy'])->name('strategy.add');
