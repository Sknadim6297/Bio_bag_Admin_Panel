<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $customers = Customer::paginate(10);
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
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_number' => 'required|digits_between:9,18',
            'ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
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
        $customer->bank_name = $validated['bank_name'];
        $customer->branch_name = $validated['branch_name'];
        $customer->account_number = $validated['account_number'];
        $customer->ifsc_code = $validated['ifsc_code'];
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
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_number' => 'required|digits_between:9,18',
            'ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
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
        $customer->bank_name = $validated['bank_name'];
        $customer->branch_name = $validated['branch_name'];
        $customer->account_number = $validated['account_number'];
        $customer->ifsc_code = $validated['ifsc_code'];
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
        $customers = Customer::where('customer_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('customer_code', 'like', '%' . $searchTerm . '%')
            ->paginate(10);

        return view('admin.customer.index', compact('customers'));
    }
}
