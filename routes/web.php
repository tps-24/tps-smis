<?php

use App\Http\Controllers\NotificationController;
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
use App\Http\Controllers\GuardAreaController;
use App\Http\Controllers\PatrolAreaController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\MPSVisitorController;
use App\Http\Controllers\StaffProgrammeCourseController;
use App\Http\Controllers\TimeSheetController;
use Carbon\Carbon;
use App\Http\Controllers\LeaveController;

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


// Route::get('/', function () {
//     return view('dashboard.default_dashboard');
// });


Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');

Route::group(['middleware' => ['auth', 'verified', 'check_active_session']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/content', [DashboardController::class, 'getContent'])->name('dashboard.content');
    // Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
});


// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });



// Route::controller(BeatController::class)->prefix('beats')->group(function () {
//     Route::get('/', 'index');
//     Route::get('companies/{beatType}','companies');
//     Route::get('companies/{companyId}/areas','get_companies_area');
//     Route::get('companies/{companyId}/patrol_areas','get_companies_patrol_area');
//     Route::get('/store', 'store');
//     Route::get('/show_guards/{area_id}', 'show_guards_beats');
//     Route::get('/show_patrol/{area_id}', 'show_patrol_beats');
//     Route::get('/show_patrol_areas', 'list_patrol_areas')->name('beats.show_patrol_areas');
//     Route::put('/update/{area_id}', 'update_area');
//     Route::put('/update_patrol_area/{patrol_area_id}', 'update_patrol_area');
    
//     Route::get('/list-guards/{area_id}', 'list_guards');
//     Route::get('/list-patrol/{patrolArea_id}', 'list_patrol');
//     Route::get('/list-patrol-guards/{patrolArea_id}', 'list_patrol_guards');
//     Route::put('/approve', 'approve_presence');
//     Route::get('/downloadPdf/{company_id}/{beatType}/{day}', 'generateTodayPdf')->name('beats.downloadPdf');
// });



Route::middleware(['auth', 'checkCourseInstructor'])->group(function () {
});

    Route::get('/coursework_results/course/{course}', [CourseworkResultController::class, 'getResultsByCourse']);
    
    Route::resource('coursework_results', CourseworkResultController::class);


// Route::middleware(['auth', 'checkCourseInstructor'])->group(function () {
//     Route::get('/course/{course}', [CourseController::class, 'show'])->name('course.show');
//     Route::get('/course/{course}/coursework', [CourseworkController::class, 'index'])->name('coursework.index');
//     Route::post('/course/{course}/coursework', [CourseworkController::class, 'store'])->name('coursework.store');
//     Route::put('/course/{course}/coursework/{id}', [CourseworkController::class, 'update'])->name('coursework.update');
   
// });



Route::get('/students/registration', [StudentController::class, 'createPage'])->name('students.createPage');
Route::post('/students/registration', [StudentController::class, 'register'])->name('students.register');

