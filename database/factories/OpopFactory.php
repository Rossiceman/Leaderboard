<?php

namespace Database\Factories;

use App\Models\Opop;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpopFactory extends Factory
{
    protected $model = Opop::class;
    public function definition()
    {
        return [
            'neme' => $this->faker->neme(),
            'content' => $this->faker->content(),
            'phone' =>$this->faker->phone(),
        ];
    }
}
