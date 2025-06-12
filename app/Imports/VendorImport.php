<?php

namespace App\Imports;

use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VendorImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $vendor = new Vendor();
            $vendor->vendor_name = $row['vendor_name'];
            $vendor->vendor_code = 'VEN' . rand(1000, 9999);
            $vendor->mobile_number = $row['mobile_number'];
            $vendor->address = $row['address'];
            $vendor->payment_terms = $row['payment_terms'] ?? 'Net 30';
            $vendor->lead_time = Carbon::now();
            $vendor->category_of_supply = $row['category_of_supply'] ?? 'raw_materials';
            $vendor->gstin = $row['gstin'] ?? '';
            $vendor->pan_number = $row['pan_number'] ?? '';
            $vendor->bank_name = $row['bank_name'] ?? '';
            $vendor->branch_name = $row['branch_name'] ?? '';
            $vendor->account_number = $row['account_number'] ?? '';
            $vendor->ifsc_code = $row['ifsc_code'] ?? '';
            $vendor->status = $row['status'] ?? 'active';
            $vendor->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.vendor_name' => ['required', 'string', 'max:255'],
            '*.mobile_number' => ['required', 'digits:10'],
            '*.address' => ['required', 'string'],
            '*.payment_terms' => ['nullable', 'string'],
            '*.category_of_supply' => ['nullable', 'string'],
            '*.gstin' => ['nullable', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            '*.pan_number' => ['nullable', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            '*.bank_name' => ['nullable', 'string'],
            '*.branch_name' => ['nullable', 'string'],
            '*.account_number' => ['nullable', 'digits_between:9,18'],
            '*.ifsc_code' => ['nullable', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            '*.status' => ['nullable', 'in:active,inactive'],
        ];
    }
}