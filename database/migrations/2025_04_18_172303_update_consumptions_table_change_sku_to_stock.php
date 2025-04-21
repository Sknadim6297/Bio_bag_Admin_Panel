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
        Schema::table('consumptions', function (Blueprint $table) {
            $table->dropForeign(['sku_id']);
            $table->dropColumn('sku_id');

            $table->foreignId('stock_id')->after('total')->constrained('stocks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('consumptions', function (Blueprint $table) {
            $table->dropForeign(['stock_id']);
            $table->dropColumn('stock_id');

            $table->foreignId('sku_id')->constrained('skus')->onDelete('cascade');
        });
    }
};
