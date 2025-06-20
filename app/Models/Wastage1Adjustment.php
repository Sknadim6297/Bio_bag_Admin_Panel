<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wastage1Adjustment extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'own_wastage', 'discrepancy'];
}
