<?php
use App\Http\Controllers\strategicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ListProjectController;

// use App\Http\Controllers\ProjectController;
// use App\Http\Controllers\ApprovalController;
// use App\Http\Controllers\SystemController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('strategic/{Id_Strategic}/edit', [strategicController::class, 'editStrategic'])->name('strategic.edit');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
Route::post('/createProject', [ListProjectController::class, 'createProject'])->name('projects.createProject');

// Project Management
// Route::get('/projects', [ProjectController::class, 'list'])->name('projects.list');
// Route::get('/projects/track', [ProjectController::class, 'trackStatus'])->name('projects.track');

// // Approval
// Route::get('/documents/collect', [ApprovalController::class, 'collectDocuments'])->name('documents.collect');
// Route::get('/reports', [ApprovalController::class, 'reportResults'])->name('reports.results');
// Route::get('/budget/check', [ApprovalController::class, 'checkBudget'])->name('budget.check');
// Route::get('/projects/approve', [ApprovalController::class, 'approveProject'])->name('projects.approve');
// Route::get('/projects/propose', [ApprovalController::class, 'proposeProject'])->name('projects.propose');

// // System Management
// Route::get('/users/manage', [SystemController::class, 'manageUsers'])->name('users.manage');
// Route::get('/employees/info', [SystemController::class, 'employeeInfo'])->name('employees.info');
// Route::get('/system/settings', [SystemController::class, 'systemSettings'])->name('system.settings');