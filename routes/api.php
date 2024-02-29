<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\MakananController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\ResetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
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

Route::delete('delete/{id}', [LaporanController::class, 'delete']);

Route::post('detail-laporan/{id}', [LaporanController::class, 'detail']);
Route::post('hasil-laporan/{id}', [LaporanController::class, 'hasilDetailLaporan']);

Route::post('makanan', [MakananController::class, 'index']);

Route::get('/news', [NewsApiController::class, 'getNews']);

Route::post('/forgot-password', [ResetPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
