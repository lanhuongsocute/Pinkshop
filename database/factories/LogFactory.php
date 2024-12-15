<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use  \App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'content'=>$this->faker->word,
            'user_id'=>$this->faker->randomElement(User::pluck('id')->toArray()),
        ];
    }
}
