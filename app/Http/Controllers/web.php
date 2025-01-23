<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SDGController;

use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\StrategicAnalysisController;

use App\Http\Controllers\ProjectAnalysisController;
use App\Http\Controllers\PDFController;


Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/account.php';
require __DIR__.'/auth.php';
require __DIR__.'/strategic.php';
require __DIR__.'/strategicUni.php';


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/setting', [SettingController::class, 'settings'])->name('setting');

Route::get('/listProject', [ListProjectController::class, 'project'])->name('project');
Route::get('/createProject/{Strategic_Id}', [ListProjectController::class, 'showCreateForm'])->name('createProject');
Route::post('/createProject/{Strategic_Id}', [ListProjectController::class, 'createProject'])->name('createProject');

Route::get('/project/{id}/details', [ListProjectController::class, 'viewProjectDetails'])->name('project.details');

Route::match(['get', 'post'],'/editProject/{Id_Project}', [ListProjectController::class, 'editProject'])->name('editProject');
Route::put('/editProject/{Id_Project}', [ListProjectController::class, 'updateProject'])->name('updateProject');


//pdf
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('actionplan-pdf', [PDFController::class, 'ActionPlanPDF']);
Route::get('strategic-pdf/{Id_Strategic}', [PDFController::class, 'PDFStrategic'])->name('PDF.strategic');
Route::get('project-pdf/{Id_Project}', [PDFController::class, 'PDFProject'])->name('PDF.Project');

Route::get('pdfStrategic/{Id_Project}', [PDFController::class, 'ctrlpPDFStrategic'])->name('pdf.strategicCtrlP');
Route::get('pdfproject/{Id_Project}', [PDFController::class, 'ctrlpPDFProject'])->name('PDF.projectCtrlP');

// project
Route::get('index', [ProjectController::class, 'index'])->name('index');
Route::get('/createProject/{Strategic_Id}', [ProjectController::class, 'showCreateProject'])->name('showCreateProject');
Route::post('/createProject/{Strategic_Id}', [ProjectController::class, 'createProject'])->name('createProject');
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



Route::get('/test', function () {
    return view('test');
});

Route::get('/testpdf', function () {
    return view('PDF.test');
});








