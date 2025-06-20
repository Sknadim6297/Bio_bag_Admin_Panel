@extends('layouts.layout')

@section('styles')
   <link rel="stylesheet" href="{{ asset('admin/css/styles.css') }}" />
@endsection

@section('content')
<div class="content-wrapper" style="background: linear-gradient(135deg, #f1efec 0%, #e9e4d9 100%); min-height: 80vh;">
    <div class="dashboard-header">
      <div>
        <h1>Dashboard Control Panel</h1>
        <p style="color: var(--deep-blue); font-size: 18px; margin-top: 8px; font-weight: 400;">Welcome to your admin dashboard. Manage your business at a glance!</p>
      </div>
    </div>
    <div class="stats-container">
      <a class="cards" href="{{ route('admin.stock.index') }}">
        <div class="stat-card card-products" tabindex="0">
          <i class="fas fa-box stat-icon"></i>
          <h3>Products</h3>
          <div class="more-info">More Info →</div>
        </div>
      </a>
      <div class="stat-card card-purchase" style="background: linear-gradient(120deg, #f8fafc 60%, #d4c9be 100%); box-shadow: 0 8px 32px rgba(18,52,88,0.07); border-left: 6px solid var(--accent-dark);" tabindex="0">
        <i class="fas fa-file-invoice-dollar stat-icon"></i>
        <h3>Purchase Bill</h3>
        <div class="more-info">More Info →</div>
      </div>
      <a class="cards" href="{{ route('admin.category.index') }}">
        <div class="stat-card card-category" tabindex="0">
          <i class="fas fa-layer-group stat-icon"></i>
          <h3>Category</h3>
          <div class="more-info">More Info →</div>
        </div>
      </a>
      <a class="cards" href="{{ route('admin.vendors.index') }}">
        <div class="stat-card card-vendors" tabindex="0">
          <i class="fas fa-users stat-icon"></i>
          <h3>Vendors</h3>
          <div class="more-info">More Info →</div>
        </div>
      </a>
      <a class="cards" href="{{ route('admin.inventory.index') }}">
        <div class="stat-card card-inventory" style="background: linear-gradient(120deg, #f8fafc 60%, #b2925e 100%); box-shadow: 0 8px 32px rgba(18,52,88,0.07); border-left: 6px solid var(--accent-gold);" tabindex="0">
          <i class="fas fa-boxes stat-icon"></i>
          <h3>Raw Materials Inventory</h3>
          <div class="more-info">More Info →</div>
        </div>
      </a>
    </div>
</div>
<style>
:root {
  --bg-light: #f1efec;
  --cream: #d4c9be;
  --deep-blue: #123458;
  --black: #030303;
  --card-glass: rgba(244, 240, 233, 0.5);
  --accent-blue: #123458;
  --accent-cream: #d4c9be;
  --accent-dark: #030303;
  --accent-gold: #b2925e;
}
.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 40px;
  margin-bottom: 30px;
}
.stat-card {
  background: var(--card-glass);
  backdrop-filter: blur(12px);
  border: 1.5px solid rgba(255, 255, 255, 0.25);
  border-bottom: 3px solid #123458;
  border-radius: 22px;
  padding: 48px 32px 40px 32px;
  min-height: 210px;
  box-shadow: 0 12px 32px rgba(18,52,88,0.10);
  transition: transform 0.3s, box-shadow 0.3s, background 0.3s;
  position: relative;
  cursor: pointer;
  outline: none;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}
.stat-card:hover, .stat-card:focus {
  transform: translateY(-10px) scale(1.04);
  box-shadow: 0 20px 48px rgba(18,52,88,0.18);
  background: linear-gradient(120deg, #f8fafc 60%, #d4c9be 100%);
}
.stat-card h3 {
  font-size: 28px;
  color: var(--deep-blue);
  margin-bottom: 18px;
  font-weight: 700;
}
.stat-card .more-info {
  margin-top: 18px;
  font-size: 18px;
  color: var(--deep-blue);
  text-align: right;
  cursor: pointer;
  font-weight: 500;
}
.stat-icon {
  position: absolute;
  top: 32px;
  right: 32px;
  font-size: 60px;
  opacity: 0.16;
  color: var(--deep-blue);
}
.card-products {
  border-left: 8px solid var(--accent-blue);
}
.card-purchase {
  border-left: 8px solid var(--accent-dark);
}
.card-category {
  border-left: 8px solid var(--accent-gold);
}
.card-vendors {
  border-left: 8px solid var(--accent-cream);
}
.card-inventory {
  border-left: 8px solid var(--accent-gold);
}
.cards {
  text-decoration: none;
}
@media (max-width: 900px) {
  .stats-container {
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }
  .stat-card {
    min-height: 180px;
    padding: 32px 18px 28px 18px;
  }
  .stat-card h3 {
    font-size: 22px;
  }
  .stat-icon {
    font-size: 40px;
    top: 18px;
    right: 18px;
  }
}
@media (max-width: 600px) {
  .stats-container {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  .stat-card {
    min-height: 120px;
    padding: 20px 10px 18px 10px;
  }
  .stat-card h3 {
    font-size: 18px;
  }
  .stat-icon {
    font-size: 28px;
    top: 10px;
    right: 10px;
  }
}
</style>
@endsection
