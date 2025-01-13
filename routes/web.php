<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ListProjectController;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PdfController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
Route::get('/createProject/{Strategic_Id}', [ListProjectController::class, 'showCreateForm'])->name('createProject');
Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');

Route::get('/project/{id}/details', [ListProjectController::class, 'viewProjectDetails'])->name('project.details');

Route::match(['get', 'post'],'/editProject/{Id_Project}', [ListProjectController::class, 'editProject'])->name('editProject');
Route::put('/editProject/{Id_Project}', [ListProjectController::class, 'updateProject'])->name('updateProject');

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

Route::get('/statusTracking', [StatusController::class, 'statusTracking'])->name('status.tracking');
Route::get('/project/{Id_Project}', [StatusController::class, 'showDetails'])->name('project.details');