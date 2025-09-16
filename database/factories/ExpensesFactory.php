<?php

namespace Database\Factories;

use App\Models\Expenses;
use App\Models\ExpensesCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpensesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expenses::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => ExpensesCategory::inRandomOrder()->first(),
            'title' => $this->faker->sentence($this->faker->numberBetween($min = 3, $max = 6), $variableNbWords = true),
            'date' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'total' => $this->faker->numberBetween($min = 10, $max = 9000),
            'notes' => $this->faker->realText($maxNbChars = 100, $indexSize = 2)
        ];
    }
}
