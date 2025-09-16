<?php

namespace Database\Factories;

use App\Models\DepotSale;
use App\Models\Depot;
use App\Models\DepotCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotSaleFactory extends Factory
{
    protected $model = DepotSale::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 10, 1000);
        $tax = $subtotal * 0.18; // 18% tax
        $total = $subtotal + $tax;

        return [
            'depot_id' => Depot::factory(),
            'depot_customer_id' => DepotCustomer::factory(),
            'invoice_no' => 'DEPOT-' . now()->format('Ymd') . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'note' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween(now()->startOfMonth(), now()),
        ]);
    }

    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => $this->faker->dateTimeBetween(now()->startOfDay(), now()),
        ]);
    }
}