Route::middleware(['auth', 'check.student.status'])->group(function () {
    Route::get('/students/courses', [StudentController::class, 'myCourses'])->name('students.myCourses');
    Route::get('/student/home', [StudentController::class, 'dashboard'])->name('students.dashboard');
    Route::get('/students/courseworks', [CourseworkResultController::class, 'coursework'])->name('students.coursework');
    Route::get('/coursework/summary/{id}', [CourseworkResultController::class, 'summary'])->name('coursework.summary');
    Route::get('/coursework/upload_explanation/{courseId}', [CourseworkResultController::class, 'create_import'])->name('coursework.upload_explanation');
    Route::post('/coursework/upload/{courseId}', [CourseworkResultController::class, 'import'])->name('coursework.upload');
    Route::get('/update-fasting-status/{studentId}/{fastingStatus}', [StudentController::class, 'updateFastStatus'])->name('updateFastingStatus');
    Route::get('/update-beat-status-to-safari/{studentId}', [StudentController::class, 'toSafari'])->name('students.toSafari');
    Route::resource('students', StudentController::class);  
    
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/default', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/print-certificates', [FinalResultController::class, 'studentList'])->name('studentList');
    Route::get('/students', [StudentController::class, 'index'])->name(name: 'students.index');
    Route::post('students/search', [StudentController::class, 'search'])->name('students.search');
    Route::get('students/search_certificate/{companyId}', [FinalResultController::class, 'search'])->name('students.search_certificate');



    Route::post('/beats/{id}', [BeatController::class, 'update'])->name('beat.update');
    Route::get('/beats/generate', [BeatController::class, 'beatCreate'])->name('beats.beatCreate');
    Route::get('/beats', [BeatController::class, 'beatsByDate'])->name('beats.byDate');
    Route::delete('/beats/{id}', [BeatController::class, 'destroy'])->name('beats.destroy');
    Route::get('/beats/{id}/edit', [BeatController::class, 'edit'])->name('beats.edit');
    Route::post('/fill-beats', [BeatController::class, 'fillBeats'])->name('beats.fillBeats');
    // Route::get('/beats', [BeatController::class, 'showBeats'])->name('beats.index');
    Route::get('/beats/{beat}', [BeatController::class, 'showBeat'])->name('beats.show');
    
    Route::get('/beats/pdf/{company}', [BeatController::class, 'generatePDF'])->name('beats.generatePDF');
    Route::post('/generate-transcript', [FinalResultController::class, 'generateTranscript'])->name('final.generateTranscript');
    
    
    // Route to generate and display the report
    Route::get('/report/generate', [BeatController::class, 'showReport'])->name('report.generate');
    // Route to download the report as a PDF
    Route::get('/report/history/{companyId}', [BeatController::class, 'downloadHistoryPdf'])->name('report.history');
    
    Route::get('/beats/reserves/{companyId}/{date}', [BeatController::class, 'beatReserves'])->name('beats.reserves');
    Route::get('/beats/approve-reserve/{studentId}', [BeatController::class, 'approveReserve'])->name('beats.approve-reserve');
    Route::get('/beats/reserve-replacement/{reserveId}/{date}/{beatReserveId}', [BeatController::class, 'beatReplacementStudent'])->name('beats.reserve-replacement');
    Route::post('/beats/replace-reserve/{reserveId}/{studentId}/{date}/{beatReserveId}', [BeatController::class, 'beatReserveReplace'])->name('beats.replace-reserve');
    
    Route::get('/students/downloadSample', [StudentController::class, 'downloadSample'])->name('studentDownloadSample');
    Route::get('/staff/downloadSample', [StaffController::class, 'downloadSample'])->name('staffDownloadSample');
    Route::get('/courseworkResult/downloadSample', [CourseworkResultController::class, 'downloadSample'])->name('courseworkResultDownloadSample');
    Route::get('students/upload-students', function(){
        return view('students.bulk_upload_explanation');
    })->name('uploadStudents');
    
    Route::get('staff/upload-staff', function(){
        return view('staffs.bulk_upload_explanation');
    })->name('uploadStaff');
  
    Route::post('staff/search', [StaffController::class, 'search'])->name('staff.search');
    Route::get('/assign-instructors', [StaffProgrammeCourseController::class, 'showAssignInstructorsForm'])->name('assign.instructors.form');
    Route::post('/assign-instructors', [StaffProgrammeCourseController::class, 'assignInstructors'])->name('assign.instructors');

   
    Route::controller(StudentController::class)->prefix('students')->group(function () {
        Route::post('activate_beat_status/{studentId}', 'activate_beat_status')->name('students.activate_beat_status');
        Route::post('deactivate_beat_status/{studentId}', 'deactivate_beat_status')->name('students.deactivate_beat_status');
        /**
         * 
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
        Route::get('dashboard', 'dashboard');
        Route::post('store', 'store');
        Route::post('{id}/update', 'update');
        Route::post('{id}/delete', 'destroy');
        
        Route::post('bulkimport', 'import');


    });


    Route::resource('students', StudentController::class);  
    
});


Route::group(['middleware' => ['auth']], function () {    
    // Define the custom route first
    Route::get('platoons/{companyName}', [AttendenceController::class,'getPlatoons']);
    Route::get('courseworks/{semesterId}', [CourseworkController::class, 'getCourseworks']);
    Route::get('/coursework_results/course/{course}', [CourseworkResultController::class, 'getResultsByCourse']);
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');

    // Define the custom route first
    Route::post(
        '/programmes/{programmeId}/semesters/{semesterId}/session/{sessionProgrammeId}/assign-courses',
        [ProgrammeController::class, 'assignCoursesToSemester']
    );


    Route::controller(MPSController::class)->prefix('mps')->group(function () {
        Route::post('search', 'search');
        Route::post('store/{id}', 'store');
        Route::post('release/{id}', 'release');
        Route::get('{company}/company', 'company');
    });

    Route::controller(MPSVisitorController::class)->prefix('visitors')->group(function () {
        Route::post('index','index')->name('visitors.index');
        Route::post('store/{studentId}','store')->name('visitors.store');
        Route::post('update/{studentId}','update')->name('visitors.update');
        Route::post('search-student','searchStudent')->name('visitors.searchStudent');
    });


    Route::post('final_results/generate', [FinalResultController::class, 'generate'])->name('final_results.generate');
    Route::post('/staff/bulkimport', [StaffController::class, 'import'])->name('staff.bulkimport');
    Route::get('/staff/profile/{id}', [StaffController::class, 'profile'])->name('profile');
    Route::get('/student/profile/{id}', [StudentController::class, 'profile'])->name('profile');
    Route::get('/profile/change-password/{id}', [UserController::class, 'changePassword'])->name('changePassword'); //Not yet, needs email config
    Route::post('users/search', [UserController::class, 'search'])->name('users.search');
    
    Route::get('assign-courses/{id}', [ProgrammeCourseSemesterController::class, 'assignCourse'])->name('assign-courses.assignCourse');
    Route::post('/students/{id}/approve', [StudentController::class, 'approveStudent'])->name('students.approve');
    Route::get('/student/complete-profile/{id}', [StudentController::class, 'completeProfile'])->name('students.complete_profile');
    Route::put('/student/profile-complete/{id}', [StudentController::class, 'profileComplete'])->name('students.profile_complete');

Route::get('patrol-areas/{patrolArea}/edit', [PatrolAreaController::class, 'edit'])->name('patrol-areas.edit');
Route::put('patrol-areas/{patrolArea}', [PatrolAreaController::class, 'update'])->name('patrol-areas.update');
Route::get('guard-areas/{guardArea}/edit', [GuardAreaController::class, 'edit'])->name('guard-areas.edit');
Route::put('guard-areas/{guardArea}', [GuardAreaController::class, 'update'])->name('guard-areas.update');
Route::put('timesheets/{timesheetId}/reject', [TimeSheetController::class, 'reject'])->name('timesheets.reject');
Route::put('timesheets/{timesheetId}/approve', [TimeSheetController::class, 'approve'])->name('timesheets.approve');
Route::post('timesheets/filter', [TimeSheetController::class, 'filter'])->name('timesheets.filter');


    
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
    Route::resource('visitors', MPSVisitorController::class);
    Route::resource('timesheets', TimeSheetController::class);
    Route::resource('grading_systems', GradingSystemController::class); 
    Route::resource('grade_mappings', GradeMappingController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('assign-courses', ProgrammeCourseSemesterController::class);
    Route::resource('enrollments', OptionalCourseEnrollmentController::class);
    Route::resource('course_works', CourseWorkController::class);
    Route::resource('semester_exams', SemesterExamController::class);
    Route::resource('semester_exam_results', SemesterExamResultController::class);
    Route::resource('final_results', FinalResultController::class);
    Route::resource('/settings/excuse_types', ExcuseTypeController::class);
    Route::resource('guard-areas', GuardAreaController::class);
    Route::resource('patrol-areas', PatrolAreaController::class);


    
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

    Route::controller(AttendenceController::class)->prefix('attendences')->group(function () {
        Route::get('type-test/{type_id}', 'attendence');
        Route::get('type/{type_id}', 'attendence')->name('attendances.summary');
        Route::post('create/{type_id}', 'create');
        Route::get('edit/{id}', 'edit');
        Route::post('{attendenceType_id}/{platoon_id}/store', 'store');
        Route::post('{id}/update', 'update');
        Route::get('list-absent_students/{list_type}/{attendence_id}/{date}', action: 'list');
        Route::get('list-safari_students/{list_type}/{attendence_id}', action: 'list_safari');
        Route::post('store-absents/{attendence_id}/{date}', action: 'storeAbsent');
        Route::post('store-safari/{attendence_id}', action: 'storeSafari');
        Route::get('today/{company_id}/{type}','today');
        Route::get('generatepdf/{companyId}/{date}','generatePdf')->name('attendences.generatePdf');
        Route::get('changanua/{attendenceId}/','changanua')->name('attendences.changanua');
        Route::post('storeMchanganuo/{attendenceId}/','storeMchanganuo')->name('attendences.storeMchanganuo');
    });

    Route::get('notifications/{notification_category}/{notification_type}/{notification_id}/{ids}',[NotificationController::class,'show']); 
    Route::get('notifications/showNotifications/{notificationIds}',[NotificationController::class,'showNotifications'])->name('notifications.showNotifications'); 

    Route::get('announcement/download/file/{documentPath}',[AnnouncementController::class,'downloadFile'])->name('download.file'); 

});

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


Route::get('/hospital/viewDetails/{timeframe}', [PatientController::class, 'viewDetails'])->name('hospital.viewDetails');
Route::get('/hospital/show/{id}', [PatientController::class, 'show'])->name('hospital.show');

// 🏥 Patients Routes
Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
Route::post('/patients/save', [PatientController::class, 'save'])->name('patients.save');
Route::put('/patients/{id}/update-status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::put('/patient/{id}/status', [PatientController::class, 'updateStatus'])->name('update.patient.status');

Route::get('/hospital', [PatientController::class, 'index'])->name('hospital.index');
Route::post('/patients/submit', [PatientController::class, 'submit'])->name('patients.submit');
Route::patch('/patients/approve/{id}', [PatientController::class, 'approvePatient'])->name('patients.approve'); 
Route::put('/patients/reject/{id}', [PatientController::class, 'reject'])->name('patients.reject');
Route::put('/patients/treat/{id}', [PatientController::class, 'treat'])->name('patients.treat');
Route::get('patients/search', [PatientController::class, 'search'])->name('patients.search');
Route::get('/dispensary', [PatientController::class, 'dispensaryPage'])->name('dispensary.page');


// 🚀 Routes for Sending to Receptionist
// Route::post('/students/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('students.sendToReceptionist');
Route::post('/hospital/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('hospital.sendToReceptionist');
Route::post('/students/send-to-receptionist', [PatientController::class, 'sendToReceptionist'])->name('students.sendToReceptionist');

// 💼 Receptionist Routes
Route::get('/receptionist', [PatientController::class, 'receptionistPage'])->name('receptionist.index')->middleware('auth');
Route::post('/patients/{id}/approve', [PatientController::class, 'approvePatient'])->name('patients.approve')->middleware('auth');
Route::get('/receptionist', [PatientController::class, 'receptionistPage'])->name('receptionist.index');

// 🩺 Doctor Routes
Route::get('/doctor', [PatientController::class, 'doctorPage'])->name('doctor.page');
Route::post('/patients/saveDetails', [PatientController::class, 'saveDetails'])->name('patients.saveDetails');

// 📅 Timetable Routes
Route::get('/timetable', [TimetableController::class, 'index'])->name('timetable.index');
Route::get('/timetable/create', [TimetableController::class, 'create'])->name('timetable.create');
Route::post('/timetable/store', [TimetableController::class, 'store'])->name('timetable.store');
Route::get('/timetable/{id}/edit', [TimetableController::class, 'edit'])->name('timetable.edit');
Route::put('/timetable/{id}', [TimetableController::class, 'update'])->name('timetable.update');
Route::delete('/timetable/{id}', [TimetableController::class, 'destroy'])->name('timetable.destroy');
Route::get('/timetable/export-pdf', [TimetableController::class, 'exportPDF'])->name('timetable.exportPDF');
Route::get('/generate-timetable', [TimetableController::class, 'generateTimetable'])->name('timetable.generate');

//Downloader Centre Routes

Route::middleware(['auth'])->group(function () {
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index'); // List files
    Route::get('/downloads/upload', [DownloadController::class, 'create'])->name('downloads.create'); // Upload form
    Route::post('/downloads/upload', [DownloadController::class, 'store'])->name('downloads.store'); // Upload action
    Route::get('/download/{file}', [DownloadController::class, 'download'])->name('downloads.file'); // Download file
});

Route::get('test', [BeatController::class,'beatReplacementStudent']);
Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/upload', [DownloadController::class, 'showUploadPage'])->name('downloads.upload.page');
Route::post('/downloads/upload', [DownloadController::class, 'upload'])->name('downloads.upload');
Route::get('/downloads/{file}', [DownloadController::class, 'download'])->name('downloads.file');
Route::delete('/downloads/{id}', [DownloadController::class, 'destroy'])
    ->name('downloads.delete')
    ->middleware('auth'); // Requires login to delete

   
//Leaves Routes



Route::middleware(['auth'])->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::post('/leave-requests/{id}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
    Route::post('/leave-requests/{id}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
});
