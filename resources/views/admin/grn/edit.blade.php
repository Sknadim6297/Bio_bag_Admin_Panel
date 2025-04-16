@extends('layouts.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('admin/css/manage-grn.css') }}">
@endsection

@section('content')
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
      <button type="submit" class="btn btn-primary">Save GRN</button>
    </div>
  </form>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
</script>
@endsection
