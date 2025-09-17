<?php

namespace Database\Factories;

use App\Models\DepotSaleItem;
use App\Models\DepotSale;
use App\Models\DepotStock;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepotSaleItemFactory extends Factory
{
    protected $model = DepotSaleItem::class;

    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 10);
        $price = $this->faker->randomFloat(2, 10, 100);
        $total = $quantity * $price;

        return [
            'depot_sale_id' => DepotSale::factory(),
            'depot_stock_id' => DepotStock::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
        ];
    }
}