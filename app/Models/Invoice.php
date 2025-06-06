<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',  
        'hsn',
        'description',
        'micron',
        'size',
        'quantity',
        'price_per_kg',
        'total_amount',       
        'cgst',
        'sgst',
        'igst',
        'tax_amount',
        'final_price',
        'inventory_source',
        'source_id' 
    ];

    /**
     * Get the customer associated with the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'source_id')
            ->where('inventory_source', 'inventory');
    }
}