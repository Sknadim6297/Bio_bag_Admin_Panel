<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_order_id',
        'sku_id',
        'description',
        'quantity',
        'unit_price',
        'total',  
        'status',
        'measurement'
    ];
    public function sku()
    {
        return $this->belongsTo(Sku::class);
    }
    public function purchaseOrderAttachment()
    {
        return $this->hasMany(PurchaseOrderAttachment::class);
    }
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
