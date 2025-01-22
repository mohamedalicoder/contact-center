<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\QueueDashboardController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

// Broadcast routes for authentication
Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::get('/', [LandingController::class, 'index'])->name('landing');

// Contact Form Routes
Route::get('/contact', [ContactFormController::class, 'show'])->name('contact.show');
Route::post('/contact/send', [ContactFormController::class, 'sendEmail'])->name('contact.send');

// Chat Routes
Route::prefix('chat')->name('chat.')->group(function () {
    // Public routes
    Route::get('/contact', [ChatController::class, 'contactForm'])->name('contact');
    Route::post('/store', [ChatController::class, 'store'])->name('store');

    // Chatbot Routes
    Route::get('/bot', [ChatbotController::class, 'bot'])->name('bot');
    Route::post('/bot', [ChatbotController::class, 'chat'])->name('bot.chat');
    Route::post('/bot/response', [ChatbotController::class, 'response'])->name('bot.response');

    // Routes that require authentication
    Route::middleware(['auth'])->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/create', [ChatController::class, 'create'])->name('create');
        Route::get('/agent', [ChatController::class, 'agent'])->name('agent');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('show');
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage'])->name('messages.store');
        Route::post('/{chat}/messages/voice', [ChatController::class, 'storeVoiceMessage'])->name('messages.voice');
        Route::post('/{chat}/end', [ChatController::class, 'endChat'])->name('end');
        Route::get('/available-agents', [ChatController::class, 'getAvailableAgents'])->name('available-agents');
        Route::post('/start-chat', [ChatController::class, 'startNewChat'])->name('start');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
     // controle users from admin
     Route::resource('users', UserController::class);

    // Live Chat Routes
    Route::prefix('livechat')->group(function () {
        Route::get('/', [LiveChatController::class, 'index'])->name('livechat.index');
        Route::get('/create', [LiveChatController::class, 'create'])->name('livechat.create');
        Route::post('/', [LiveChatController::class, 'store'])->name('livechat.store');
        Route::get('/{chat}', [LiveChatController::class, 'show'])->name('livechat.show');
        Route::post('/{chat}/message', [LiveChatController::class, 'sendMessage'])->name('livechat.message');
        Route::post('/{chat}/end', [LiveChatController::class, 'end'])->name('livechat.end');
    });

    // Route::middleware(['auth', 'check.role:admin'])->group(function () {
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
        Route::get('/analytics/{analytics}/edit', [AnalyticsController::class, 'edit'])->name('analytics.edit');
        Route::put('/analytics/{analytics}', [AnalyticsController::class, 'update'])->name('analytics.update');
        Route::delete('/analytics/{analytics}', [AnalyticsController::class, 'destroy'])->name('analytics.destroy');
    // });

    // Agent Routes
    // Route::middleware(['auth', 'check.role:admin,supervisor,agent'])->group(function () {
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
        Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
        Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');

        // Tag routes
        Route::resource('tags', TagController::class)->except(['create', 'edit', 'show']);

        // Custom field routes
        Route::resource('custom-fields', CustomFieldController::class)->except(['create', 'edit', 'show']);

        Route::resource('support-requests', SupportRequestController::class);
    // });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Queue Dashboard Routes
    Route::get('/queue-dashboard', [QueueDashboardController::class, 'index'])->name('queue.dashboard');
    Route::get('/queues/{queue}/details', [QueueDashboardController::class, 'queueDetails'])->name('queue.details');

    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/agent', [ChatController::class, 'agent'])->name('chat.agent');
        Route::get('/{chat}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/{chat}/messages', [ChatController::class, 'sendMessage'])->name('chat.messages.store');
        Route::post('/{chat}/messages/voice', [ChatController::class, 'storeVoiceMessage'])->name('chat.messages.voice');
        Route::post('/{chat}/end', [ChatController::class, 'endChat'])->name('chat.end');
    });
});

require __DIR__.'/auth.php';
