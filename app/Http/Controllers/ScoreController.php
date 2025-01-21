<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ScoreController extends Controller
{
    // 更新分數

    public function updateScore(Request $request)
    {
        $userId = $request->input('user_id');
        $additionalScore = rand(0,100);

        // 取得原先分數並加上新的分數
        $currenScore = Redis::hget('laravel_database_leaderboard');
        $newScore = $currenScore + $additionalScore;

        // 更新redis使用者的分數
        Redis::hset('laravel_database_leaderboard', $userId, $newScore);

        // 回傳更新後的分數
        return response()->json(['userId' => $userId, 'new_score' => $newScore]);

    }
}
