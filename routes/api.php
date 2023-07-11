<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoalController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// register
Route::post('/register', [AuthController::class, 'register']);

// get
Route::get('/soal', [SoalController::class, 'index']);

// post
Route::post('/pertanyaan', [SoalController::class, 'store']);

// get with id
Route::get('/pertanyaan/{id}', [SoalController::class, 'show']);

// update with post
Route::post('/pertanyaan/{id}', [SoalController::class, 'update']);

// delete
Route::delete('/pertanyaan/{id}', [SoalController::class, 'destroy']);