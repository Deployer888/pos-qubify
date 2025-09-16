<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        User::updateOrCreate([
            'name' => 'John Doe',
            'email' => 'admin@app.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'status' => User::STATUS_ACTIVE,
            'remember_token' => Str::random(10),
        ]);
        User::updateOrCreate([
            'name' => 'John Doe',
            'email' => 'manager@app.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
            'status' => User::STATUS_ACTIVE,
            'remember_token' => Str::random(10),
        ]);
        Customer::updateOrCreate([
            'first_name'        => $faker->firstName,
            'last_name'         => $faker->lastName,
            'email'             => 'customer@app.com',
            'email_verified_at' => now(),
            'phone'             => '01234567891',
            'password'          => \Illuminate\Support\Facades\Hash::make('12345678'),
            'status'            => Customer::STATUS_ACTIVE,
            'remember_token'    => Str::random(10),
        ]);

        Supplier::updateOrCreate([
            'first_name'        => 'In House',
            'last_name'         => 'Supplier',
            'email'             => 'supplier@app.com',
            'phone'             => '01234567891',
            'company'           => $faker->company,
            'status'            => Supplier::STATUS_ACTIVE,
        ]);
    }
}
