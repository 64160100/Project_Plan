<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StorageFileController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\RegisterController;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SDGController;

use App\Http\Controllers\ProjectAnalysisController;
use App\Http\Controllers\PDFController;


// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/employees', [EmployeeController::class, 'employee'])->name('employees.employee');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}', [EmployeeController::class, 'showemployee'])->name('employees.showemployee');

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth');


//pdf
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('actionplan-pdf', [PDFController::class, 'ActionPlanPDF']);
Route::get('strategic-pdf/{Id_Project}', [PDFController::class, 'PDFStrategic'])->name('PDF.strategic');
Route::get('project-pdf/{Id_Project}', [PDFController::class, 'PDFProject'])->name('PDF.Project');

Route::get('pdfStrategic/{Id_Project}', [PDFController::class, 'ctrlpPDFStrategic'])->name('pdf.strategicCtrlP');
Route::get('project/{Id_Project}', [PDFController::class, 'ctrlpPDFProject'])->name('PDF.projectCtrlP');

// project
Route::get('index', [ProjectController::class, 'index'])->name('index');
Route::get('/createProject/{Strategic_Id}', [ProjectController::class, 'showCreateProject'])->name('showCreateProject');
Route::post('/createProject/{Strategic_Id}/{Employee_Id}', [ProjectController::class, 'createProject'])->name('createProject');
Route::match(['get', 'post'],'/editProject/{Id_Project}', [ProjectController::class, 'editProject'])->name('editProject');
Route::put('/editProject/{Id_Project}', [ProjectController::class, 'updateProject'])->name('updateProject');

//แสดงหน้า Strategic ของ project
Route::get('/viewProjectInStrategic/{Id_Strategic}', [ProjectController::class, 'viewProjectInStrategic'])->name('viewProjectInStrategic');
Route::get('/viewProject/{Id_Project}', [ProjectController::class, 'viewProject'])->name('viewProject');

//Sdg
Route::get('showSdg', [SDGController::class, 'showSdg'])->name('showSdg');
Route::post('/showSdg', [SDGController::class, 'createSDG'])->name('createSDG');
Route::put('/editSDG/{id_SDGs}', [SDGController::class, 'editSDG'])->name('editSDG');
Route::delete('/deleteSDG/{id_SDGs}', [SDGController::class, 'deleteSDG'])->name('deleteSDG');

//Project Analysis
Route::get('/report', [ProjectAnalysisController::class, 'report'])->name('report');
Route::get('/checkBudget', [ProjectAnalysisController::class, 'checkBudget'])->name('checkBudget');
Route::get('/allProject', [ProjectAnalysisController::class, 'allProject'])->name('allProject');

Route::get('/editBudget', [ProjectAnalysisController::class, 'editBudget'])->name('editBudget');
Route::get('/showProjectDepartment/{Id_Department}', [ProjectAnalysisController::class, 'showProjectDepartment'])->name('showProjectDepartment');



Route::get('/test', function () {
    return view('test');
});

Route::get('/testpdf', function () {
    return view('PDF.test');
});








