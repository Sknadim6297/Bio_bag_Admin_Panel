<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consumption;
use App\Helpers\NumberToWords;
use App\Models\Sku;
use App\Models\Stock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str; // Add this import
use Illuminate\Support\Facades\DB;

class ConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Consumption::with('stock');

        // Apply date filters
        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('stock', function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        // Get paginated results
        $consumptions = $query->orderBy('date', 'desc')
            ->orderBy('time', 'desc')
            ->paginate(10);

        // Calculate total consumption
        $totalConsumption = $query->sum('quantity');

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => $consumptions->items(),
                'pagination' => [
                    'current_page' => $consumptions->currentPage(),
                    'last_page' => $consumptions->lastPage(),
                    'total' => $consumptions->total(),
                ],
                'total_consumption' => number_format($totalConsumption, 2)
            ]);
        }

        return view('admin.consumption.index');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stocks = Stock::all();

        return view('admin.consumption.create', compact('stocks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'items' => 'required|array',
            'items.*.stock_id' => 'required|exists:stocks,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                $stock = Stock::find($item['stock_id']);

                // Check if stock is available
                if ($stock->quantity < $item['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => "Insufficient stock for {$stock->product_name}. Available: {$stock->quantity}, Requested: {$item['quantity']}"
                    ], 400);
                }

                // Calculate total = quantity × rate
                $rate = $stock->rate ?? 0;
                $itemTotal = $item['quantity'] * $rate;

                // Reduce quantity from stock
                $stock->quantity -= $item['quantity'];
                $stock->save();

                // Update or create in Consumption
                $existing = Consumption::where('date', $validated['date'])
                    ->where('stock_id', $item['stock_id'])
                    ->first();

                if ($existing) {
                    $existing->update([
                        'quantity' => $existing->quantity + $item['quantity'],
                        'unit' => $item['unit'],
                        'time' => $validated['time'],
                        'total' => $existing->total + $itemTotal,
                    ]);
                } else {
                    Consumption::create([
                        'date' => $validated['date'],
                        'time' => $validated['time'],
                        'stock_id' => $item['stock_id'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'total' => $itemTotal,
                    ]);
                }
            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Consumption data saved and stock updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'An error occurred while saving data.'], 500);
        }
    }




    public function show(string $id) {}
    public function edit($id)
    {
        $consumption = Consumption::findOrFail($id);
        $items = Consumption::where('date', $consumption->date)
            ->where('time', $consumption->time)
            ->get();

        $skus = Sku::all();

        return view('admin.consumption.edit', compact('consumption', 'items', 'skus'));
    }

    public function update(Request $request, $id)
    {

        $consumption = Consumption::findOrFail($id);


        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'total' => 'required|numeric',
            'items' => 'required|array',
            'items.*.sku_id' => 'required|exists:skus,id',
            'items.*.quantity' => 'required|numeric',
            'items.*.unit' => 'required|string'
        ]);
        $consumption->date = $request->input('date');
        $consumption->time = $request->input('time');
        $consumption->total = $request->input('total');

        $consumption->delete();

        // Add new items
        foreach ($request->input('items') as $item) {
            Consumption::create([
                'date' => $consumption->date,
                'time' => $consumption->time,
                'total' => $consumption->total,
                'sku_id' => $item['sku_id'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Consumption updated successfully.'
        ]);
    }




    public function destroy(string $id)
    {
        $consumption = Consumption::findOrFail($id);
        $consumption->delete();

        return response()->json(['status' => 'success', 'message' => 'Consumption data deleted successfully.']);
    }
    public function search(Request $request)
    {
        $query = Consumption::query();

        if ($request->search) {
            $query->where('product_name', 'like', '%' . $request->search . '%');
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('date', [$request->from_date, $request->to_date]);
        }

        $results = $query->orderBy('date', 'desc')->get();

        return response()->json($results);
    }

    public function downloadReport(Request $request)
    {
        try {
            $query = Consumption::with('stock');

            // Apply filters
            if ($request->filled('from_date')) {
                $query->whereDate('date', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('date', '<=', $request->to_date);
            }
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                $query->whereHas('stock', function ($q) use ($searchTerm) {
                    $q->where('product_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $consumptions = $query->orderBy('date', 'desc')
                ->orderBy('time', 'desc')
                ->get();

            $total = $consumptions->sum('quantity');

            // Use our custom helper instead of NumberFormatter
            $totalInWords = NumberToWords::convert($total);

            // Add search criteria to the report
            $searchCriteria = [];
            if ($request->filled('search')) {
                $searchCriteria[] = "Product: " . $request->search;
            }
            if ($request->filled('from_date')) {
                $searchCriteria[] = "From: " . date('d/m/Y', strtotime($request->from_date));
            }
            if ($request->filled('to_date')) {
                $searchCriteria[] = "To: " . date('d/m/Y', strtotime($request->to_date));
            }

            $pdf = PDF::loadView('admin.consumption.report_pdf', [
                'consumptions' => $consumptions,
                'total' => $total,
                'totalInWords' => $totalInWords,
                'reportNumber' => 'CONS-' . now()->format('YmdHis'),
                'date' => now()->format('d/m/Y'),
                'filters' => $request->all(),
                'searchCriteria' => $searchCriteria
            ]);

            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');

            // Add report name based on search criteria
            $fileName = 'consumption_report';
            if ($request->filled('search')) {
                $fileName .= '_' . Str::slug($request->search);
            }
            $fileName .= '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {

            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }
}
