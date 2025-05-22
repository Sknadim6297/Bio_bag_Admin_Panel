<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Consumption extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'total',
        'stock_id',
        'quantity',
        'unit'
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2',
        'quantity' => 'decimal:2'
    ];

    // Convert time to database format when setting
    public function setTimeAttribute($value)
    {
        $this->attributes['time'] = Carbon::parse($value)->format('H:i:s');
    }

    // Format time when getting
    public function getTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
