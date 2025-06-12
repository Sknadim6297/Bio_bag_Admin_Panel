<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CustomerImport;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::paginate(10);

        if ($request->ajax()) {
            $view = view('admin.customer.index', compact('customers'))->render();
            return response()->json(['table' => $view]);
        }

        return view('admin.customer.index', compact('customers'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = [
            'customer_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'address' => 'required|string',
            'payment_terms' => 'required|string',
            'gstin' => 'required|string|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|unique:customers,gstin',
            'pan_number' => 'required|string|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/|unique:customers,pan_number',
            'status' => 'required|in:active,inactive',
        ];
        $validated = $request->validate($validation);
        $customer_code = 'CUS' . rand(1000, 9999);
        // Create a new customer
        $customer = new Customer();
        $customer->customer_name = $validated['customer_name'];
        $customer->customer_code = $customer_code;
        $customer->mobile_number = $validated['mobile_number'];
        $customer->address = $validated['address'];
        $customer->payment_terms = $validated['payment_terms'];
        $customer->gstin = $validated['gstin'];
        $customer->pan_number = $validated['pan_number'];
        $customer->status = $validated['status'];
        $customer->save();

        return response()->json([
            'status' => true,
            'message' => 'Customer created successfully.',
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
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $customer = Customer::findOrFail($id);

        // Validation rules
        $validation = [
            'customer_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'address' => 'required|string',
            'payment_terms' => 'required|string',
            'gstin' => [
                'required',
                'string',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('customers', 'gstin')->ignore($customer->id),
            ],
            'pan_number' => [
                'required',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                Rule::unique('customers', 'pan_number')->ignore($customer->id),
            ],
            'status' => 'required|in:active,inactive',
        ];

        // Validate the incoming data
        $validated = $request->validate($validation);

        // Update the existing customer data
        $customer->customer_name = $validated['customer_name'];
        $customer->mobile_number = $validated['mobile_number'];
        $customer->address = $validated['address'];
        $customer->payment_terms = $validated['payment_terms'];
        $customer->gstin = $validated['gstin'];
        $customer->pan_number = $validated['pan_number'];
        $customer->status = $validated['status'];
        $customer->save();

        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $vendor = Customer::findOrFail($id);

        if ($vendor->delete()) {
            return response()->json(['success' => true, 'message' => 'Vendor deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Error occurred while deleting vendor.']);
    }


    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $query = Customer::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('customer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customer_code', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mobile_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('payment_terms', 'like', '%' . $searchTerm . '%')
                    ->orWhere('address', 'like', '%' . $searchTerm . '%')
                    ->orWhere('status', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $customers = $query->paginate(10);

        if ($request->ajax()) {
            $tableView = view('admin.customer.index', compact('customers'))->render();
            $paginationView = View::make('pagination::bootstrap-5', ['paginator' => $customers])->render();

            return response()->json([
                'table' => $tableView,
                'pagination' => $paginationView,
            ]);
        }

        return view('admin.customer.index', compact('customers'));
    }

    /**
     * Import customers from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new CustomerImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Customers imported successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing customers: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export template for customer import
     */
    public function exportTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="customer_import_template.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add header row
            fputcsv($file, [
                'customer_name', 
                'mobile_number', 
                'address', 
                'payment_terms', 
                'gstin', 
                'pan_number', 
                'bank_name', 
                'branch_name', 
                'account_number', 
                'ifsc_code', 
                'status'
            ]);
            
            // Add example row
            fputcsv($file, [
                'Example Customer', 
                '9876543210', 
                '123 Main St, City', 
                'Net 30', 
                '22AAAAA0000A1Z5', 
                'AAAAA1234A', 
                'HDFC Bank', 
                'Main Branch', 
                '123456789012', 
                'HDFC0123456', 
                'active'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
