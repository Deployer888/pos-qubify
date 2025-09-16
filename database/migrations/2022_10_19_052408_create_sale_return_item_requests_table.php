<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnItemRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_request_id')->constrained('sale_return_requests');
            $table->foreignId('invoice_item_id')->constrained('invoice_items');
            $table->foreignId('product_id')->constrained('products');
            $table->string('product_name');
            $table->integer('return_qty');
            $table->double('return_price');
            $table->double('return_sub_total');
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
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
        Schema::dropIfExists('sale_return_item_requests');
    }
}
