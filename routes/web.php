<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PtppRequestController;
use App\Http\Controllers\PtppApprovalController;
use App\Http\Controllers\PtppRequestDetailController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::post('/logout', [AuthController::class, 'handleLogout'])->name('logout.handle');

Route::middleware('auth')->group(function () {
    Route::get('/requests', [PtppRequestController::class, 'showUserRequests'])->name('ptpp-requests.show');
    Route::get('/requests/create', [PtppRequestController::class, 'showCreate'])->name('ptpp-requests.create.show');
    Route::post('/requests/create', [PtppRequestController::class, 'handleCreate'])->name('ptpp-requests.create.handle');
    Route::get('/requests/detail/{id}', [PtppRequestController::class, 'showDetail'])->name('ptpp-requests.detail.show');
    Route::get('/requests/print/{request}', [PtppRequestController::class, 'printRequest'])->name('ptpp-requests.print');
    Route::get('/requests/detail-input/{request}', [PtppRequestDetailController::class, 'showCreate'])->name('ptpp-request-details.create.show');
    Route::post('/requests/detail-input/{request}', [PtppRequestDetailController::class, 'handleCreate'])->name('ptpp-request-details.create.handle');
    Route::get('/requests/report', [PtppRequestController::class, 'showReport'])->name('ptpp-requests.report.show');
    Route::get('/requests/report/export', [PtppRequestController::class, 'exportReport'])->name('ptpp-requests.report.export');
    Route::put('/approval/requester-review/{request}/approve', [PtppApprovalController::class, 'handleRequesterReviewApprove'])->name('ptpp-approval.requester-review-approve.handle');
    Route::put('/approval/requester-review/{request}/reject', [PtppApprovalController::class, 'handleRequesterReviewReject'])->name('ptpp-approval.requester-review-reject.handle');
});

Route::middleware('auth')->group(function () {
    Route::middleware('itm')->group(function () {
        Route::get('/approval/itm-approval', [PtppApprovalController::class, 'showITMPtppApproval'])->name('ptpp-approval.itm-approval.show');
        Route::put('/approval/itm-initial-review/{id}/approve', [PtppApprovalController::class, 'handleITMInitialReviewApprove'])->name('ptpp-approval.itm-initial-review-approve.handle');
        Route::put('/approval/itm-initial-review/{id}/reject', [PtppApprovalController::class, 'handleITMInitialReviewReject'])->name('ptpp-approval.itm-initial-review-reject.handle');
        Route::put('/approval/itm-final-review/{id}/approve', [PtppApprovalController::class, 'handleITMFinalReviewApprove'])->name('ptpp-approval.itm-final-review-approve.handle');
        Route::put('/approval/itm-final-review/{id}/reject', [PtppApprovalController::class, 'handleITMFinalReviewReject'])->name('ptpp-approval.itm-final-review-reject.handle');
    });
});
