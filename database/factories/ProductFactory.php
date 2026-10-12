<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id'   => Category::factory(),
            'sku'           => 'INV-'.strtoupper(fake()->unique()->bothify('???-###')),
            'name'          => fake()->randomElement(['Wireless', 'USB-C', 'Bluetooth', 'Portable', 'Ergonomic', 'Gaming', 'Premium', 'Compact'])
                               .' '.fake()->randomElement(['Webcam', 'Headset', 'Speaker', 'Laptop Stand', 'Desk Lamp', 'Power Bank', 'Monitor Arm', 'Notebook', 'Flash Drive', 'Cable Organizer']),
            'description'   => fake()->sentence(),
            'price'         => fake()->numberBetween(10, 500) * 1000,
            'stock'         => fake()->numberBetween(1, 60),
            'minimum_stock' => 5,
        ];
    }
}