<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use Illuminate\Support\Str;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'vendor_name' => 'ABC Suppliers Ltd',
                'vendor_code' => 'VEN001',
                'mobile_number' => '9876543210',
                'payment_terms' => 'Net 30',
                'address' => '123 Business Park, Delhi',
                'lead_time' => '7 days',
                'category_of_supply' => 'Raw Materials',
                'gstin' => '22ABCDE1234F1Z5',
                'pan_number' => 'ABCDE1234F',
                'bank_name' => 'HDFC Bank',
                'branch_name' => 'Delhi Main Branch',
                'account_number' => '1234567890',
                'ifsc_code' => 'HDFC0001234',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'XYZ Trading Co',
                'vendor_code' => 'VEN002',
                'mobile_number' => '9876543211',
                'payment_terms' => 'Net 15',
                'address' => '456 Industrial Area, Mumbai',
                'lead_time' => '5 days',
                'category_of_supply' => 'Packaging Materials',
                'gstin' => '27XYZAB1234G1Z5',
                'pan_number' => 'XYZAB1234G',
                'bank_name' => 'ICICI Bank',
                'branch_name' => 'Mumbai Central',
                'account_number' => '2345678901',
                'ifsc_code' => 'ICIC0002345',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Global Chemicals Inc',
                'vendor_code' => 'VEN003',
                'mobile_number' => '9876543212',
                'payment_terms' => 'Cash on Delivery',
                'address' => '789 Chemical Zone, Chennai',
                'lead_time' => '10 days',
                'category_of_supply' => 'Chemicals',
                'gstin' => '33GLBCH1234H1Z5',
                'pan_number' => 'GLBCH1234H',
                'bank_name' => 'SBI',
                'branch_name' => 'Chennai T Nagar',
                'account_number' => '3456789012',
                'ifsc_code' => 'SBIN0003456',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Prime Plastics Pvt Ltd',
                'vendor_code' => 'VEN004',
                'mobile_number' => '9876543213',
                'payment_terms' => 'Net 45',
                'address' => '321 Plastic City, Pune',
                'lead_time' => '14 days',
                'category_of_supply' => 'Plastic Materials',
                'gstin' => '27PRPLS1234I1Z5',
                'pan_number' => 'PRPLS1234I',
                'bank_name' => 'Axis Bank',
                'branch_name' => 'Pune FC Road',
                'account_number' => '4567890123',
                'ifsc_code' => 'UTIB0004567',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Steel Works Limited',
                'vendor_code' => 'VEN005',
                'mobile_number' => '9876543214',
                'payment_terms' => 'Net 30',
                'address' => '654 Steel Complex, Kolkata',
                'lead_time' => '21 days',
                'category_of_supply' => 'Metal Components',
                'gstin' => '19STWRK1234J1Z5',
                'pan_number' => 'STWRK1234J',
                'bank_name' => 'Kotak Bank',
                'branch_name' => 'Kolkata Park Street',
                'account_number' => '5678901234',
                'ifsc_code' => 'KKBK0005678',
                'status' => 'inactive'
            ],
            [
                'vendor_name' => 'Textile Manufacturers',
                'vendor_code' => 'VEN006',
                'mobile_number' => '9876543215',
                'payment_terms' => 'Net 20',
                'address' => '987 Textile Hub, Bangalore',
                'lead_time' => '12 days',
                'category_of_supply' => 'Fabric Materials',
                'gstin' => '29TXTMF1234K1Z5',
                'pan_number' => 'TXTMF1234K',
                'bank_name' => 'HDFC Bank',
                'branch_name' => 'Bangalore MG Road',
                'account_number' => '6789012345',
                'ifsc_code' => 'HDFC0006789',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Electronic Components Co',
                'vendor_code' => 'VEN007',
                'mobile_number' => '9876543216',
                'payment_terms' => 'Cash on Delivery',
                'address' => '147 Electronics Market, Hyderabad',
                'lead_time' => '3 days',
                'category_of_supply' => 'Electronic Parts',
                'gstin' => '36ELCMP1234L1Z5',
                'pan_number' => 'ELCMP1234L',
                'bank_name' => 'ICICI Bank',
                'branch_name' => 'Hyderabad Banjara Hills',
                'account_number' => '7890123456',
                'ifsc_code' => 'ICIC0007890',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Machinery Solutions Ltd',
                'vendor_code' => 'VEN008',
                'mobile_number' => '9876543217',
                'payment_terms' => 'Net 60',
                'address' => '258 Industrial Zone, Ahmedabad',
                'lead_time' => '30 days',
                'category_of_supply' => 'Machinery Parts',
                'gstin' => '24MCHSL1234M1Z5',
                'pan_number' => 'MCHSL1234M',
                'bank_name' => 'SBI',
                'branch_name' => 'Ahmedabad Navrangpura',
                'account_number' => '8901234567',
                'ifsc_code' => 'SBIN0008901',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Food Ingredients Corp',
                'vendor_code' => 'VEN009',
                'mobile_number' => '9876543218',
                'payment_terms' => 'Net 15',
                'address' => '369 Food Park, Lucknow',
                'lead_time' => '7 days',
                'category_of_supply' => 'Food Additives',
                'gstin' => '09FDIGD1234N1Z5',
                'pan_number' => 'FDIGD1234N',
                'bank_name' => 'Axis Bank',
                'branch_name' => 'Lucknow Hazratganj',
                'account_number' => '9012345678',
                'ifsc_code' => 'UTIB0009012',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Rubber Products Inc',
                'vendor_code' => 'VEN010',
                'mobile_number' => '9876543219',
                'payment_terms' => 'Net 30',
                'address' => '741 Rubber Estate, Kochi',
                'lead_time' => '15 days',
                'category_of_supply' => 'Rubber Materials',
                'gstin' => '32RBPRD1234O1Z5',
                'pan_number' => 'RBPRD1234O',
                'bank_name' => 'Kotak Bank',
                'branch_name' => 'Kochi Marine Drive',
                'account_number' => '0123456789',
                'ifsc_code' => 'KKBK0001234',
                'status' => 'inactive'
            ],
            [
                'vendor_name' => 'Paper Mills Pvt Ltd',
                'vendor_code' => 'VEN011',
                'mobile_number' => '9876543220',
                'payment_terms' => 'Cash on Delivery',
                'address' => '852 Paper Valley, Jaipur',
                'lead_time' => '8 days',
                'category_of_supply' => 'Paper Products',
                'gstin' => '08PPRML1234P1Z5',
                'pan_number' => 'PPRML1234P',
                'bank_name' => 'HDFC Bank',
                'branch_name' => 'Jaipur MI Road',
                'account_number' => '1234567891',
                'ifsc_code' => 'HDFC0001235',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Glass Industries Ltd',
                'vendor_code' => 'VEN012',
                'mobile_number' => '9876543221',
                'payment_terms' => 'Net 25',
                'address' => '963 Glass City, Indore',
                'lead_time' => '18 days',
                'category_of_supply' => 'Glass Materials',
                'gstin' => '23GLSID1234Q1Z5',
                'pan_number' => 'GLSID1234Q',
                'bank_name' => 'ICICI Bank',
                'branch_name' => 'Indore Vijay Nagar',
                'account_number' => '2345678902',
                'ifsc_code' => 'ICIC0002346',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Ceramic Works Co',
                'vendor_code' => 'VEN013',
                'mobile_number' => '9876543222',
                'payment_terms' => 'Net 40',
                'address' => '159 Ceramic Hub, Surat',
                'lead_time' => '25 days',
                'category_of_supply' => 'Ceramic Products',
                'gstin' => '24CRMWK1234R1Z5',
                'pan_number' => 'CRMWK1234R',
                'bank_name' => 'SBI',
                'branch_name' => 'Surat Ring Road',
                'account_number' => '3456789013',
                'ifsc_code' => 'SBIN0003457',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Adhesive Solutions Inc',
                'vendor_code' => 'VEN014',
                'mobile_number' => '9876543223',
                'payment_terms' => 'Net 30',
                'address' => '753 Chemical Park, Nagpur',
                'lead_time' => '9 days',
                'category_of_supply' => 'Adhesives',
                'gstin' => '27ADHSL1234S1Z5',
                'pan_number' => 'ADHSL1234S',
                'bank_name' => 'Axis Bank',
                'branch_name' => 'Nagpur Civil Lines',
                'account_number' => '4567890124',
                'ifsc_code' => 'UTIB0004568',
                'status' => 'active'
            ],
            [
                'vendor_name' => 'Packaging Masters Ltd',
                'vendor_code' => 'VEN015',
                'mobile_number' => '9876543224',
                'payment_terms' => 'Net 20',
                'address' => '486 Packaging Zone, Bhopal',
                'lead_time' => '6 days',
                'category_of_supply' => 'Packaging Solutions',
                'gstin' => '23PKGMS1234T1Z5',
                'pan_number' => 'PKGMS1234T',
                'bank_name' => 'Kotak Bank',
                'branch_name' => 'Bhopal New Market',
                'account_number' => '5678901235',
                'ifsc_code' => 'KKBK0005679',
                'status' => 'active'
            ]
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }
    }
}
