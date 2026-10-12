<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Supplier> */
class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_name'  => fake()->unique()->company(),
            'contact_person' => fake()->firstName(),
            'email'          => fake()->unique()->safeEmail(),
            'phone'          => fake()->numerify('0812########'),
            'address'        => fake()->city(),
            'is_active'      => true,
        ];
    }

    public function archived(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
