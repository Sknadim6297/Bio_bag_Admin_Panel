<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumption extends Model
{
    use HasFactory;
    public function sku()
    {
        return $this->belongsTo(Sku::class, 'sku_id');
    }
    protected $fillable = ['date', 'time', 'sku_id', 'quantity', 'unit'];
}
