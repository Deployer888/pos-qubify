<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for depot table
        Schema::table('depots', function (Blueprint $table) {
            $table->index('status', 'idx_depots_status');
            $table->index('user_id', 'idx_depots_user_id');
            $table->index(['status', 'user_id'], 'idx_depots_status_user');
        });

        // Add indexes for depot_sales table for revenue calculations
        Schema::table('depot_sales', function (Blueprint $table) {
            $table->index('created_at', 'idx_depot_sales_created_at');
            $table->index(['depot_id', 'created_at'], 'idx_depot_sales_depot_date');
            $table->index(['created_at', 'total'], 'idx_depot_sales_date_total');
        });

        // Add indexes for depot_stocks table
        Schema::table('depot_stocks', function (Blueprint $table) {
            $table->index('depot_id', 'idx_depot_stocks_depot_id');
            $table->index(['depot_id', 'current_stock'], 'idx_depot_stocks_depot_stock');
            $table->index('measurement_unit', 'idx_depot_stocks_unit');
        });

        // Add indexes for depot_customers table
        Schema::table('depot_customers', function (Blueprint $table) {
            $table->index('depot_id', 'idx_depot_customers_depot_id');
            $table->index(['depot_id', 'status'], 'idx_depot_customers_depot_status');
            $table->index('created_at', 'idx_depot_customers_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from depot table
        Schema::table('depots', function (Blueprint $table) {
            $table->dropIndex('idx_depots_status');
            $table->dropIndex('idx_depots_user_id');
            $table->dropIndex('idx_depots_status_user');
        });

        // Remove indexes from depot_sales table
        Schema::table('depot_sales', function (Blueprint $table) {
            $table->dropIndex('idx_depot_sales_created_at');
            $table->dropIndex('idx_depot_sales_depot_date');
            $table->dropIndex('idx_depot_sales_date_total');
        });

        // Remove indexes from depot_stocks table
        Schema::table('depot_stocks', function (Blueprint $table) {
            $table->dropIndex('idx_depot_stocks_depot_id');
            $table->dropIndex('idx_depot_stocks_depot_stock');
            $table->dropIndex('idx_depot_stocks_unit');
        });

        // Remove indexes from depot_customers table
        Schema::table('depot_customers', function (Blueprint $table) {
            $table->dropIndex('idx_depot_customers_depot_id');
            $table->dropIndex('idx_depot_customers_depot_status');
            $table->dropIndex('idx_depot_customers_created_at');
        });
    }
};