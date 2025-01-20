<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SDGController;
use App\Http\Controllers\StrategicUniController;

use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\StrategicAnalysisController;

use App\Http\Controllers\ProjectAnalysisController;
use App\Http\Controllers\PDFController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/employees', [EmployeeController::class, 'employee'])->name('employees.employee');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/employees/{id}', [EmployeeController::class, 'showemployee'])->name('employees.showemployee');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');



// แผนยุทศาสตร์
Route::get('/strategic',[StrategicController::class, 'index'])->name('strategic.index');
Route::post('/AddStrategic', [StrategicController::class, 'addStrategic'])->name('strategic.add');
Route::put('strategic/{Id_Strategic}', [strategicController::class, 'updateStrategic'])->name('strategic.update');
Route::delete('/strategic/{Id_Strategic}', [StrategicController::class, 'deleteStrategic'])->name('strategic.destroy');

// กลยุทธ์
Route::get('/strategy/{Id_Strategic}',[StrategyController::class, 'index'])->name('strategy.index');
Route::post('/AddStrategy', [StrategyController::class, 'addStrategy'])->name('strategy.add');

// ตัวชี้วัด
Route::get('/strategy/{Id_Strategy}/Kpi', [StrategyController::class, 'Kpi'])->name('kpi.index');
Route::post('/strategy/{Id_Strategy}/add-kpi', [StrategyController::class, 'addKpi'])->name('kpi.addKpi');

// วัตถุประสงค์เชิงกลยุทธ์
Route::get('/strategy/{Id_Strategy}/Strategic_Objectives', [StrategyController::class, 'StrategicObjectives'])->name('StrategicObjectives.index');
Route::post('/strategy/{Id_Strategy}/addStrategic_Objectives', [StrategyController::class, 'addStrategicObjectives'])->name('StrategicObjectives.add');


Route::get('/strategy/{Id_Strategy}/edit', [StrategyController::class, 'editStrategy'])->name('strategy.edit');
Route::put('/strategy/{Id_Strategy}', [StrategyController::class, 'updateStrategy'])->name('strategy.update');
Route::delete('/strategy/{Id_Strategy}', [StrategyController::class, 'deleteStrategy'])->name('strategy.destroy');


Route::post('/strategic-analysis/add', [StrategicAnalysisController::class, 'addDetail'])->name('StrategicAnalysis.addDetail');
Route::put('/strategic-analysis/update-detail/{type}/{id}', [StrategicAnalysisController::class, 'updateDetail'])->name('StrategicAnalysis.updateDetail');
Route::delete('/StrategicAnalysis/delete-detail/{type}/{id}', [StrategicAnalysisController::class, 'destroyDetail'])->name('StrategicAnalysis.destroyDetail');


//pdf
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('actionplan-pdf', [PDFController::class, 'ActionPlanPDF']);
Route::get('strategic-pdf/{Id_Project}', [PDFController::class, 'PDFStrategic'])->name('PDF.strategic');
Route::get('project-pdf/{Id_Project}', [PDFController::class, 'PDFProject'])->name('PDF.Project');

Route::get('pdfStrategic/{Id_Project}', [PDFController::class, 'ctrlpPDFStrategic'])->name('pdf.strategicCtrlP');
// Route::get('pdfStrategic', [PDFController::class, 'ctrlpPDFStrategic'])->name('pdf.strategicCtrlP');
Route::get('project{Id_Project}', [PDFController::class, 'ctrlpPDFProject'])->name('PDF.projectCtrlP');



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

// StrategicUniversity
//Platform
Route::get('showPlatform', [StrategicUniController::class, 'showPlatform'])->name('showPlatform');
Route::post('/platform', [StrategicUniController::class, 'createPlatform'])->name('createPlatform');
Route::put('/editPlatform/{Id_Platform}', [StrategicUniController::class, 'editPlatform'])->name('editPlatform');
Route::delete('/deletePlatform/{Id_Platform}', [StrategicUniController::class, 'deletePlatform'])->name('deletePlatform');

//Platform_Kpi
Route::get('/showPlatformKpi/{Id_Platform}', [StrategicUniController::class, 'showPlatformKpi'])->name('showPlatformKpi');
Route::post('/createPlatformKpi/{Id_Platform}', [StrategicUniController::class, 'createPlatformKpi'])->name('createPlatformKpi');
Route::put('/editPlatformKpi/{Id_Platform}/{Id_Platform_Kpi}', [StrategicUniController::class, 'editPlatformKpi'])->name('editPlatformKpi');
Route::delete('/deletePlatformKpi/{Id_Platform_Kpi}', [StrategicUniController::class, 'deletePlatformKpi'])->name('deletePlatformKpi');

//Program
Route::get('/showProgram/{Id_Platform}/{Id_Program}', [StrategicUniController::class, 'showProgram'])->name('showProgram');
Route::post('/createProgram/{Id_Platform}', [StrategicUniController::class, 'createProgram'])->name('createProgram');
Route::put('/editProgram/{Id_Platform}/{Id_Program}', [StrategicUniController::class, 'editProgram'])->name('editProgram');
Route::delete('/deleteProgram/{Id_Program}', [StrategicUniController::class, 'deleteProgram'])->name('deleteProgram');

//Program_Kpi
Route::get('/showProgramKpi/{Id_Program}', [StrategicUniController::class, 'showProgramKpi'])->name('showProgramKpi');
Route::post('/createProgramKpi/{Id_Program}', [StrategicUniController::class, 'createProgramKpi'])->name('createProgramKpi');
Route::put('/editProgramKpi/{Id_Program}/{Id_Program_Kpi}', [StrategicUniController::class, 'editProgramKpi'])->name('editProgramKpi');
Route::delete('/deleteProgramKpi/{Id_Program_Kpi}', [StrategicUniController::class, 'deleteProgramKpi'])->name('deleteProgramKpi');

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








