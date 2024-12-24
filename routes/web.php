<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\SDGController;
use App\Http\Controllers\StrategicUniController;


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
Route::delete('/deletePlatform/{id_SDGs}', [StrategicUniController::class, 'deletePlatform'])->name('deletePlatform');

//Platform_Kpi
Route::get('showPlatformObj/{Id_Platform}', [StrategicUniController::class, 'showPlatformObj'])->name('showPlatformObj');
Route::post('/createPlatformKpi/{Id_Platform}', [StrategicUniController::class, 'createPlatformKpi'])->name('createPlatformKpi');


Route::get('/test', function () {
    return view('test');
});








