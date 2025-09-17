<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('optimize:clear');
        $this->call(SystemUserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(DepotManagerRoleSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(WarehouseSeeder::class);
    }
}
