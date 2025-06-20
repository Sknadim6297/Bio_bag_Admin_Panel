<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wastage1_adjustments', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->float('own_wastage')->nullable();
            $table->float('discrepancy')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('wastage1_adjustments');
    }
};
