<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'production_datetime',
        'rolls_done',
        'size',
        'kilograms_produced',
        'machine_number',
        'micron',
        'notes'
    ];

    protected $casts = [
        'production_datetime' => 'datetime',
        'kilograms_produced' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
