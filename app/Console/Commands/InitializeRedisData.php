<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class InitializeRedisData extends Command
{
    protected $signature = 'initialize:redis-data';

    protected $description = 'Migrate MySQL data to Redis as Hash + ZSET';

    public function handle()
    {
        $this->info('開始遷移 MySQL 資料到 Redis...');

        DB::table('users')->orderBy('id')->chunk(1000, function ($users) {
            foreach ($users as $user) {
                // 1. Hash 結構存儲用戶詳細資料
                $hashKey = "user:{$user->id}";
                Redis::hset($hashKey, 'id', $user->id);
                Redis::hset($hashKey, 'name', $user->name);
                Redis::hset($hashKey, 'email', $user->email);
                Redis::hset($hashKey, 'score', $user->score);
                // 2. ZSET 結構存儲分數排名
                Redis::zadd('users_scores', $user->score, $user->id);
            }
            $this->info("已遷移 1000 筆資料...");
        });

        $this->info('遷移完成！');
    }
}
