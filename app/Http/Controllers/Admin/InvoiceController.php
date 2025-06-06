<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->get();
        return view('admin.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.invoice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'hsn' => 'nullable|string',
            'description' => 'nullable|string',
            'micron' => 'nullable|string',
            'size' => 'nullable|string',
            'quantity' => 'required|numeric|min:0.01',
            'price_per_kg' => 'required|numeric|min:0.01',
            'total_price' => 'required|numeric|min:0',
            'cgst' => 'nullable|numeric|min:0|max:100',
            'sgst' => 'nullable|numeric|min:0|max:100',
            'igst' => 'nullable|numeric|min:0|max:100',
            'tax_amount' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
            'inventory_item' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Start transaction
            DB::beginTransaction();

            // Check if the inventory_item starts with 'semi_' which indicates semi inventory
            if (strpos($request->inventory_item, 'semi_') === 0) {
                // Extract the real ID from the semi_ prefix
                $semiInventoryId = (int)str_replace('semi_', '', $request->inventory_item);

                // Find the semi inventory record
                $semiInventory = Inventory::findOrFail($semiInventoryId);

                // Check if quantity exceeds available inventory
                if ($request->quantity > $semiInventory->quantity) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['quantity' => ['Quantity exceeds available semi-production inventory.']]
                    ], 422);
                }

                // Create invoice
                $invoice = Invoice::create([
                    'customer_id' => $request->customer_id,
                    'invoice_date' => now(),
                    'invoice_number' => 'INV-' . time(), // You may want to use a better numbering system
                    'hsn' => $request->hsn,
                    'description' => $request->description,
                    'micron' => $request->micron,
                    'size' => $request->size,
                    'quantity' => $request->quantity,
                    'price_per_kg' => $request->price_per_kg,
                    'total_amount' => $request->total_price, // Changed to match frontend field name
                    'cgst' => $request->cgst,
                    'sgst' => $request->sgst,
                    'igst' => $request->igst,
                    'tax_amount' => $request->tax_amount,
                    'final_price' => $request->final_price,
                    'inventory_source' => 'semi_production',
                    'source_id' => $semiInventoryId
                ]);

                // Update semi inventory
                $semiInventory->quantity -= $request->quantity;

                // Delete or save based on remaining quantity
                if ($semiInventory->quantity <= 0) {
                    $semiInventory->delete();
                } else {
                    $semiInventory->save();
                }
            } else {
                // Handle regular inventory
                $inventory = Inventory::find($request->inventory_item);

                // Check if inventory exists and has enough quantity
                if (!$inventory || $request->quantity > $inventory->quantity) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['quantity' => ['Quantity exceeds available inventory.']]
                    ], 422);
                }

                // Create invoice
                $invoice = Invoice::create([
                    'customer_id' => $request->customer_id,
                    'invoice_date' => now(),
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . rand(1000, 9999), // Better invoice numbering
                    'hsn' => $request->hsn,
                    'description' => $request->description,
                    'micron' => $request->micron,
                    'size' => $request->size,
                    'quantity' => $request->quantity,
                    'price_per_kg' => $request->price_per_kg,
                    'total_amount' => $request->total_price, // Changed to match frontend field name
                    'cgst' => $request->cgst,
                    'sgst' => $request->sgst,
                    'igst' => $request->igst,
                    'tax_amount' => $request->tax_amount,
                    'final_price' => $request->final_price,
                    'inventory_source' => 'inventory',
                    'source_id' => $inventory->id
                ]);

                // Update inventory
                $inventory->quantity -= $request->quantity;
                $inventory->save();
            }

            // Commit transaction
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice created successfully.',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to create invoice: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Load the relationships
        $invoice->load(['customer']);

        return view('admin.invoice.show', compact('invoice'));
    }

    /**
     * Get all customers
     */
    public function getCustomers()
    {
        $customers = Customer::select('id', 'customer_name')
            ->orderBy('customer_name')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $customers
        ]);
    }

    /**
     * Get customer details by ID
     */
    public function getCustomerDetails(Request $request)
    {
        $customer = Customer::find($request->customer_id);

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $customer
        ]);
    }

    /**
     * Get unique micron values for a customer from inventory
     */
    public function getMicronValues(Request $request)
    {
        $customerId = $request->customer_id;

        // Get regular inventory microns
        $regularMicrons = Inventory::where('customer_id', $customerId)
            ->where('quantity', '>', 0)
            ->distinct()
            ->pluck('micron')
            ->filter();

        // Add semi-production inventory microns
        // Using a direct DB query for better performance when joining tables
        $semiMicrons = [];

        if (Schema::hasTable('semi_production_inventories') && Schema::hasTable('products')) {
            $semiMicrons = DB::table('semi_production_inventories')
                ->join('products', 'semi_production_inventories.product_id', '=', 'products.id')
                ->where('semi_production_inventories.customer_id', $customerId)
                ->where('semi_production_inventories.quantity', '>', 0)
                ->whereNotNull('products.micron')
                ->distinct()
                ->pluck('products.micron')
                ->filter();
        }

        // Combine and return unique microns
        $combinedMicrons = $regularMicrons->concat($semiMicrons)
            ->unique()
            ->values();

        return response()->json([
            'status' => true,
            'data' => $combinedMicrons
        ]);
    }

    /**
     * Get sizes for a customer and micron
     */
    public function getSizesByMicron(Request $request)
    {
        $customerId = $request->customer_id;
        $micron = $request->micron;

        // Get sizes from regular inventory
        $regularSizes = Inventory::where('customer_id', $customerId)
            ->where('micron', $micron)
            ->where('quantity', '>', 0)
            ->distinct()
            ->pluck('size')
            ->filter();

        // Get sizes from semi-production inventory
        $semiSizes = [];

        if (Schema::hasTable('semi_production_inventories') && Schema::hasTable('products')) {
            $semiSizes = DB::table('semi_production_inventories')
                ->join('products', 'semi_production_inventories.product_id', '=', 'products.id')
                ->where('semi_production_inventories.customer_id', $customerId)
                ->where('products.micron', $micron)
                ->where('semi_production_inventories.quantity', '>', 0)
                ->whereNotNull('products.size')
                ->distinct()
                ->pluck('products.size')
                ->filter();
        }

        // Combine sizes from both sources
        $combinedSizes = $regularSizes->concat($semiSizes)
            ->unique()
            ->values();

        return response()->json([
            'status' => true,
            'data' => $combinedSizes
        ]);
    }

    /**
     * Get inventory items by micron and size
     */
    public function getInventoryItems(Request $request)
    {
        $customerId = $request->customer_id;
        $micron = $request->micron;
        $size = $request->size;

        // Get regular inventory items
        $regularItems = Inventory::with('customer')
            ->where('customer_id', $customerId)
            ->where('micron', $micron)
            ->where('size', $size)
            ->where('quantity', '>', 0)
            ->get();

        // Get semi-production inventory items if the tables exist
        $semiItems = collect();

        if (Schema::hasTable('semi_production_inventories') && Schema::hasTable('products')) {
            $semiItems = Inventory::with('product', 'customer')
                ->where('customer_id', $customerId)
                ->where('quantity', '>', 0)
                ->whereHas('product', function ($query) use ($micron, $size) {
                    $query->where('micron', $micron)
                        ->where('size', $size);
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => 'semi_' . $item->id,
                        'customer_id' => $item->customer_id,
                        'product_id' => $item->product_id,
                        'hsn' => $item->product->hsn ?? '',
                        'description' => $item->product->description ?? '',
                        'micron' => $item->product->micron ?? '',
                        'size' => $item->product->size ?? '',
                        'quantity' => $item->quantity,
                        'price_per_kg' => $item->product->price_per_kg ?? 0,
                        'is_semi_inventory' => true,
                        'customer' => $item->customer
                    ];
                });
        }

        // Combine items from both sources
        $combinedItems = $regularItems->concat($semiItems);

        return response()->json([
            'status' => true,
            'data' => $combinedItems
        ]);
    }
    /**
     * Generate PDF for invoice
     */
    public function downloadPdf($id)
    {
        // Find the invoice with customer relationship
        $invoice = Invoice::with('customer')->findOrFail($id);
        
        // Initialize number to words formatter
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
        
        // Amount in words (final price)
        $amount = round($invoice->final_price);
        $totalInWords = ucfirst($formatter->format($amount)) . ' Rupees Only';
        
        // Tax amount in words
        $taxAmount = round($invoice->tax_amount);
        $taxInWords = ucfirst($formatter->format($taxAmount)) . ' Rupees Only';
        
        // Format date for display
        $formattedDate = \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y');
        
        // Calculate total GST rate
        $gstRate = $invoice->cgst + $invoice->sgst + $invoice->igst;
        
        // Generate PDF using DomPDF
        $data = [
            'invoice' => $invoice,
            'totalInWords' => $totalInWords,
            'taxInWords' => $taxInWords,
            'formattedDate' => $formattedDate,
            'gstRate' => $gstRate
        ];
        
        $pdf = app()->make('dompdf.wrapper');
        $pdf->loadView('admin.invoice.pdf', $data);
        $pdf->setPaper('a4');
        
        // Return the PDF as download with filename based on invoice number
        return $pdf->download('Invoice-' . $invoice->invoice_number . '.pdf');
    }
}
