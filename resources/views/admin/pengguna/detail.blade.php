@extends('master')

@section('title', 'Detail Pengguna')
@section('pengguna', 'active')

@push('extra-css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Detail Pengguna</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}" style="color: #001F3D;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/pengguna')}}" style="color: #001F3D;">Pengguna</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- Widget: user widget style 1 -->
                    <div class="card card-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header" style="background-color: #002952; color:white;">
                            <h3 class="widget-user-username">{{$user->name}}</h3>
                            <h5 class="widget-user-desc">{{$user->email}}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle elevation-2" src="{{asset('default_user.png')}}" alt="User Avatar">
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{count($orders)}}</h5>
                                        <span class="description-text">Order</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">(+62) {{$user->phone_number}}</h5>
                                        <span class="description-text">No Telepon</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">{{$panic}}</h5>
                                        <span class="description-text">Panic!</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Riwayat Order</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-3">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Identifier</th>
                                    <th>Nama Supir</th>
                                    <th>Tipe Order</th>
                                    <th>Rating</th>
                                    <th>Dibuat</th>
                                    <th>Action</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($orders as $order)
                                <tr>
                                    <td>{{$order->identifier}}</td>
                                    <td>{{$order->driver->name}}</td>
                                    <td>
                                        <div class="ml-3">
                                            <span class="badge {{$order->type == 'time' ? 'badge-success' : 'badge-info'}}">{{$order->type == 'time' ? 'Time' : 'Trip'}}</span>
                                        </div>
                                    </td>
                                    <td>{{$order->rating()->get()->isEmpty() ? 'Belum ada rating' : $order->rating()->first()->rating}}</td>
                                    <td>{{\Carbon\Carbon::parse($order->created_at)->format('d F Y')}}</td>
                                    <td>
                                        <a href="{{url('/admin/transaksi/detail/'.$order->id)}}" class="btn btn-sm btn-info float-left">Detail</a>&nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>

                    </div>
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
    <!-- DataTables -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
                "order": [],
            });
        });
    </script>
@endpush
