<?php

namespace Database\Factories;

use App\Models\DepotStock;
use App\Models\Depot;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotStockFactory extends Factory
{
    protected $model = DepotStock::class;

    public function definition(): array
    {
        $products = [
            ['name' => 'Rice', 'unit' => 'kg'],
            ['name' => 'Wheat', 'unit' => 'kg'],
            ['name' => 'Sugar', 'unit' => 'kg'],
            ['name' => 'Oil', 'unit' => 'liter'],
            ['name' => 'Dal', 'unit' => 'kg'],
            ['name' => 'Salt', 'unit' => 'kg'],
        ];

        $product = $this->faker->randomElement($products);
        $price = $this->faker->randomFloat(2, 10, 100);

        return [
            'depot_id' => Depot::factory(),
            'product_name' => $product['name'],
            'measurement_unit' => $product['unit'],
            'current_stock' => $this->faker->randomFloat(2, 0, 1000),
            'price' => $price,
            'customer_price' => $price * 1.1, // 10% markup for customers
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => $this->faker->randomFloat(2, 0, 10),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'current_stock' => 0,
        ]);
    }
}