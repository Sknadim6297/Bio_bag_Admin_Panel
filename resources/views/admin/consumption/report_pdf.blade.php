<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumption Report</title>
    <link rel="stylesheet" href="{{ public_path('admin/css/invoice.css') }}">
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            Consumption Report
        </div>

       <div class="company-section">
            <div class="seller-details">
                <div class="logo-section">
                    <img src="{{ public_path('admin/images/images__4_-removebg-preview.png') }}" alt="Company Logo">
                </div>
                <div class="company-content">
                    <div class="company-name">GRE   ENWARE REVOLUTION 2024-2025 - (from 1-Apr-2024)</div>
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
                    <div class="ref-value">GR-G/0098/25-26</div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Delivery Note</div>
                    <div class="ref-value"></div>
                </div>
                <div class="ref-row">
                    <div class="ref-label">Supplier's Ref.</div>
                    <div class="ref-value">GR-G/0098/25-26</div>
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

        <!-- Consumption Data Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 30px;">Sl<br>No.</th>
                    <th style="width: 80px;">Date &<br>Time</th>
                    <th style="width: 200px;">Product<br>Name</th>
                    <th style="width: 80px;">Quantity<br>(kg)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consumptions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ date('d/m/y H:i', strtotime($item->date . ' ' . $item->time)) }}</td>
                    <td>{{ $item->stock->product_name ?? 'N/A' }}</td>
                    <td class="amount-col">{{ number_format($item->quantity, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No data available</td>
                </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>Total Consumption:</strong></td>
                    <td class="amount-col"><strong>{{ number_format($total, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Amount in Words -->
        <div class="amount-words-section">
            <strong>Total Consumption (in words):</strong><br>
            {{ $totalInWords }} Kilograms Only
        </div>
        
        <div class="footer-section">
            <div class="remarks-column">
                <div class="section-title">Remarks:</div>
                <div>BEING INVOICE GR-G/0098/25-26 DT.21.05.2025</div>
                
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