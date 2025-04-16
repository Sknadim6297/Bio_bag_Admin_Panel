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
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('sku_code');
            $table->string('product_name');
            $table->integer('quantity');
            $table->string('measurement');
            $table->integer('msq');
            $table->decimal('price', 10, 2);
            $table->decimal('gst', 5, 2);
            $table->decimal('freight', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->dateTime('date_time');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
