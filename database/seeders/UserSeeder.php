<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
   
    public function run()
    {
        $batchSize = 10000; // 減少每批次生成的資料數量
$totalBatches = 20;

for ($i = 1; $i <= $totalBatches; $i++) {
    User::factory()->count($batchSize)->create();
    $this->command->info("第 {$i} 批次資料已生成，共 {$batchSize} 筆！");
}

$this->command->info("所有資料生成完成！");
    }

    }

