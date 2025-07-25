<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="{{ url('/admin/dashboard') }}" class="brand-link">
    <img src="https://adminlte.io/themes/v4/dist/assets/img/AdminLTELogo.png"
      alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text fw-light">Admin Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->is('admin/products') ? 'active' : '' }}">
            <i class="nav-icon fas fa-box-open"></i>
            <p>Produk</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.categories.index') }}" class="nav-link">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Katalog</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.galleries.index') }}" class="nav-link">
            <i class="nav-icon fas fa-image"></i>
            <p>Galeri</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.orders.index') }}" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>Pesanan</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.contact.index') }}" class="nav-link">
            <i class="nav-icon fas fa-envelope"></i>
            <p>Kontak</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.sliders.index') }}" class="nav-link">
            <i class="nav-icon fas fa-sliders-h"></i>
            <p>Slider</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>