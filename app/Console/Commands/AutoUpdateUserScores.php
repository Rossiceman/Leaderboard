<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class AutoUpdateUserScores extends Command
{
    protected $signature = 'auto:update-scores';
    protected $description = '自動隨機增加 Redis 中所有用戶的分數';

    public function handle()
    {
        $this->info('開始自動更新用戶分數...');

            // 獲取所有用戶 ID（從 ZSET 中獲取）
        $userIds = Redis::zrange('users_scores', 0, -1); // 獲取所有用戶的 ID
        foreach ($userIds as $userId) {
            $hashKey = "user:{$userId}";

            // 獲取當前分數
            $currentScore = Redis::hget($hashKey, 'score');
            if ($currentScore === null) {
                $this->error("用戶 {$userId} 的分數不存在於 Hash 中，跳過...");
                continue;
            }

            // 隨機增加 10~50 分
            $scoreIncrease = rand(10, 50);
            $newScore = $currentScore + $scoreIncrease;

            // 更新 Hash 和 ZSET
            Redis::hset($hashKey, 'score', $newScore);

            // 添加時間偏移，避免同分
            $offset = (microtime(true) - floor(microtime(true))) * 0.000001;
            $rankingScore = $newScore + $offset;

            Redis::zadd('users_scores', $rankingScore, $userId);

            $this->info("用戶 {$userId} 分數增加 {$scoreIncrease}，新分數：{$newScore}");
        }

        $this->info('分數更新完成！');
    }
}
