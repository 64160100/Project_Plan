<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ListProjectController;
use App\Http\Controllers\StorageFileController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
Route::get('/createProject', [ListProjectController::class, 'showCreateForm'])->name('showCreateForm');
Route::post('/createProject', [ListProjectController::class, 'createProject'])->name('projects.createProject');

//แสดงหน้า Strategic
Route::get('/viewProjectInStrategic/{Id_Strategic}', [ListProjectController::class, 'viewProjectInStrategic'])->name('viewProjectInStrategic');
Route::get('/viewProject/{Id_Project}', [ListProjectController::class, 'viewProject'])->name('viewProject');

// ... existing code
Route::get('/requestApproval', [ListProjectController::class, 'showAllApprovals'])->name('requestApproval');
Route::put('/approvals/{id}/status/{status}', [ListProjectController::class, 'updateApprovalStatus'])->name('approvals.updateStatus');

Route::get('/proposeProject', [ListProjectController::class, 'proposeProject'])->name('proposeProject');
Route::post('/projects/{id}/submitForApproval', [ListProjectController::class, 'submitForApproval'])->name('projects.submitForApproval');

Route::get('/editForm', [ListProjectController::class, 'approveProject'])->name('approveProject');


// ... existing code
Route::get('/storage-files', [StorageFileController::class, 'index'])->name('StorageFiles.index');
Route::post('/storage-files', [StorageFileController::class, 'store'])->name('StorageFiles.store');
Route::delete('/storage-files/{id}', [StorageFileController::class, 'destroy'])->name('StorageFiles.destroy');
Route::get('/{id}/download', [StorageFileController::class, 'download'])->name('StorageFiles.download');
Route::get('/storage-files/{id}/view', [StorageFileController::class, 'view'])->name('StorageFiles.view');