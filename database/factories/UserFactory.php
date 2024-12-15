<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
 
 
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'username' => $this->faker->word,
            'phone'=>fake()->phoneNumber(),
            'address'=>fake()->address(),
            'description'=>$this->faker->sentences(5,true),
            'photo'=>fake()->imageUrl(60,60),
            'ugroup_id'=>1,
            'role'=>fake()->randomElement([ 'manager','vendor','customer','supplier','supcustomer','delivery']),
            'status'=>fake()->randomElement(['active','inactive']),
            'remember_token' => Str::random(10),
        ];
    }
    
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
