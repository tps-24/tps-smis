<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionProgrammeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    return view('dashboard/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('dashboard/dashboard');
    });
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('session_programmes', SessionProgrammeController::class);
    Route::resource('products', ProductController::class);
});


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');