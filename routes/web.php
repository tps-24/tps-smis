<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;


require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('dashboard/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::group(['middleware' => ['auth']], function() {
    
Route::get('registration', [StudentController::class, 'create']);
Route::post('create-new-student', [StudentController::class, 'store']);
//Route::get('students/{id}/edit', [StudentController::class, 'edit']);
Route::controller(StudentController::class)->prefix('students')->group(function(){
    Route::get('', 'index');
    Route::get('{id}/show', 'show');
    Route::get('registration','create');
    Route::get('{id}/edit','edit');
    Route::post('{id}/update','update');
    Route::post('{id}/delete','destroy');
    Route::post('create-new-student','store');

});

});
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');

use App\Http\Controllers\PatientController;

Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');
// web.php


Route::post('update-patient-status/{id}', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
Route::post('/patients/{id}/update', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/update-patient-status/{id}', [PatientController::class, 'update'])->name('update.patient.status');
