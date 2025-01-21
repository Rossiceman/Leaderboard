<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * 執行資料庫填充。
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
    }
}
