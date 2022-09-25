@extends('master')

@section('title', 'Riwayat Darurat')
@section('darurat', 'active')

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
                    <h1 class="m-0 text-dark">Riwayat Darurat</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Transaksi</li>
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
                            <h3 class="card-title">Tabel Riwayat Darurat</h3>


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-3">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <!-- <th>Order Identifier</th> -->
                                    <th>Pengguna</th>
                                    <th>No Telepon</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th style="max-width: 400px">Koordinat</th>
                                    <!-- <th style="width: 100px">Action</th> -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($panics as $panic)
                                <tr>
                                    <!-- <td>{{$panic->order_id ? $panic->order->identifier : 'Tidak Ada'}}</td> -->
                                    <td>{{$panic->user->name}}</td>
                                    <td>{{'(+62) '.$panic->user->phone_number}}</td>
                                    <td>{{\Carbon\Carbon::parse($panic->created_at)->format('d F Y')}}</td>
                                    <td>{{$panic->status ? 'Sudah ditangani' : 'Belum ditangani'}}</td>
                                    <td><a href={{"https://google.com/search?q={$panic->latitude}+{$panic->longitude}"}} target="blank">{{$panic->latitude.' '.$panic->longitude}}</a></td>
                                    <td>
                                    @if ($panic->status)
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-block btn-secondary float-left">Selesai</a>
                                    @else
                                        <a href="javascript:void(0)" 
                                           id="btn-verify" 
                                           data-panicid="{{$panic->id}}"
                                           class="btn btn-sm btn-block btn-info float-left">Konfirmasi</a>                                        
                                    @endif
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
        <div class="modal fade" id="modal-default">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Penanganan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <p>Apakah anda yakin sudah menangani panic! pada user ini?</p>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btnSubmit" data-orderid="">Konfirmasi</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <form id="formKonfirmasi" action="{{url('/admin/darurat/konfirmasi')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="panic_id" id="panic_id" value="">
        </form>
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
                "autoWidth": true,
                "order": [],
            });

            $('#example1').delegate('#btn-verify', 'click', function(e) {
                e.preventDefault();
                $('#panic_id').val($(this).data('panicid'));
                $('#modal-default').modal('toggle');
            });

            $('#modal-default').delegate('.btnSubmit', 'click', function(e) {
                e.preventDefault();
                // alert('btnSubmit sukses!');
                $('#formKonfirmasi').submit();
            });
        });
    </script>
@endpush
