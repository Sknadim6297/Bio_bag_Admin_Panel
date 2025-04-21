<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StockExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Stock::all();
    }

    public function headings(): array
    {
        return [
            'ID', 
            'Product Name', 
            'Stock', 
            'Quantity',
        ];
    }
}
