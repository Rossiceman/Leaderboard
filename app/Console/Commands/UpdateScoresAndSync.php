<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UpdateScoresAndSync extends Command
{
    // Artisan 命令名稱
    protected $signature = 'scores:update-and-sync';

    // Artisan 命令描述
    protected $description = '自動更新 Redis 用戶分數並同步到資料庫';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('開始同步 Redis 用戶分數到資料庫...');

        // 1. 從 Redis 的 ZSET 中獲取所有用戶 ID 和分數
        $users = Redis::zrevrange('users_scores', 0, -1, 'WITHSCORES');

        // 2. 將分數同步到資料庫
        $this->info('開始將分數同步到資料庫...');
        foreach ($users as $userId => $score) {
            // 獲取更新後的分數
            $updatedScore = Redis::hget("user:{$userId}", 'score');

            // 同步到資料庫
            DB::table('users')->where('id', $userId)->update(['score' => $updatedScore]);
        }

        $this->info('分數同步到資料庫完成！');
    }
}
