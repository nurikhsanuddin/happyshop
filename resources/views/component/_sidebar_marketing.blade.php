<div class="sidebar" data-color="red">
  <!--
        Tip 1: You can change the color of the fdebar using: data-color="blue | green | orange | red | yellow"
        -->
  <div class="logo">
    <a href="http://www.creative-tim.com" class="simple-text logo-mini">
      TH
    </a>
    <a href="{{ route('home') }}" class="simple-text logo-normal">
      Toko Happy
    </a>
  </div>
  <div class="sidebar-wrapper" id="sidebar-wrapper">
    <ul class="nav">
      <li class="@if(request()->routeIs('marketing.dashboard.index')) active @endif">
        <a href="{{ route('marketing.dashboard.index') }}">
          <i class="now-ui-icons design_app"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="@if(request()->routeIs('marketing.product.index')) active @endif">
        <a href="{{ route('marketing.product.index') }}">
          <i class="now-ui-icons design_palette"></i>
          <p>Produk</p>
        </a>
      </li>
      <li class="@if(request()->routeIs('marketing.transaction.index')) active @endif">
        <a href="{{ route('marketing.transaction.index') }}">
          <i class="now-ui-icons design_palette"></i>
          <p>Transaksi</p>
        </a>
      </li>
    </ul>
  </div>
</div>