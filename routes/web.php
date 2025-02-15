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
use App\Http\Controllers\BeatController;
use App\Http\Controllers\TimetableController;

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
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\AnnouncementController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
        $pending_message = session('pending_message');
        if (auth()->user()->hasRole('Student')) {
            return view('dashboard.student_dashboard', compact('pending_message'));
        } else {
            return view('dashboard/dashboard');
        }
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::group(['middleware' => 'session_programme'], function () {
    // Add more routes as needed

    // Route::controller(BeatController::class)->prefix('beats')->group(function () {
    //     Route::get('{area_id}/create', 'create');
    //     Route::get('{area_id}/search_students', 'search');
    //     Route::post('{area_id}/assign_students', 'store');
    //     Route::post('approve','approve_presence');
    // });

});

Route::controller(BeatController::class)->prefix('beats')->group(function () {
    Route::get('/', 'index');
    Route::get('companies/{beatType}','companies');
    Route::get('companies/{companyId}/areas','get_companies_area');
    Route::get('companies/{companyId}/patrol_areas','get_companies_patrol_area');
    Route::get('/store', 'store');
    Route::get('/show_guards/{area_id}', 'show_guards_beats');
    Route::get('/show_patrol/{area_id}', 'show_patrol_beats');
    Route::get('/show_patrol_areas', 'list_patrol_areas')->name('beats.show_patrol_areas');
    Route::put('/update/{area_id}', 'update_area');
    Route::put('/update_patrol_area/{patrol_area_id}', 'update_patrol_area');
    
    Route::get('/list-guards/{area_id}', 'list_guards');
    Route::get('/list-patrol/{patrolArea_id}', 'list_patrol');
    Route::get('/list-patrol-guards/{patrolArea_id}', 'list_patrol_guards');
    Route::put('/approve', 'approve_presence');
    Route::get('/downloadPdf/{company_id}/{beatType}/{day}', 'generateTodayPdf')->name('beats.downloadPdf');
});

Route::get('/students/registration', [StudentController::class, 'createPage'])->name('students.createPage');
Route::post('/students/registration', [StudentController::class, 'register'])->name('students.register');

// Route::get('/students', [StudentController::class, 'index'])->name(name: 'students.index');
// Route::resource('students', StudentController::class);

