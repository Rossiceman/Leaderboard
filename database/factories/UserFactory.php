<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
        'name' => $this->faker->name, // 隨機姓名
        'email' => $this->faker->unique()->safeEmail, // 隨機電子郵件
        'password' => $this->faker->password, // 預設密碼
        'score' => $this->faker->numberBetween(0, 100000), // 隨機分數 (0~1000)

        ];
    }
}
