<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionProgrammeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\MPSController;

require __DIR__ . '/auth.php';

Route::get('students', [StudentController::class, 'index']);

Route::get('/', function () {
    $role = Auth::user()->role_id;
    if($role == 1){
        return view('dashboard/dashboard');
    }
    elseif($role == 2){
        return view('/students/dashboard');
    }
    //return view('dashboard/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/', function () {
//     return view('dashboard/dashboard');
// });
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::group(['middleware' => ['auth']], function () {
    Route::get('/students/dashboard', function () {
        return view('students/dashboard');
    });

    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('session_programmes', SessionProgrammeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('students', StudentController::class);
    Route::resource('attendences', AttendenceController::class);
    Route::resource('mps', MPSController::class);

    Route::controller(StudentController::class)->prefix('students')->group(function () {
        /**
         *  Wizard route for student registration
         */
        Route::prefix('create')->group(function(){
            Route::get('/', function () {
                return view('students/wizards/stepOne');
            });
            Route::get('step-two/{type}', function () {
                return view('students/wizards/stepTwo');
            });
            Route::get('step-three', function () {
                return view('students/wizards/stepThree');
            });
            Route::post('post-step-one/{type}','postStepOne');
            Route::post('post-step-two/{type}','postStepTwo');
            Route::post('post-step-three/{type}','postStepThree');
        });
        /**
         * End of wizard for student registration
         */

        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');
        Route::post('bulkimport', 'import');


    });

    Route::controller(MPSController::class)->prefix('mps')->group(function(){
        Route::post('search','search');
        Route::post('store/{id}','store');
        Route::post('release/{id}','release');
        Route::get('{company}/company','company');
    });

});


Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {
    Route::get('type-test/{type_id}', 'testAttendence');
    Route::get('type/{type_id}', 'attendence');
    Route::post('create/{type_id}', 'create');
    Route::get('edit/{id}', 'edit');
    Route::post('{id}/store', 'store');
    Route::post('{id}/update', 'update');
    Route::get('list-absent_students/{list_type}/{attendence_id}',action: 'list');
    Route::get('list-safari_students/{list_type}/{attendence_id}',action: 'list_safari');
    Route::post('store-absents/{attendence_id}',action: 'storeAbsent');
    Route::post('store-safari/{attendence_id}',action: 'storeSafari');
    Route::get('today/{company_id}','today_attendence');
    
});


Route::get('/today/{company_id}/{type}', [AttendenceController::class, 'today']);
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


Route::get('/test/{company}',[AttendenceController::class,'list']);
Route::post('/test_post/{attendence_id}',[AttendenceController::class,'storeAbsent']);