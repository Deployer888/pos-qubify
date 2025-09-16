<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expenses_id')->nullable();
            $table->string('original_name')->nullable();
            $table->string('file_name')->nullable();
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
        Schema::dropIfExists('expenses_files');
    }
}
