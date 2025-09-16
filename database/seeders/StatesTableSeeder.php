<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['name' => 'Andaman and Nicobar Islands', 'country_id' => 101],
            ['name' => 'Andhra Pradesh', 'country_id' => 101],
            ['name' => 'Arunachal Pradesh', 'country_id' => 101],
            ['name' => 'Assam', 'country_id' => 101],
            ['name' => 'Bihar', 'country_id' => 101],
            ['name' => 'Chandigarh', 'country_id' => 101],
            ['name' => 'Chhattisgarh', 'country_id' => 101],
            ['name' => 'Dadra and Nagar Haveli', 'country_id' => 101],
            ['name' => 'Daman and Diu', 'country_id' => 101],
            ['name' => 'Delhi', 'country_id' => 101],
            ['name' => 'Goa', 'country_id' => 101],
            ['name' => 'Gujarat', 'country_id' => 101],
            ['name' => 'Haryana', 'country_id' => 101],
            ['name' => 'Himachal Pradesh', 'country_id' => 101],
            ['name' => 'Jammu and Kashmir', 'country_id' => 101],
            ['name' => 'Jharkhand', 'country_id' => 101],
            ['name' => 'Karnataka', 'country_id' => 101],
            ['name' => 'Kerala', 'country_id' => 101],
            ['name' => 'Madhya Pradesh', 'country_id' => 101],
            ['name' => 'Maharashtra', 'country_id' => 101],
            ['name' => 'Manipur', 'country_id' => 101],
            ['name' => 'Meghalaya', 'country_id' => 101],
            ['name' => 'Mizoram', 'country_id' => 101],
            ['name' => 'Nagaland', 'country_id' => 101],
            ['name' => 'Odisha', 'country_id' => 101],
            ['name' => 'Puducherry', 'country_id' => 101],
            ['name' => 'Punjab', 'country_id' => 101],
            ['name' => 'Rajasthan', 'country_id' => 101],
            ['name' => 'Sikkim', 'country_id' => 101],
            ['name' => 'Tamil Nadu', 'country_id' => 101],
            ['name' => 'Telangana', 'country_id' => 101],
            ['name' => 'Tripura', 'country_id' => 101],
            ['name' => 'Uttar Pradesh', 'country_id' => 101],
            ['name' => 'Uttarakhand', 'country_id' => 101],
            ['name' => 'West Bengal', 'country_id' => 101]
        ];  

        DB::table('states')->insert($states);
    }
}
