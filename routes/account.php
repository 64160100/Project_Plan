<?php
use App\Http\Controllers\AccountController;

Route::get('/employees', [AccountController::class, 'employee'])->name('account.employee');
Route::get('/employees/{Id_Employee}', [AccountController::class, 'showEmployees'])->name('account.showemployee');
Route::post('/employees', [AccountController::class, 'store'])->name('account.store');
Route::get('/employees/create', [AccountController::class, 'create'])->name('account.create');

Route::put('/updatePassword/{Id_Employee}', [AccountController::class, 'updatePassword'])->name('account.updatePassword');

Route::get('/employees/edit/{Id_Employee}', [AccountController::class, 'editUser'])->name('account.editUser');
Route::put('/account/updateUserPermissions/{Id_Employee}', [AccountController::class, 'updateUserPermissions'])->name('account.updateUserPermissions');

Route::prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/create', [AccountController::class, 'create'])->name('create');
    Route::post('/store', [AccountController::class, 'store'])->name('store');
    Route::put('/update/{id}', [AccountController::class, 'update'])->name('update');
    
    // เพิ่มเส้นทางสำหรับการตรวจสอบ Username
    Route::post('/check-username', [AccountController::class, 'checkUsername'])->name('checkUsername');
});