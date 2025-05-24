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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('scientific_name')->nullable();
            $table->string('botanical_family')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description');
            $table->text('detailed_description')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->integer('discount_percentage')->default(0);
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('review_count')->default(0);
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('in_stock')->default(true);
            $table->integer('stock_quantity')->default(0);
            $table->boolean('featured')->default(false);
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->json('specifications')->nullable();
            $table->json('nutritional_info')->nullable();
            $table->json('ingredients')->nullable();
            $table->json('benefits')->nullable();
            $table->json('usage')->nullable();
            $table->json('market_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
