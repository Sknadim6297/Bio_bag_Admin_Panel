<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FinalOutput;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with('customer')->latest()->get();
        return view('admin.inventory.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('status', 1)->get();
        return view('admin.inventory.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'micron' => 'nullable|string',
            'size' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'hsn' => 'nullable|string',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $inventory = Inventory::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Inventory record created successfully.',
            'inventory' => $inventory
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        $customers = Customer::where('status', 1)->get();
        return view('admin.inventory.edit', compact('inventory', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'micron' => 'nullable|string',
            'size' => 'nullable|string',
            'quantity' => 'nullable|numeric',
            'hsn' => 'nullable|string',
            'description' => 'nullable|string',
            'price_per_kg' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $inventory->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Inventory record updated successfully.',
            'inventory' => $inventory
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json([
            'status' => true,
            'message' => 'Inventory record deleted successfully.'
        ]);
    }

    /**
     * Fetch final output data for a specific customer.
     */
    public function fetchFinalOutput($customerId)
    {
        // Get latest data for this customer
        $finalOutputData = FinalOutput::where('customer_id', $customerId)
            ->select(
                'micron',
                'size',
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy('micron', 'size')
            ->orderBy('final_output_datetime', 'desc')
            ->first();

        if (!$finalOutputData) {
            return response()->json([
                'status' => false,
                'message' => 'No final output data found for this customer.'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'micron' => $finalOutputData->micron,
                'size' => $finalOutputData->size,
                'quantity' => $finalOutputData->total_quantity
            ]
        ]);
    }
}