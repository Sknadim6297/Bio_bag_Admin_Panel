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
        // Check if stock_id column doesn't exist before adding it
        if (!Schema::hasColumn('consumptions', 'stock_id')) {
            Schema::table('consumptions', function (Blueprint $table) {
                $table->foreignId('stock_id')->after('total');
            });
        }

        // Check if sku column exists before dropping it
        if (Schema::hasColumn('consumptions', 'sku')) {
            Schema::table('consumptions', function (Blueprint $table) {
                $table->dropColumn('sku');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original state
        if (!Schema::hasColumn('consumptions', 'sku')) {
            Schema::table('consumptions', function (Blueprint $table) {
                $table->string('sku')->after('total')->nullable();
            });
        }

        // Don't try to drop stock_id in down migration if it already existed before
        // This prevents errors if the original table already had stock_id
    }
};