Route::middleware(['auth', 'check.student.status'])->group(function () {
    Route::get('/students/courses', [StudentController::class, 'myCourses'])->name('students.myCourses');
    Route::get('/student/home', [StudentController::class, 'dashboard'])->name('students.dashboard');
    Route::get('/students/courseworks', [CourseworkResultController::class, 'coursework'])->name('students.coursework');
    Route::get('/coursework/summary/{id}', [CourseworkResultController::class, 'summary'])->name('coursework.summary');
    Route::resource('students', StudentController::class);  
    
});

Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/students', [StudentController::class, 'index'])->name(name: 'students.index');
Route::post('students/search', [StudentController::class, 'search'])->name('students.search');
Route::resource('students', StudentController::class);
Route::group(['middleware' => ['auth']], function () {
    
    // Define the custom route first
    Route::post(
        '/programmes/{programmeId}/semesters/{semesterId}/session/{sessionProgrammeId}/assign-courses',
        [ProgrammeController::class, 'assignCoursesToSemester']
    );
    Route::post('final_results/generate', [FinalResultController::class, 'generate'])->name('final_results.generate');

    Route::get('/staff/profile/{id}', [StaffController::class, 'profile'])->name('profile');
    Route::get('/student/profile/{id}', [StudentController::class, 'profile'])->name('profile');
    Route::get('/profile/change-password/{id}', [UserController::class, 'changePassword'])->name('changePassword'); //Not yet, needs email config
    Route::get('/coursework_results/course/{course}', [CourseworkResultController::class, 'getResultsByCourse']);
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');
    Route::post('/students/{id}/approve', [StudentController::class, 'approveStudent'])->name('students.approve');
    Route::get('/student/complete-profile/{id}', [StudentController::class, 'completeProfile'])->name('students.complete_profile');
    Route::put('/student/profile-complete/{id}', [StudentController::class, 'profileComplete'])->name('students.profile_complete');

    
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('session_programmes', SessionProgrammeController::class);
    Route::resource('programmes', ProgrammeController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('products', ProductController::class);
    Route::resource('attendences', AttendenceController::class);
    Route::resource('mps', MPSController::class);
    Route::resource('staffs', StaffController::class);
    Route::resource('campuses', CampusController::class);
    Route::resource('announcements', AnnouncementController::class);


    
    // Define the custom route first
    Route::get('/coursework_results/course/{course}', [CourseworkResultController::class, 'getResultsByCourse']);
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');



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
    // Route::resource('beats', BeatController::class);




    Route::controller(StudentController::class)->prefix('students')->group(function () {
        /**
         *  Wizard route for student registration
         */
        Route::prefix('create')->group(function () {
            Route::get('step-two/{type}', "createStepTwo");
            Route::get('step-three/{type}', [StudentController::class, 'createStepThree']);

            Route::get('step-three', "createStepTwo");
            Route::post('post-step-one/{type}', 'postStepOne');
            Route::post('post-step-two/{type}', 'postStepTwo');
            Route::post('post-step-three/{type}', 'postStepThree');
        });
        /**
         * End of wizard for student registration
         */
        Route::post('students/search', 'search')->name('students.search');
        Route::get('dashboard', 'dashboard');
        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');
        Route::post('bulkimport', 'import');


    });

    Route::controller(MPSController::class)->prefix('mps')->group(function () {
        Route::post('search', 'search');
        Route::post('store/{id}', 'store');
        Route::post('release/{id}', 'release');
        Route::get('{company}/company', 'company');
    });





    Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {
        Route::get('type-test/{type_id}', 'attendence');
        Route::get('type/{type_id}', 'attendence');
        Route::post('create/{type_id}', 'create');
        Route::get('edit/{id}', 'edit');
        Route::post('{attendenceType_id}/{platoon_id}/store', 'store');
        Route::post('{id}/update', 'update');
        Route::get('list-absent_students/{list_type}/{attendence_id}', action: 'list');
        Route::get('list-safari_students/{list_type}/{attendence_id}', action: 'list_safari');
        Route::post('store-absents/{attendence_id}', action: 'storeAbsent');
        Route::post('store-safari/{attendence_id}', action: 'storeSafari');
        Route::get('today/{company_id}/{type}','today');
        Route::get('today/{company_id}/{$type}', 'today')->name('today_attendance');
    });

});



Route::get('/today/{company_id}/{type}', [AttendenceController::class, 'today']);


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

//start
Route::controller(StudentController::class)->prefix('students')->group(function () {
    /**
     *  Wizard route for student registration
     */
    Route::prefix('create')->group(function () {
      
        Route::get('step-two/{type}', function () {
            return view('students/wizards/stepTwo');
        });
        Route::get('step-three/{type}', function () {
            return view('students/wizards/stepThree');
        });
        Route::post('post-step-one/{type}', 'postStepOne');
        Route::post('post-step-two/{type}', 'postStepTwo');
        Route::post('post-step-three/{type}', 'postStepThree');
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
Route::get('patients/search', [PatientController::class, 'search'])->name('patients.search');

Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');

Route::post('/patients/save', [PatientController::class, 'save'])->name('patients.save');

Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');




Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');
Route::post('/patients/submit', [PatientController::class, 'submit'])->name('patients.submit');
Route::put('/patients/approve/{id}', [PatientController::class, 'approve'])->name('patients.approve');
Route::put('/patients/reject/{id}', [PatientController::class, 'reject'])->name('patients.reject');
Route::put('/patients/treat/{id}', [PatientController::class, 'treat'])->name('patients.treat');
Route::post('/patients/approve/{id}', [PatientController::class, 'approve'])->name('patients.approve');





// Route for sending details to receptionist
Route::post('/patients/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('patients.sendToReceptionist');

// Route for receptionist to see pending approvals
Route::get('/receptionist', [PatientController::class, 'receptionistPage'])->name('receptionist.index');

// Route for receptionist to approve patient
Route::patch('/patients/approve/{id}', [PatientController::class, 'approvePatient'])->name('patients.approve');



// Receptionist Routes
Route::get('/receptionist', [PatientController::class, 'receptionistIndex'])->name('receptionist.index');
Route::post('/patients/approve/{id}', [PatientController::class, 'approve'])->name('patients.approve');

//Daktari routes
Route::get('/doctor', [PatientController::class, 'doctorPage'])->name('doctor.page');
Route::post('/patients/saveDetails', [PatientController::class, 'saveDetails'])->name('patients.saveDetails');



Route::get('test', [BeatController::class,'test']);
