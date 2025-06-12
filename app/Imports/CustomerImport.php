<?php

namespace App\Imports;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $customer = new Customer();
            $customer->customer_name = $row['customer_name'];
            $customer->customer_code = 'CUS' . rand(1000, 9999);
            $customer->mobile_number = $row['mobile_number'];
            $customer->address = $row['address'];
            $customer->payment_terms = $row['payment_terms'] ?? 'Net 30';
            $customer->gstin = $row['gstin'] ?? '';
            $customer->pan_number = $row['pan_number'] ?? '';
            $customer->bank_name = $row['bank_name'] ?? '';
            $customer->branch_name = $row['branch_name'] ?? '';
            $customer->account_number = $row['account_number'] ?? '';
            $customer->ifsc_code = $row['ifsc_code'] ?? '';
            $customer->status = $row['status'] ?? 'active';
            $customer->save();
        }
    }

    public function rules(): array
    {
        return [
            '*.customer_name' => ['required', 'string', 'max:255'],
            '*.mobile_number' => ['required', 'digits:10'],
            '*.address' => ['required', 'string'],
            '*.payment_terms' => ['nullable', 'string'],
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