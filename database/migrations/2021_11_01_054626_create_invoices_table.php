<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('due_date')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->json('customer')->nullable();
            $table->json('billing_info')->nullable();
            $table->json('shipping_info')->nullable();
            $table->json('items_data')->nullable();
            $table->decimal('tax_amount')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('global_discount')->default(0)->nullable();
            $table->string('global_discount_type')->nullable();
            $table->decimal('total')->nullable();
            $table->decimal('total_paid')->nullable();
            $table->decimal('last_paid')->default(0.00);
            $table->string('payment_type', 50)->nullable();
            $table->string('notes')->nullable();
            $table->string('status', 20)->nullable();
            $table->string('token')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
