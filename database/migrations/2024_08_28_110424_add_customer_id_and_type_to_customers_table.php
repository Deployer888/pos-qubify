<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdAndTypeToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained('customers', 'id')->onDelete('cascade')->after('id');
            $table->string('type')->nullable()->default(Customer::TYPE_CUSTOMER)->after('customer_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('priority');
            $table->string('barcode_image')->nullable()->after('barcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {});

        Schema::table('warehouses', function (Blueprint $table) {});
    }
}
