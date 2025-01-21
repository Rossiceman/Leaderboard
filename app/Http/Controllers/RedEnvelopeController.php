<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class RedEnvelopeController extends Controller
{
    public function grab(Request $request)
    {
        $userId = $request->input('user_id');

        // Redis NX 鎖，防止重複搶紅包
        $lockKey = "user_grab_lock:{$userId}";
        if (!Redis::set($lockKey, 1, 'NX', 'EX', 5)) {
            return response()->json(['message' => '請勿重複搶紅包'], 429);
        }

        // 從紅包池中取一個紅包金額
        $amount = Redis::lpop('red_envelope_pool');
        if (!$amount) {
            return response()->json(['message' => '紅包已搶完'], 404);
        }

        // 更新 Redis 用戶紅包總金額
        Redis::hincrby('red_envelope_users', $userId, $amount);

        // 更新資料庫
        DB::transaction(function () use ($userId, $amount) {
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'red_envelope_amount' => DB::raw("red_envelope_amount + {$amount}"),
                    'grab_count' => DB::raw("grab_count + 1"),
                    'last_grab_timestamp' => now(),
                ]);

            DB::table('red_envelope_history')->insert([
                'user_id' => $userId,
                'amount' => $amount,
                'attempt_count' => 1,
                'grab_timestamp' => now(),
            ]);
        });

        return response()->json(['message' => '搶紅包成功', 'amount' => $amount]);
    }
}
