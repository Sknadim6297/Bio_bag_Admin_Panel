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
        Schema::table('final_outputs', function (Blueprint $table) {
            $table->dropForeign(['production_id']);
            $table->dropColumn('production_id');


            $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('final_outputs', function (Blueprint $table) {
            $table->unsignedBigInteger('production_id')->after('id');
            $table->foreign('production_id')->references('id')->on('productions')->onDelete('cascade');

            // Drop customer_id
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};
