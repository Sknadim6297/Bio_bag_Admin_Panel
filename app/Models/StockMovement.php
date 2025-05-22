<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';
    protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}