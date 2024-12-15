<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use  \App\Models\Brand;
use  \App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'title'=>$this->faker->word,
            'slug'=>$this->faker->unique()->slug,
            'summary'=>$this->faker->sentences(3,true),
            'description'=>$this->faker->sentences(5,true),
            'stock'=>0,
            'brand_id'=>$this->faker->randomElement(Brand::pluck('id')->toArray()),
            'cat_id'=>$this->faker->randomElement(Category::where('is_parent',0)->pluck('id')->toArray()),
            'parent_cat_id'=>$this->faker->randomElement(Category::where('is_parent',1)->pluck('id')->toArray()),
            'photo'=>$this->faker->imageUrl('100','100').','.$this->faker->imageUrl('400','200').','.$this->faker->imageUrl('100','100'),
            'size'=>$this->faker->word,
            'weight'=>$this->faker->randomFloat(2,0),
            'expired'=>$this->faker->randomNumber(2, false),
            'status'=>$this->faker->randomElement(['active','inactive']),
        ];
    }
}
 