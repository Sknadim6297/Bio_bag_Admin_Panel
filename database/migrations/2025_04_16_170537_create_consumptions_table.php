<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptionsTable extends Migration
{
    public function up()
{
    Schema::create('consumptions', function (Blueprint $table) {
        $table->id();
        $table->date('date');
        $table->time('time');
        $table->decimal('total', 10, 2)->default(0);  
        $table->foreignId('sku_id')->constrained('skus')->onDelete('cascade'); 
        $table->decimal('quantity', 10, 2); 
        $table->string('unit'); 
        $table->timestamps();
    });
}


    public function down()
    {
        Schema::dropIfExists('consumptions');
    }
}
