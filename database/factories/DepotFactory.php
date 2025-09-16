<?php

namespace Database\Factories;

use App\Models\Depot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotFactory extends Factory
{
    protected $model = Depot::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'depot_type' => $this->faker->randomElement(['Standard Depot', 'Premium Depot', 'Mini Depot']),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}