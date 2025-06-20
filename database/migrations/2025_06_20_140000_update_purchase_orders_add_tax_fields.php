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
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('cgst', 5, 2)->nullable()->after('tax');
            $table->decimal('sgst', 5, 2)->nullable()->after('cgst');
            $table->decimal('igst', 5, 2)->nullable()->after('sgst');
            $table->decimal('cess', 5, 2)->nullable()->after('igst');
            $table->decimal('tax', 5, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['cgst', 'sgst', 'igst', 'cess']);
            $table->decimal('tax', 5, 2)->default(0)->change();
        });
    }
}; 