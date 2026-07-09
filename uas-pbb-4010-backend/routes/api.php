<?php
//routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MahasiswaApiController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/mahasiswa', [MahasiswaApiController::class, 'index']);
    Route::post('/mahasiswa', [MahasiswaApiController::class, 'store']);
    Route::get('/mahasiswa/{mahasiswa}', [MahasiswaApiController::class, 'show']);
    Route::post('/mahasiswa/{mahasiswa}', [MahasiswaApiController::class, 'update']);
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaApiController::class, 'update']);
    Route::delete('/mahasiswa/{mahasiswa}', [MahasiswaApiController::class, 'destroy']);
});