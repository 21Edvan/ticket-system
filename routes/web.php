<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketAttachmentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/admin', function () {
        return 'Panel de administrador';
    })->middleware('role:admin');

    Route::get('/technician', function () {
        return 'Panel de técnico';
    })->middleware('role:technician');

    Route::get('/user', function () {
        return 'Panel de usuario';
    })->middleware('role:user');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::view('/tickets/index', 'tickets.index')->name('tickets.index');
    Route::view('/tickets/create', 'tickets.create')->name('tickets.create');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show')->can('view', 'ticket');
    
    Route::get('/attachments/{attachment}',[TicketAttachmentController::class, 'show'])->name('attachments.show');

    Route::get('/attachments/{attachment}/download',[TicketAttachmentController::class, 'download'])->name('attachments.download');

});

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {

    Route::view(
        '/admin/users',
        'admin.users'
    )->name('admin.users.index');

    Route::view(
        '/admin/settings/branding',
        'admin.branding'
    )->name('admin.branding.edit');

    Route::view(
        '/admin/reports',
        'admin.reports'
    )->name('admin.reports.index');

});