<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionProgrammeController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;


require __DIR__ . '/auth.php';

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');