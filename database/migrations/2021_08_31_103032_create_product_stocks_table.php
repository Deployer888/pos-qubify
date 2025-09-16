<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->foreignId('product_id')->nullable()->constrained('products', 'id')->onDelete('cascade');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses', 'id')->onDelete('set null');
            $table->foreignId('attribute_id')->nullable()->constrained('attributes', 'id')->onDelete('set null');
            $table->foreignId('attribute_item_id')->nullable()->constrained('attribute_items', 'id')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}
