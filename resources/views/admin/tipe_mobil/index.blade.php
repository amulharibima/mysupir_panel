@extends('master')

@section('title', 'Tipe Mobil MySupir')
@section('tipemobil', 'active')
@section('tipemenu', 'menu-open')
@section('listtipe', 'active')

@push('extra-css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">List Tipe Mobil</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Tipe Mobil</li>
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
                <h3 class="card-title">List Supir</h3>

                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-3">
                <table id="example1" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tipe Mobil</th>
                      <th>Foto</th>
                      <th>Lisensi</th>
                      <th>Action</th>
                      <!-- <th>Foto</th> -->
                      <!-- <th style="width: 200px">Action</th> -->
                    </tr>
                  </thead>
                  <tbody>
                  @php $i=1; @endphp
                  @foreach ($cartype as $data)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$data->name}}</td>
                        <td>
                            <div class="text-center">
                                <a href="{{url('/get_image?img_path='.$data->foto)}}" 
                                   data-toggle="lightbox" 
                                   data-title="{{$data->name}}" 
                                   data-gallery="gallery">
                                <img class="profile-user-img img-fluid"
                                    style="height: 100px !important; object-fit: cover;"
                                    src="{{url('/get_image?img_path='.$data->foto)}}"
                                    alt="User profile picture">
                            </div>
                        </td>
                        <td>{{$data->license->name}}</td>
                        <td>
                            <div class="container-fluid">
                                <div class="row">
                                    <form style="display: contents;" method="POST" action="{{ route('admin.tipe.delete', [$data->id]) }}">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-sm btn-danger float-left btn-block">Delete</button>
                                    </form>
                                </div>
                            </div>
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
<!-- Ekko Lightbox -->
<script src="{{asset('plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true
        });
    });

    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": true,
      "order": []
    });
  });
</script>
    <script>
        $(function () {
        })
    </script>
@endpush