<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('draft_invoice_id');
            $table->index('draft_invoice_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->integer('quantity');
            $table->decimal('price',14,2);
            $table->integer('tax')->default(0);
            $table->string('discount')->nullable();
            $table->string('discount_type', 20)->nullable();
            $table->decimal('sub_total',14,2);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('draft_invoice_id')->references('id')->on('draft_invoices')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('no action');
            $table->foreign('created_by')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('draft_invoice_items');
    }
}
