<?php

use App\Http\Controllers\Api\DaftarMenuController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/login',LoginController::class)->name('login');
Route::middleware('auth:api')->get('/user',function(Request $request){
    return $request->user();
});
Route::post('/logout', LogoutController::class)->name('logout');

Route::group(['prefix' => 'daftarMenu', 'middleware' => 'authorize:ADM'], function () {
    Route::get('/', [DaftarMenuController::class, 'index']);
    Route::get('/{id}/subMenu', [DaftarMenuController::class, 'subMenu']);
    Route::get('/content/{pageId}', [DaftarMenuController::class, 'content']);
});
