<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // 每分鐘自動更新用戶分數
    $schedule->command('auto:update-scores')->everyMinute()->withoutOverlapping(60);

    $schedule->command('scores:update-and-sync')->hourly();

    // 新增的排程命令：模擬搶紅包的命令
    $schedule->command('simulate:grab-red-envelope')->everyMinute(); // 每分鐘執行一次搶紅包

    // 每小時將紅包資料存回資料庫
    $schedule->command('simulate:save-red-envelope-data')->hourly(); // 每小時執行一次儲存資料

    }

    /**
     * 註冊命令以供 Artisan 使用
     */
    protected function commands()
    {
        // 自動載入 App\Console\Commands 資料夾內的所有命令類別
        $this->load(__DIR__.'/Commands');

        // 載入 routes/console.php 中定義的 Artisan 命令
        require base_path('routes/console.php');
    }
}
