<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExportUserIds extends Command
{
    protected $signature = 'export:user-ids';
    protected $description = '匯出用戶 ID 到檔案';

    public function handle()
    {
        $this->info("開始匯出用戶 ID...");
        $userIds = DB::table('users')->pluck('id');

        // 將用戶 ID 儲存到檔案
        Storage::put('user_ids.txt', implode("\n", $userIds->toArray()));
        $this->info("用戶 ID 已匯出至 user_ids.txt");
    }
}
