<?php

use App\Http\Controllers\ActivationCodeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OptionsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SliderController;
use App\Livewire\SliderAdd;
use App\Livewire\SliderEdit;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'verifyRole:admin']], function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin-home');

    Route::get('/servers', [ServerController::class, 'Index'])->name('all-servers');

    Route::get('/add-server', [ServerController::class, 'AddServer'])->name('add-server');

    Route::get('/server/{server}/edit', [ServerController::class, 'EditServer'])->name('edit-server');

    Route::delete('/delete-server/{server}', [ServerController::class, 'DeleteServer'])->name('delete-server');

    Route::get('/plans', [PlanController::class, 'Plans'])->name('all-plans');
    Route::get('/add-plan', [PlanController::class, 'AddPlan'])->name('add-plan');
    Route::get('/plans/{plan:slug}', [PlanController::class, 'EditPlan'])->name('edit-plan');
    Route::delete('/plans/{plan:slug}', [PlanController::class, 'deletePlan'])->name('delete-plan');

    Route::get('/codes', [ActivationCodeController::class, 'codes'])->name('all-codes');
    Route::post('/generate-code', [ActivationCodeController::class, 'generateActivationCode'])->name('generate-code');
    Route::delete('/codes/delete/{code:code}', [ActivationCodeController::class, 'destroy'])->name('delete-code');

    Route::get('/sliders', [SliderController::class, 'index'])->name('all-sliders');
    Route::get('/slider/add', SliderAdd::class)->name('add-slider');
    Route::get('/slider/{slider}/edit', SliderEdit::class)->name('edit-slider');
    Route::delete('/slider/{slider}/delete', [SliderController::class, 'destroy'])->name('delete-slider');

    Route::get('/customers', [AdminController::class, 'AllUsers'])->name('all-users');
    Route::delete('/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('delete-user');

    Route::get('/options', [OptionsController::class, 'Options'])->name('all-options');
    Route::post('/options/save', [OptionsController::class, 'saveOptions'])->name('save-options');

    Route::get('/adminUsers', [AdminController::class, 'allAdmins'])->name('all-admins');

    Route::get('/signup', [AdminController::class, 'addAdmin'])->name('add-admin');

    Route::get('/edit-admin/{user}', [AdminController::class, 'editAdmin'])->name('edit-admin');

    Route::delete('/delete-admin/{user}', [AdminController::class, 'deleteAdmin'])->name('delete-admin');
});
