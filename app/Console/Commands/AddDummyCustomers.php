<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class AddDummyCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:add-dummy {count=15 : Number of dummy customers to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add dummy customer data for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->argument('count');
        
        $this->info("Creating {$count} dummy customers...");
        
        $customerNames = [
            'Rajesh Kumar', 'Priya Sharma', 'Amit Patel', 'Sunita Singh', 'Vikram Reddy',
            'Meera Joshi', 'Rohit Agarwal', 'Kavitha Nair', 'Deepak Gupta', 'Anita Verma',
            'Sanjay Mishra', 'Pooja Saxena', 'Ravi Iyer', 'Neha Kapoor', 'Suresh Pillai',
            'Lakshmi Murthy', 'Ajay Singh', 'Divya Rao', 'Manoj Tiwari', 'Shanti Devi'
        ];
        
        $cities = [
            'Bangalore, Karnataka', 'Mumbai, Maharashtra', 'Ahmedabad, Gujarat', 
            'New Delhi, Delhi', 'Hyderabad, Telangana', 'Pune, Maharashtra',
            'Kolkata, West Bengal', 'Kochi, Kerala', 'Jaipur, Rajasthan', 
            'Chandigarh', 'Lucknow, Uttar Pradesh', 'Bhopal, Madhya Pradesh',
            'Chennai, Tamil Nadu', 'Gurgaon, Haryana', 'Coimbatore, Tamil Nadu'
        ];
        
        $banks = ['HDFC Bank', 'ICICI Bank', 'SBI Bank', 'Axis Bank', 'PNB Bank', 'Canara Bank', 'Indian Bank'];
        $paymentTerms = ['Net 15', 'Net 30', 'Net 45', 'Net 60'];
        
        $createdCount = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $customerCode = 'CUS' . str_pad(1001 + Customer::count() + $i, 4, '0', STR_PAD_LEFT);
            $mobile = '987654' . str_pad(3210 + $i, 4, '0', STR_PAD_LEFT);
            $name = $customerNames[$i % count($customerNames)];
            $city = $cities[$i % count($cities)];
            $bank = $banks[$i % count($banks)];
            $paymentTerm = $paymentTerms[$i % count($paymentTerms)];
            
            // Generate random GSTIN and PAN
            $gstinPrefix = str_pad(rand(1, 37), 2, '0', STR_PAD_LEFT);
            $gstinRandom = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
            $gstinNumber = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $gstin = $gstinPrefix . $gstinRandom . $gstinNumber . 'A1Z5';
            
            $panRandom = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
            $panNumber = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $pan = $panRandom . $panNumber . 'A';
            
            $customer = Customer::create([
                'customer_name' => $name . ' ' . ($i + 1),
                'customer_code' => $customerCode,
                'mobile_number' => $mobile,
                'address' => rand(100, 999) . ' Street, ' . $city . ' - ' . rand(400001, 600001),
                'payment_terms' => $paymentTerm,
                'gstin' => $gstin,
                'pan_number' => $pan,
                'bank_name' => $bank,
                'branch_name' => 'Main Branch',
                'account_number' => '5' . str_pad(rand(10000000000, 99999999999), 11, '0', STR_PAD_LEFT),
                'ifsc_code' => strtoupper(substr($bank, 0, 4)) . '000' . rand(1000, 9999),
                'status' => $i % 4 == 0 ? 'inactive' : 'active', // 25% inactive
            ]);
            
            $createdCount++;
        }
        
        $this->info("✅ Successfully created {$createdCount} dummy customers!");
        $this->info("Total customers in database: " . Customer::count());
        
        return 0;
    }
}
