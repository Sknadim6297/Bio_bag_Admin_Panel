<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_name',
        'vendor_code',
        'mobile_number',
        'address',
        'payment_terms',
        'lead_time',
        'category_of_supply',
        'gstin',
        'pan_number',
        'bank_name',
        'branch_name',
        'account_number',
        'ifsc_code',
        'status'
    ];
}
