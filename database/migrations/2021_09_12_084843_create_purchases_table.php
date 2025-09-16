<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number',100)->unique();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->string('company')->nullable();
            $table->dateTime('date');
            $table->text('notes')->nullable();
            $table->decimal('total');
            $table->string('status');
            //Address
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->foreignId('country')->nullable()->constrained('system_countries', 'id')->onDelete('set null');
            $table->foreignId('state')->nullable()->constrained('system_states', 'id')->onDelete('set null');
            $table->foreignId('city')->nullable()->constrained('system_cities', 'id')->onDelete('set null');
            $table->string('zipcode', 20)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->timestamps();
            $table->boolean('received')->nullable();

            $table->date('cancel_date')->nullable();
            $table->foreignId('cancel_by')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->text('cancel_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
