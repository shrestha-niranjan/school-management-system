<?php

use App\Http\Controllers\MarkEntryController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;

Route::get('/', function () {
    return to_route('dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('settings')
        ->name('settings.')
        ->group(
            function () {
                Route::get('/', [SettingController::class, 'edit'])->name('edit');
                Route::patch('/', [SettingController::class, 'update'])->name('update');
            }
        )
    ;

    Route::resource('users', UserController::class);
    Route::resource('mark-entry', MarkEntryController::class);

});

require __DIR__.'/auth.php';
