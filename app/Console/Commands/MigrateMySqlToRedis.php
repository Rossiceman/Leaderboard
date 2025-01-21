<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MigrateMySQLToRedis extends Command
{
    // 指令名稱
    protected $signature = 'migrate:mysql-to-redis';

    // 指令描述
    protected $description = 'Migrate MySQL data to Redis';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('開始將 MySQL 資料遷移到 Redis...');

        // 1. 清空 Redis 中的舊資料
        Redis::del('users_data');
        $this->info('Redis 中的舊資料已清空。');

        // 2. 分批提取 MySQL 資料
        DB::table('users') // 確保資料表名稱正確

            ->orderBy('id') // 確保按順序提取
            ->chunk(1000, function ($users) {
                
                foreach ($users as $user) {
                    // 3. 將每筆資料以 JSON 格式存入 Redis
                    Redis::rpush('users_data', json_encode($user));
                }
                $this->info('完成 1000 筆資料遷移。');
            });

        // 4. 驗證 Redis 資料筆數
        $count = Redis::llen('users_data');
        $this->info("遷移完成，共 {$count} 筆資料存入 Redis。");
    }
}
