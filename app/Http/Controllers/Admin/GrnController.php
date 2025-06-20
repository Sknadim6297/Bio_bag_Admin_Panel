<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderAttachment;
use App\Models\PurchaseOrderItem;
use App\Models\Sku;
use App\Models\Stock;
use App\Models\Vendor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GrnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['vendor', 'items'])->latest();

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('po_number', 'like', '%' . $request->search . '%')
                    ->orWhere('vendor_name', 'like', '%' . $request->search . '%')
                    ->orWhere('deliver_to_location', 'like', '%' . $request->search . '%');
            });
        }

        // Date range filter
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('po_date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('po_date', '<=', $request->to_date);
        }

        // Paginate the results
        $purchaseOrders = $query->paginate($request->entries ?? 10);

        if ($request->ajax()) {
            $data = view('admin.grn.index', compact('purchaseOrders'))->render();
            $pagination = view('pagination::bootstrap-5', ['paginator' => $purchaseOrders])->render();

            return response()->json([
                'data' => $data,
                'pagination' => $pagination
            ]);
        }

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
        // Step 1: Validate the request
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
            'products.*' => 'required|string|max:255',
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

        DB::beginTransaction();

        try {
            // Step 2: Generate Unique PO Number
            $year = now()->year;
            $latestPo = PurchaseOrder::whereYear('po_date', $year)->orderBy('id', 'desc')->first();
            $poNumber = 'PO-' . $year . '-001';

            if ($latestPo) {
                $lastSequence = (int) substr($latestPo->po_number, -3);
                $poNumber = 'PO-' . $year . '-' . str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);
            }

            // Step 3: Create Purchase Order
            $po = PurchaseOrder::create([
                'po_date' => $request->po_date,
                'po_number' => $poNumber,
                'vendor_id' => $request->vendor_id,
                'deliver_to_type' => $request->deliver_to_type,
                'deliver_to_location' => $request->deliver_to_location,
                'deliver_address' => $request->deliver_address,
                'expected_delivery' => $request->expected_delivery,
                'payment_terms' => $request->payment_terms,
                'subtotal' => $request->subtotal,
                'cgst' => $request->cgst,
                'sgst' => $request->sgst,
                'igst' => $request->igst,
                'cess' => $request->cess,
                'discount' => $request->discount,
                'total' => $request->total,
                'terms' => $request->terms,
                'notes' => $request->notes,
                'reference' => $request->reference,
            ]);

            // Step 4: Save each product item & update stock
            foreach ($request->products as $index => $productName) {
                $description = $request->descriptions[$index];
                $quantity = $request->quantities[$index];
                $unit_price = $request->unit_prices[$index];
                $total = $request->totals[$index];
                $measurement = $request->measurements[$index];

                // Normalize product name and measurement
                $normalizedName = Str::of($productName)->lower()->trim()->squish();
                $normalizedMeasurement = Str::of($measurement)->lower()->trim();

                // Save item
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name' => ucfirst($normalizedName),
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'total' => $total,
                    'measurement' => $normalizedMeasurement,
                ]);

                // Update or Create Stock
                $stock = Stock::whereRaw('LOWER(TRIM(product_name)) = ?', [$normalizedName])
                    ->whereRaw('LOWER(TRIM(measurement)) = ?', [$normalizedMeasurement])
                    ->first();

                if ($stock) {
                    $stock->quantity += $quantity;
                    $stock->stock = 'in_stock';
                    $stock->save();
                } else {
                    Stock::create([
                        'product_name' => ucfirst($normalizedName),
                        'measurement' => $normalizedMeasurement,
                        'quantity' => $quantity,
                        'stock' => 'in_stock',
                    ]);
                }
            }

            // Step 5: Handle Attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    PurchaseOrderAttachment::create([
                        'purchase_order_id' => $po->id,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Purchase Order created and stock updated successfully!',
                'data' => $po
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($po_number)
    {
        $purchaseOrder = PurchaseOrder::with(['vendor', 'items.sku'])
            ->where('po_number', $po_number)
            ->first();

        if (!$purchaseOrder) {
            return response()->json([
                'status' => false,
                'message' => 'Purchase Order not found'
            ]);
        }
        $grnNumber = 'GRN-' . date('Y') . '-' . str_pad($purchaseOrder->id, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => true,
            'data' => [
                'grn_number' => $grnNumber,
                'po_number' => $purchaseOrder->po_number,
                'po_date' => $purchaseOrder->po_date,
                'vendor_name' => $purchaseOrder->vendor->vendor_name,
                'vendor_address' => $purchaseOrder->vendor->address,
                'items' => $purchaseOrder->items->map(function ($item) {
                    return [
                        'sku' => $item->sku->product_name ?? '',
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total
                    ];
                }),
                'subtotal' => $purchaseOrder->subtotal,
                'tax' => $purchaseOrder->tax,
                'discount' => $purchaseOrder->discount,
                'total' => $purchaseOrder->total,
            ]
        ]);
    }

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
        $validator = Validator::make($request->all(), [
            'po_date' => 'required|date',
            'vendor_id' => 'required|exists:vendors,id',
            'deliver_to_type' => 'required|in:customer,organization',
            'deliver_to_location' => 'required|string',
            'deliver_address' => 'required|string',
            'expected_delivery' => 'required|date',
            'payment_terms' => 'required|string',
            'products' => 'required|array',
            'products.*' => 'required|string|max:255',
            'descriptions' => 'required|array',
            'quantities' => 'required|array',
            'quantities.*' => 'required|numeric',
            'unit_prices' => 'required|array',
            'unit_prices.*' => 'required|numeric',
            'totals' => 'required|array',
            'totals.*' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::findOrFail($id);
            $po->update([
                'po_date' => $request->po_date,
                'vendor_id' => $request->vendor_id,
                'deliver_to_type' => $request->deliver_to_type,
                'deliver_to_location' => $request->deliver_to_location,
                'deliver_address' => $request->deliver_address,
                'expected_delivery' => $request->expected_delivery,
                'payment_terms' => $request->payment_terms,
                'subtotal' => $request->subtotal,
                'cgst' => $request->cgst,
                'sgst' => $request->sgst,
                'igst' => $request->igst,
                'cess' => $request->cess,
                'discount' => $request->discount,
                'total' => $request->total,
                'terms' => $request->terms,
                'notes' => $request->notes,
                'reference' => $request->reference,
            ]);

            // Remove old items
            $po->items()->delete();
            // Add new items
            foreach ($request->products as $index => $productName) {
                $description = $request->descriptions[$index];
                $quantity = $request->quantities[$index];
                $unit_price = $request->unit_prices[$index];
                $total = $request->totals[$index];
                $measurement = $request->measurements[$index] ?? null;
                $normalizedName = Str::of($productName)->lower()->trim()->squish();
                $normalizedMeasurement = $measurement ? Str::of($measurement)->lower()->trim() : null;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_name' => ucfirst($normalizedName),
                    'description' => $description,
                    'quantity' => $quantity,
                    'unit_price' => $unit_price,
                    'total' => $total,
                    'measurement' => $normalizedMeasurement,
                ]);
            }

            // Handle new attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments', 'public');
                    PurchaseOrderAttachment::create([
                        'purchase_order_id' => $po->id,
                        'file_path' => $path,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.grn.index')->with('success', 'Purchase Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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


    public function getSuggestions(Request $request)
    {
        $search = $request->get('term');

        $results = PurchaseOrderItem::where('product_name', 'LIKE', "%{$search}%")
            ->pluck('product_name');

        return response()->json($results);
    }

    public function downloadPdf($id)
    {
        try {
            $purchaseOrder = PurchaseOrder::with(['vendor', 'items'])->findOrFail($id);
            
            $pdf = PDF::loadView('admin.grn.pdf', [
                'po' => $purchaseOrder,
                'reportNumber' => 'GRN-' . str_pad($id, 6, '0', STR_PAD_LEFT),
                'date' => now()->format('d/m/Y')
            ]);

            return $pdf->download('grn_' . $purchaseOrder->po_number . '_' . now()->format('Y-m-d') . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }
}
