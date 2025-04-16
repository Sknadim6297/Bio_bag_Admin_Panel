@extends('layouts.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-purchase-order.css') }}">
@endsection
@section('content')
<div class="dashboard-header">
    <h1>Add Purchase Order</h1>
  </div>
<form class="purchase-order-form" id="purchaseOrderForm" enctype="multipart/form-data">
  @csrf
  <!-- Basic Information Section -->
  <div class="form-section">
    <h2>Basic Information</h2>

    <div class="form-row">
      <div class="form-col">
        <div class="form-group">
          <label for="po-date">PO Date</label>
          <input type="date" id="po-date" name="po_date" required>
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
              <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="form-col">
        <div class="form-group">
          <label for="vendor-address">Vendor Address</label>
          <textarea id="vendor-address" name="vendor_address" rows="2" readonly></textarea>
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
            <option value="main">Main Warehouse</option>
            <option value="north">North Facility</option>
            <option value="south">South Facility</option>
          </select>
        </div>
      </div>
      <div class="form-col">
        <div class="form-group">
          <label for="deliver-address">Deliver Address*</label>
          <textarea id="deliver-address" name="deliver_address" rows="2" required></textarea>
        </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-col">
        <div class="form-group">
          <label for="expected-delivery">Expected Delivery Date</label>
          <input type="date" id="expected-delivery" name="expected_delivery" required>
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
            <label>Attach Files to Purchase Order</label>
            <div class="file-upload">
              <input type="file" id="file-upload" name="attachments[]" class="file-upload-input" multiple>
              <label for="file-upload" class="file-upload-label">
                <i class="fas fa-paperclip"></i> Choose Files
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
          <th>Unit Price</th>
           <th>Measurement</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="items-table-body">
        <tr>
            <td>
                <select class="product-select" name="products[]" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" name="descriptions[]" class="description" placeholder="Description"></td>
            <td><input type="number" name="quantities[]" class="quantity" min="1" value="1" required></td>
            <td><input type="number" name="unit_prices[]" class="unit-price" min="0" step="0.01" required></td>
            <td>
              <select class="form-select" name="measurements[]" required>
                <option value="">Select Unit</option>
                @foreach (['kg', 'g', 'l', 'pcs'] as $unit)
                    <option value="{{ $unit }}">{{ strtoupper($unit) }}</option>
                @endforeach
            </select>
            
            </td>
            <td><input type="text" name="totals[]" class="total" readonly></td>
            <td><button type="button" class="btn btn-secondary remove-item">Remove</button></td>
        </tr>
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
        <input type="text" id="subtotal" name="subtotal" readonly>
      </div>
      <div class="form-col">
        <label for="tax">Tax (%)</label>
        <input type="number" id="tax" name="tax" value="0">
      </div>
      <div class="form-col">
        <label for="discount">Discount</label>
        <input type="number" id="discount" name="discount" value="0">
      </div>
      <div class="form-col">
        <label for="total">Total</label>
        <input type="text" id="total" name="total" readonly>
      </div>
    </div>
  </div>

  <!-- Terms & Notes Section -->
  <div class="form-section">
    <h2>Terms & Notes</h2>
    <div class="form-row">
      <div class="form-col">
        <label for="terms">Terms & Conditions</label>
        <textarea id="terms" name="terms"></textarea>
      </div>
      <div class="form-col">
        <label for="notes">Customer Notes</label>
        <textarea id="notes" name="notes"></textarea>
      </div>
      <div class="form-col">
        <label for="reference">Reference #</label>
        <input type="text" id="reference" name="reference">
      </div>
    </div>
  </div>

  <!-- Submit Section -->
  <div class="form-actions">
    <a href="{{ route('admin.grn.index') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Save Purchase Order</button>

  </div>
</form>
@endsection

@section('scripts')
<script>
  const vendorData = @json($vendors);

