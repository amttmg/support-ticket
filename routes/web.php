<?php

use App\Http\Controllers\front\NotificationController;
use App\Http\Controllers\front\TicketCommentController;
use App\Http\Controllers\front\TicketController;
use App\Http\Controllers\ProfileController;
use App\Models\Branch;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['web', 'ensure.frontend.user']], function () {

    // This route is used to serve the frontend application before login
    Route::get('/', function () {
        // return Inertia::render('Welcome', [
        //     'canLogin' => Route::has('login'),
        //     'canRegister' => Route::has('register'),
        //     'branches' => Branch::all(),
        //     'auto_detect_ip' => config('app.auto_detect_ip'),
        //     'appName' => config('app.name')
        // ]);
        return redirect()->route('login');
    })->name('home');
    require __DIR__ . '/auth.php';

    // This route is used to serve the frontend application after login

    Route::middleware(['auth', 'verified'])->group(function () {

        Route::get('/dashboard', function () {
            return redirect()->route('tickets.index'); //return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('tickets', TicketController::class);
        Route::get('branch-tickets', [TicketController::class, 'branchTickets'])->name('tickets.branch-tickets');
        Route::get('/departments', [TicketController::class, 'getDepartments']);
        Route::get('/departments/{departmentId}/units', [TicketController::class, 'getUnits']);
        Route::get('/units/{unitId}/topics', [TicketController::class, 'getTopics']);
        Route::get('/ticket-statuses', [TicketController::class, 'getStatuses']);

        Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'storeComment'])->name('tickets.comments.store');
        Route::put('tickets/{ticket}/reopen', [TicketController::class, 'reopen'])
            ->name('tickets.reopen');

        Route::put('tickets/{ticket}/close', [TicketController::class, 'close'])
            ->name('tickets.close');


        Route::get('/notifications-all', [NotificationController::class, 'allNotification'])->name('notifications.all');
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    });
    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/support-ticket/livewire/update', $handle);
    });
});
