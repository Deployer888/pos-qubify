<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ResetDB extends Command
{
    use \App\Traits\CurrencyExchange;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        try {
//            Artisan::call("backup:run --only-db --disable-notifications");
//
//            Log::info("DB backup successful");
//        } catch (\Exception $ex) {
//            Log::error($ex->getMessage());
//        }

        try {
            // Drop all table
            Schema::disableForeignKeyConstraints();
            foreach (DB::select('SHOW TABLES') as $table) {
                $table_array = get_object_vars($table);
                $table = $table_array[key($table_array)];
                $table = str_replace('ic_', '', $table);
                Schema::drop($table);
            }

            // Upload db
            $sql_path = base_path('icdemo.sql');
            DB::unprepared(file_get_contents($sql_path));

            // info('DB uploaded');
            $this->info('DB uploaded '.date('Y-m-d H:i:s'));

            Log::info('DB uploaded '.date('Y-m-d H:i:s'));
        } catch (\Exception $ex) {
            logger($ex);
        }

    }
}
