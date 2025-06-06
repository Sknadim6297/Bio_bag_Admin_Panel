<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FinalOutput;
use App\Models\Production;
use Illuminate\Http\Request;

class FinalOutputController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = \App\Models\FinalOutput::with('customer')
        ->orderBy('final_output_datetime', 'desc');

    if ($request->filled('from_date')) {
        $query->whereDate('final_output_datetime', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('final_output_datetime', '<=', $request->to_date);
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

    if ($request->filled('search')) {
        $searchTerm = trim($request->search);
        $query->whereHas('customer', function ($q) use ($searchTerm) {
            $q->where('customer_name', 'like', '%' . $searchTerm . '%');
        });
    }

    // ✅ Get all records (no pagination)
    $finalOutputs = $query->get();
    $totalOutput = $finalOutputs->sum('quantity');

    if ($request->ajax()) {
        return response()->json([
            'status' => true,
            'data' => $finalOutputs,
            'total' => $totalOutput,
        ]);
    }

    $customers = \App\Models\Customer::select('id', 'customer_name as name')->get();
    $micronList = \App\Models\FinalOutput::distinct()->pluck('micron');
    $sizeList = \App\Models\FinalOutput::distinct()->pluck('size');

    return view('admin.final.index', compact('finalOutputs', 'customers', 'micronList', 'sizeList'));
}








    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $customers = Customer::where('status', 1)->get();
        return view('admin.final.create', compact('customers'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {

        $date = $request->input('output_date');
        $time = $request->input('output_time');
        $finalOutputDateTime = date("Y-m-d H:i:s", strtotime("$date $time"));


        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'size' => 'required|string',
            'micron' => 'required|integer|min:0',
            'quantity' => 'required|numeric|min:0',
        ]);

        FinalOutput::create([
            'customer_id' => $request->customer_id,
            'final_output_datetime' => $finalOutputDateTime,
            'size' => $request->size,
            'micron' => $request->micron,
            'quantity' => $request->quantity,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Final output record created successfully.',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

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

    public function filter(Request $request)
    {
        $query = FinalOutput::query();

        // Apply date filter if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('final_output_datetime', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('final_output_datetime', '<=', $request->to_date);
        }

        // Apply search filter if provided
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                    ->orWhere('size', 'like', '%' . $request->search . '%')
                    ->orWhere('micron', 'like', '%' . $request->search . '%');
            });
        }

        // Fetch data
        $finalOutputs = $query->get();

        return response()->json([
            'status' => true,
            'data' => $finalOutputs
        ]);
    }
}