<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LeaderboardController extends Controller
{
    public function getTopUsers(Request $request)
{
    $page = max(1, (int)$request->get('page', 1)); // 當前頁碼，默認第 1 頁
    $perPage = 25; // 每頁顯示的用戶數
    $start = ($page - 1) * $perPage;
    $end = $start + $perPage - 1;

    $result = [];
    
    // 查詢前200名玩家，最多只顯示200個玩家
    $users = Redis::zRevRange('users_scores', 0, 199, true); // 取得前200名玩家
    
    // 只取當前頁的玩家
    $usersForPage = array_slice($users, $start, $perPage, true);

    foreach ($usersForPage as $userId => $score) {
        $userDetails = Redis::hgetall("user:{$userId}");
        $globalRank = Redis::zRevRank('users_scores', $userId) + 1;

        $result[] = [
            'rank' => $globalRank,
            'id' => $userId,
            'name' => $userDetails['name'] ?? 'Unknown',
            'score' => (int)$score,
        ];
    }

    // 計算是否有下一頁
    $hasNextPage = ($end + 1) < count($users);

    return view('leaderboard', [
        'users' => $result,
        'page' => $page,
        'perPage' => $perPage,
        'totalUsers' => 200, // 顯示前200名
        'hasNextPage' => $hasNextPage,
    ]);
}

}
