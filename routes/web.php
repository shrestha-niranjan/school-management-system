<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MarkEntryController;
use App\Http\Controllers\MarkSheetController;
use App\Http\Controllers\MarkLedgerController;
use App\Http\Controllers\GradeLedgerController;

Route::get('/', function () {
    return to_route('dashboard');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('school-settings')
        ->name('settings.')
        ->group(
            function () {
                Route::get('/', [SettingController::class, 'edit'])->name('edit');
                Route::patch('/', [SettingController::class, 'update'])->name('update');
            }
        )
    ;

    Route::resource('students', StudentController::class);
    Route::resource('courses', CourseController::class);


    Route::get('mark-entry', [MarkEntryController::class, 'index'])->name('mark-entry.index');
    Route::post('mark-entry', [MarkEntryController::class, 'store'])->name('mark-entry.store');
    Route::get('mark-entry/create', [MarkEntryController::class, 'create'])->name('mark-entry.create');
    Route::get('mark-entry/{student}', [MarkEntryController::class, 'edit'])->name('mark-entry.edit');
    Route::put('mark-entry/{student}', [MarkEntryController::class, 'update'])->name('mark-entry.update');

    Route::group(['prefix' => 'mark-ledger', 'as' => 'mark-ledger.'], function () {
        Route::get('/', [MarkLedgerController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'grade-ledger', 'as' => 'grade-ledger.'], function () {
        Route::get('/', [GradeLedgerController::class, 'index'])->name('index');
    });

    Route::get('marksheet/{student}', MarkSheetController::class)->name('generate.marksheet');
});

require __DIR__.'/auth.php';
