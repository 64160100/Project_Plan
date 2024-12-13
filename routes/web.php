<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StrategicController;


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
Route::get('/index', [ProjectController::class, 'index'])->name('index');

// Route::get('/addProject', [ProjectController::class, 'addProject'])->name('addProject');
// Route::get('/createProject', [ProjectController::class, 'createProject'])->name('createProject');
Route::post('/createProject', [ProjectController::class, 'createProject'])->name('createProject');
// Route::post('/addProjectStore', [ProjectController::class, 'addProjectStore'])->name('addProjectStore');




//แสดงหน้า Strategic
Route::get('/viewProjectInStrategic/{Id_Strategic}', [ProjectController::class, 'viewProjectInStrategic'])->name('viewProjectInStrategic');
Route::get('/viewProject/{Id_Project}', [ProjectController::class, 'viewProject'])->name('viewProject');











