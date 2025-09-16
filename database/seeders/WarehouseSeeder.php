<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Warehouse::query()->create([
            'name' => 'Default Warehouse',
            'email' => 'default@email.com',
            'phone' => '12345678',
            'company_name' => 'Default Company',
            'address_1' => '',
            'address_2' => '',
            'priority' => 1,
            'is_default' => true,
            'status' => 'active',
            'created_by' => User::query()->first()->id,
            'updated_by' => User::query()->first()->id
        ]);
    }
}
