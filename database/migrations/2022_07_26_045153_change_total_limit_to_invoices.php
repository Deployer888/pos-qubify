<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalLimitToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('tax_amount', 14, 2)->nullable()->change();
            $table->decimal('discount_amount', 14, 2)->nullable()->change();
            $table->decimal('global_discount', 14, 2)->default(0)->nullable()->change();

            $table->decimal('total', 14, 2)->nullable()->change();
            $table->decimal('total_paid', 14, 2)->nullable()->change();
            $table->decimal('last_paid', 14, 2)->default(0.00)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
