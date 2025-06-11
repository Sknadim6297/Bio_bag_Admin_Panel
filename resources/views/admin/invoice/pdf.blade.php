<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice - {{ $invoice->customer->customer_name }}</title>
    <style>
        @font-face {
            font-family: 'RupeeFont';
            src: url({{ public_path('fonts/DejaVuSans.ttf') }});
        }
        
        .amount-prefix {
            font-family: 'Arial', sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.2;
            background: white;
            padding: 10px;
        }
        .invoice-container {
            margin: 0 auto;
            border: 2px solid black;
            background: white;
        }
        
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 10px;
            border-bottom: 2px solid black;
            background: #f8f8f8;
        }
        
        .company-section {
            display: table;
            width: 100%;
            border-bottom: 2px solid black;
        }
        
        .seller-details {
            display: table-cell;
            width: 50.3%;
            padding: 12px;
            border-right: 2px solid black;
            vertical-align: top;
        }
        
        .buyer-details {
            display: table-cell;
            width: 50%;
            padding: 12px;
            vertical-align: top;
        }
        
        .logo-section {
            float: left;
            width: 120px;
            height: auto;
            border: none;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .logo-section img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 5px;
            color: #000;
        }
        
        .buyer-label {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .gstin-line {
            margin: 3px 0;
        }
        
        .contact-info {
            margin: 2px 0;
        }
        
        .invoice-ref-section {
            display: table;
            width: 100%;
            border-bottom: 2px solid black;
        }
        
        .left-ref {
            display: table-cell;
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        
        .right-ref {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border-left: 2px solid black; 
            vertical-align: top;
        }
        
        .ref-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .ref-label {
            display: table-cell;
            width: 45%;
            font-weight: bold;
            padding-right: 10px;
        }
        
        .ref-value {
            display: table-cell;
            width: 55%;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid black;
        }
        
        .items-table th {
            background-color: #f0f0f0;
            border: 1px solid black;
            padding: 8px 4px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            vertical-align: middle;
        }
        
        .items-table td {
            border: 1px solid black;
            padding: 6px 4px;
            text-align: center;
            font-size: 10px;
            vertical-align: middle;
        }
        
        .desc-col {
            text-align: left !important;
            width: 180px;
            padding-left: 8px !important;
        }
        
        .amount-col {
            text-align: right !important;
            padding-right: 8px !important;
        }
        
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .amount-words-section {
            padding: 12px;
            border-bottom: 2px solid black;
            font-size: 11px;
        }
        
        .tax-summary-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid black;
        }
        
        .tax-summary-table th {
            background-color: #f0f0f0;
            border: 1px solid black;
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }
        
        .tax-summary-table td {
            border: 1px solid black;
            padding: 6px 4px;
            text-align: center;
            font-size: 10px;
        }
        
        .tax-words-section {
            padding: 12px;
            border-bottom: 2px solid black;
            font-size: 11px;
        }
        
        .footer-section {
            display: table;
            width: 100%;
            min-height: 180px;
        }
        
        .remarks-column {
            display: table-cell;
            width: 50%;
            padding: 12px;
            border-right: 2px solid black;
            vertical-align: top;
        }
        
        .bank-column {
            display: table-cell;
            width: 50%;
            padding: 12px;
            vertical-align: top;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 12px;
        }
        
        .declaration-text {
            margin-top: 15px;
            line-height: 1.3;
        }
        
        .bank-details {
            margin-bottom: 15px;
        }
        
        .bank-row {
            margin-bottom: 8px;
        }
        
        .signature-section {
            text-align: right;
            margin-top: 40px;
        }
        
        .signature-line {
            margin-bottom: 5px;
        }
        
        .company-content {
            margin-left: 100px;
        }
        
        .computer-generated {
            text-align: center;
            font-size: 10px;
            font-style: italic;
            padding: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            Tax Invoice
        </div>
        
        <!-- Company Details Section -->
        <div class="company-section">
            <div class="seller-details">
                <div class="logo-section">
                    <img src="{{ public_path('admin/images/images__4_-removebg-preview.png') }}" alt="Company Logo">
                </div>
                <div class="company-content">
                    <div class="company-name">GREENWARE REVOLUTION 2024-2025 - (from 1-Apr-2024)</div>
                    <div>42E, CHAULPATTY ROAD</div>
                    <div>KOLKATA- 700010</div>
                    <div class="gstin-line"><strong>GSTIN/UIN:</strong> 19ADNPG4007H1ZM</div>
                    <div class="gstin-line"><strong>State Name :</strong> West Bengal, Code : 19</div>
                    <div class="contact-info"><strong>Contact :</strong> 6290515957</div>
                    <div class="contact-info"><strong>E-Mail :</strong> info@greenware.co.in</div>
                    <div class="contact-info">www.greenware.co.in</div>
                </div>
            </div>
            <div class="left-ref">
                <div class="ref-row">
                    <div class="ref-label">Invoice No.</div>
                    <div class="ref-value">{{ $invoice->invoice_number }}</div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Delivery Note</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Supplier's Ref.</div>
                    <div class="ref-value">{{ $invoice->invoice_number }}</div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Buyer's Order No.</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Despatch Document No.</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Despatched through</div>
                    <div class="ref-value"></div>
                </div>
            </div>
            
            
        </div>
        
        <!-- Invoice Reference Details -->
        <div class="invoice-ref-section">
            <div class="buyer-details">
                <div class="buyer-label">Buyer</div>
                <div class="company-name">{{ $invoice->customer->customer_name }}</div>
                <div>{{ $invoice->customer->address }}</div>
                <div>{{ $invoice->customer->city ?? '' }}, {{ $invoice->customer->phone ?? '' }}</div>
                <div class="gstin-line"><strong>GSTIN/UIN :</strong> {{ $invoice->customer->gstin ?? 'N/A' }}</div>
                <div class="gstin-line"><strong>State Name :</strong> {{ $invoice->customer->state ?? 'West Bengal' }}, Code : {{ $invoice->customer->state_code ?? '19' }}</div>
            </div>
            
            <div class="right-ref">
                <div class="ref-row">
                    <div class="ref-label">Dated</div>
                    <div class="ref-value">{{ $formattedDate }}</div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Mode/Terms of Payment</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Other Reference(s)</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Dated</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Delivery Note Date</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Destination</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Terms of Delivery</div>
                    <div class="ref-value"></div>
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 30px;">Sl<br>No.</th>
                    <th class="desc-col">Description of Goods</th>
                    <th style="width: 80px;">HSN/SAC</th>
                    <th style="width: 50px;">GST<br>Rate</th>
                    <th style="width: 60px;">Quantity</th>
                    <th style="width: 70px;">Rate</th>
                    <th style="width: 40px;">per</th>
                    <th style="width: 50px;">Disc. %</th>
                    <th style="width: 80px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td class="desc-col">{{ $invoice->description }}</td>
                    <td>{{ $invoice->hsn }}</td>
                    <td>{{ $gstRate }}%</td>
                    <td>{{ number_format($invoice->quantity, 2) }} KG</td>
                    <td class="amount-col">{{ number_format($invoice->price_per_kg, 2) }}</td>
                    <td>KG</td>
                    <td></td>
                    <td class="amount-col"><span class="amount-prefix">Rs.</span> {{ number_format($invoice->total_amount, 2) }}</td>
                </tr>
                
                @if($invoice->cgst > 0)
                <tr>
                    <td></td>
                    <td class="desc-col">CGST {{ $invoice->cgst }}% OUTPUT</td>
                    <td></td>
                    <td>{{ $invoice->cgst }}%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-col">{{ number_format($invoice->total_amount * $invoice->cgst / 100, 2) }}</td>
                </tr>
                @endif
                
                @if($invoice->sgst > 0)
                <tr>
                    <td></td>
                    <td class="desc-col">SGST {{ $invoice->sgst }}% OUTPUT</td>
                    <td></td>
                    <td>{{ $invoice->sgst }}%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-col">{{ number_format($invoice->total_amount * $invoice->sgst / 100, 2) }}</td>
                </tr>
                @endif
                
                @if($invoice->igst > 0)
                <tr>
                    <td></td>
                    <td class="desc-col">IGST {{ $invoice->igst }}% OUTPUT</td>
                    <td></td>
                    <td>{{ $invoice->igst }}%</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-col">{{ number_format($invoice->total_amount * $invoice->igst / 100, 2) }}</td>
                </tr>
                @endif
                
                @php
                    $roundOff = round($invoice->final_price) - $invoice->final_price;
                @endphp
                
                @if($roundOff != 0)
                <tr>
                    <td></td>
                    <td class="desc-col">ROUND OFF</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-col">{{ number_format($roundOff, 2) }}</td>
                </tr>
                @endif
                
                <tr class="total-row">
                    <td></td>
                    <td class="desc-col"><strong>Total</strong></td>
                    <td></td>
                    <td></td>
                    <td><strong>{{ number_format($invoice->quantity, 2) }} KG</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount-col"><strong>Rs. {{ number_format(round($invoice->final_price), 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Amount in Words -->
        <div class="amount-words-section">
            <strong>Amount Chargeable (in words)</strong> E. & O.E<br>
            <strong>{{ $totalInWords }}</strong>
        </div>
        
        <!-- Tax Summary Table -->
        <table class="tax-summary-table">
            <thead>
                <tr>
                    <th rowspan="2">HSN/SAC</th>
                    <th rowspan="2">Taxable<br>Value</th>
                    <th colspan="2">Central Tax</th>
                    <th colspan="2">State Tax</th>
                    <th rowspan="2">Total<br>Tax Amount</th>
                </tr>
                <tr>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>Rate</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->hsn }}</td>
                    <td>{{ number_format($invoice->total_amount, 2) }}</td>
                    <td>{{ $invoice->cgst }}%</td>
                    <td>{{ number_format($invoice->total_amount * $invoice->cgst / 100, 2) }}</td>
                    <td>{{ $invoice->sgst }}%</td>
                    <td>{{ number_format($invoice->total_amount * $invoice->sgst / 100, 2) }}</td>
                    <td>{{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td><strong>Total</strong></td>
                    <td><strong>{{ number_format($invoice->total_amount, 2) }}</strong></td>
                    <td></td>
                    <td><strong>{{ number_format($invoice->total_amount * $invoice->cgst / 100, 2) }}</strong></td>
                    <td></td>
                    <td><strong>{{ number_format($invoice->total_amount * $invoice->sgst / 100, 2) }}</strong></td>
                    <td><strong>{{ number_format($invoice->tax_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Tax Amount in Words -->
        <div class="tax-words-section">
            <strong>Tax Amount (in words) :</strong> {{ $taxInWords }}
        </div>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <div class="remarks-column">
                <div class="section-title">Remarks:</div>
                <div>BEING INVOICE {{ $invoice->invoice_number }} DT.{{ $formattedDate }}</div>
                
                <div class="declaration-text">
                    <div class="section-title">Declaration</div>
                    <div>We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</div>
                </div>
            </div>
            
            <div class="bank-column">
                <div class="section-title">Company's Bank Details</div>
                <div class="bank-details">
                    <div class="bank-row"><strong>Bank Name :</strong><br>AXIS BANK LTD( OD)</div>
                    <div class="bank-row"><strong>A/c No. :</strong><br>922030025648607</div>
                    <div class="bank-row"><strong>Branch & IFS Code :</strong><br>KOLKATA & UTIB0000005</div>
                </div>
                
                <div class="signature-section">
                    <div class="signature-line">for GREENWARE REVOLUTION 2024-2025 - (from 1-Apr-2024)</div>
                    <br><br>
                    <div class="signature-line"><strong>Authorised Signatory</strong></div>
                </div>
            </div>
        </div>
        
        <div class="computer-generated">
            This is a Computer Generated Invoice
        </div>
    </div>
</body>
</html>