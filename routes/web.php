<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskResponceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/route-cache', function () {
    return 'Routes cache cleared';
});

Route::get('/Tasks', function () {
    return redirect()->route('indexTask');

})->middleware(['auth', 'verified'])->name('Tasks');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::any('storeTask', [TaskController::class, 'storeTask'])->name('storeTask');
    Route::any('deleteTask/{id}', [TaskController::class, 'destroyTask'])->name('deleteTask');
    Route::get('indexTask', [TaskController::class, 'indexTask'])->name('indexTask');
    Route::put('/updateTask', [TaskController::class, 'updateTask'])->name('updateTask');
    Route::get('indexWorkerTasks', [TaskController::class, 'indexWorkerTasks'])->name('indexWorkerTasks');

    Route::post('storeTaskResp', [TaskResponceController::class, 'store'])->name('storeTaskResp');
    Route::get('taskResponses/{id}', [TaskResponceController::class, 'index'])->name('taskResponses');
    
    Route::get('taskResponsesAdmin/{id}', [TaskResponceController::class, 'indexAdmin'])->name('taskResponsesAdmin');


    Route::any('/sendPush', [NotificationController::class, 'push'])->name('sendPush');

    Route::get('/test', [TaskController::class, 'test'])->name('testtest');
    Route::get('/adminTeam', [AdminController::class, 'team'])->name('adminTeam');
    Route::put('/skillsValidate', [AdminController::class, 'skillsValidate'])->name('skillsValidate');
    Route::put('/workerUpdate', [AdminController::class, 'workerUpdate'])->name('workerUpdate');
    Route::put('/filterTeleworkers', [AdminController::class, 'filterTeleworkers'])->name('filterTeleworkers');
    Route::delete('/destroyTeleworker/{id}', [RegisteredUserController::class, 'destroy'])->name('destroyTeleworker');

    
    Route::post('skill', [SkillController::class, 'store'])->name('skill.store');
    Route::any('deleteSkill/{id}', [SkillController::class, 'destroy'])->name('deleteSkill');
    Route::any('deleteSkillForever/{id}', [SkillController::class, 'deleteSkillForever'])->name('deleteSkillForever');

    Route::get('skills', [SkillController::class, 'index'])->name('skills');
    Route::get('skillAdd', [SkillController::class, 'skillAdd'])->name('skillAdd');
    Route::get('workerSkills/{id}', [SkillController::class, 'workerSkills'])->name('workerSkills');
    Route::put('validateSkills/', [SkillController::class, 'validateSkills'])->name('validateSkills');

    Route::get('skillWorkers/{id}', [SkillController::class, 'skillWorkers'])->name('skillWorkers');

    Route::post('disableSkill', [SkillController::class, 'disableSkill'])->name('disableSkill');

    Route::post('enableSkill', [SkillController::class, 'enableSkill'])->name('enableSkill');

});

require __DIR__ . '/auth.php';
