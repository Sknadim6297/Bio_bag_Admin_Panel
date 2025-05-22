<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Production;
use Illuminate\Http\Request;
use NumberFormatter;
use App\Helpers\NumberToWords;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductionController extends Controller
{

    public function index(Request $request)
{
    $query = Production::with('customer');

    if ($request->filled('from_date')) {
        $query->whereDate('production_datetime', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('production_datetime', '<=', $request->to_date);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->whereHas('customer', function ($q2) use ($search) {
                $q2->where('customer_name', 'like', "%$search%");
            })->orWhere('machine_number', 'like', "%$search%")
                ->orWhere('size', 'like', "%$search%")
                ->orWhere('notes', 'like', "%$search%")
                ->orWhere('rolls_done', 'like', "%$search%")
                ->orWhere('kilograms_produced', 'like', "%$search%")
                ->orWhere('micron', 'like', "%$search%");
        });
    }

    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->filled('micron')) {
        $query->where('micron', $request->micron);
    }

    if ($request->filled('size')) {
        $query->where('size', $request->size);
    }

    if ($request->filled('rolls_done')) {
        $query->where('rolls_done', $request->rolls_done);
    }

    if ($request->filled('machine_number')) {
        $query->where('machine_number', 'like', '%' . $request->machine_number . '%');
    }

    if ($request->filled('kilograms_produced')) {
        $query->where('kilograms_produced', $request->kilograms_produced);
    }

    $productions = $query->orderBy('production_datetime', 'desc')->paginate(10);

    $totalKilogram = $query->clone()->sum('kilograms_produced');

    if ($request->ajax()) {
        return response()->json([
            'status' => true,
            'data' => $productions->items(),
            'total_kilogram' => $totalKilogram,
            'pagination' => [
                'total' => $productions->total(),
                'current_page' => $productions->currentPage(),
                'per_page' => $productions->perPage(),
                'last_page' => $productions->lastPage(),
            ],
        ]);
    }


    $customers = Customer::all();
    $micronList = Production::pluck('micron')->unique();
    $sizeList = Production::pluck('size')->unique();
    $rollsDoneList = Production::pluck('rolls_done')->unique();
    $machineNumberList = Production::pluck('machine_number')->unique();
    $kilogramsProducedList = Production::pluck('kilograms_produced')->unique();

    return view('admin.production.index', compact('productions', 'customers', 'micronList', 'sizeList', 'rollsDoneList', 'machineNumberList', 'kilogramsProducedList'));
}






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = \App\Models\Customer::all();
        return view('admin.production.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'production_datetime' => 'required|date',
            'rolls_done' => 'required|integer|min:0',
            'size' => 'required|string',
            'kilograms_produced' => 'required|numeric|min:0',
            'machine_number' => 'required|string',
            'micron' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);
        Production::create($request->all());

        return response()->json(['status' => true, 'message' => 'Production record created successfully.']);
    }

    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function downloadReport(Request $request)
{
    // Build a query based on the provided filters
    $query = Production::with('customer');

    // Apply all search filters
    if ($request->filled('from_date')) {
        $query->whereDate('production_datetime', '>=', $request->from_date);
    }
    
    if ($request->filled('to_date')) {
        $query->whereDate('production_datetime', '<=', $request->to_date);
    }
    
    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }

    if ($request->filled('micron')) {
        $query->where('micron', $request->micron);
    }

    if ($request->filled('size')) {
        $query->where('size', $request->size);
    }

    if ($request->filled('rolls_done')) {
        $query->where('rolls_done', $request->rolls_done);
    }

    if ($request->filled('machine_number')) {
        $query->where('machine_number', $request->machine_number);
    }
    
    if ($request->filled('kilograms_produced')) {
        $query->where('kilograms_produced', $request->kilograms_produced);
    }
    
    if ($request->filled('search')) {
        $searchTerm = trim($request->search);
        $query->where(function ($q) use ($searchTerm) {
            $q->whereHas('customer', function ($q2) use ($searchTerm) {
                $q2->where('customer_name', 'like', "%$searchTerm%");
            })
            ->orWhere('machine_number', 'like', "%$searchTerm%")
            ->orWhere('size', 'like', "%$searchTerm%")
            ->orWhere('micron', 'like', "%$searchTerm%");
        });
    }
    
    // Get productions with applied filters (includes search results)
    $productions = $query->orderBy('production_datetime', 'desc')->get();
    $total = $productions->sum('kilograms_produced');
    $totalInWords = ucfirst(NumberToWords::convert($total));
    
    // Generate PDF with totalInWords included
    $pdf = PDF::loadView('admin.production.report_pdf', [
        'productions' => $productions,
        'total' => $total,
        'totalInWords' => $totalInWords,
        'reportNumber' => 'PROD-' . now()->format('YmdHis'),
        'date' => now()->format('d/m/Y'),
        'filters' => $request->all()
    ]);
    
    return $pdf->stream('production_report_preview_' . now()->format('Y-m-d') . '.pdf');

}
}
