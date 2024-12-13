<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\QueueDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin Routes
    // Route::middleware(['role:admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // Settings Routes
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
        Route::put('/settings/{setting}', [SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');

        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/create', [AnalyticsController::class, 'create'])->name('analytics.create');
        Route::post('/analytics', [AnalyticsController::class, 'store'])->name('analytics.store');
        Route::put('/analytics/{analytics}', [AnalyticsController::class, 'update'])->name('analytics.update');
        Route::delete('/analytics/{analytics}', [AnalyticsController::class, 'destroy'])->name('analytics.destroy');
    // });

    // Agent Routes
    // Route::middleware(['role:admin|supervisor|agent'])->group(function () {
        // Contacts Routes
        Route::resource('contacts', ContactController::class);
        Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
        Route::get('/contacts/{contact}/delete', [ContactController::class, 'destroy'])->name('contacts.delete');
        Route::get('/contacts/{contact}/activities', [ContactController::class, 'activities'])->name('contacts.activities');

        // Calls Routes
        Route::resource('calls', CallController::class);
        Route::get('/calls/{call}/delete', [CallController::class, 'destroy'])->name('calls.delete');

        // Tickets Routes
        Route::resource('tickets', TicketController::class);
        Route::get('/tickets/{ticket}/delete', [TicketController::class, 'destroy'])->name('tickets.delete');

        // Tag routes
        Route::resource('tags', TagController::class)->except(['create', 'edit', 'show']);

        // Custom field routes
        Route::resource('custom-fields', CustomFieldController::class)->except(['create', 'edit', 'show']);
    // });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chat routes
    Route::get('/chat', [LiveChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [LiveChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/{chat}', [LiveChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/message', [LiveChatController::class, 'sendMessage'])->name('chat.message');
    Route::post('/chat/{chat}/end', [LiveChatController::class, 'end'])->name('chat.end');

    // Queue Dashboard Routes
    Route::get('/queue-dashboard', [QueueDashboardController::class, 'index'])->name('queue.dashboard');
    Route::get('/queues/{queue}/details', [QueueDashboardController::class, 'queueDetails'])->name('queue.details');
});

require __DIR__.'/auth.php';
