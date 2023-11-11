<?php

use App\Http\Controllers\MarkEntryController;
use App\Http\Controllers\MarkLedgerController;
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

    Route::get('mark-entry', [MarkEntryController::class, 'index'])->name('mark-entry.index');
    Route::post('mark-entry', [MarkEntryController::class, 'store'])->name('mark-entry.store');
    Route::get('mark-entry/create', [MarkEntryController::class, 'create'])->name('mark-entry.create');
    Route::get('mark-entry/{student}', [MarkEntryController::class, 'edit'])->name('mark-entry.edit');
    Route::put('mark-entry/{student}', [MarkEntryController::class, 'update'])->name('mark-entry.update');

    Route::group(['prefix' => 'mark-ledger', 'as' => 'mark-ledger.'], function () {
        Route::get('/', [MarkLedgerController::class, 'index'])->name('index');

    });
});

require __DIR__.'/auth.php';
