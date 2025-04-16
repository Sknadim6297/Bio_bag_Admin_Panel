<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consumption;
use App\Models\Sku;
use Illuminate\Http\Request;

class ConsumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consumptions = Consumption::with('sku')->paginate(10);
        return view('admin.consumption.index', compact('consumptions'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skus = Sku::all();
        return view('admin.consumption.create', compact('skus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'items' => 'required|array',
            'items.*.sku_id' => 'required|exists:skus,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string',
        ]);

        foreach ($validated['items'] as $item) {
            $existing = Consumption::where('date', $validated['date'])
                ->where('sku_id', $item['sku_id']) // removed time condition
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => $existing->quantity + $item['quantity'],
                    'unit' => $item['unit'],
                    'time' => $validated['time'], // optionally update latest time
                ]);
            } else {
                Consumption::create([
                    'date' => $validated['date'],
                    'time' => $validated['time'],
                    'sku_id' => $item['sku_id'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Consumption data saved successfully.']);
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
}
