<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \App\Models\Warehouse;
use  \App\Models\Product;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'=>$this->faker->randomElement(Product::pluck('id')->toArray()),
            'wh_id'=>$this->faker->randomElement(Warehouse::pluck('id')->toArray()),
            'quantity'=>$this->faker->randomNumber(2, false),
            
            //
        ];
    }
}
