@extends('master')

@section('title', 'List Supir')
@section('supir', 'active')
@section('supirmenu', 'menu-open')
@section('listsupir', 'active')

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
            <h1 class="m-0 text-dark">List Supir</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
              <li class="breadcrumb-item active">Supir</li>
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
                      <th>Nama</th>
                      <th>Identifier</th>
                      <th>Lisensi</th>
                      <th>Email</th>
                      <th>Alamat</th>
                      <!-- <th>Foto</th> -->
                      <th style="width: 200px">Action</th>
                      <!-- <th>Action</th> -->
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($driver as $driver)
                    <tr>
                      <td>{{$driver->name}}</td>
                      <td>{{$driver->identifier}}</td>
                      <td>
                        @php $count = $driver->licenses()->count();
                             $i = 1;
                        @endphp
                        @foreach ($driver->licenses()->get() as $license) 
                          @if($i < $count)
                            {{$license->name.', '}}
                          @else
                            {{$license->name}}
                          @endif
                            @php $i++; @endphp 
                        @endforeach
                      </td>
                      <td>{{$driver->email}}</td>
                      <td>{{$driver->address}}</td>
                      <td>
                        <div class="row">
                          <div class="col-4">                            
                            <a href="{{url('/admin/supir/detail')}}/{{$driver->id}}" class="btn btn-sm btn-info btn-block float-left">Detail</a> 
                          </div>
                          <div class="col-4">                            
                            <a href="{{url('/admin/supir/edit-form/')}}/{{$driver->id}}" class="btn btn-sm btn-warning float-left btn-block">Edit</a> 
                          </div>
                          <div class="col-4">                            
                            <form style="display: contents;" method="POST" action="{{ route('admin.supir.delete', [$driver->id]) }}">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-sm btn-danger float-left btn-block">Delete</button>
                            </form>
                          </div>
                          <div class="col-12 mt-2">
                          <form style="display: contents;" method="POST" action="{{ route('admin.supir.suspend', [$driver->id]) }}">
                                {{ csrf_field() }}
                                @if($driver->is_suspended)
                                  <p>Alasan suspend : </p>
                                  <p>{{$driver->alasan_suspend}}</p>
                                @else
                                  <input type="text" name="alasan_suspend" class="form-control mb-2" placeholder="Alasan suspend..." required>
                                @endif
                                <button type="submit" class="btn btn-sm btn-{{$driver->is_suspended ? 'success' : 'danger'}} float-left btn-block">{{$driver->is_suspended ? 'Cabut suspend' : 'Suspend'}}</button>
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
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": true,
      "order": []
    });
  });
</script>
@endpush