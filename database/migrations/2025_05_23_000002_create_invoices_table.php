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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('invoice_number');
            $table->date('invoice_date');
            $table->string('hsn')->nullable();
            $table->text('description')->nullable();
            $table->string('micron')->nullable();
            $table->string('size')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('price_per_kg', 10, 2);
            $table->decimal('total_amount', 12, 2); // Changed from total_price to total_amount for consistency
            $table->decimal('cgst', 5, 2)->default(0);
            $table->decimal('sgst', 5, 2)->default(0);
            $table->decimal('igst', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('final_price', 12, 2);
            $table->string('inventory_source')->default('inventory'); // 'inventory' or 'semi_production'
            $table->unsignedBigInteger('source_id'); // ID reference to inventory or semi inventory
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};