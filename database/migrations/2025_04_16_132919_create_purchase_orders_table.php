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
        // database/migrations/xxxx_xx_xx_create_purchase_orders_table.php
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->date('po_date');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->text('vendor_address')->nullable();
            $table->enum('deliver_to_type', ['customer', 'organization']);
            $table->string('deliver_to_location');
            $table->text('deliver_address');
            $table->date('expected_delivery')->nullable();
            $table->string('payment_terms');
            $table->json('files')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 5, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->string('reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
