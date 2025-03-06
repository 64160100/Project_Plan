<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ListProjectController;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\PlanDLCController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProjectBatchController;
use App\Http\Controllers\FiscalYearQuarterController;
use App\Http\Controllers\ProposeProjectController;
use App\Http\Controllers\RequestApprovalController;
use App\Http\Controllers\ReportFormController;
use App\Http\Controllers\SustainableDevelopmentGoalsController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
// Route::get('/projects/{Id_Project}/edit', [ProjectController::class, 'editProject'])->name('editProject');

// ฟอร์มเริ่มต้น [count-step-0,1]

Route::get('/createFirstForm/{Strategic_Id}', [ListProjectController::class, 'showCreateFirstForm'])->name('showCreateFirstForm');
Route::get('/createProject/{Strategic_Id}', [ListProjectController::class, 'showCreateForm'])->name('showCreateProject');
Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');
Route::put('/approvals/disapproveAll/{id}', [ListProjectController::class, 'disapproveAll'])->name('disapproveAll');
Route::put('/approvals/updateAllStatus', [RequestApprovalController::class, 'updateAllStatus'])->name('updateAllStatus');


// การเสนอโครงการ
Route::get('/proposeProject', [ProposeProjectController::class, 'proposeProject'])->name('proposeProject');
Route::post('/projects/submit-for-approval/{id}', [ProposeProjectController::class, 'submitForApproval'])->name('projects.submitForApproval');

Route::post('/projects/update-status/{id}', [ProposeProjectController::class, 'updateStatus'])->name('projects.updateStatus');

Route::post('/submit-for-all-approval', [ProposeProjectController::class, 'submitForAllApproval'])->name('projects.submitForAllApproval');

Route::get('/project/{id}/edit', [ListProjectController::class, 'editAllProject'])->name('projects.edit');

Route::post('/projects/reset-status/{id}', [ListProjectController::class, 'resetStatus'])->name('projects.resetStatus');

// สร้างชุดโครงการ
Route::get('/createSetProject', [ProjectBatchController::class, 'createSetProject'])->name('createSetProject');
Route::post('/project-batches', [ProjectBatchController::class, 'storeProjectBatch'])->name('project-batches.store');
Route::get('/project-batches/submit/{id}', [ProjectBatchController::class, 'submitProjectBatch'])->name('project-batches.submit');
Route::delete('/project-batches/{batch_id}', [ProjectBatchController::class, 'removeBatch'])->name('project-batches.removeBatch');
Route::delete('/project-batches/{batch_id}/projects/{project_id}', [ProjectBatchController::class, 'removeProjectFromBatch'])->name('project-batches.removeProject');
Route::post('/project-batches/add-projects', [ProjectBatchController::class, 'addProjectsToBatch'])->name('project-batches.addProjects');
Route::put('/approvals/batch/{id}/status/{status}', [ProjectBatchController::class, 'updateBatchApprovalStatus'])->name('approvals.updateBatchStatus');

// แสดง PDF
Route::get('/projects/{id}', [ProjectBatchController::class, 'showBatchesProject'])->name('projects.showBatchesProject');
Route::get('/projects/batch/{batch_id}/all', [ProjectBatchController::class, 'showBatchAll'])->name('projects.showBatchAll');

// แก้ไขโครงการ
Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');
Route::match(['get', 'post'],'/editProject/{Id_Project}', [ListProjectController::class, 'editProject'])->name('editProject');
Route::put('/editProject/{Id_Project}', [ListProjectController::class, 'updateProject'])->name('updateProject');

// แก้ไขโครงการ
Route::put('/project/{id}', [ListProjectController::class, 'update'])->name('projects.update');
Route::get('/project/{Id_Project}', [ProjectController::class, 'viewProject'])->name('viewProject');

//แสดงหน้า Strategic
Route::get('/viewProjectInStrategic/{Id_Strategic}', [ListProjectController::class, 'viewProjectInStrategic'])->name('viewProjectInStrategic');
Route::get('/viewProject/{Id_Project}', [ListProjectController::class, 'viewProject'])->name('viewProject');

//การอนุมัติโครงการ
Route::get('/requestApproval', [RequestApprovalController::class, 'showAllApprovals'])->name('requestApproval');
Route::put('/approvals/{id}/status/{status}', [RequestApprovalController::class, 'updateApprovalStatus'])->name('approvals.updateStatus');
Route::get('/editForm', [RequestApprovalController::class, 'approveProject'])->name('approveProject');
Route::put('/disapprove-project/{id}', [RequestApprovalController::class, 'disapproveProject'])->name('disapproveProject');


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
Route::get('generate-pdf/{id}', [PDFController::class, 'generatePDF'])->name('PDF.generate');
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

// FiscalYearQuarter
Route::resource('fiscalYearQuarter', FiscalYearQuarterController::class);

Route::post('/projects/update-field', [ListProjectController::class, 'updateField'])->name('projects.updateField');

Route::get('/report-form/{id}', [ReportFormController::class, 'showReportForm'])->name('reportForm');
Route::post('/projects/complete/{id}', [ReportFormController::class, 'completeProject'])->name('projects.complete');

//Sdg
Route::get('showSdg', [SustainableDevelopmentGoalsController::class, 'showSdg'])->name('showSdg');
Route::post('/showSdg', [SustainableDevelopmentGoalsController::class, 'createSDG'])->name('createSDG');
Route::put('/editSDG/{id_SDGs}', [SustainableDevelopmentGoalsController::class, 'editSDG'])->name('editSDG');
Route::delete('/deleteSDG/{id_SDGs}', [SustainableDevelopmentGoalsController::class, 'deleteSDG'])->name('deleteSDG');