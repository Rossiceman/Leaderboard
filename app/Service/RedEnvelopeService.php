<?php
namespace App\Services;

use App\Models\RedEnvelopeHistory;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class RedEnvelopeService
{
    private $redisKey = 'red_envelope_pool'; // Redis 中的紅包池名稱
    private $hashKey = 'user_red_envelope';  // 用來存儲用戶紅包金額的 Redis hash

    // 初始化紅包池
    public function initializeRedEnvelopePool($totalAmount, $totalPackets)
    {
        // 檢查是否已經初始化紅包池
        if (Redis::hlen($this->redisKey) === 0) {
            $amounts = $this->generateRandomAmounts($totalAmount, $totalPackets);
            foreach ($amounts as $index => $amount) {
                Redis::hset($this->redisKey, $index, $amount);
            }
        }
    }

    // 用戶搶紅包
    public function grabRedEnvelope($userId)
    {
        // 從 Redis 獲取紅包池中一份紅包
        $amount = Redis::hget($this->redisKey, 0); // 獲取紅包池的第一個紅包金額

        if (!$amount) {
            return ['message' => '紅包已經搶完！', 'success' => false];
        }

        // 這邊從 Redis 中刪除該紅包
        Redis::hdel($this->redisKey, 0);

        // 記錄搶紅包歷史
        RedEnvelopeHistory::create([
            'user_id' => $userId,
            'amount' => $amount,
            'grab_timestamp' => now(),
        ]);

        // 更新用戶的紅包金額和搶紅包次數
        $user = User::find($userId);
        $user->increment('red_envelope_amount', $amount);
        $user->increment('grab_count');
        $user->update(['last_grab_timestamp' => now()]);

        return ['message' => '成功搶到紅包！', 'amount' => $amount, 'success' => true];
    }

    // 隨機生成紅包金額
    private function generateRandomAmounts($totalAmount, $totalPackets)
    {
        $amounts = [];
        $remainingAmount = $totalAmount;
        $remainingPackets = $totalPackets;

        for ($i = 0; $i < $totalPackets - 1; $i++) {
            $max = $remainingAmount / $remainingPackets * 2;
            $amount = round(mt_rand(1, $max * 100) / 100, 2);
            $amounts[] = $amount;
            $remainingAmount -= $amount;
            $remainingPackets--;
        }

        $amounts[] = round($remainingAmount, 2);
        shuffle($amounts);

        return $amounts;
    }
}
