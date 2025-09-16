<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained();
            $table->date('return_date');
            $table->text('return_note')->nullable();
            $table->double('return_total_amount');
            $table->longText('items_info');
            $table->string('status',20)->default(\App\Models\SaleReturnRequest::STATUS_PENDING);
            $table->foreignId('requested_by')->nullable()->constrained('customers', 'id')->onDelete('set null');
            $table->foreignId('status_updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamp('status_updated_at')->nullable()->default(now());
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
        Schema::dropIfExists('sale_return_requests');
    }
}
