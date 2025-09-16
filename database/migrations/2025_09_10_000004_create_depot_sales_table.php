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
        Schema::create('depot_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->foreignId('depot_customer_id')->constrained()->onDelete('cascade');
            $table->string('invoice_no')->unique();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('depot_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depot_sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('depot_stock_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_sale_items');
        Schema::dropIfExists('depot_sales');
    }
};
