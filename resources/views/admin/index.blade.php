@extends('layouts.layout')

@section('styles')
   <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="content-wrapper">
    <div class="dashboard-header">
      <h1>Dashboard Control Panel</h1>
    </div>
    <a class="cards" href="{{ route('admin.stock.index') }}">
    <div class="stats-container">
      <div class="stat-card card-products">
        <i class="fas fa-box stat-icon"></i>
        <h3>Products</h3>
        <p>{{ $stockCount }}</p>
        <div class="more-info">More Info →</div>
      </div>
      </a>
        
      <div class="stat-card card-purchase">
        <i class="fas fa-file-invoice-dollar stat-icon"></i>
        <h3>Purchase Order</h3>
        <p>{{ $purchaseOrderCount }}</p>
        <div class="more-info">More Info →</div>
      </div>
      <a class="cards" href="{{ route('admin.category.index') }}">
      <div class="stat-card card-category">
        <i class="fas fa-layer-group stat-icon"></i>
        <h3>Category</h3>
        <p>{{ $categoryCount }}</p>
        <div class="more-info">More Info →</div>
      </div>
      </a>

      <a class="cards" href="{{ route('admin.vendors.index') }}">
        <div class="stat-card card-vendors">
          <i class="fas fa-users stat-icon"></i>
          <h3>Vendors</h3>
          <p>{{ $vendorCount }}</p>
          <div class="more-info">More Info →</div>
        </div>
      </a>
    </div>
</div>
@endsection
