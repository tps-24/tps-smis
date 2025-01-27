<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
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
use App\Http\Controllers\MPSController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PatientController;

use App\Http\Controllers\GradingSystemController; 
use App\Http\Controllers\GradeMappingController;
use App\Http\Controllers\SemesterController; 
use App\Http\Controllers\ProgrammeCourseSemesterController;
use App\Http\Controllers\OptionalCourseEnrollmentController; 
use App\Http\Controllers\CourseWorkController; 
use App\Http\Controllers\SemesterExamController; 
use App\Http\Controllers\CourseworkResultController; 
use App\Http\Controllers\SemesterExamResultController; 
use App\Http\Controllers\FinalResultController; 
use App\Http\Controllers\ExcuseTypeController; 
use App\Http\Controllers\CampusController;


require __DIR__ . '/auth.php';

Route::get('students', [StudentController::class, 'index']);

Route::get('/', function () {
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
Route::group(['middleware' => 'session_programme'], function () {
    // Add more routes as needed
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::resource('students', StudentController::class);

Route::group(['middleware' => ['auth', 'verified']], function () {
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
    Route::resource('attendences', AttendenceController::class);
    Route::resource('mps', MPSController::class);
    Route::resource('staffs', StaffController::class);
    Route::resource('campuses', CampusController::class);


    
    Route::resource('grading_systems', GradingSystemController::class); 
    Route::resource('grade_mappings', GradeMappingController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('assign-courses', ProgrammeCourseSemesterController::class);
    Route::resource('enrollments', OptionalCourseEnrollmentController::class); 
    Route::resource('course_works', CourseWorkController::class); 
    Route::resource('semester_exams', SemesterExamController::class); 
    Route::resource('coursework_results', CourseworkResultController::class); 
    Route::resource('semester_exam_results', SemesterExamResultController::class); 
    Route::resource('final_results', FinalResultController::class);
    Route::resource('/settings/excuse_types', ExcuseTypeController::class);
    Route::post('/programmes/{programmeId}/semesters/{semesterId}/session/{sessionProgrammeId}/assign-courses', 
       [ProgrammeController::class, 'assignCoursesToSemester']); 
    Route::post('final_results/generate', [FinalResultController::class, 'generate'])->name('final_results.generate'); 

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
    Route::post('{attendenceType_id}/{platoon_id}/store', 'store');
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

//start
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
//end

Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');

Route::post('update-patient-status/{id}', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::resource('hospital', PatientController::class);
Route::post('patients/search', [PatientController::class, 'search'])->name('patients.search');

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

Route::post('/patients/save', [PatientController::class, 'save'])->name('patients.save');

Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');

Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');
