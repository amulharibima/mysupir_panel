@extends('master')

@section('title', 'Tarif')
@section('masterdata', 'active')
@section('datamenu', 'menu-open')
@section('tarif', 'active')

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
                    <h1 class="m-0 text-dark">Tarif</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Tarif</li>
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
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent">
                            <h3 class="card-title">Tarif</h3>

                            <div class="card-tools">
                                <a href="{{url('/admin/masterdata/create-tarif')}}" class="fa fa-plus" style="margin-right: 30px"> Create</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-3">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tipe</th>
                                    <th>Jarak/Waktu (km/jam)</th>
                                    <th>Tarif</th>
                                    <th style="width: 100px">Action</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Trip</td>
                                    <td>5</td>
                                    <td>Rp. 10000</td>
                                    <td>
                                        <a href="{{url('/admin/masterdata/edit-tarif')}}" class="btn btn-sm btn-info float-left">Edit</a>&nbsp;&nbsp;&nbsp;
                                        <a href="javascript:void(0)" class="btn btn-sm btn-info float-left ml-2">Delete</a>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Tipe</th>
                                    <th>Jarak/Waktu (km/jam)</th>
                                    <th>Tarif</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <!-- <div class="card-footer clearfix">
                          <a href="javascript:void(0)" class="btn btn-sm btn-info float-left">Place New Order</a>
                          <a href="javascript:void(0)" class="btn btn-sm btn-secondary float-right">View All Orders</a>
                        </div> -->
                        <!-- /.card-footer -->
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
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endpush

