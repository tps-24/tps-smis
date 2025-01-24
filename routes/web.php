<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionProgrammeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TimetableController;

require __DIR__ . '/auth.php';

Route::get('students', [StudentController::class, 'index']);

Route::get('/', function () {
    // $role = Auth::user()->role_id;
    // if($role == 1){
    //     return view('dashboard/dashboard');
    // }
    // elseif($role == 2){
    //     return view('/students/dashboard');
    // }
    return view('dashboard/dashboard');
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
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('products', ProductController::class);
    Route::resource('students', StudentController::class);  
    Route::resource('attendences', AttendenceController::class);
    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/change-password/{id}', [UserController::class, 'changePassword'])->name('changePassword');

    Route::controller(StudentController::class)->prefix('students')->group(function () {
        /**
         *  Wizard route for student registration
         */
        Route::prefix('create')->group(function(){
            Route::get('/', function () {
                return view('students/wizards/stepOne');
            });
            Route::get('step-two', function () {
                return view('students/wizards/stepTwo');
            });
            Route::get('step-three', function () {
                return view('students/wizards/stepThree');
            });
            Route::post('post-step-one','postStepOne');
            Route::post('post-step-two','postStepTwo');
            Route::post('post-step-three','postStepThree');
        });
        /**
         * End of wizard for student registration
         */

        

        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');
        Route::post('bulkimport', 'import');


    });

});


Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {
    Route::get('type/{type_id}', 'attendence');
    Route::post('create', 'create');
    Route::get('edit/{id}', 'edit');
    Route::post('{id}/store', 'store');
    Route::post('{id}/update', 'update');
    Route::get('today/{company_id}','today_attendence');
});


Route::get('/today/{company_id}/{type}', [AttendenceController::class, 'today']);
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');



Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');

Route::post('update-patient-status/{id}', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::resource('hospital', PatientController::class);
Route::get('patients/search', [PatientController::class, 'search'])->name('patients.search');

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

Route::post('/patients/save', [PatientController::class, 'save'])->name('patients.save');

Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::post('/patients/{id}/update', [PatientController::class, 'updateStatus'])->name('update.patient.status');

// routes for CCP timetable management:
Route::get('/timetables', [TimetableController::class, 'index'])->name('timetable.index');
Route::get('/timetables/create', [TimetableController::class, 'create'])->name('timetable.create');
Route::post('/timetables', [TimetableController::class, 'store'])->name('timetable.store');
Route::get('/timetables/{timetable}/edit', [TimetableController::class, 'edit'])->name('timetable.edit');
Route::put('/timetables/{timetable}', [TimetableController::class, 'update'])->name('timetable.update');
Route::delete('/timetables/{timetable}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
Route::get('/timetables/{company}/pdf', [TimetableController::class, 'generatePdf'])->name('timetable.pdf');
