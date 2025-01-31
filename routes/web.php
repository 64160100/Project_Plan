<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ListProjectController;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PlanDLCController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProjectBatchController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
Route::get('/projects/{Id_Project}/edit', [ProjectController::class, 'editProject'])->name('editProject');

Route::get('/createFirstForm/{Strategic_Id}', [ListProjectController::class, 'showCreateFirstForm'])->name('showCreateFirstForm');

Route::get('/createProject/{Strategic_Id}', [ListProjectController::class, 'showCreateForm'])->name('showCreateProject');
Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');

// สร้างชุดโครงการ
Route::get('/createSetProject', [ProjectBatchController::class, 'createSetProject'])->name('createSetProject');
Route::post('/project-batches', [ProjectBatchController::class, 'storeProjectBatch'])->name('project-batches.store');
Route::get('/project-batches/submit/{id}', [ProjectBatchController::class, 'submitProjectBatch'])->name('project-batches.submit');

Route::delete('/project-batches/{batch_id}', [ProjectBatchController::class, 'removeBatch'])->name('project-batches.removeBatch');

Route::delete('/project-batches/{batch_id}/projects/{project_id}', [ProjectBatchController::class, 'removeProjectFromBatch'])->name('project-batches.removeProject');

Route::post('/project-batches/add-projects', [ProjectBatchController::class, 'addProjectsToBatch'])->name('project-batches.addProjects');

Route::get('/projects/{id}', [ProjectBatchController::class, 'showBatchesProject'])->name('projects.showBatchesProject');


Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');
Route::match(['get', 'post'],'/editProject/{Id_Project}', [ListProjectController::class, 'editProject'])->name('editProject');
Route::put('/editProject/{Id_Project}', [ListProjectController::class, 'updateProject'])->name('updateProject');

// แก้ไขโครงการ
Route::get('/project/{id}/edit', [ListProjectController::class, 'edit'])->name('projects.edit');
Route::put('/project/{id}', [ListProjectController::class, 'update'])->name('projects.update');

Route::get('/project/{Id_Project}', [ProjectController::class, 'viewProject'])->name('viewProject');

//แสดงหน้า Strategic
Route::get('/viewProjectInStrategic/{Id_Strategic}', [ListProjectController::class, 'viewProjectInStrategic'])->name('viewProjectInStrategic');
Route::get('/viewProject/{Id_Project}', [ListProjectController::class, 'viewProject'])->name('viewProject');

//การอนุมัติโครงการ
Route::get('/requestApproval', [ListProjectController::class, 'showAllApprovals'])->name('requestApproval');
Route::put('/approvals/{id}/status/{status}', [ListProjectController::class, 'updateApprovalStatus'])->name('approvals.updateStatus');
Route::get('/editForm', [ListProjectController::class, 'approveProject'])->name('approveProject');

// การเสนอโครงการ
Route::get('/proposeProject', [ListProjectController::class, 'proposeProject'])->name('proposeProject');
Route::post('/projects/{id}/submitForApproval', [ListProjectController::class, 'submitForApproval'])->name('projects.submitForApproval');

// คลังไฟล์ PDF
Route::get('/storage-files/{project_id?}', [StorageFileController::class, 'index'])->name('StorageFiles.index');
Route::post('/storage-files', [StorageFileController::class, 'store'])->name('StorageFiles.store');

Route::delete('/storage-files/{id}', [StorageFileController::class, 'destroy'])->name('StorageFiles.destroy');
Route::get('/{id}/download', [StorageFileController::class, 'download'])->name('StorageFiles.download');
Route::get('/storage-files/{id}/view', [StorageFileController::class, 'view'])->name('StorageFiles.view');

// การแสดงสถานะโครงการ
Route::get('/statusTracking', [StatusController::class, 'statusTracking'])->name('status.tracking');
Route::get('/project/{Id_Project}', [StatusController::class, 'showDetails'])->name('project.details');

// การแสดง PDF
Route::get('generate-pdf/{id}', [PDFController::class, 'generatePDF']);
Route::get('actionplan-pdf', [PDFController::class, 'ActionPlanPDF']);
Route::get('strategic-pdf/{Id_Strategic}', [PDFController::class, 'PDFStrategic'])->name('PDF.strategic');

Route::get('pdfStrategic/{Id_Project}', [PDFController::class, 'ctrlpPDFStrategic'])->name('pdf.strategicCtrlP');

Route::get('project-pdf/{Id_Project}', [PDFController::class, 'PDFProject'])->name('PDF.Project');
Route::get('pdfproject/{Id_Project}', [PDFController::class, 'ctrlpPDFProject'])->name('PDF.projectCtrlP');

//PlanDLC
Route::get('/report', [PlanDLCController::class, 'report'])->name('PlanDLC.report');
Route::get('/checkBudget', [PlanDLCController::class, 'checkBudget'])->name('PlanDLC.checkBudget');
Route::get('/allProject', [PlanDLCController::class, 'allProject'])->name('PlanDLC.allProject');
Route::get('/editBudget', [PlanDLCController::class, 'editBudget'])->name('PlanDLC.editBudget');
Route::get('/showProjectDepartment/{Id_Department}', [PlanDLCController::class, 'showProjectDepartment'])->name('showProjectDepartment');