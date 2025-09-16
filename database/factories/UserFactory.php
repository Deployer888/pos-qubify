<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'John Doe',
            'email' => 'admin@app.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'status' => User::STATUS_ACTIVE,
            'remember_token' => Str::random(10),
        ];
    }
}
