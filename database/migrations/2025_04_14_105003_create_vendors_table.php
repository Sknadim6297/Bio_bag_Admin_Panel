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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');                  // Vendor Name
            $table->string('vendor_code')->unique();        // Vendor Code
            $table->string('mobile_number', 10);             // 10 digit mobile number
            $table->text('address');                         // Address
            $table->string('payment_terms');                 // Payment Terms
            $table->string('lead_time');                     // Lead Time
            $table->string('category_of_supply');            // Category of Supply
            $table->string('gstin')->unique();               // GSTIN (Format: 22AAAAA0000A1Z5)
            $table->string('pan_number')->unique();          // PAN Number (Format: AAAAA9999A)
            $table->string('bank_name');                     // Bank Name
            $table->string('branch_name');                   // Branch Name
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->enum('status', ['active', 'inactive'])->default('active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
