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
        Schema::create('depot_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->enum('measurement_unit', ['Kg', 'Ltr', 'Piece']);
            $table->decimal('current_stock', 10, 2);
            $table->decimal('price', 10, 2);
            $table->decimal('customer_price', 10, 2);
            $table->string('barcode')->unique();
            $table->string('barcode_image')->nullable();
            $table->timestamps();

            // Add unique constraint to prevent duplicate products in same depot
            $table->unique(['depot_id', 'product_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_stocks');
    }
};
