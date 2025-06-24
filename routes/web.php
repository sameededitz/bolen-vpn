<?php

use App\Livewire\Auth\Login;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin-home');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', Logout::class)->name('logout')->middleware('auth');

require __DIR__ . '/admin.php';

Route::get('artisan/{command}', function ($command) {
    if (Auth::check() && Auth::user()->isAdmin()) {
        Artisan::call($command);
        return response()->json(['output' => Artisan::output(), 'status' => Artisan::output() ? 'success' : 'error', 'command' => $command]);
    }
    return response()->json(['error' => 'Unauthorized'], 403);
})->where('command', '.*');