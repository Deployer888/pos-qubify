<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('delivery_status')->nullable()->default(\App\Models\Invoice::DELIVERY_STATUS_DELIVERED)->after('status');
            $table->string('invoice_created_from')->nullable()->default(\App\Models\Invoice::CREATED_FROM_ADMIN)->after('delivery_status');
            $table->timestamp('delivered_at')->nullable()->default(now())->after('invoice_created_from');
            $table->timestamp('canceled_at')->nullable()->default(now())->after('delivered_at');
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
