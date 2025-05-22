<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consumption;
use App\Models\Production;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Wastage1Controller extends Controller
{
  public function index(Request $request)
{
    // Build base query with subqueries for consumptions and productions grouped by date
    $query = DB::table(DB::raw('(
                SELECT DATE(date) as date, SUM(quantity) as total_consumption
                FROM consumptions
                GROUP BY DATE(date)
            ) as c'))
        ->leftJoin(DB::raw('(
                SELECT DATE(production_datetime) as date, SUM(kilograms_produced) as total_production
                FROM productions
                GROUP BY DATE(production_datetime)
            ) as p'), 'c.date', '=', 'p.date')
        ->select(
            'c.date',
            DB::raw('c.total_consumption'),
            DB::raw('COALESCE(p.total_production, 0) as total_production'),
            DB::raw('(c.total_consumption - COALESCE(p.total_production, 0)) as wastage')
        );

    // Apply date filters
    if ($request->filled('from_date')) {
        $query->whereDate('c.date', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('c.date', '<=', $request->to_date);
    }

    // Get results ordered by date (newest first)
    $wastageReport = $query->orderBy('c.date', 'desc')->get();

    // Calculate total wastage
    $totalWastage = $wastageReport->sum(function ($report) {
        return abs($report->wastage);
    });

    // AJAX response
    if ($request->ajax()) {
        return response()->json([
            'status' => true,
            'data' => $wastageReport,
            'totalWastage' => $totalWastage
        ]);
    }

    // For date filter dropdown
    $dates = Consumption::distinct()
        ->orderBy('date')
        ->pluck('date');

    return view('admin.wastage.wastage1', compact(
        'wastageReport',
        'totalWastage',
        'dates'
    ));
}

}
