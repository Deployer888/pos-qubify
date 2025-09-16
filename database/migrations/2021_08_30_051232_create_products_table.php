<?php

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->index('name');
            $table->string('sku');
            $table->index('sku');
            $table->string('barcode')->nullable();
            $table->index('barcode');
            $table->string('barcode_image')->nullable();
            $table->string('model')->nullable();
            $table->string('price')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('dimension_l')->nullable();
            $table->integer('dimension_w')->nullable();
            $table->integer('dimension_d')->nullable();
            $table->string('thumb')->nullable();
            $table->string('notes')->nullable();
            $table->text('desc')->nullable();
            $table->boolean('is_variant')->default(false);
            $table->string('status', 20)->default(Product::STATUS_ACTIVE);
            $table->foreignId('category_id')->nullable()->constrained('product_categories', 'id')->onDelete('set null');
            $table->foreignId('brand_id')->nullable()->constrained('brands', 'id')->onDelete('set null');
            $table->foreignId('manufacturer_id')->nullable()->constrained('manufacturers', 'id')->onDelete('set null');
            $table->foreignId('weight_unit_id')->nullable()->constrained('weight_units', 'id')->onDelete('set null');
            $table->foreignId('measurement_unit_id')->nullable()->constrained('measurement_units', 'id')->onDelete('set null');
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
        Schema::dropIfExists('products');
    }
}
