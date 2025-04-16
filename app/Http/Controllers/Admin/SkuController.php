<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Sku;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skus = Sku::with('category')->latest()->get();
        return view('admin.sku.index', compact('skus'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.sku.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    private function generateSkuCode()
    {
        $latestSku = Sku::latest('id')->first();
        $nextId = $latestSku ? $latestSku->id + 1 : 1;
        return 'SKU' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }
    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'measurement' => 'required|string',
            'msq' => 'required|integer',
            'price' => 'required|numeric',
            'gst' => 'required|numeric',
            'freight' => 'required|numeric',
            'total_price' => 'required|numeric',
            'date_time' => 'required|date',
        ]);

        $sku = new Sku();
        $sku->category_id = $request->category_id;
        $sku->sku_code = $this->generateSkuCode();
        $sku->product_name = $request->product_name;
        $sku->quantity = $request->quantity;
        $sku->measurement = $request->measurement;
        $sku->msq = $request->msq;
        $sku->price = $request->price;
        $sku->gst = $request->gst;
        $sku->freight = $request->freight;
        $sku->total_price = $request->total_price;
        $sku->date_time = $request->date_time;
        $sku->save();

        return response()->json(['success' => true, 'message' => 'SKU added successfully!']);
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
        $sku = Sku::with('category')->findOrFail($id);
        $categories = Category::all();

        return view('admin.sku.edit', compact('sku', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'measurement' => 'required|string',
            'msq' => 'required|integer',
            'price' => 'required|numeric',
            'gst' => 'required|numeric',
            'freight' => 'required|numeric',
            'total_price' => 'required|numeric',
            'date_time' => 'required|date',
        ]);

        $sku = Sku::findOrFail($id);
        $sku->category_id = $request->category_id;
        $sku->product_name = $request->product_name;
        $sku->quantity = $request->quantity;
        $sku->measurement = $request->measurement;
        $sku->msq = $request->msq;
        $sku->price = $request->price;
        $sku->gst = $request->gst;
        $sku->freight = $request->freight;
        $sku->total_price = $request->total_price;
        $sku->date_time = $request->date_time;
        $sku->save();

        return response()->json(['success' => true, 'message' => 'SKU updated successfully!']);
    }

    public function destroy(string $id)
    {
        $sku = Sku::findOrFail($id);
        $sku->delete();

        return response()->json(['success' => true, 'message' => 'SKU deleted successfully!']);
    }
}
