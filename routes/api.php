<?php

use App\Http\Controllers\CreateSub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/create/sub',[CreateSub::class,'createsub']);
Route::post('/create/sub/test',[CreateSub::class,'createsubtest']);
Route::get('/streamers/getAll',[\App\Http\Controllers\StreamerController::class,'getAll']);
Route::post('/streamers/killAll',[\App\Http\Controllers\StreamerController::class,'killAll']);
Route::post('/streamers/changeStatus',[\App\Http\Controllers\StreamerController::class,'changeStatus']);
Route::post('/streamers/changeOnline',[\App\Http\Controllers\StreamerController::class,'changeStatus']);
