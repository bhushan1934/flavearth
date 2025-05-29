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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('waybill')->unique()->nullable();
            $table->string('courier_name')->default('delhivery');
            $table->string('status')->default('pending');
            $table->string('pickup_location')->nullable();
            $table->json('shipment_data')->nullable();
            $table->json('tracking_data')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('packing_slip_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('waybill');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};