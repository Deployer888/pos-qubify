<?php

namespace Database\Factories;

use App\Models\DepotCustomer;
use App\Models\Depot;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotCustomerFactory extends Factory
{
    protected $model = DepotCustomer::class;

    public function definition(): array
    {
        return [
            'family_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'adhaar_no' => $this->faker->numerify('############'),
            'ration_card_no' => $this->faker->numerify('##########'),
            'card_range' => $this->faker->randomElement(['APL', 'BPL', 'AAY']),
            'name' => $this->faker->name(),
            'mobile' => $this->faker->phoneNumber(),
            'age' => $this->faker->numberBetween(18, 80),
            'is_family_head' => $this->faker->boolean(30), // 30% chance of being family head
            'address' => $this->faker->address(),
            'depot_id' => Depot::factory(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function familyHead(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_family_head' => true,
        ]);
    }
}