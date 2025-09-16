<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDecimalPrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'customers' => ['total_wallet_amount'],
            'purchase_returns' => ['total'],
            'purchase_return_items' => ['price', 'sub_total'],
            'expenses' => ['total'],
            'sale_returns' => ['return_total_amount'],
            'sale_return_items' => ['return_price', 'return_sub_total']
        ];

        foreach ($tables as $table => $columns) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->decimal($column, 20, 3)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = [
            'customers' => ['total_wallet_amount'],
            'purchase_returns' => ['total'],
            'purchase_return_items' => ['price', 'sub_total'],
            'expenses' => ['total'],
            'sale_returns' => ['return_total_amount'],
            'sale_return_items' => ['return_price', 'return_sub_total']
        ];

        foreach ($tables as $table => $columns) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                foreach ($columns as $column) {
                    $table->decimal($column, 8, 2)->change();
                }
            });
        }
    }
}
