<?php

use App\Models\Supplier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100);
            $table->string('phone', 20);
            $table->string('company', 200)->nullable();
            $table->string('designation', 200)->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->foreignId('country')->nullable()->constrained('system_countries', 'id')->onDelete('set null');
            $table->foreignId('state')->nullable()->constrained('system_states', 'id')->onDelete('set null');
            $table->foreignId('city')->nullable()->constrained('system_cities', 'id')->onDelete('set null');
            $table->string('zipcode', 20);
            $table->string('avatar')->nullable();
            $table->string('status', 20)->default(Supplier::STATUS_ACTIVE);
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
        Schema::dropIfExists('suppliers');
    }
}
