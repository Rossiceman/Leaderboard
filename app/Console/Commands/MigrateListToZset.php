<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class MigrateListToZset extends Command
{
  
    protected $signature = 'migrate:list-to-zset';

    protected $description = 'Migrate data from Redis List to Sorted Set';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('開始將資料從 Redis List 轉換為 Sorted Set...');

        // 1. 從 Redis List 中提取資料（分批）
        $batchSize = 100;  // 每次提取 1000 筆資料
        $start = 0;  // 開始索引

        while (true) {
            $users = Redis::lrange('users_data', $start, $start + $batchSize - 1);
            
            if (empty($users)) {
                break;  // 如果沒有資料，停止處理
            }
            
            // 2. 將每筆資料轉換為 Sorted Set (ZSET)
            foreach ($users as $userJson) {
                $user = json_decode($userJson, true);
                $score = $user['score'];  // 分數
                $name = $user['name'];    // 用戶名稱
                
                // 將資料加入到 Redis Sorted Set 中
                Redis::zadd('leaderboard', $score, $name);
            }
            
            $start += $batchSize;  // 更新開始索引
            $this->info("已處理 $start 筆資料...");
        }

        // 3. 完成後可以查詢 Sorted Set 進行排序
        $topUsers = Redis::zrevrange('leaderboard', 0, 100, 'WITHSCORES');
        dd($topUsers);  // 顯示分數最高的前 100 名
    }
}
