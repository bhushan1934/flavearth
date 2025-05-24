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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('weight'); // 250gm, 500gm, 1kg, 3kg
            $table->decimal('weight_value', 8, 3); // numerical value for sorting
            $table->string('weight_unit'); // gm, kg
            $table->decimal('price', 10, 2); // price in rupees
            $table->decimal('original_price', 10, 2)->nullable(); // original price before discount
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['product_id', 'weight']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};