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
        Schema::create('final_outputs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id'); 
            $table->dateTime('final_output_datetime');
            $table->string('size');
            $table->integer('micron');
            $table->float('quantity'); 
            $table->timestamps();

            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_outputs');
    }
};
