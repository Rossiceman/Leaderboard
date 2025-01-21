<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class SetupRedEnvelopeData extends Command
{
    protected $signature = 'php';
    protected $description = '初始化紅包資料到 Redis';

    public function handle()
    {
        $this->info("清空舊的 Redis 資料...");
        Redis::del('red_envelope_pool'); // 清空舊的紅包池
        Redis::del('red_envelope_users'); // 清空用戶資料

        $this->info("生成紅包資料...");
        $totalAmount = 10000;
        $minAmount = 10;
        $maxAmount = 50;
        $numEnvelopes = 1000;

        $amounts = [];
        for ($i = 0; $i < $numEnvelopes; $i++) {
            $amount = rand($minAmount, $maxAmount);
            $totalAmount -= $amount;
            $amounts[] = $amount;
        }

        foreach ($amounts as $amount) {
            Redis::rpush('red_envelope_pool', $amount);
        }
        $this->info("紅包資料已儲存到 Redis！");

        $this->info("匯入用戶資料...");
        $userIds = DB::table('users')->pluck('id');
        foreach ($userIds as $userId) {
            Redis::hset('red_envelope_users', $userId, 0); // 初始化每個用戶的紅包金額
        }
        $this->info("用戶資料已初始化！");
    }
}
