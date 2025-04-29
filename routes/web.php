<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\RequestDetailController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::post('/logout', [AuthController::class, 'handleLogout'])->name('logout.handle');

Route::middleware('auth')->group(function () {
    Route::get('/requests', [RequestController::class, 'showUserRequests'])->name('requests.show');
    Route::get('/requests/create', [RequestController::class, 'showCreate'])->name('requests.create.show');
    Route::post('/requests/create', [RequestController::class, 'handleCreate'])->name('requests.create.handle');
    Route::get('/requests/detail-input/{request}', [RequestDetailController::class, 'create'])->name('request-details.create.show');
    Route::post('/requests/detail-input/{request}', [RequestDetailController::class, 'store'])->name('request-details.create.handle');

    Route::put('/approval/requester-review/{request}/approve', [ApprovalController::class, 'handleRequesterReviewApprove'])->name('approval.requester-review-approve.handle');
    Route::put('/approval/requester-review/{request}/reject', [ApprovalController::class, 'handleRequesterReviewReject'])->name('approval.requester-review-reject.handle');
});

Route::middleware('auth')->group(function () {
    Route::middleware('itm')->group(function () {
        Route::get('/approval/itm-approval', [ApprovalController::class, 'showITMApproval'])->name('approval.itm-approval.show');
        Route::put('/approval/itm-initial-review/{id}/approve', [ApprovalController::class, 'handleITMInitialReviewApprove'])->name('approval.itm-initial-review-approve.handle');
        Route::put('/approval/itm-initial-review/{id}/reject', [ApprovalController::class, 'handleITMInitialReviewReject'])->name('approval.itm-initial-review-reject.handle');
        Route::put('/approval/itm-final-review/{id}/approve', [ApprovalController::class, 'handleITMFinalReviewApprove'])->name('approval.itm-final-review-approve.handle');
        Route::put('/approval/itm-final-review/{id}/reject', [ApprovalController::class, 'handleITMFinalReviewReject'])->name('approval.itm-final-review-reject.handle');
    });
});
