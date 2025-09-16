<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reading data from city.sql file
        $sqlFile = file_get_contents(database_path('seeders/data/city.sql'));
        $statements = array_filter(array_map('trim', explode('INSERT INTO', $sqlFile)));
        
        foreach ($statements as $statement) {
            if (str_contains($statement, 'ic_system_cities')) {
                // Extract values
                preg_match('/VALUES\s*(.*?);/s', $statement, $matches);
                if (isset($matches[1])) {
                    $values = $matches[1];
                    // Execute with the new table name
                    DB::statement("INSERT INTO cities $values;");
                }
            }
        }
    }
}
