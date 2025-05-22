<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'quantity',
        'sku_id',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2'
    ];

    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
