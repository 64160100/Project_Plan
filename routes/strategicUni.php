<?php
use App\Http\Controllers\StrategicUniController;

// StrategicUniversity
//Platform
Route::get('showPlatform', [StrategicUniController::class, 'showPlatform'])->name('showPlatform');
Route::post('/platform', [StrategicUniController::class, 'createPlatform'])->name('createPlatform');
Route::put('/editPlatform/{Id_Platform}', [StrategicUniController::class, 'editPlatform'])->name('editPlatform');
Route::delete('/deletePlatform/{Id_Platform}', [StrategicUniController::class, 'deletePlatform'])->name('deletePlatform');

//Platform_Kpi
Route::get('/showPlatformKpi/{Id_Platform}', [StrategicUniController::class, 'showPlatformKpi'])->name('showPlatformKpi');
Route::post('/createPlatformKpi/{Id_Platform}', [StrategicUniController::class, 'createPlatformKpi'])->name('createPlatformKpi');
Route::put('/editPlatformKpi/{Id_Platform}/{Id_Platform_Kpi}', [StrategicUniController::class, 'editPlatformKpi'])->name('editPlatformKpi');
Route::delete('/deletePlatformKpi/{Id_Platform_Kpi}', [StrategicUniController::class, 'deletePlatformKpi'])->name('deletePlatformKpi');

//Program
Route::get('/showProgram/{Id_Platform}/{Id_Program}', [StrategicUniController::class, 'showProgram'])->name('showProgram');
Route::post('/createProgram/{Id_Platform}', [StrategicUniController::class, 'createProgram'])->name('createProgram');
Route::put('/editProgram/{Id_Platform}/{Id_Program}', [StrategicUniController::class, 'editProgram'])->name('editProgram');
Route::delete('/deleteProgram/{Id_Program}', [StrategicUniController::class, 'deleteProgram'])->name('deleteProgram');

//Program_Kpi
Route::get('/showProgramKpi/{Id_Program}', [StrategicUniController::class, 'showProgramKpi'])->name('showProgramKpi');
Route::post('/createProgramKpi/{Id_Program}', [StrategicUniController::class, 'createProgramKpi'])->name('createProgramKpi');
Route::put('/editProgramKpi/{Id_Program}/{Id_Program_Kpi}', [StrategicUniController::class, 'editProgramKpi'])->name('editProgramKpi');
Route::delete('/deleteProgramKpi/{Id_Program_Kpi}', [StrategicUniController::class, 'deleteProgramKpi'])->name('deleteProgramKpi');
