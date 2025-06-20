@extends('layouts.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('admin/css/add-purchase-order.css') }}">
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
<div class="dashboard-header">
    <h1>Add Purchase Bill</h1>
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
          <input type="date" id="po-date" name="po_date"required>
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
  <div class="form-section mt-4">
    <h2 class="mb-3">Items</h2>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center">
            <thead class="table-dark" style="background-color:#123458; color:white;padding:20px">
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
                <tr>
                   <td>
    <input type="text" name="products[]" class="form-control" placeholder="Product Name" required>
</td>

                    <td>
                        <input type="text" name="descriptions[]" class="form-control" placeholder="Description">
                    </td>
                    <td>
                        <input type="number" name="quantities[]" class="form-control quantity" min="1" value="1" required>
                    </td>
                    <td>
                        <select class="form-select" name="measurements[]" required>
                            <option value="">Select Unit</option>
                            @foreach (['kg', 'g', 'l', 'pcs'] as $unit)
                                <option value="{{ $unit }}">{{ strtoupper($unit) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="unit_prices[]" class="form-control unit-price" min="0" step="0.01" required>
                    </td>
                   
                    <td>
                        <input type="text" name="totals[]" class="form-control total" readonly>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger remove-item">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <button type="button" id="add-item" class="btn btn-success mt-2">+ Add Item</button>
</div>

  <!-- Summary Section -->
  <div class="form-section">
    <h2>Summary</h2>
    <div class="form-row">
      <div class="form-col">
        <label for="subtotal">Subtotal</label>
        <input type="text" id="subtotal" name="subtotal" readonly>
      </div>
      <div class="form-col tax-group-cgst-sgst">
        <label for="cgst">CGST (%)</label>
        <input type="number" id="cgst" name="cgst" value="0" min="0" max="100">
      </div>
      <div class="form-col tax-group-cgst-sgst">
        <label for="sgst">SGST (%)</label>
        <input type="number" id="sgst" name="sgst" value="0" min="0" max="100">
      </div>
      <div class="form-col tax-group-igst" style="display:none;">
        <label for="igst">IGST (%)</label>
        <input type="number" id="igst" name="igst" value="0" min="0" max="100">
      </div>
      <div class="form-col">
        <label for="cess">CESS (%)</label>
        <input type="number" id="cess" name="cess" value="0" min="0" max="100">
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

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
  ['cgst', 'sgst', 'igst', 'cess', 'discount'].forEach(id => {
    document.getElementById(id).addEventListener('input', calculateSummary);
  });

  // Show/hide CGST/SGST or IGST fields
  function updateTaxFieldsVisibility() {
    const cgst = parseFloat(document.getElementById('cgst').value) || 0;
    const sgst = parseFloat(document.getElementById('sgst').value) || 0;
    const igst = parseFloat(document.getElementById('igst').value) || 0;
    if (cgst > 0 || sgst > 0) {
      // Show CGST/SGST, hide IGST
      document.querySelectorAll('.tax-group-cgst-sgst').forEach(el => el.style.display = 'block');
      document.querySelectorAll('.tax-group-igst').forEach(el => el.style.display = 'none');
      document.getElementById('igst').value = 0;
    } else if (igst > 0) {
      // Show IGST, hide CGST/SGST
      document.querySelectorAll('.tax-group-cgst-sgst').forEach(el => el.style.display = 'none');
      document.querySelectorAll('.tax-group-igst').forEach(el => el.style.display = 'block');
      document.getElementById('cgst').value = 0;
      document.getElementById('sgst').value = 0;
    } else {
      // Show all by default
      document.querySelectorAll('.tax-group-cgst-sgst').forEach(el => el.style.display = 'block');
      document.querySelectorAll('.tax-group-igst').forEach(el => el.style.display = 'block');
    }
  }
  document.getElementById('cgst').addEventListener('input', updateTaxFieldsVisibility);
  document.getElementById('sgst').addEventListener('input', updateTaxFieldsVisibility);
  document.getElementById('igst').addEventListener('input', updateTaxFieldsVisibility);
  // Initial call
  updateTaxFieldsVisibility();

  function calculateSummary() {
    let subtotal = 0;
    document.querySelectorAll('.total').forEach(input => {
      subtotal += parseFloat(input.value) || 0;
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);

    const cgst = parseFloat(document.getElementById('cgst').value) || 0;
    const sgst = parseFloat(document.getElementById('sgst').value) || 0;
    const igst = parseFloat(document.getElementById('igst').value) || 0;
    const cess = parseFloat(document.getElementById('cess').value) || 0;
    const discount = parseFloat(document.getElementById('discount').value) || 0;

    let taxAmount = 0;
    if (igst > 0) {
      taxAmount = subtotal * (igst / 100);
    } else {
      taxAmount = subtotal * ((cgst + sgst) / 100);
    }
    let cessAmount = subtotal * (cess / 100);
    const total = subtotal + taxAmount + cessAmount - discount;

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

      document.getElementById('file-upload').files = dataTransfer.files;

      // Remove the file from the display list
      this.closest('li').remove();
    });
  });
});

window.addEventListener('DOMContentLoaded', (event) => {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    const currentDate = `${yyyy}-${mm}-${dd}`;
    document.getElementById('po-date').value = currentDate;
  });


  $(document).ready(function () {

    $(document).on('focus', 'input[name="products[]"]', function () {
    const $this = $(this);

    if (!$this.data("ui-autocomplete")) {
        $this.autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "{{ route('admin.grn.suggestions') }}",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        response(data);
                    }
                });
            },
            minLength: 1
        });
    }

   
    $this.autocomplete("search", "");
});

});

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

</script>
@endsection
