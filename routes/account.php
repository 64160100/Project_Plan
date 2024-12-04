<?php
use App\Http\Controllers\AccountController;

Route::get('/employees', [AccountController::class, 'employee'])->name('account.employee');
Route::get('/employees/{Id_Employee}', [AccountController::class, 'showemployee'])->name('account.showemployee');
Route::post('/employees', [AccountController::class, 'store'])->name('account.store');
Route::get('/employees/create', [AccountController::class, 'create'])->name('account.create');


Route::get('/user', [AccountController::class, 'userAccount'])->name('account.user');
Route::get('/user/edit/{Id_Employee}', [AccountController::class, 'editUser'])->name('account.editUser');
Route::delete('/user/{Id_Employee}', [AccountController::class, 'destroyUser'])->name('account.destroyUser');
Route::put('/user/{Id_Employee}', [AccountController::class, 'updateUser'])->name('account.updateUser');
