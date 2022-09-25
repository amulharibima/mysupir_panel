@extends('master')

@section('title', 'Pencairan Dana')
@section('dana', 'active')

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
                    <h1 class="m-0 text-dark">Pencairan Dana</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Dana</li>
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
                            <h3 class="card-title">Tabel Pencairan Dana</h3>


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-3">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Nama Supir</th>
                                    <th>Status</th>
                                    <th>Nominal</th>
                                    <th>Bank</th>
                                    <th>Nomor Rekening</th>
                                    <th>Pemegang Rekening</th>
                                    <th>Tanggal</th>
                                    <!-- <th style="width: 150px">Action</th> -->
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($request as $request)
                                <tr>
                                    <td>{{$request->driver->name}}</td>
                                    <td>{{$request->status === 'requested' ? 'Sedang Diproses' : 'Telah Ditransfer'}}</td>
                                    <td>Rp. @convert($request->nominal)</td>
                                    <td>{{$request->bank}}</td>
                                    <td>{{$request->bank_account_number}}</td>
                                    <td>{{$request->bank_account_holder}}</td>
                                    <td>{{\Carbon\Carbon::parse($request->created_at)->format('d F Y')}}</td>
                                    <td>
                                    @if ($request->status === 'requested')
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="javascript:void(0)" 
                                                class="btn btn-sm btn-info btn-block float-left konfirmasi"
                                                data-orderid="{{$request->id}}" >Konfirmasi</a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-block btn-secondary float-left">Selesai</a>
                                        </div>
                                        <!-- <div class="col-6">
                                            <a href="javascript:void(0)" class="btn btn-sm btn-block btn-secondary float-left">Selesai</a>
                                        </div> -->
                                    </div>
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
                <h4 class="modal-title">Konfirmasi Pendapatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <p>Apakah anda yakin akan mengkonfirmasi penarikan pendapatan pada driver ini?</p>
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
        <!-- /.modal -->

        <div class="modal fade" id="modal-danger">
            <div class="modal-dialog">
            <div class="modal-content bg-danger">
                <div class="modal-header">
                <h4 class="modal-title">Konfirmasi Pendapatan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                <p>Apakah anda yakin akan mengkonfirmasi penarikan pendapatan pada driver ini?</p>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btnSubmit">Konfirmasi</button>
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <form id="formKonfirmasi" action="{{url('/admin/dana/konfirmasi')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="request_id" id="request_id" value="">
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
                "autoWidth": false,
            });

            $('#example1').delegate('.konfirmasi', 'click', function(e) {
                e.preventDefault();
                console.log($(this).data('orderid'));
                // $('.btnSubmit').attr('data-orderid', $(this).data('orderid'));
                $('#request_id').val($(this).data('orderid'));
                $('#modal-default').modal('show');
            });

            $('#modal-default').delegate('.btnSubmit', 'click', function(e) {
                e.preventDefault();
                $('#formKonfirmasi').submit();
                //  alert('berhasil');
            });
        });
    </script>
@endpush
