<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Production;
use Illuminate\Http\Request;

class ProductionController extends Controller
{

    public function index()
    {
        $productions = Production::with('customer')->paginate(10);
        return view('admin.production.index', compact('productions'));
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
    public function filter(Request $request)
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

        $data = $query->orderBy('production_datetime', 'desc')->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }
}
