<?php
use App\Http\Controllers\AccountController;

Route::get('/employees', [AccountController::class, 'employee'])->name('account.employee');
Route::get('/employees/{Id_Employee}', [AccountController::class, 'showEmployees'])->name('account.showemployee');
Route::post('/employees', [AccountController::class, 'store'])->name('account.store');
Route::get('/employees/create', [AccountController::class, 'create'])->name('account.create');

Route::get('/employees/edit/{Id_Employee}', [AccountController::class, 'editUser'])->name('account.editUser');
Route::put('/account/updateUserPermissions/{Id_Employee}', [AccountController::class, 'updateUserPermissions'])->name('account.updateUserPermissions');
