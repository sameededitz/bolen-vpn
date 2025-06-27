<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\UserFeedbackController;
use App\Livewire\AllCodes;
use App\Livewire\AllSliders;
use App\Livewire\AllUsers;
use App\Livewire\ManageUser;
use App\Livewire\SliderAdd;
use App\Livewire\SliderEdit;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'role:admin']], function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin-home');

    Route::get('/servers', [ServerController::class, 'Index'])->name('all-servers');
    Route::get('/add-server', [ServerController::class, 'AddServer'])->name('add-server');
    Route::get('/server/{server}/edit', [ServerController::class, 'EditServer'])->name('edit-server');
    Route::delete('/delete-server/{server}', [ServerController::class, 'DeleteServer'])->name('delete-server');

    Route::get('/plans', [PlanController::class, 'index'])->name('all-plans');
    Route::get('/add-plan', [PlanController::class, 'AddPlan'])->name('add-plan');
    Route::get('/plans/{plan:slug}', [PlanController::class, 'EditPlan'])->name('edit-plan');
    Route::delete('/plans/{plan:slug}', [PlanController::class, 'deletePlan'])->name('delete-plan');

    Route::get('/codes', AllCodes::class)->name('all-codes');

    Route::get('/sliders', AllSliders::class)->name('all-sliders');
    Route::get('/slider/add', SliderAdd::class)->name('add-slider');
    Route::get('/slider/{slider}/edit', SliderEdit::class)->name('edit-slider');

    Route::get('/feedbacks', [UserFeedbackController::class, 'feedbacks'])->name('all-feedbacks');
    Route::get('/feedbacks/{feedback}', [UserFeedbackController::class, 'view'])->name('edit-feedback');
    Route::delete('/feedbacks/{feedback}', [UserFeedbackController::class, 'destroy'])->name('delete-feedback');

    Route::get('/customers', AllUsers::class)->name('all-users');
    Route::get('/customers/{user}/manage', ManageUser::class)->name('manage-user');
    Route::delete('/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');

    Route::get('/options', [OptionsController::class, 'Options'])->name('all-options');
    Route::post('/options/save-info', [OptionsController::class, 'saveInfo'])->name('save-info');
    Route::post('/options/save', [OptionsController::class, 'saveOptions'])->name('save-options');

    Route::get('/adminUsers', [AdminController::class, 'allAdmins'])->name('all-admins');

    Route::get('/signup', [AdminController::class, 'addAdmin'])->name('add-admin');

    Route::get('/edit-admin/{user}', [AdminController::class, 'editAdmin'])->name('edit-admin');

    Route::delete('/delete-admin/{user}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');
});
