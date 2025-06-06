<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // VendorController.php

    public function index(Request $request)
    {
        $vendors = Vendor::paginate(10);
        $totalVendors = Vendor::count();


        return view('admin.vendor.index', compact('vendors', 'totalVendors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'address' => 'required|string',
            'payment_terms' => 'required|string',
            'category_of_supply' => 'required|string',
            'gstin' => 'required|string|unique:vendors,gstin',
            'pan_number' => 'required|string|unique:vendors,pan_number',
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_number' => 'required|digits_between:9,18',
            'ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'active' => 'active|in:active,inactive',
        ]);

        $vendor_code = 'VEN' . rand(1000, 9999);
        $lead_time = Carbon::now();
        // Create a new vendor
        $vendor = new Vendor();
        $vendor->vendor_name = $validated['vendor_name'];
        $vendor->vendor_code = $vendor_code;
        $vendor->mobile_number = $validated['mobile_number'];
        $vendor->address = $validated['address'];
        $vendor->payment_terms = $validated['payment_terms'];
        $vendor->lead_time = $lead_time;
        $vendor->category_of_supply = $validated['category_of_supply'];
        $vendor->gstin = $validated['gstin'];
        $vendor->pan_number = $validated['pan_number'];
        $vendor->bank_name = $validated['bank_name'];
        $vendor->branch_name = $validated['branch_name'];
        $vendor->account_number = $validated['account_number'];
        $vendor->ifsc_code = $validated['ifsc_code'];
        $vendor->status = $validated['status'] ?? 'active';
        $vendor->save();

        return response()->json(['success' => true, 'message' => 'Vendor added successfully!']);
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
        $vendor = Vendor::findOrFail($id);
        return view('admin.vendor.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vendor = Vendor::findOrFail($id);
        $validated = $request->validate([
            'vendor_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'address' => 'required|string',
            'payment_terms' => 'required|string',
            'category_of_supply' => 'required|string',
            'gstin' => [
                'required',
                'string',
                'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                Rule::unique('vendors', 'gstin')->ignore($vendor->id),
            ],
            'pan_number' => [
                'required',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                Rule::unique('vendors', 'pan_number')->ignore($vendor->id),
            ],
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_number' => 'required|digits_between:9,18',
            'ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'status' => 'required|in:active,inactive',
        ]);

        $vendor->vendor_name = $validated['vendor_name'];
        $vendor->mobile_number = $validated['mobile_number'];
        $vendor->address = $validated['address'];
        $vendor->payment_terms = $validated['payment_terms'];
        $vendor->category_of_supply = $validated['category_of_supply'];
        $vendor->gstin = $validated['gstin'];
        $vendor->pan_number = $validated['pan_number'];
        $vendor->bank_name = $validated['bank_name'];
        $vendor->branch_name = $validated['branch_name'];
        $vendor->account_number = $validated['account_number'];
        $vendor->ifsc_code = $validated['ifsc_code'];
        $vendor->status = $validated['status'];

        $vendor->save();

        return response()->json(['success' => true, 'message' => 'Vendor updated successfully!']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        if ($vendor->delete()) {
            return response()->json(['success' => true, 'message' => 'Vendor deleted successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Error occurred while deleting vendor.']);
    }
    public function search(Request $request)
    {
        $search = $request->input('search');

        $vendors = Vendor::where('vendor_name', 'like', "%{$search}%")
            ->orWhere('vendor_code', 'like', "%{$search}%")
            ->get();

        $data = $vendors->map(function ($vendor) {
            return [
                'vendor_name' => $vendor->vendor_name,
                'vendor_code' => $vendor->vendor_code,
                'mobile_number' => $vendor->mobile_number,
                'payment_terms' => $vendor->payment_terms,
                'address' => $vendor->address,
                'status' => $vendor->status,
                'edit_url' => route('admin.vendors.edit', $vendor->id),
                'delete_url' => route('admin.vendors.destroy', $vendor->id),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
