<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BarberScheduleController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;

//Auth
Route::prefix('auth')->group(function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:sanctum')->group(function(){
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

//User Controller
Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function(){
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
        Route::post('users', [UserController::class, 'store']);
    });
    Route::put('users/{id}', [UserController::class, 'update']);
});

//Barber Controller
Route::get('barber', [BarberController::class, 'index']);
Route::get('barber/{id}', [BarberController::class, 'show']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::post('barber', [BarberController::class, 'store']);
    Route::put('barber/{id}', [BarberController::class, 'update']);
    Route::delete('barber/{id}', [BarberController::class, 'destroy']);
});

//Barber Schedule
Route::get('barber/{id}/schedule', [BarberScheduleController::class, 'index']);

Route::prefix('barber/{id}')->group(function(){
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function (){
        Route::post('schedule', [BarberScheduleController::class, 'store']);
        Route::put('schedule/{id}', [BarberScheduleController::class, 'update']);
        Route::delete('schedule/{id}', [BarberScheduleController::class, 'destroy']);
    });
});

//Time Slot
Route::get('barber/{id}/slots', [TimeSlotController::class, 'index']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::post('barber/{id}/slots/generate', [TimeSlotController::class, 'generate']);
    Route::patch('slots/{id}/block', [TimeSlotController::class, 'block']);
    Route::patch('slots/{id}/unblock', [TimeSlotController::class, 'unblock']);
});


//Service
Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{id}', [ServiceController::class, 'show']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::post('services', [ServiceController::class, 'store']);
    Route::put('services/{id}', [ServiceController::class, 'update']);
    Route::delete('services/{id}', [ServiceController::class, 'destroy']);
});

//Booking
Route::middleware(['auth:sanctum', 'role:admin,user'])->group(function(){
    Route::post('bookings', [BookingController::class, 'store']);
    Route::get('bookings/my', [BookingController::class, 'myBookings']);
    Route::get('bookings/{id}', [BookingController::class, 'show']);
    Route::patch('bookings/{id}/cancel', [BookingController::class, 'cancel']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::get('bookings', [BookingController::class, 'index']);
    Route::patch('bookings/{id}/status', [BookingController::class, 'updateStatus']);
});


//Product
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::post('products', [ProductController::class, 'store']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);
    Route::patch('products/{id}/stock', [ProductController::class, 'updateStock']);
});

//Transaction
Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::patch('transactions/{id}/status', [TransactionController::class, 'updateStatus']);
});
Route::middleware(['auth:sanctum', 'role:user'])->group(function(){
    Route::get('transaction/my', [TransactionController::class, 'myTransaction']);
});
Route::middleware(['auth:sanctum', 'role:user,admin'])->group(function(){
    Route::get('transactions/{id}', [TransactionController::class, 'show']);
});



//Report
Route::get('reports/top-rated-barber', [ReportController::class, 'topRatedBarber']);
Route::middleware(['auth:sanctum', 'role:admin'])->group(function(){
    Route::prefix('reports')->group(function (){
        Route::get('summary', [ReportController::class, 'summary']);
        Route::get('top-services', [ReportController::class, 'topService']);
        Route::get('top-products', [ReportController::class, 'topProduct']);
        Route::get('top-barbers', [ReportController::class, 'topBarber']);
        Route::get('revenue', [ReportController::class,'revenue']);
    });
});

