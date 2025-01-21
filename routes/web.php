<?php

use App\Http\Controllers\CheckAdminController;

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\RedEnvelopeController;
use App\Http\Controllers\RedisTestController;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", [LeaderboardController::class, 'getTopUsers']);


Route::get('/check-redis', function () {
    $data = Redis::lrange('users_data', 0, 9); // 取出前 10 筆資料
    return response()->json([
        'count' => Redis::llen('users_data'), // 總筆數
        'sample' => array_map('json_decode', $data), // 解析 JSON
    ]);
});

Route::get('/test-redis', [RedisTestController::class, 'test']);
Route::get('/leaderboard', [LeaderboardController::class, 'getTopUsers'])->name('leaderboard');





Route::get('/red-envelope', function () {
    return view('red_envelope.index');
});


Route::post('/grab-red-envelope', [RedEnvelopeController::class, 'grab']);

