<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributeWisePriceToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->double('price')->nullable()->after('attribute_item_id');
            $table->double('customer_buying_price')->nullable()->after('price');
//            $table->string('sku')->index();
//            $table->string('barcode')->index()->nullable();
//            $table->string('barcode_image')->nullable();
        });
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('sale_return_items', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('sale_return_item_requests', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('draft_invoice_items', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('purchase_return_items', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
        Schema::table('purchase_item_receives', function (Blueprint $table) {
            $table->foreignId('product_stock_id')->after('product_id')->nullable()->constrained('product_stocks', 'id')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
}
