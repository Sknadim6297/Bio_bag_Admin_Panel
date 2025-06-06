<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wastage2Controller extends Controller
{
    public function index(Request $request)
    {
       $query = DB::table('productions as p')
    ->select(
        DB::raw('DATE(p.production_datetime) as date'),
        'p.size',
        'c.customer_name as product_name',
        'p.customer_id',
        DB::raw('SUM(p.kilograms_produced) as total_production'),
        DB::raw('COALESCE(SUM(f.quantity), 0) as total_final_output'),
        DB::raw('SUM(p.kilograms_produced) - COALESCE(SUM(f.quantity), 0) as wastage')
    )
    ->join('customers as c', 'c.id', '=', 'p.customer_id')
    ->leftJoin('final_outputs as f', function ($join) {
        $join->whereRaw('DATE(f.final_output_datetime) = DATE(p.production_datetime)')
             ->whereColumn('f.size', 'p.size');
    })
    ->groupBy(DB::raw('DATE(p.production_datetime)'), 'p.size', 'c.customer_name', 'p.customer_id');


        // Date filters
        if ($request->filled('from_date')) {
            $query->whereDate('p.production_datetime', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('p.production_datetime', '<=', $request->to_date);
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where('c.customer_name', 'like', '%' . $request->search . '%');
        }

        // Paginate results
        $wastageReport = $query->orderBy('date', 'desc')->paginate(10);

        return view('admin.wastage.wastage2', compact('wastageReport'));
    }
}