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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_code')->unique();
            $table->string('mobile_number', 15)->nullable();
            $table->text('address')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('gstin')->nullable();
            $table->string('pan_number')->nullable();
    
            // Bank Details
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
    
            $table->enum('status', ['active', 'inactive'])->default('active');
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
