<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/current_user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('books')->group(function () {
        Route::get('/get/{book}', [BookController::class, 'get']);
        Route::get('/get_dropdown_list', [BookController::class, 'getDropdownList']);
        Route::post('/get_list', [BookController::class, 'getList']);
        Route::post('/create', [BookController::class, 'store']);
        Route::put('/update/{book}', [BookController::class, 'update']);
        Route::delete('/destroy/{book}', [BookController::class, 'destroy']);
    });

    Route::prefix('borrowings')->group(function () {
        Route::get('/get/{borrowing}', [BorrowingController::class, 'get']);
        Route::post('/get_list', [BorrowingController::class, 'getDTList']);
        Route::post('/create', [BorrowingController::class, 'store']);
        Route::put('/update/{borrowing}', [BorrowingController::class, 'update']);
        Route::delete('/destroy/{borrowing}', [BorrowingController::class, 'destroy']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/get/{category}', [CategoryController::class, 'get']);
        Route::post('/get_list', [CategoryController::class, 'getDTList']);
        Route::get('/get_dropdown_list', [CategoryController::class, 'getDropdownList']);
        Route::post('/create', [CategoryController::class, 'store']);
        Route::put('/update/{category}', [CategoryController::class, 'update']);
        Route::delete('/destroy/{category}', [CategoryController::class, 'destroy']);
    });

    Route::prefix('fines')->group(function () {
        Route::get('/get/{fine}', [FineController::class, 'get']);
        Route::get('/get_all', [FineController::class, 'getAll']);
        Route::post('/create', [FineController::class, 'store']);
        Route::put('/update/{fine}', [FineController::class, 'update']);
        Route::delete('/destroy/{fine}', [FineController::class, 'destroy']);
    });

    Route::prefix('reservations')->group(function () {
        Route::get('/get/{reservation}', [ReservationController::class, 'get']);
        Route::post('/get_list', [ReservationController::class, 'getDTList']);
        Route::post('/create', [ReservationController::class, 'store']);
        Route::put('/update/{reservation}', [ReservationController::class, 'update']);
        Route::delete('/destroy/{reservation}', [ReservationController::class, 'destroy']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/get/{user}', [UsersController::class, 'get']);
        Route::post('/get_list', [UsersController::class, 'getDTList']);
        Route::get('/get_dropdown_list', [UsersController::class, 'getDropdownList']);
        Route::post('/create', [UsersController::class, 'store']);
        Route::put('/update/{user}', [UsersController::class, 'update']);
        Route::delete('/destroy/{user}', [UsersController::class, 'destroy']);
    });
});
