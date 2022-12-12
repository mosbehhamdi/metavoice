<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/route-cache', function() {
    return 'Routes cache cleared';
});

Route::get('/dashboard', function () {
    return redirect()->route('file.upload');

})->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
     Route::post('file-upload', [FileController::class, 'store'])->name('file.upload.store');
     Route::any('deleteImage/{id}',[FileController::class,'destroy'])->name('deleteImage');
     Route::get('file-upload', [FileController::class, 'index'])->name('file.upload');


     Route::any('/sendPush', [NotificationController::class, 'push'])->name('sendPush');
     
     
     Route::put('/updateImage', [FileController::class, 'update'])->name('updateImage');

});

require __DIR__.'/auth.php';
