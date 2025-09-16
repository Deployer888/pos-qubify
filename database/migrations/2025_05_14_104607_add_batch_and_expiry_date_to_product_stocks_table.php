<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->string('batch')->nullable()->after('warehouse_id');
            $table->date('expiry_date')->nullable()->after('batch');

            $table->unique(['product_id', 'warehouse_id', 'batch'], 'unique_product_warehouse_batch');
        });
    }

    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropUnique('unique_product_warehouse_batch');

            $table->dropColumn(['batch', 'expiry_date']);
        });
    }
};

