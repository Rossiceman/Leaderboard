<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

class SaveRedEnvelopeData extends Command
{
    protected $signature = 'simulate:save-red-envelope-data';
    protected $description = 'Save red envelope data back to the database every hour';

    public function handle()
    {
        // 可以在這裡將紅包資料儲存到資料庫
        // 比如說將已經搶過紅包的用戶金額儲存到資料庫

        // 假設你有一個記錄紅包數據的資料表，更新這些數據
        $users = User::all();

        foreach ($users as $user) {
            // 假設你有儲存用戶紅包金額的欄位
            $user->red_envelope_amount = Redis::hget('red_envelope_hash', $user->id); // 取得redis中的金額
            $user->last_grab_timestamp = now(); // 更新最後搶紅包的時間
            $user->save();
        }

        $this->info('紅包資料已成功儲存回資料庫');
    }
}
