<?php

use App\Http\Controllers\Api\ApiSekolahController;
use App\Http\Controllers\Api\ApiPanoramaController;
use App\Http\Controllers\Api\ApiUsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SekolahController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('cctvsekolah', ApiSekolahController::class);

Route::apiResource('cctvpanorama', ApiPanoramaController::class);

Route::apiResource('users', ApiUsersController::class);

Route::post('/cctvsekolah/{id}/toggle', [SekolahController::class, 'toggle']);

Route::post('/cctvsekolah/bulk-toggle', [SekolahController::class, 'bulkToggle']);

