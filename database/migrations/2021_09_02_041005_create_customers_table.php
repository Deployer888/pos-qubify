<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
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
            $table->boolean('billing_same')->default(0);
            $table->string('b_first_name', 100)->nullable();
            $table->string('b_last_name', 100)->nullable();
            $table->string('b_email', 100)->nullable();
            $table->string('b_phone', 20)->nullable();
            $table->string('b_address_line_1')->nullable();
            $table->string('b_address_line_2')->nullable();
            $table->foreignId('b_country')->nullable()->constrained('system_countries', 'id')->onDelete('set null');
            $table->foreignId('b_state')->nullable()->constrained('system_states', 'id')->onDelete('set null');
            $table->foreignId('b_city')->nullable()->constrained('system_cities', 'id')->onDelete('set null');
            $table->string('b_zipcode', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->string('status', 20)->default(Customer::STATUS_ACTIVE);
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
        Schema::dropIfExists('customers');
    }
}
