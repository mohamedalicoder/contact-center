<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CustomFieldController;

Route::middleware(['auth'])->group(function () {
    // Queue Dashboard
    Route::get('/queue-dashboard', [QueueController::class, 'dashboard'])
        ->name('queue.dashboard');
    
    // Queue Management
    Route::resource('queues', QueueController::class);
    
    // Tags Management
    Route::resource('tags', TagController::class);
    
    // Custom Fields Management
    Route::resource('custom-fields', CustomFieldController::class);
    
    // Queue Items Management
    Route::prefix('queues/{queue}')->group(function () {
        Route::get('items', [QueueController::class, 'items'])
            ->name('queues.items');
        Route::post('items', [QueueController::class, 'addItem'])
            ->name('queues.items.store');
        Route::put('items/{item}', [QueueController::class, 'updateItem'])
            ->name('queues.items.update');
        Route::delete('items/{item}', [QueueController::class, 'removeItem'])
            ->name('queues.items.destroy');
    });
    
    // Queue Analytics
    Route::get('queue-analytics', [QueueController::class, 'analytics'])
        ->name('queues.analytics');
});
