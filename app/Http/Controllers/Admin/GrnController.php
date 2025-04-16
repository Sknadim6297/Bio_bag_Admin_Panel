<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderAttachment;
use App\Models\PurchaseOrderItem;
use App\Models\Sku;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GrnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('vendor')->latest()->get();
        return view('admin.grn.index', compact('purchaseOrders'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::with('vendor')->get();
        $products = Sku::all();
        $vendors = Vendor::all();

        return view('admin.grn.create', compact('purchaseOrders', 'products', 'vendors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{

    $validator = Validator::make($request->all(), [
        'po_date' => 'required|date',
        'vendor_id' => 'required|exists:vendors,id',
        'deliver_to_type' => 'required|in:customer,organization',
        'deliver_to_location' => 'required|string',
        'deliver_address' => 'required|string',
        'expected_delivery' => 'required|date',
        'payment_terms' => 'required|string',
        'attachments' => 'nullable|array',
        'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:10240',
        'products' => 'required|array',
        'products.*' => 'required|exists:skus,id',
        'descriptions' => 'required|array',
        'quantities' => 'required|array',
        'quantities.*' => 'required|numeric',
        'unit_prices' => 'required|array',
        'unit_prices.*' => 'required|numeric',
        'totals' => 'required|array',
        'totals.*' => 'required|numeric',
        'measurements' => 'required|array',
        'measurements.*' => 'required|in:kg,g,l,pcs',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    try {
        $year = now()->year;
        $latestPo = PurchaseOrder::whereYear('po_date', $year)->orderBy('id', 'desc')->first();
        $poNumber = 'PO-' . $year . '-001';

        if ($latestPo) {
            $lastPoNumber = $latestPo->po_number;
            $lastSequence = (int) substr($lastPoNumber, -3);
            $newSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
            $poNumber = 'PO-' . $year . '-' . $newSequence;
        }

        $po = new PurchaseOrder();
        $po->po_date = $request->po_date;
        $po->po_number = $poNumber;
        $po->vendor_id = $request->vendor_id;
        $po->deliver_to_type = $request->deliver_to_type;
        $po->deliver_to_location = $request->deliver_to_location;
        $po->deliver_address = $request->deliver_address;
        $po->expected_delivery = $request->expected_delivery;
        $po->payment_terms = $request->payment_terms;
        $po->subtotal = $request->subtotal;
        $po->tax = $request->tax;
        $po->discount = $request->discount;
        $po->total = $request->total;
        $po->terms = $request->terms;
        $po->notes = $request->notes;
        $po->reference = $request->reference;
        $po->save();

        foreach ($request->products as $index => $productId) {
            // Check if all required data exists for each product
            if (isset($request->products[$index], $request->descriptions[$index], $request->quantities[$index], $request->unit_prices[$index], $request->totals[$index], $request->measurements[$index])) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'sku_id' => $productId,
                    'description' => $request->descriptions[$index],
                    'quantity' => $request->quantities[$index],
                    'unit_price' => $request->unit_prices[$index],
                    'total' => $request->totals[$index],
                    'measurement' => $request->measurements[$index],
                ]);
            } else {
                return response()->json(['error' => 'Missing required data for product at index ' . $index], 422);
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');
                PurchaseOrderAttachment::create([
                    'purchase_order_id' => $po->id,
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Purchase Order created successfully!',
            'data' => $po
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.',
            'error' => $e->getMessage()
        ], 500);
    }
}





    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $poNumbers = PurchaseOrder::orderBy('po_date', 'desc')->pluck('po_number');
        $purchaseOrder = PurchaseOrder::with('vendor', 'items.sku')->findOrFail($id);
        $products = Sku::all();
        $vendors = Vendor::all();
        $attachments = PurchaseOrderAttachment::where('purchase_order_id', $id)->get();
        return view('admin.grn.edit', compact('poNumbers', 'purchaseOrder', 'products', 'vendors', 'attachments'));
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
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->items()->delete();
        $purchaseOrder->attachments()->delete();
        $purchaseOrder->delete();
        
        return response()->json(['success' => true, 'message' => 'Purchase Order deleted successfully!']);

    }
}
