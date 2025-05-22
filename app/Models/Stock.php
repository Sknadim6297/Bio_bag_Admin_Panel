<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Consumption;
use App\Models\PurchaseOrderItem;

class Stock extends Model
{
    use HasFactory;
    
    protected $table = 'stocks'; // Explicitly define the table name
    
    protected $fillable = [
        'product_name',
        'measurement',
        'quantity',
        'stock'
    ];

    protected $casts = [
        'quantity' => 'decimal:2'
    ];

    // Define primary key if it's not 'id'
    protected $primaryKey = 'id';

    public function consumptions()
    {
        return $this->hasMany(Consumption::class, 'stock_id');
    }

    public function purchases()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_name', 'product_name');
    }

    // Add a scope to find by product name
    public function scopeByProductName($query, $productName)
    {
        return $query->where('product_name', $productName);
    }
}
