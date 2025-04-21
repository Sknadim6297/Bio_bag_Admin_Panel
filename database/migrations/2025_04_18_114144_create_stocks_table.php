<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->enum('measurement', ['kg', 'g', 'l', 'pcs'])->default('pcs');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->enum('stock', ['in_stock', 'out_of_stock'])->default('in_stock');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
