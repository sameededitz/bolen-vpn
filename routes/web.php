<?php

use App\Livewire\Auth\Login;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\VerifyController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin-home');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', Logout::class)->name('logout')->middleware('auth');

require __DIR__ . '/admin.php';

Route::get('/mailable', function () {
    $user = App\Models\User::find(2);
    $plan = App\Models\Plan::find(1);

    // return new App\Mail\UserActivationCode('TESTCODE123', $user, $plan);
    \Illuminate\Support\Facades\Mail::to($user->email)->send(new App\Mail\UserActivationCode('TESTCODE123', $user, $plan));
    return 'Mailable sent successfully';
});

Route::get('/optimize', function () {
    Artisan::call('optimize:clear');
    return 'Optimization completed';
});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Migration completed';
});

Route::get('/storage', function () {
    Artisan::call('storage:link');
    return 'Storage linked';
});

Route::get('/artisan/{command}', function ($command) {
    if (Auth::check()) {
        Artisan::call($command);
        return response()->json(['message' => 'Command executed successfully.']);
    }
    return response()->json(['message' => 'Unauthorized'], 403);
})->where('command', '.*')->name('artisan.command');
