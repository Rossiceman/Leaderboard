<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    public function test()
    {
        // 將資料寫入 Redis
        Redis::set('test_key', 'Hello from Laravel Redis!');
        
        // 從 Redis 獲取資料
        $value = Redis::get('test_key');

        // 返回結果
        return response()->json(['redis_value' => $value]);
    }
}
