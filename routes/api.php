<?php

use App\Http\Controllers\API\TrnazakController;
use App\Http\Controllers\ApiContoller;
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

Route::post('tranzak/web_redirect/return_callback', [TrnazakController::class, 'web_redirect_payment_callback'])->name('tranzak.return_url');


