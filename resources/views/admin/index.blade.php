@extends('master')

@section('title', 'Dashboard')
@section('dashboard', 'active')

@push('extra-css')
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Log Darurat</span>
                <span class="info-box-number">
                  {{$countpanic}}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-history"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Transaksi</span>
                <span class="info-box-number">{{$countorder}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-taxi"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Driver Terdaftar</span>
                <span class="info-box-number">{{$countdriver}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pengguna Baru</span>
                <span class="info-box-number">{{$countuser}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <div class="col-md-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-header">
                <h3 class="card-title">Pendapatan</h3>
                </div>
                <div class="card-body p-0">
                <div id="pendapatan-chart"></div>
                <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
            </div>
          </div>
          <div class="col-md-8">
            <!-- TABLE: LATEST ORDERS -->
            <div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Transaksi Terkini</h3>

                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Order ID</th>
                      <th>Pemesan</th>
                      <th>Supir</th>
                      <th>Tipe Order</th>
                      <th>Alamat Jemput / Tujuan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($order as $order)
                    <tr>
                      <td><a href="#">{{$order->identifier}}</a></td>
                      <td>{{$order->user->name}}</td>
                      <td>{{$order->driver_id ? $order->driver->name : "Supir belum tersedia"}}</td>
                      <td>
                        <div class="ml-3">
                          <span class="badge {{$order->type == 'time' ? 'badge-success' : 'badge-info'}}">{{$order->type == 'time' ? 'Time' : 'Trip'}}</span>
                        </div>
                      </td>
                      <td>
                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                          {{$order->type == 'time' ? mb_strimwidth($order->locations[0]->name, 0, 35, "...") : mb_strimwidth($order->locations[1]->name, 0, 20, "...")}}
                          <!-- {{mb_strimwidth("Hello World", 0, 20, "...")}} -->
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->

          <div class="col-md-4">
            <!-- USERS LIST -->
            <div class="card">
                <div class="card-header">
                <h3 class="card-title">Driver Terkini</h3>

                <div class="card-tools">
                    <span class="badge badge-danger">{{$driver->count()}} Driver Baru</span>
                </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                <ul class="users-list clearfix">
                    @foreach($driver as $d)
                    <a href="{{url('/admin/supir/detail/'.$d->id)}}">
                    <li> 
                    @if($d->foto)
                      <img src="{{strpos($d->foto, 'picsum') !== false ? $d->foto : url('/get_image?img_path='.$d->foto) }}" 
                           style="height: 78.99px !important; width: 78.99px !important; object-fit: cover;"
                           alt="User Image">
                    @else
                      <img src="{{asset('default_user.png')}}" alt="User Image">
                    @endif
                    <a class="users-list-name" href="{{url('/admin/supir/detail/'.$d->id)}}">
                    @php
                      $arr = explode(' ',trim($d->name));
                    @endphp
                    {{$arr[0]}}
                    </a>
                    <span class="users-list-date">{{$d->licenses[0]->name}}</span>
                    </li>
                    </a>
                    @endforeach
                </ul>
                <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-center">
                <a href="javascript::">View All Users</a>
                </div>
                <!-- /.card-footer -->
            </div>
            <!--/.card -->
            <!-- Calendar -->
            <!-- /.card -->
          </div>

          <div class="col-md-8">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-header">
                <h3 class="card-title">Perbandingan Supir</h3>
                </div>
                <div class="card-body p-0">
                <div id="piechart" style="margin: 0 25%"></div>
                <!-- /.users-list -->
                </div>
                <!-- /.card-body -->
            </div>
            <!--/.card -->
            <!-- Calendar -->
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('extra-js')
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Supir', 'Jumlah'],
  ['Aktif', {{$countactivedriver}}],
  ['Nonaktif', {{$countnonactivedriver}}],
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'width':550, 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);

  var pendapatan_data = google.visualization.arrayToDataTable([
        ["Bulan", "Pendapatan"],
        @foreach($arrMonths as $k => $m)
          ["{{$m}}", {{isset($earnings_per_month[$k + 1][0]) ? $earnings_per_month[$k + 1][0]->amount : 0}}],
        @endforeach
      ]);

  var pendapatan_view = new google.visualization.DataView(pendapatan_data);

  pendapatan_view.setColumns([0, 1,
  { calc: "stringify",
    sourceColumn: 1,
    type: "string",
    role: "annotation" },
  ]);

  var pendapatan_options = {
    title: `Tahun ${new Date().getFullYear()}`,
    height: 400,
    bar: {groupWidth: "40%"},
    chartArea: {width: "100$"},
    legend: { position: "none" },
  };
  var pendapatan_chart = new google.visualization.ColumnChart(document.getElementById("pendapatan-chart"));
  pendapatan_chart.draw(pendapatan_view, pendapatan_options);
}
</script>
@endpush