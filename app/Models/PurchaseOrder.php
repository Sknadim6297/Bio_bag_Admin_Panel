<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    public static function generatePoNumber()
    {
        $last = self::latest()->first();
        $lastId = $last ? $last->id + 1 : 1;
        return 'PO-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(PurchaseOrderAttachment::class);
    }
    protected $fillable = [
        'po_number',
        'po_date',
        'vendor_id',
        'vendor_address',
        'deliver_to_type',
        'deliver_to_location',
        'deliver_address',
        'expected_delivery',
        'payment_terms',

        'files',
        'subtotal',
        'tax',
        'discount',
        'total',
        'terms',
        'notes',
        'reference',
        'measurement'
    ];
    // App\Models\PurchaseOrder.php

}
