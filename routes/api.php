<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransferController;
use App\Http\Middleware\HasSetPinMiddleware;
use App\Http\Controllers\OnBoardingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountDepositController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AccountWithdrawalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthenticationController::class, 'register']);
    Route::post('login', [AuthenticationController::class, 'login']);

    Route::middleware("auth:sanctum")->group(function () {
        Route::get('user', [AuthenticationController::class, 'user']);
        Route::get('logout', [AuthenticationController::class, 'logout']);
    });
});

Route::middleware("auth:sanctum")->group(function () {
    Route::post('setup/pin', [PinController::class, 'setupPin']);
    Route::middleware([HasSetPinMiddleware::class])->group(function () {
        Route::post('validatepin', [PinController::class, 'validatePin']);
        Route::post('generate/account-number', [AccountController::class, 'store']);
        
        Route::prefix('account')->group(function () {
            Route::post('deposit', [AccountDepositController::class, 'store']);
            Route::post('withdraw', [AccountWithdrawalController::class, 'store']);
            Route::post('transfer', [TransferController::class, 'store']);
        });

        Route::prefix('transaction')->group(function () {
            Route::get('history', [TransactionController::class, 'index']);
        });
    });
});

// Route::post('deposit', [AccountDepositController::class, 'store']);
