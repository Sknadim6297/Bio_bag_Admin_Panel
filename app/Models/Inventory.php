<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'micron',
        'size',
        'quantity',
        'hsn',
        'description',
        'price_per_kg',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}