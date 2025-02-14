<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodosController;



Auth::routes();

Route::resource('todo', TodosController::class);
Route::resource('comments', CommentController::class)->middleware('auth');
Route::resource('images', ImageController::class);

Route::get('comment-delete/{id}', [CommentController::class, 'destroy'])->middleware('auth');
Route::get('comment-restore/{id}', [CommentController::class, 'restore'])->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/notifications/{notificationId}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

Route::middleware("auth")->controller(NotificationController::class)->group(function () {
    Route::get('/fetchNotifications', 'fetchNotifications');
});



Route::middleware('auth')->group(function () {
    Route::get('/chat/messages/{recipientId}', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/typing-status', [ChatController::class, 'typingStatus'])->name('chat.typingStatus');
    Route::post('/chat/send-status', [ChatController::class, 'sendStatus'])->name('chat.sendStatus');
});
