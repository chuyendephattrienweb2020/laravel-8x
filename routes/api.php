<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
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

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('refreshtoken', [UserController::class, 'refreshToken']);


Route::get('/unauthorized',  [UserController::class, 'unauthorized']);

Route::group(['middleware' => ['CheckClientCredentials','auth:api']], function() {
    Route::post('logout',  [UserController::class, 'logout']);
    Route::post('details',  [UserController::class, 'details']);
});