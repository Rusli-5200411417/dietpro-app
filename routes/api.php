<?php

use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController as ApiForgotPasswordController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\MakananController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::put('update-user/{id}', [AuthController::class, 'update']);
Route::post('upload-user/{id}', [AuthController::class, 'upload']);

Route::post('laporan/{id}', [LaporanController::class, 'index']);
Route::post('minggu/{id}', [LaporanController::class, 'dataSatuMinggu']);
Route::post('bulan/{id}', [LaporanController::class, 'dataSatuBulan']);
Route::post('store/{id}', [LaporanController::class, 'store']);

Route::post('makanan', [MakananController::class,'index'],);
// Route::get('/daily-users', [ApiUserController::class, 'dailyUsers'])->name('daily-users');
Route::get('/news', [NewsApiController::class, 'getNews']);
Route::post('/forgot-password', [ResetPasswordController::class,'sendResetLinkEmail'])->name('password.email');
// Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
// Route::post('/reset-password', [ResetPasswordController::class, 'reset']);
// Route::get('/forgot-password', [ResetPasswordController::class, 'showForgotPasswordForm'])
//     ->middleware('guest') ->name('password.request');

// Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])
//     ->middleware('guest') ->name('password.email');

// Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])
//     ->middleware('guest') ->name('password.reset');

// Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
//     ->middleware('guest') ->name('password.update');