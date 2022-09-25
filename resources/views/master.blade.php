<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title')</title>

  <!-- Font Awesome Icons -->
  <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @stack('extra-css')
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-navy">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-cog"></i>
          <!-- <span class="badge badge-warning navbar-badge">15</span> -->
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">Setting</span>
          <div class="dropdown-divider"></div>
          <a href="{{ route('logout') }}"  class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
          </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-navy elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('admin.home')}}" class="brand-link navbar-light">
      <img src="{{asset('dist/img/MySupir-User.jpg')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Admin MySupir</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="{{route('admin.home')}}" class="nav-link @yield('dashboard')">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item @yield('supirmenu')">
            <a href="#" class="nav-link @yield('supir')">
              <i class="nav-icon fas fa-taxi"></i>
              <p>
                Supir
                <!-- <span class="right badge badge-info">New</span> -->
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/supir')}}" class="nav-link @yield('listsupir')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Supir</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/supir/locations')}}" class="nav-link @yield('lokasisupir')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Lokasi Supir</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/supir/create')}}" class="nav-link @yield('supircreate')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buat Akun Baru</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/admin/supir/histori')}}" class="nav-link @yield('histori')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Histori Foto</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @yield('tipemenu')">
            <a href="#" class="nav-link @yield('tipemobil')">
              <i class="nav-icon fas fa-tasks"></i>
              <p>
                Tipe Mobil
                <!-- <span class="right badge badge-info">New</span> -->
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/tipemobil')}}" class="nav-link @yield('listtipe')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Tipe Mobil</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/tipemobil/create')}}" class="nav-link @yield('tipecreate')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buat Tipe Mobil Baru</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @yield('cancelsmenu')">
            <a href="#" class="nav-link @yield('cancels')">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                Alasan Pembatalan
                <!-- <span class="right badge badge-info">New</span> -->
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/cancels')}}" class="nav-link @yield('listcancels')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Alasan Pembatalan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/cancels/create')}}" class="nav-link @yield('cancelscreate')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buat Alasan Pembatalan</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @yield('kotamenu')">
            <a href="#" class="nav-link @yield('kota')">
              <i class="nav-icon fas fa-city"></i>
              <p>
                Kota
                <!-- <span class="right badge badge-info">New</span> -->
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/admin/kota')}}" class="nav-link @yield('listkota')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>List Daftar Kota</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{url('/admin/kota/create')}}" class="nav-link @yield('kotacreate')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Buat Kota Baru</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/pengguna')}}" class="nav-link @yield('pengguna')">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Pengguna
                <!-- <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/harga')}}" class="nav-link @yield('harga')">
              <i class="nav-icon fas fa-dollar-sign"></i>
              <p>
                Harga
                <!-- <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/fee')}}" class="nav-link @yield('fee')">
              <i class="nav-icon fas fa-dollar-sign"></i>
              <p>
                Fee
                <!-- <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/laporan-kerusakan')}}" class="nav-link @yield('laporan-kerusakan')">
              <i class="nav-icon fas fa-window-close"></i>
              <p>
                Laporan Kerusakan
                <!-- <i class="fas fa-angle-left right"></i>
                <span class="badge badge-info right">6</span> -->
              </p>
            </a>

          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/transaksi')}}" class="nav-link @yield('transaksi')">
              <i class="nav-icon fas fa-history"></i>
              <p>
                Transaksi
                <!-- <i class="right fas fa-angle-left"></i> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/dana')}}" class="nav-link @yield('dana')">
              <i class="nav-icon fas fa-credit-card"></i>
              <p>
                Pencairan Dana
                <!-- <i class="fas fa-angle-left right"></i> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="{{url('/admin/darurat')}}" class="nav-link @yield('darurat')">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>
                Riwayat Darurat
                <!-- <i class="fas fa-angle-left right"></i> -->
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <form id="logout" action="/logout" method="post">
              @csrf
            </form>
            <a href="#" onclick="$('#logout').submit()" class="nav-link @yield('darurat')">
              <i class="nav-icon fas fa-door-open"></i>
              <p>
                Keluar
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @yield('content')
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <!-- <footer class="main-footer" style="padding: 0.5rem 1rem;">
    <strong>Copyright &copy; 2020 <a href="#">My Supir</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.5
    </div>
  </footer> -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ mix('/js/app.js') }}"></script>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- SweetAlert2 -->
<script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>

<script>
  $(function () {
    // console.log('hehe');
    Echo.private('App.User.8').notification((notification) => {
        // console.log(notification );
        // alert('ini masuk panic baru woy!!');
        Swal.fire({
            title: 'Sinyal Darurat',
            text: "Seseorang baru saja mengirimkan sinyal darurat, tanggapi sekarang!",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Go To Panic! Page',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                window.location = "/admin/darurat";
            }
        });
    });
  });
</script>
@stack('extra-js')

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="{{asset('plugins/jquery-mousewheel/jquery.mousewheel.js')}}"></script>
<script src="{{asset('plugins/raphael/raphael.min.js')}}"></script>
<script src="{{asset('plugins/jquery-mapael/jquery.mapael.min.js')}}"></script>
<script src="{{asset('plugins/jquery-mapael/maps/usa_states.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>

<!-- PAGE SCRIPTS -->
<script src="{{asset('dist/js/pages/dashboard2.js')}}"></script>

</body>
</html>
