<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalRageToPurchaseItemReceives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_item_receives', function (Blueprint $table) {
            $table->decimal('price', 14, 2)->change();
            $table->decimal('sub_total', 14, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_item_receives', function (Blueprint $table) {
            //
        });
    }
}
