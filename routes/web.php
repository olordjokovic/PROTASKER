<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\GoogleCalendarController;

use App\Http\Controllers\Admin\ManagerUserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CompletionRequestController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.form');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('forgot.send');

Route::get('/verify-code', [AuthController::class, 'showVerifyCode'])->name('code.form');
Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('code.verify');

Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

//Verificación de Código Registro
Route::get('/verify-register-code', [AuthController::class, 'showRegisterCodeForm'])->name('register.code.form');
Route::post('/verify-register-code', [AuthController::class, 'verifyRegisterCode'])->name('register.code.verify');

//Google Socialite

Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile.show');
Route::post('/profile/google/link', [AuthController::class, 'linkGoogleFromProfile'])->name('profile.google.link');

//Tema Dashboard

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
Route::post('/profile/remove-photo', [ProfileController::class, 'removePhoto'])->name('profile.removePhoto');

Route::prefix('admin')->group(function () {
    Route::resource('users', AdminUserController::class)->names('admin.users');
    Route::resource('projects', AdminProjectController::class)->names('admin.projects');
    Route::resource('tasks', AdminTaskController::class)->names('admin.tasks');
});
Route::get('/google/calendar/connect', [GoogleCalendarController::class, 'redirectToGoogle'])
    ->name('google.calendar.connect');

Route::get('/google/calendar/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])
    ->name('google.calendar.callback');

Route::get('/google/calendar/events', [GoogleCalendarController::class, 'listEvents'])
    ->name('google.calendar.events');

Route::post('/google/calendar/events', [GoogleCalendarController::class, 'storeEvent'])
    ->name('google.calendar.events.store');

Route::post('/google/calendar/disconnect', [GoogleCalendarController::class, 'disconnect'])
    ->name('google.calendar.disconnect');

    

Route::delete('/profile/delete', [ProfileController::class, 'destroy'])
    ->name('profile.delete');

    Route::get('/admin/gestores', [ManagerUserController::class, 'index'])
    ->name('admin.managers.index');

Route::post('/admin/gestores/{manager}/usuarios', [ManagerUserController::class, 'syncUsers'])
    ->name('admin.managers.syncUsers');

    Route::get('/about', function () {
    return view('about');
})->name('about');


Route::get('/loginadmin', [AuthController::class, 'showAdminLogin'])
    ->name('admin.login.form');

Route::post('/loginadmin', [AuthController::class, 'adminLogin'])
    ->name('admin.login');

    

Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');

Route::get('/messages', [MessageController::class, 'index'])
    ->name('messages.index');

Route::get('/messages/create', [MessageController::class, 'create'])
    ->name('messages.create');

Route::post('/messages', [MessageController::class, 'store'])
    ->name('messages.store');

Route::get('/messages/{message}', [MessageController::class, 'show'])
    ->name('messages.show');

    Route::get('/messages/{message}/reply', [MessageController::class, 'reply'])
    ->name('messages.reply');

Route::post('/messages/{message}/reply', [MessageController::class, 'storeReply'])
    ->name('messages.reply.store');

    use App\Http\Controllers\DashboardDetailController;

Route::get('/dashboard/projects', [DashboardDetailController::class, 'projects'])
    ->name('dashboard.projects');

Route::get('/dashboard/tasks/pending', [DashboardDetailController::class, 'pendingTasks'])
    ->name('dashboard.tasks.pending');

Route::get('/dashboard/tasks/progress', [DashboardDetailController::class, 'progressTasks'])
    ->name('dashboard.tasks.progress');

Route::get('/dashboard/tasks/completed', [DashboardDetailController::class, 'completedTasks'])
    ->name('dashboard.tasks.completed');

Route::get('/dashboard/report/{type}/{id}', [DashboardDetailController::class, 'report'])
    ->name('dashboard.report');

Route::post('/dashboard/report/{type}/{id}', [DashboardDetailController::class, 'sendReport'])
    ->name('dashboard.report.send');

    use App\Http\Controllers\EventCalendarController;

Route::get('/calendar', [EventCalendarController::class, 'index'])
    ->name('calendar.index');

Route::get('/calendar/events', [EventCalendarController::class, 'events'])
    ->name('calendar.events');

    

Route::get('/completion/{type}/{id}', [CompletionRequestController::class, 'create'])
    ->name('completion.create');

Route::post('/completion/{type}/{id}', [CompletionRequestController::class, 'store'])
    ->name('completion.store');

Route::get('/admin/completions', [CompletionRequestController::class, 'adminIndex'])
    ->name('completion.admin.index');

Route::get('/admin/completions/{completionRequest}', [CompletionRequestController::class, 'adminShow'])
    ->name('completion.admin.show');

Route::post('/admin/completions/{completionRequest}/approve', [CompletionRequestController::class, 'approve'])
    ->name('completion.approve');

Route::post('/admin/completions/{completionRequest}/reject', [CompletionRequestController::class, 'reject'])
    ->name('completion.reject');