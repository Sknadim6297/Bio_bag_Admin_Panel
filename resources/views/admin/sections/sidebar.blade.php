<div class="sidebar">
    <div>
      <div class="sidebar-header">
        <h2>Bio Bag</h2>
      </div>
      <nav class="sidebar-nav">
        <ul>
          <li>
            <a href="{{ route('admin.admin.dashboard') }}"
              ><i class="fas fa-dashboard"></i>&nbsp;&nbsp; Dashboard</a
            >
          </li>
          {{-- <li>
            <a href="user.html"><i class="fas fa-user"></i>&nbsp;&nbsp; User</a>
          </li> --}}
          <li class="menu-item">
            <a href="{{ route('admin.vendors.index') }}" class="has-submenu"
              ><i class="fas fa-briefcase"></i>&nbsp;&nbsp;Manage Categories
              </a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.category.index') }}"><i class="fas fa-list"></i>Category</a>
              </li>
            </ul>
            
          </li>
          <li class="menu-item">
            <a href="{{ route('admin.vendors.index') }}" class="has-submenu"
              ><i class="fas fa-briefcase"></i>&nbsp;&nbsp; Vendor
              Management</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.vendors.index') }}"><i class="fas fa-list"></i> Manage Vendor</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-th-large"></i>&nbsp;&nbsp; Customer
              Management</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.customer.index') }}"><i class="fas fa-list"></i> Manage Customer</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-cubes"></i>&nbsp;&nbsp; SKU Management (Raw
              Materials)</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.sku.index') }}"><i class="fas fa-list"></i> Manage SKU/Product</a>
              </li>
              
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-file-alt"></i>&nbsp;&nbsp; GRN Management</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.grn.index') }}"><i class="fas fa-list"></i> Manage Purchase Bill</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-warehouse"></i>&nbsp;&nbsp; Stock Management
              (Raw Materials)</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.stock.index') }}"><i class="fas fa-list"></i>Manage Stock</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="{{ route('admin.consumption.index') }}" class="has-submenu"
              ><i class="fas fa-boxes"></i>&nbsp;&nbsp; Consumption</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.consumption.index') }}"
                  ><i class="fas fa-list"></i> Manage Consumption</a
                >
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="{{ route('admin.production.index') }}" class="has-submenu"
              ><i class="fas fa-boxes"></i>&nbsp;&nbsp; Production</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.production.index') }}"
                  ><i class="fas fa-list"></i> Manage Production</a
                >
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-boxes"></i>&nbsp;&nbsp; Final Output
              Product</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.final-output.index') }}"
                  ><i class="fas fa-list"></i>Manage Final Output</a
                >
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu"
              ><i class="fas fa-chart-line"></i>&nbsp;&nbsp; Report
              Management</a
            >
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.wastage1.index') }}"
                  ><i class="fas fa-recycle"></i> Wastage 1 (Consumption → Production)</a
                >
              </li>
              <li>
                <a href="{{ route('admin.wastage2.index') }}"
                  ><i class="fas fa-recycle"></i> Wastage 2 (Production → Final)</a
                >
              </li>
            </ul>
          </li>
          <!-- Add this to your sidebar menu -->
          <li class="menu-item">
            <a href="#" class="has-submenu">
              <i class="fas fa-box-open"></i>&nbsp;&nbsp; Inventory Management</a>
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.inventory.index') }}"><i class="fas fa-list"></i> Manage Inventory</a>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <a href="#" class="has-submenu">
              <i class="fas fa-file-invoice"></i>&nbsp;&nbsp; Invoice Management</a>
            <ul class="submenu">
              <li>
                <a href="{{ route('admin.invoice.index') }}"><i class="fas fa-list"></i> All Invoices</a>
              </li>
              <li>
                <a href="{{ route('admin.invoice.create') }}"><i class="fas fa-plus"></i> Create Invoice</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </div>