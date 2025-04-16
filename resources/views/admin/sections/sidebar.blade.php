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
          <li>
            <a href="user.html"><i class="fas fa-user"></i>&nbsp;&nbsp; User</a>
          </li>
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
                <a href="{{ route('admin.grn.index') }}"><i class="fas fa-list"></i> Manage Purchase Order</a>
              </li>
              <li>
                <a href="{{ route('admin.grn.manage') }}"><i class="fas fa-list"></i> Manage GRN Process</a>
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
                <a href="manage-stock.html"><i class="fas fa-list"></i>Manage Stock</a>
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
                <a href="final-output.html"
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
                <a href="#"
                  ><i class="fas fa-file-alt"></i> Inventory Reports</a
                >
              </li>
              <li>
                <a href="#"
                  ><i class="fas fa-file-invoice"></i> Transaction
                  Reports</a
                >
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </div>