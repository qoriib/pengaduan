<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MwtReportController;
use App\Http\Controllers\PtppRequestController;
use App\Http\Controllers\PtppApprovalController;
use App\Http\Controllers\PtppRequestDetailController;
use App\Http\Controllers\UserController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'handleLogin'])->name('login.handle');
Route::post('/logout', [AuthController::class, 'handleLogout'])->name('logout.handle');

Route::middleware('auth')->group(function () {
    Route::get('/ptpp-requests', [PtppRequestController::class, 'showUserRequests'])->name('ptpp-requests.show');
    Route::get('/ptpp-requests/create', [PtppRequestController::class, 'showCreate'])->name('ptpp-requests.create.show');
    Route::post('/ptpp-requests/create', [PtppRequestController::class, 'handleCreate'])->name('ptpp-requests.create.handle');
    Route::get('/ptpp-requests/detail/{id}', [PtppRequestController::class, 'showDetail'])->name('ptpp-requests.detail.show');
    Route::get('/ptpp-requests/print/{request}', [PtppRequestController::class, 'printRequest'])->name('ptpp-requests.print');
    Route::get('/ptpp-requests/detail-input/{request}', [PtppRequestDetailController::class, 'showCreate'])->name('ptpp-request-details.create.show');
    Route::post('/ptpp-requests/detail-input/{request}', [PtppRequestDetailController::class, 'handleCreate'])->name('ptpp-request-details.create.handle');
    Route::put('/ptpp-approval/requester-review/{request}/approve', [PtppApprovalController::class, 'handleRequesterReviewApprove'])->name('ptpp-approval.requester-review-approve.handle');
    Route::put('/ptpp-approval/requester-review/{request}/reject', [PtppApprovalController::class, 'handleRequesterReviewReject'])->name('ptpp-approval.requester-review-reject.handle');

    Route::get('/mwt-requests', [MwtReportController::class, 'showRequests'])->name('mwt-requests.show');
    Route::get('/mwt-requests/detail/{id}', [MwtReportController::class, 'showDetail'])->name('mwt-requests.detail.show');
    Route::get('/mwt-reports/print/{report}', [MwtReportController::class, 'printRequest'])->name('mwt-requests.print');
    Route::get('/mwt-requests/create', [MwtReportController::class, 'showCreate'])->name('mwt-requests.create.show');
    Route::post('/mwt-requests/create', [MwtReportController::class, 'handleCreate'])->name('mwt-requests.create.handle');
});

Route::middleware('auth')->group(function () {
    Route::middleware('itm')->group(function () {
        Route::get('/ptpp-approval/itm-approval', [PtppApprovalController::class, 'showITMPtppApproval'])->name('ptpp-approval.itm-approval.show');
        Route::put('/ptpp-approval/itm-initial-review/{id}/approve', [PtppApprovalController::class, 'handleITMInitialReviewApprove'])->name('ptpp-approval.itm-initial-review-approve.handle');
        Route::put('/ptpp-approval/itm-initial-review/{id}/reject', [PtppApprovalController::class, 'handleITMInitialReviewReject'])->name('ptpp-approval.itm-initial-review-reject.handle');
        Route::put('/ptpp-approval/itm-final-review/{id}/approve', [PtppApprovalController::class, 'handleITMFinalReviewApprove'])->name('ptpp-approval.itm-final-review-approve.handle');
        Route::put('/ptpp-approval/itm-final-review/{id}/reject', [PtppApprovalController::class, 'handleITMFinalReviewReject'])->name('ptpp-approval.itm-final-review-reject.handle');
        Route::get('/ptpp-requests/itm-report', [PtppRequestController::class, 'showReport'])->name('ptpp-requests.report.show');
        Route::get('/ptpp-requests/itm-report/export', [PtppRequestController::class, 'exportReport'])->name('ptpp-requests.report.export');
        Route::put('/mwt-approval/approve/{id}', [MwtReportController::class, 'handleApprove'])->name('mwt-requests.approve.handle');
        Route::get('/mwt-reports/export', [MwtReportController::class, 'exportReport'])->name('mwt-requests.export');

        Route::get('/users', [UserController::class, 'showUsers'])->name('users.show');
        Route::get('/users/create', [UserController::class, 'showCreate'])->name('users.create.show');
        Route::post('/users/create', [UserController::class, 'handleCreate'])->name('users.create.handle');
        Route::get('/users/edit/{user}', [UserController::class, 'showEdit'])->name('users.edit.show');
        Route::put('/users/edit/{user}', [UserController::class, 'handleEdit'])->name('users.edit.handle');
        Route::delete('/users/delete/{user}', [UserController::class, 'handleDelete'])->name('users.delete.handle');
    });
});
