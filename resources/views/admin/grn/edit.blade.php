  @extends('layouts.layout')
  @section('styles')
    <link rel="stylesheet" href="{{ asset('admin/css/manage-grn.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
    <style>
      .custom-file-upload {
        border: 2px dashed #2563eb;
        border-radius: 10px;
        padding: 24px 16px;
        background: #f8fafc;
        text-align: center;
        position: relative;
        margin-bottom: 10px;
        transition: border-color 0.2s;
      }
      .custom-file-upload.dragover {
        border-color: #1741a0;
        background: #e0e7ef;
      }
      .file-upload-label {
        display: inline-block;
        background: #2563eb;
        color: #fff;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 10px;
        transition: background 0.2s;
      }
      .file-upload-label:hover {
        background: #1741a0;
      }
      .file-list {
        list-style: none;
        padding: 0;
        margin: 10px 0 0 0;
        text-align: left;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
      }
      .file-list li {
        background: #f1f5f9;
        border-radius: 4px;
        padding: 6px 12px;
        margin-bottom: 4px;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
      }
      .file-list .remove-file {
        color: #ef4444;
        cursor: pointer;
        margin-left: 10px;
        font-size: 18px;
      }
    </style>
  @endsection

  @section('content')
  <form class="purchase-order-form" id="purchaseOrderForm" method="POST" action="{{ route('admin.grn.update', $purchaseOrder->id) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <!-- Basic Information Section -->
      <div class="form-section">
        <h2>Basic Information</h2>
    
        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label for="po-date">PO Date</label>
              <input type="date" id="po-date" name="po_date" required value="{{ $purchaseOrder->po_date }}">
            </div>
          </div>
        </div>
    
        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label for="vendor">Vendor</label>
              <select id="vendor" name="vendor_id" required>
                <option value="">Select Vendor</option>
                @foreach($vendors as $vendor)
                  <option value="{{ $vendor->id }}" {{ $vendor->id == $purchaseOrder->vendor_id ? 'selected' : '' }}>{{ $vendor->vendor_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-col">
            <div class="form-group">
              <label for="vendor-address">Vendor Address</label>
              <textarea id="vendor-address" name="vendor_address" rows="2" readonly>{{ $purchaseOrder->vendor_address }}</textarea>
            </div>
          </div>
        </div>
    
        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label>Deliver To</label>
              <div class="radio-group">
                <label><input type="radio" name="deliver_to_type" value="customer" checked> Customer</label>
                <label><input type="radio" name="deliver_to_type" value="organization"> Organization</label>
              </div>
              <select id="deliver-to" name="deliver_to_location" required>
                <option value="">Select Location</option>
                <option value="main" {{ $purchaseOrder->deliver_to_location == 'main' ? 'selected' : '' }}>Main Warehouse</option>
                <option value="north" {{ $purchaseOrder->deliver_to_location == 'north' ? 'selected' : '' }}>North Facility</option>
                <option value="south" {{ $purchaseOrder->deliver_to_location == 'south' ? 'selected' : '' }}>South Facility</option>
              </select>
            </div>
          </div>
          <div class="form-col">
            <div class="form-group">
              <label for="deliver-address">Deliver Address*</label>
              <textarea id="deliver-address" name="deliver_address" rows="2" required>{{ $purchaseOrder->deliver_address }}</textarea>
            </div>
          </div>
        </div>
    
        <div class="form-row">
          <div class="form-col">
            <div class="form-group">
              <label for="expected-delivery">Expected Delivery Date</label>
              <input type="date" id="expected-delivery" name="expected_delivery" required value="{{ $purchaseOrder->expected_delivery }}">
            </div>
          </div>
          <div class="form-col">
            <div class="form-group">
                <label for="payment-terms">Payment Terms</label>
                <select id="payment-terms" name="payment_terms" required>
                  <option value="">Select Payment Terms</option>
                  <!-- Dynamically populated -->
                </select>
              </div>
              
          </div>
        </div>
    
        <div class="form-row">
            <div class="form-col">
              <div class="form-group">
                <label>Attach Files to Purchase Bill</label>
                <div class="custom-file-upload" id="customFileUpload">
                  <input type="file" id="file-upload" name="attachments[]" class="file-upload-input" multiple hidden>
                  <label for="file-upload" class="file-upload-label">
                    <i class="fas fa-cloud-upload-alt"></i> <span id="file-upload-text">Choose Files or Drag & Drop</span>
                  </label>
                  <ul class="file-list" id="file-list"></ul>
                </div>
              </div>
            </div>
          </div>
          
    
      <!-- Items Section -->
      <div class="form-section">
        <h2>Items</h2>
        <table class="items-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Description</th>
              <th>Quantity</th>
              <th>Measurement</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="items-table-body">
            @foreach($purchaseOrder->items as $item)
              <tr>
                <td>
                  <select class="product-select" name="products[]" required>
                    <option value="">Select Product</option>
                    @php $found = false; @endphp
                    @foreach($products as $product)
                      <option value="{{ $product->product_name }}" {{ trim(strtolower($product->product_name)) == trim(strtolower($item->product_name)) ? ($found = true) && 'selected' : '' }}>{{ $product->product_name }}</option>
                    @endforeach
                    @if(!$found)
                      <option value="{{ $item->product_name }}" selected>{{ $item->product_name }}</option>
                    @endif
                  </select>
                </td>
                <td><input type="text" name="descriptions[]" class="description" placeholder="Description" value="{{ $item->description }}"></td>
                <td><input type="number" name="quantities[]" class="quantity" min="1" value="{{ $item->quantity }}" required></td>
                <td>
                  <select class="form-select" name="measurements[]" required>
                    <option value="">Select Unit</option>
                    @foreach(['kg', 'g', 'l', 'pcs'] as $unit)
                      <option value="{{ $unit }}" {{ $item->measurement == $unit ? 'selected' : '' }}>{{ strtoupper($unit) }}</option>
                    @endforeach
                  </select>
                </td>
                <td><input type="number" name="unit_prices[]" class="unit-price" min="0" step="0.01" value="{{ $item->unit_price }}" required></td>
                <td><input type="text" name="totals[]" class="total" readonly value="{{ $item->total }}"></td>
                <td><button type="button" class="btn btn-secondary remove-item">Remove</button></td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <button type="button" id="add-item" class="btn btn-secondary">+ Add Item</button>
      </div>
    
      <!-- Summary Section -->
      <div class="form-section">
        <h2>Summary</h2>
        <div class="form-row">
          <div class="form-col">
            <label for="subtotal">Subtotal</label>
            <input type="text" id="subtotal" name="subtotal" value="{{ $purchaseOrder->subtotal }}" readonly>
          </div>
          <div class="form-col tax-group-cgst-sgst">
            <label for="cgst">CGST (%)</label>
            <input type="number" id="cgst" name="cgst" value="{{ $purchaseOrder->cgst }}" min="0" max="100">
          </div>
          <div class="form-col tax-group-cgst-sgst">
            <label for="sgst">SGST (%)</label>
            <input type="number" id="sgst" name="sgst" value="{{ $purchaseOrder->sgst }}" min="0" max="100">
          </div>
          <div class="form-col tax-group-igst" style="display:none;">
            <label for="igst">IGST (%)</label>
            <input type="number" id="igst" name="igst" value="{{ $purchaseOrder->igst }}" min="0" max="100">
          </div>
          <div class="form-col">
            <label for="cess">CESS (%)</label>
            <input type="number" id="cess" name="cess" value="{{ $purchaseOrder->cess }}" min="0" max="100">
          </div>
          <div class="form-col">
            <label for="discount">Discount</label>
            <input type="number" id="discount" name="discount" value="{{ $purchaseOrder->discount }}">
          </div>
          <div class="form-col">
            <label for="total">Total</label>
            <input type="text" id="total" name="total" value="{{ $purchaseOrder->total }}" readonly>
          </div>
        </div>
      </div>
    
      <!-- Terms & Notes Section -->
      <div class="form-section">
        <h2>Terms & Notes</h2>
        <div class="form-row">
          <div class="form-col">
            <label for="terms">Terms & Conditions</label>
            <textarea id="terms" name="terms">{{ $purchaseOrder->terms }}</textarea>
          </div>
          <div class="form-col">
            <label for="notes">Customer Notes</label>
            <textarea id="notes" name="notes">{{ $purchaseOrder->notes }}</textarea>
          </div>
          <div class="form-col">
            <label for="reference">Reference #</label>
            <input type="text" id="reference" name="reference" value="{{ $purchaseOrder->reference }}">
          </div>
        </div>
      </div>
    
      <!-- Submit Section -->
      <div class="form-actions">
        <a href="{{ route('admin.grn.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">Save GRN</button>
      </div>
    </form>
  @endsection

  @section('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    // Prepare vendor address lookup
    const vendors = @json($vendors);
    function setVendorAddress(vendorId) {
      const vendor = vendors.find(v => v.id == vendorId);
      if (vendor) {
        $('#vendor-address').val(vendor.address);
      } else {
        $('#vendor-address').val('');
      }
    }
    function setPaymentTerms(vendorId) {
      const vendor = vendors.find(v => v.id == vendorId);
      const termsSelect = $('#payment-terms');
      termsSelect.html('<option value="">Select Payment Terms</option>');
      if (vendor && vendor.payment_terms) {
        let terms = vendor.payment_terms;
        if (typeof terms === 'string') {
          terms = terms.split(',');
        }
        terms.forEach(term => {
          const formatted = formatPaymentTerm(term);
          const selected = term == "{{ $purchaseOrder->payment_terms }}" ? 'selected' : '';
          termsSelect.append(`<option value="${term}" ${selected}>${formatted}</option>`);
        });
      }
    }
    function formatPaymentTerm(term) {
      switch(term) {
        case 'net15': return 'Net 15';
        case 'net30': return 'Net 30';
        case 'net45': return 'Net 45';
        case '50advance': return '50% Advance';
        case '100advance': return '100% Advance';
        default: return term;
      }
    }
    $(document).ready(function() {
      // On vendor change
      $('#vendor').on('change', function() {
        setVendorAddress(this.value);
        setPaymentTerms(this.value);
      });
      // On page load, set address and payment terms for selected vendor
      setVendorAddress($('#vendor').val());
      setPaymentTerms($('#vendor').val());

      // --- Price Calculation Logic ---
      function updateTaxFieldsVisibility() {
        const cgst = parseFloat($('#cgst').val()) || 0;
        const sgst = parseFloat($('#sgst').val()) || 0;
        const igst = parseFloat($('#igst').val()) || 0;
        if (cgst > 0 || sgst > 0) {
          $('.tax-group-cgst-sgst').show();
          $('.tax-group-igst').hide();
          $('#igst').val(0);
        } else if (igst > 0) {
          $('.tax-group-cgst-sgst').hide();
          $('.tax-group-igst').show();
          $('#cgst').val(0);
          $('#sgst').val(0);
        } else {
          $('.tax-group-cgst-sgst').show();
          $('.tax-group-igst').show();
        }
      }
      $('#cgst, #sgst, #igst').on('input', updateTaxFieldsVisibility);
      updateTaxFieldsVisibility();

      function calculateSummary() {
        let subtotal = 0;
        $('.total').each(function() {
          subtotal += parseFloat($(this).val()) || 0;
        });
        $('#subtotal').val(subtotal.toFixed(2));
        const cgst = parseFloat($('#cgst').val()) || 0;
        const sgst = parseFloat($('#sgst').val()) || 0;
        const igst = parseFloat($('#igst').val()) || 0;
        const cess = parseFloat($('#cess').val()) || 0;
        const discount = parseFloat($('#discount').val()) || 0;
        let taxAmount = 0;
        if (igst > 0) {
          taxAmount = subtotal * (igst / 100);
        } else {
          taxAmount = subtotal * ((cgst + sgst) / 100);
        }
        let cessAmount = subtotal * (cess / 100);
        const total = subtotal + taxAmount + cessAmount - discount;
        $('#total').val(total.toFixed(2));
      }
      $('#cgst, #sgst, #igst, #cess, #discount').on('input', calculateSummary);
      $('#items-table-body').on('input', '.quantity, .unit-price', function() {
        const row = $(this).closest('tr');
        const qty = parseFloat(row.find('.quantity').val()) || 0;
        const price = parseFloat(row.find('.unit-price').val()) || 0;
        row.find('.total').val((qty * price).toFixed(2));
        calculateSummary();
      });
      // Initial calculation
      calculateSummary();

      // Custom file upload logic
      const fileInput = document.getElementById('file-upload');
      const fileLabel = document.getElementById('file-upload-text');
      const fileList = document.getElementById('file-list');
      const customFileUpload = document.getElementById('customFileUpload');

      fileInput.addEventListener('change', function(e) {
        updateFileList();
      });

      customFileUpload.addEventListener('dragover', function(e) {
        e.preventDefault();
        customFileUpload.classList.add('dragover');
      });
      customFileUpload.addEventListener('dragleave', function(e) {
        customFileUpload.classList.remove('dragover');
      });
      customFileUpload.addEventListener('drop', function(e) {
        e.preventDefault();
        customFileUpload.classList.remove('dragover');
        const files = Array.from(e.dataTransfer.files);
        const dataTransfer = new DataTransfer();
        Array.from(fileInput.files).forEach(f => dataTransfer.items.add(f));
        files.forEach(f => dataTransfer.items.add(f));
        fileInput.files = dataTransfer.files;
        updateFileList();
      });

      function updateFileList() {
        fileList.innerHTML = '';
        const files = Array.from(fileInput.files);
        if (files.length === 0) {
          fileLabel.textContent = 'Choose Files or Drag & Drop';
        } else {
          fileLabel.textContent = files.length + ' file(s) selected';
        }
        files.forEach((file, idx) => {
          const li = document.createElement('li');
          li.innerHTML = `<span>${file.name}</span> <span class="remove-file" data-index="${idx}"><i class="fas fa-times"></i></span>`;
          fileList.appendChild(li);
        });
        // Remove file logic
        fileList.querySelectorAll('.remove-file').forEach(btn => {
          btn.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            const filesArr = Array.from(fileInput.files);
            filesArr.splice(index, 1);
            const dataTransfer = new DataTransfer();
            filesArr.forEach(f => dataTransfer.items.add(f));
            fileInput.files = dataTransfer.files;
            updateFileList();
          });
        });
      }
    });
  </script>
  @endsection
