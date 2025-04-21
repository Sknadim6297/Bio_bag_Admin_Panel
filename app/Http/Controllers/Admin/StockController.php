<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StockExport;
use App\Http\Controllers\Controller;
use App\Models\Consumption;
use App\Models\PurchaseOrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::orderBy('id', 'desc')->get();
        return view('admin.stock.index', compact('stocks'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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

    public function getStockMovement($id)
    {
        $stock = Stock::findOrFail($id);
        $productName = $stock->product_name;
        $productId = $stock->id;

        // Get purchase and consumption data
        $purchaseData = PurchaseOrderItem::select('quantity', 'created_at')
            ->where('product_name', $productName)
            ->whereNotNull('created_at')
            ->get();

        $consumptionData = Consumption::select('quantity', 'created_at')
            ->where('stock_id', $productId)
            ->whereNotNull('created_at')
            ->get();

        $transactions = [];

        // Format purchase transactions
        foreach ($purchaseData as $purchase) {
            $transactions[] = [
                'date' => $purchase->created_at,
                'type' => 'Purchase',
                'quantity' => (float) $purchase->quantity
            ];
        }

        // Format consumption transactions
        foreach ($consumptionData as $consumption) {
            $transactions[] = [
                'date' => $consumption->created_at,
                'type' => 'Consumption',
                'quantity' => -(float) $consumption->quantity // negative value for consumption
            ];
        }

        // Sort transactions by date
        usort($transactions, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        // Calculate running balance
        $balance = 0;
        foreach ($transactions as &$t) {
            $balance += $t['quantity'];
            $t['balance'] = $balance;
        }

        return response()->json([
            'status' => true,
            'product_name' => $productName,
            'transactions' => $transactions,
            'available_stock' => $balance
        ]);
    }






    public function search(Request $request)
    {
        $search = $request->search;

        $stocks = Stock::where('product_name', 'LIKE', "%{$search}%")
            ->get()
            ->map(function ($item, $index) {
                return [
                    'sl' => $index + 1,
                    'product_name' => $item->product_name,
                    'stock' => ucfirst(str_replace('_', ' ', $item->stock)),
                    'quantity' => number_format($item->quantity),
                    'id' => $item->id,
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $stocks
        ]);
    }

    public function export()
    {
        return Excel::download(new StockExport, 'stocks.xlsx');
    }
}
