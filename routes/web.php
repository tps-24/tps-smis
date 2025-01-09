<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;


Route::get('/', function () {
    return view('layouts/main');
});

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::group(['middleware' => ['auth']], function() {
    Route::controller(StudentController::class)->prefix('students')->group(function(){
        Route::get('', 'index');
        Route::get('{id}/show', 'show');
        //Route::get('create','create');
        Route::get('{id}/edit','edit');
        Route::post('{id}/update','update');
        Route::post('{id}/delete','destroy');
        Route::post('create-new-student','store');
    });
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});
Route::get('create', [StudentController::class, 'create']);
Route::post('create-new-student', [StudentController::class, 'store']);
//Route::get('students/{id}/edit', [StudentController::class, 'edit']);


Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home');