<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = ['product_name', 'stock', 'quantity'];

    public function purchases()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_id', 'product_id');
    }

    public function consumptions()
    {
        return $this->hasMany(Consumption::class, 'product_id', 'product_id');
    }
}
