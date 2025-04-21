<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalOutput extends Model
{

    use HasFactory;

    protected $fillable = [
        'customer_id',
        'final_output_datetime',
        'size',
        'micron',
        'quantity',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
