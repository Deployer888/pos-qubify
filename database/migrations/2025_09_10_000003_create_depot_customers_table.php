<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('depot_customers', function (Blueprint $table) {
            $table->id();
            $table->string('family_id')->index();
            $table->string('adhaar_no', 12)->unique();
            $table->string('ration_card_no')->unique();
            $table->string('card_range');
            $table->string('name');
            $table->string('mobile', 20)->nullable();
            $table->unsignedTinyInteger('age');
            $table->boolean('is_family_head')->default(false);
            $table->text('address');
            $table->foreignId('depot_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Index to quickly find all family members
            $table->index(['family_id', 'depot_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depot_customers');
    }
};
