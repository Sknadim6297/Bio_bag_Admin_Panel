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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            if (Schema::hasColumn('purchase_order_items', 'sku_id')) {
                $table->dropForeign(['sku_id']);
                $table->dropColumn('sku_id');
            }

            if (!Schema::hasColumn('purchase_order_items', 'product_name')) {
                $table->string('product_name')->after('purchase_order_id');
            }
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            //
        });
    }
};
