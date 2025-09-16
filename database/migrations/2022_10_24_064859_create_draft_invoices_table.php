<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDraftInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draft_invoices', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->json('customer')->nullable();
            $table->json('billing_info')->nullable();
            $table->json('shipping_info')->nullable();
            $table->json('bank_info')->nullable();
            $table->json('items_data')->nullable();
            $table->decimal('tax_amount', 14, 2)->nullable();
            $table->decimal('discount_amount', 14, 2)->nullable();
            $table->decimal('global_discount', 14, 2)->default(0)->nullable();
            $table->string('global_discount_type')->nullable();
            $table->decimal('total', 14, 2)->nullable();
            $table->decimal('total_paid', 14, 2)->nullable();
            $table->decimal('last_paid', 14, 2)->default(0.00);
            $table->string('payment_type', 50)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->nullable();
            $table->string('invoice_created_from')->nullable()->default(\App\Models\DraftInvoice::CREATED_FROM_ADMIN);
            $table->string('token')->nullable();


            $table->foreignId('warehouse_id')->nullable()->constrained();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
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
        Schema::dropIfExists('draft_invoices');
    }
}
