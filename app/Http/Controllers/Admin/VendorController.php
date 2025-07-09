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
    public function index(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Vendor index request:', [
            'search' => $request->input('search'),
            'per_page' => $request->input('per_page', 10),
            'page' => $request->input('page', 1),
            'is_ajax' => $request->input('is_ajax'),
            'ajax' => $request->ajax()
        ]);
        
        $perPage = $request->input('per_page', 10);
        $searchTerm = $request->input('search');
        
        $query = Vendor::query();
        
        // Handle search if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vendor_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('vendor_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('mobile_number', 'like', '%' . $searchTerm . '%')
                  ->orWhere('payment_terms', 'like', '%' . $searchTerm . '%')
                  ->orWhere('address', 'like', '%' . $searchTerm . '%')
                  ->orWhere('status', 'like', '%' . $searchTerm . '%');
            });
        }
        
        $vendors = $query->paginate($perPage);
        $vendors->appends($request->all());

        // Handle AJAX requests for search/filter/pagination
        if ($request->ajax() || $request->input('is_ajax')) {
            try {
                $response = $this->createVendorTableResponse($vendors, $request);
                return response()->json($response);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error generating vendor search response: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your request',
                    'total' => 0
                ], 500);
            }
        }

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
        $rules = [
            'vendor_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'address' => 'required|string',
            'payment_terms' => 'required|string',
            'category_of_supply' => 'required|string',
            'pan_number' => 'required|string|unique:vendors,pan_number',
            'bank_name' => 'required|string',
            'branch_name' => 'required|string',
            'account_number' => 'required|digits_between:9,18',
            'ifsc_code' => 'required|string|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
            'status' => 'required|in:active,inactive',
        ];

        // Only require GSTIN if no_gst is NOT checked
        if (!$request->has('no_gst')) {
            $rules['gstin'] = 'required|string|unique:vendors,gstin';
        } else {
            $rules['gstin'] = 'nullable|string|unique:vendors,gstin';
        }

        $validated = $request->validate($rules);

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
        $vendor->gstin = $validated['gstin'] ?? null;
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

    /**
     * Create a consistent response for vendor table AJAX requests
     */
    private function createVendorTableResponse($vendors, $request)
    {
        $vendorsHtml = '';
        foreach ($vendors as $index => $vendor) {
            $slNo = ($vendors->currentPage() - 1) * $vendors->perPage() + $index + 1;
            $statusBadge = $vendor->status == 'active' ? 'bg-success' : 'bg-secondary';
            
            $vendorsHtml .= '<tr>';
            $vendorsHtml .= '<td>' . $slNo . '</td>';
            $vendorsHtml .= '<td>' . htmlspecialchars($vendor->vendor_name) . '</td>';
            $vendorsHtml .= '<td>' . htmlspecialchars($vendor->vendor_code) . '</td>';
            $vendorsHtml .= '<td>' . htmlspecialchars($vendor->mobile_number) . '</td>';
            $vendorsHtml .= '<td>' . htmlspecialchars($vendor->payment_terms) . '</td>';
            $vendorsHtml .= '<td>' . htmlspecialchars($vendor->address) . '</td>';
            $vendorsHtml .= '<td><span class="badge ' . $statusBadge . '">' . ucfirst($vendor->status) . '</span></td>';
            $vendorsHtml .= '<td>';
            $vendorsHtml .= '<div class="action-buttons">';
            $vendorsHtml .= '<a href="' . route('admin.vendors.edit', $vendor->id) . '" class="btn btn-sm btn-primary me-1" title="Edit">';
            $vendorsHtml .= '<i class="fas fa-edit"></i><span class="action-text">Edit</span></a>';
            $vendorsHtml .= '<a href="javascript:void(0);" class="btn btn-sm btn-danger delete-item" data-url="' . route('admin.vendors.destroy', $vendor->id) . '" title="Delete">';
            $vendorsHtml .= '<i class="fas fa-trash-alt"></i><span class="action-text">Delete</span></a>';
            $vendorsHtml .= '</div>';
            $vendorsHtml .= '</td>';
            $vendorsHtml .= '</tr>';
        }

        // Generate pagination HTML
        $paginationHtml = '';
        if ($vendors->hasPages()) {
            $paginationHtml = '<div class="pagination-container"><nav aria-label="Pagination Navigation" role="navigation">';
            $paginationHtml .= '<ul class="pagination">';
            
            $currentPage = $vendors->currentPage();
            $lastPage = $vendors->lastPage();
            
            // Previous Page Link
            if ($currentPage <= 1) {
                $paginationHtml .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">‹</span></li>';
            } else {
                $prevUrl = $vendors->url($currentPage - 1);
                $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $prevUrl . '" rel="prev">‹</a></li>';
            }
            
            // Smart pagination logic
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
            
            // Show first page if not in range
            if ($start > 1) {
                $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $vendors->url(1) . '">1</a></li>';
                if ($start > 2) {
                    $paginationHtml .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
            }
            
            // Show page numbers in range
            for ($page = $start; $page <= $end; $page++) {
                if ($page == $currentPage) {
                    $paginationHtml .= '<li class="page-item active" aria-current="page"><span class="page-link">' . $page . '</span></li>';
                } else {
                    $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $vendors->url($page) . '">' . $page . '</a></li>';
                }
            }
            
            // Show last page if not in range
            if ($end < $lastPage) {
                if ($end < $lastPage - 1) {
                    $paginationHtml .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
                $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $vendors->url($lastPage) . '">' . $lastPage . '</a></li>';
            }
            
            // Next Page Link
            if ($currentPage >= $lastPage) {
                $paginationHtml .= '<li class="page-item disabled" aria-disabled="true"><span class="page-link">›</span></li>';
            } else {
                $nextUrl = $vendors->url($currentPage + 1);
                $paginationHtml .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '" rel="next">›</a></li>';
            }
            
            $paginationHtml .= '</ul>';
            $paginationHtml .= '</nav></div>';
        }

        // Calculate showing information
        $from = ($vendors->currentPage() - 1) * $vendors->perPage() + 1;
        $to = min($vendors->currentPage() * $vendors->perPage(), $vendors->total());
        $showingInfo = "Showing {$from} to {$to} of {$vendors->total()} entries";

        $response = [
            'vendors_html' => $vendorsHtml,
            'pagination_html' => $paginationHtml,
            'showing_info' => $showingInfo,
            'total' => $vendors->total()
        ];
        
        // Add debug information if requested
        if ($request->has('debug')) {
            $response['debug'] = [
                'request' => [
                    'search' => $request->input('search'),
                    'per_page' => $vendors->perPage(),
                    'page' => $vendors->currentPage(),
                ],
                'pagination' => [
                    'current_page' => $vendors->currentPage(),
                    'last_page' => $vendors->lastPage(),
                    'per_page' => $vendors->perPage(),
                    'total' => $vendors->total(),
                ],
                'timestamp' => now()->toDateTimeString()
            ];
            
            \Illuminate\Support\Facades\Log::info('Vendor response created', $response);
        }
        
        return $response;
    }
}
