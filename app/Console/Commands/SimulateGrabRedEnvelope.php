<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SimulateGrabRedEnvelope extends Command
{
    protected $signature = 'simulate:grab-red-envelope';
    protected $description = '模擬 20 萬用戶搶紅包';

    public function handle()
    {
        $totalUsers = 200000;
        $client = new Client();
        $apiUrl = config('app.url') . '/api/grab-red-envelope';

        $this->info("開始模擬 $totalUsers 用戶搶紅包...");

        for ($i = 1; $i <= $totalUsers; $i++) {
            $response = $client->post($apiUrl, [
                'form_params' => ['user_id' => $i],
            ]);
            $this->info("用戶 $i: " . $response->getBody());
        }

        $this->info("模擬完成！");
    }
}
