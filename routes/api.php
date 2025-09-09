<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\VerifierController;
use App\Http\Controllers\API\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::group(
        ['prefix' => 'user', 'middleware' => ['role:user,verifikator,admin']],
        function () {
            Route::post('leaves', [UserController::class, 'createLeave']);
            Route::get('leaves', [UserController::class, 'listLeaves']);
            Route::get('leaves/{id}', [UserController::class, 'showLeave']);
            Route::put('leaves/{id}', [UserController::class, 'updateLeave']);
            Route::post(
                'leaves/{id}/cancel',
                [UserController::class, 'cancelLeave']
            );
            Route::delete('leaves/{id}', [UserController::class, 'deleteLeave']);
            Route::post('password', [UserController::class, 'updatePassword']);
        }
    );

    // verifier
    Route::group(
        ['prefix' => 'verifier', 'middleware' => ['role:verifikator']],
        function () {
            Route::get('users', [VerifierController::class, 'listUsers']);
            Route::post(
                'users/{id}/verify',
                [VerifierController::class, 'verifyUser']
            );
            Route::get(
                'leaves',
                [VerifierController::class, 'listLeaveRequests']
            );
            Route::post(
                'leaves/{id}/act',
                [VerifierController::class, 'actOnLeave']
            );
        }
    );

    // admin
    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        Route::get('users', [AdminController::class, 'allUsers']);
        Route::post('verifier', [AdminController::class, 'registerVerifier']);
        Route::post(
            'users/{id}/promote',
            [AdminController::class, 'promoteToVerifier']
        );
        Route::get('leaves', [AdminController::class, 'viewLeaveRequests']);
        Route::post(
            'users/{id}/reset-password',
            [AdminController::class, 'resetPassword']
        );
    });
});
