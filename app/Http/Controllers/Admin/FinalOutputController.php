<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\FinalOutput;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\NumberToWords;
use Illuminate\Support\Str;

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

    public function downloadInvoice(Request $request)
    {
        try {
            $query = FinalOutput::with('customer');
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                // Changed to match exact micron number
                if (is_numeric($searchTerm)) {
                    $query->where('micron', $searchTerm);
                } else {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->whereHas('customer', function ($subQ) use ($searchTerm) {
                            $subQ->where('customer_name', 'like', '%' . $searchTerm . '%');
                        })
                            ->orWhere('size', 'like', '%' . $searchTerm . '%');
                    });
                }
            }

            // Apply other filters
            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->filled('micron')) {
                $query->where('micron', $request->micron);
            }

            if ($request->filled('size')) {
                $query->where('size', $request->size);
            }

            if ($request->filled('from_date')) {
                $query->whereDate('final_output_datetime', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('final_output_datetime', '<=', $request->to_date);
            }



            $finalOutputs = $query->orderBy('final_output_datetime', 'desc')->get();


            if ($finalOutputs->isEmpty()) {
                return back()->with('error', 'No data found for the selected criteria.');
            }

            $total = $finalOutputs->sum('quantity');
            $totalInWords = NumberToWords::convert($total);

            // Build search criteria description
            $searchCriteria = [];
            if ($request->filled('search')) {
                $searchCriteria[] = "Search Term: " . $request->search;
            }
            if ($request->filled('customer_id')) {
                $customer = Customer::find($request->customer_id);
                $searchCriteria[] = "Customer: " . ($customer ? $customer->customer_name : 'N/A');
            }
            if ($request->filled('size')) {
                $searchCriteria[] = "Size: " . $request->size;
            }
            if ($request->filled('micron')) {
                $searchCriteria[] = "Micron: " . $request->micron;
            }
            if ($request->filled('from_date')) {
                $searchCriteria[] = "From: " . date('d/m/Y', strtotime($request->from_date));
            }
            if ($request->filled('to_date')) {
                $searchCriteria[] = "To: " . date('d/m/Y', strtotime($request->to_date));
            }

            $pdf = PDF::loadView('admin.final.invoice_pdf', [
                'finalOutputs' => $finalOutputs,
                'total' => $total,
                'totalInWords' => $totalInWords,
                'reportNumber' => 'INV-' . now()->format('YmdHis'),
                'date' => now()->format('d/m/Y'),
                'filters' => $request->all(),
                'searchCriteria' => $searchCriteria,
                'searchTerm' => $request->search ?? ''
            ]);

            $pdf->setPaper('A4', 'portrait');

            // Generate filename with search terms
            $fileName = 'final_output_invoice';
            if ($request->filled('search')) {
                $fileName .= '_' . Str::slug($request->search);
            }
            if ($request->filled('from_date')) {
                $fileName .= '_from_' . $request->from_date;
            }
            if ($request->filled('to_date')) {
                $fileName .= '_to_' . $request->to_date;
            }
            $fileName .= '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    public function viewReport(Request $request)
    {
        $query = FinalOutput::with('customer');

        if ($request->filled('from_date')) {
            $query->whereDate('final_output_datetime', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('final_output_datetime', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('customer', function ($q) use ($searchTerm) {
                $q->where('customer_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $finalOutputs = $query->orderBy('final_output_datetime', 'desc')->get();
        $total = $finalOutputs->sum('quantity');

        return view('admin.final.invoice_view', [
            'finalOutputs' => $finalOutputs,
            'total' => $total,
            'reportNumber' => 'FO-' . now()->format('YmdHis'),
            'date' => now()->format('d/m/Y'),
            'filters' => $request->all()
        ]);
    }
}
