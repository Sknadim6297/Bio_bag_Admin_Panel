<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VendorImport;

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

    /**
     * Import vendors from Excel file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new VendorImport, $request->file('file'));

            return response()->json([
                'success' => true,
                'message' => 'Vendors imported successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error importing vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export template for vendor import
     */
    public function exportTemplate()
    {
        // Create a simple spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add header row
        $sheet->setCellValue('A1', 'vendor_name');
        $sheet->setCellValue('B1', 'mobile_number');
        $sheet->setCellValue('C1', 'address');
        $sheet->setCellValue('D1', 'payment_terms');
        $sheet->setCellValue('E1', 'category_of_supply');
        $sheet->setCellValue('F1', 'gstin');
        $sheet->setCellValue('G1', 'pan_number');
        $sheet->setCellValue('H1', 'bank_name');
        $sheet->setCellValue('I1', 'branch_name');
        $sheet->setCellValue('J1', 'account_number');
        $sheet->setCellValue('K1', 'ifsc_code');
        $sheet->setCellValue('L1', 'status');
        
        // Add example data row
        $sheet->setCellValue('A2', 'Example Vendor');
        $sheet->setCellValue('B2', '1234567890');
        $sheet->setCellValue('C2', '123 Main St, City');
        $sheet->setCellValue('D2', 'Net 30');
        $sheet->setCellValue('E2', 'raw_materials');
        $sheet->setCellValue('F2', '22AAAAA0000A1Z5');
        $sheet->setCellValue('G2', 'AAAAA1234A');
        $sheet->setCellValue('H2', 'HDFC Bank');
        $sheet->setCellValue('I2', 'Main Branch');
        $sheet->setCellValue('J2', '123456789012');
        $sheet->setCellValue('K2', 'HDFC0123456');
        $sheet->setCellValue('L2', 'active');
        
        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Save to output
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        
        // Return response
        return response($content)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="vendor_import_template.xlsx"')
            ->header('Content-Length', strlen($content))
            ->header('Cache-Control', 'max-age=0');
    }
}
