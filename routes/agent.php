<?php

use App\Http\Controllers\SalesAgent\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesAgent\SalesAgentAuthController;
use App\Http\Controllers\SalesAgent\SalesAgentLoginController;
use App\Http\Controllers\SalesAgent\SalesAgentNotificationController;
use App\Http\Controllers\SalesAgent\SalesAgentPrivateNotesController;

//################################ Sales Agent Routes #############################
Route::get('/sales-agent', [SalesAgentLoginController::class, 'getAgentLoginPage']);
Route::post('sales-agent/login', [SalesAgentLoginController::class, 'loginSalesAgent']);
Route::get('/salesAgent-forgot-password', [SalesAgentAuthController::class, 'salesAgentforgetPassword']);
Route::post('/salesAgent-reset-password-link', [SalesAgentAuthController::class, 'salesAgentResetPasswordLink']);
Route::get('/salesAgent-change-password/{id}', [SalesAgentAuthController::class, 'salesAgentChangePassword']);
Route::post('/salesAgent-reset-password', [SalesAgentAuthController::class, 'salesAgentResetPassword']);

Route::prefix('sales-agent')->middleware('sales_agent')->group(function () {
    // ############## Sales Agent Profile ############
    Route::controller(SalesAgentAuthController::class)->group(function () {
        Route::get('dashboard', 'getSalesAgentdashboard')->name('dashboard.salesAgent');
        Route::get('profile', 'getSalesAgentProfile');
        Route::post('update-profile', 'sales_agent_update_profile');
        Route::get('logout', 'salesAgentlogout');
    });

    // ############## Sales Agent Notification Controller ############
    Route::controller(SalesAgentNotificationController::class)->group(function () {
        Route::get('/notifications',  'getNotifications')->name('notifications.index');
        Route::post('/notifcation-read', 'markAllAsRead')->name('notification.read');
        Route::post('/notifcation-read/{notificationId}', 'markAsRead')->name('notification.marked');
        Route::get('/notifcations', 'notificationScreen')->name('notification.screen');
    });
    // ############## Sales Agent Private Notes ############
    Route::controller(SalesAgentPrivateNotesController::class)->group(function () {
        Route::get('/agentNotes',  'agentNotesIndex')->name('agentNotes.index');
        Route::post('/agentNotes-create',  'agentNotesCreate')->name('agentNotes.create');
        Route::get('/agentNotesData',  'agentNotesData')->name('agentNotes.get');
        Route::get('/agentNotes/{id}',  'showAgentNotes')->name('agentNotes.show');
        Route::post('/agentNotesUpdate/{id}',  'updateAgentNotes')->name('agentNotes.update');
        Route::get('/agentNotes/delete/{id}',  'deleteAgentNotes')->name('agentNotes.delete');
    });

       // ############## SalesAgent Orders ############
       Route::controller(OrderController::class)->group(function () {
        Route::get('/orderData',  'orderData')->name('order.get')->middleware('permission:Pending Orders');
        Route::get('/order',  'orderIndex')->name('order.index')->middleware('permission:Pending Orders');
        Route::get('/orders/{id}/status',  'getStatus')->name('orders.status')->middleware('permission:Pending Orders');
        Route::post('/orders/{id}/update-status',  'updateStatus')->name('orders.update-status')->middleware('permission:Pending Orders');
        Route::get('/order/delete/{id}',  'deleteOrder')->name('order.delete')->middleware('permission:Pending Orders');
        Route::get('/order/counter',  'getOrderCount')->name('orders.count')->middleware('permission:Pending Orders');
        Route::get('/order/details/{id}',  'getOrderDetails')->name('order.details')->middleware('permission:Pending Orders');
        ### InVoice ####
        Route::get('/order/invoice/{id}',  'getInVoiceDetails')->name('invoice.index')->middleware('permission:Pending Orders');
    });
});
