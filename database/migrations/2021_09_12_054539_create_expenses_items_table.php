<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expenses_id')->nullable();
            $table->string('item_name')->nullable();
            $table->string('amount')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('expenses_id')->references('id')->on('expenses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses_items');
    }
}
