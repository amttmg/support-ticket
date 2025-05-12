<?php

use App\Http\Controllers\front\TicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['web', 'ensure.frontend.user']], function () {

    // This route is used to serve the frontend application before login
    Route::get('/', function () {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'appName' => config('app.name')
        ]);
    });
    require __DIR__ . '/auth.php';

    // This route is used to serve the frontend application after login

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('tickets', TicketController::class);
        Route::get('/departments', [TicketController::class, 'getDepartments']);
        Route::get('/departments/{departmentId}/units', [TicketController::class, 'getUnits']);
        Route::get('/units/{unitId}/topics', [TicketController::class, 'getTopics']);
        Route::get('/ticket-statuses', [TicketController::class, 'getStatuses']);
        Route::get('/abc')->name('knowledge-base');
    });
});
