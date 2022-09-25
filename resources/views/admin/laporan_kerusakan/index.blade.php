@extends('master')

@section('title', 'Laporan Kerusakan')
@section('laporan-kerusakan', 'active')

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
                    <h1 class="m-0 text-dark">Laporan Kerusakan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Kerusakan</li>
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
                            <h3 class="card-title">Tabel Laporan Kerusakan</h3>


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-3">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID Order</th>
                                    <th>Nama Customer</th>
                                    <th>Rating</th>
                                    <th>Catatan</th>
                                    <th>Foto Kerusakan</th>
                                    <th>Tanggal</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($orders as $o)
                                <tr>
                                    <td>{{$o->identifier}}</td>
                                    <td>{{$o->user->name}}</td>
                                    <td>{{$o->rating->rating}}</td>
                                    <td>{{$o->crash_report->notes}}</td>
                                    <td>
                                        @foreach($o->crash_report->photos as $cr)
                                            <a href="/get_image?img_path={{$cr}}" target="_blank">
                                                <img style="height: 50px; display: block; margin: 15px auto" src="/get_image?img_path={{$cr}}" alt="">
                                            </a>
                                        @endforeach
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($o->created_at)->format('d F Y')}}</td>
                                    <td>
                                        @if($o->crash_report->is_solved)
                                            <div class="row">
                                                <div class="col-12">
                                                    <button class="btn btn-sm btn-info btn-block float-left konfirmasi"
                                                        disabled >Solved</a>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <a href="javascript:void(0)" 
                                                        class="btn btn-sm btn-info btn-block float-left konfirmasi"
                                                        data-orderid="{{$o->id}}" >Solve</a>
                                                </div>
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
                <h4 class="modal-title">Solve Laporan<n/h4> 
                </div>
                <div class="modal-body">
                <p>Apakah anda yakin akan solve laporan ini?</p>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btnSubmit" data-orderid="">Ya</button>
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
        <form id="formKonfirmasi" action="{{url('/admin/laporan-kerusakan/solve')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="order_id" id="order_id" value="">
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
                $('#order_id').val($(this).data('orderid'));
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