document.getElementById('vendor').addEventListener('change', function () {
  const selectedId = this.value;
  const vendor = vendorData.find(v => v.id == selectedId);

  // Set vendor address
  document.getElementById('vendor-address').value = vendor ? vendor.address : '';

  // Update Payment Terms
  const termsSelect = document.getElementById('payment-terms');
  termsSelect.innerHTML = '<option value="">Select Payment Terms</option>';

  if (vendor && vendor.payment_terms) {
    let terms = vendor.payment_terms;

    // If comma separated string, convert to array
    if (typeof terms === 'string') {
      terms = terms.split(',');
    }

    terms.forEach(term => {
      const option = document.createElement('option');
      option.value = term;
      option.text = formatPaymentTerm(term);
      termsSelect.appendChild(option);
    });
  }
});

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
  // Add item row
  document.getElementById('add-item').addEventListener('click', () => {
    const row = document.querySelector('#items-table-body tr').cloneNode(true);
    row.querySelectorAll('input').forEach(input => input.value = '');
    document.getElementById('items-table-body').appendChild(row);
  });

  // Remove item row
  document.getElementById('items-table-body').addEventListener('click', (e) => {
    if (e.target.classList.contains('remove-item')) {
      const rows = document.querySelectorAll('#items-table-body tr');
      if (rows.length > 1) {
        e.target.closest('tr').remove();
      }
    }
  });

  // Auto calculate row total
  document.getElementById('items-table-body').addEventListener('input', (e) => {
    const row = e.target.closest('tr');
    const qty = row.querySelector('.quantity').value;
    const price = row.querySelector('.unit-price').value;
    const total = qty * price;

    row.querySelector('.total').value = total.toFixed(2);

    calculateSummary();
  });

  // Summary calculation
  ['tax', 'discount'].forEach(id => {
    document.getElementById(id).addEventListener('input', calculateSummary);
  });

  function calculateSummary() {
    let subtotal = 0;
    document.querySelectorAll('.total').forEach(input => {
      subtotal += parseFloat(input.value) || 0;
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);

    const taxPercent = parseFloat(document.getElementById('tax').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const taxAmount = (subtotal * taxPercent) / 100;
    const total = subtotal + taxAmount - discount;

    document.getElementById('total').value = total.toFixed(2);
  }


  $(document).ready(function () {

    $('#price, #gst, #freight').on('input', function () {
        let price = parseFloat($('#price').val()) || 0;
        let gst = parseFloat($('#gst').val()) || 0;
        let freight = parseFloat($('#freight').val()) || 0;

        let gstAmount = price * (gst / 100);
        let total = price + gstAmount + freight;
        $('#totalPrice').val(total.toFixed(2));
    });
    

    $("#purchaseOrderForm").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: "{{ route('admin.grn.store') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            if (response.success) {
                toastr.success(response.message);
                setTimeout(() => {
                    window.location.href = "{{ route('admin.grn.index') }}";
                }, 1000);
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0]);
                    }
                }
            } else {
                toastr.error('Something went wrong. Please try again.');
            }
        }
    });
});

});
document.getElementById('file-upload').addEventListener('change', function(e) {
  const fileList = document.getElementById('file-list');
  fileList.innerHTML = '';

  for (let i = 0; i < this.files.length; i++) {
    const file = this.files[i];
    const listItem = document.createElement('li');
    listItem.innerHTML = `
      <span>${file.name}</span>
      <span class="remove-file" data-index="${i}"><i class="fas fa-times"></i></span>
    `;
    fileList.appendChild(listItem);
  }

  // Add event listeners to remove buttons
  document.querySelectorAll('.remove-file').forEach(button => {
    button.addEventListener('click', function() {
      const index = parseInt(this.getAttribute('data-index'));
      const files = Array.from(document.getElementById('file-upload').files);

      // Remove the selected file from the list
      files.splice(index, 1);

      // Create new DataTransfer object and update files
      const dataTransfer = new DataTransfer();
      files.forEach(file => dataTransfer.items.add(file));

      // Update the file input with the new files
      document.getElementById('file-upload').files = dataTransfer.files;

      // Remove the file from the display list
      this.closest('li').remove();
    });
  });
});


</script>
@endsection
