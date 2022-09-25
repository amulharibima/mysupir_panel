@extends('master')

@section('title', 'Detail Supir')
@section('supir', 'active')
@section('supirmenu', 'menu-open')
@section('listsupir', 'active')

@push('extra-css')
  <!-- Ekko Lightbox -->
  <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/ekko-lightbox.css')}}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Detail Supir</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{url('/admin/supir')}}">Supir</a></li>
              <li class="breadcrumb-item active">Detail</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-navy card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                                style="height: 100px !important;"
                                src="{{!$driver->foto ? asset('default_user.png') : (strpos($driver->foto, 'picsum') !== false ? $driver->foto : url('/get_image?img_path='.$driver->foto) )}}"
                                alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{$driver->name}}</h3>

                            <p class="text-muted text-center">(+62) {{$driver->phone_number}}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Order Done</b> <a class="float-right">{{$driver->finished_orders()->count()}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Earning</b> <a class="float-right">Rp. @convert($driver->earning()->first()->amount)</a>
                            </li>
                            </ul>

                            <!-- <a href="#" class="btn btn-danger btn-block"><b>Edit</b></a> -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-navy">
                        <div class="card-header">
                            <h3 class="card-title">About Me</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-at mr-1"></i> Email</strong>

                            <p class="text-muted">
                            {{$driver->email}}
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>

                            <p class="text-muted">{{$driver->address}}</p>

                            <hr>

                            <strong><i class="fas fa-address-card mr-1"></i> Licenses</strong>

                            <p class="text-muted">
                            @php $count = $driver->licenses()->count();
                                $i = 1;
                            @endphp
                            @foreach ($driver->licenses()->get() as $license) 
                            @if($i < $count)                                
                                <span class="tag tag-info">{{ucwords($license->name).', '}}</span>
                            @else                                
                                <span class="tag tag-info">{{ucwords($license->name)}}</span>
                            @endif
                                @php $i++; @endphp 
                            @endforeach
                            </p>

                            <hr>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-9">
                    <div class="card card-navy">
                        <div class="card-header">
                            <div class="card-title">
                            Driver Presensi
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php 
                                    $index = 0;
                                    $count = $driver->driver_photos()->count();
                                    $photo_path = $driver->driver_photos()->get();
                                    $sisa = $count%2;
                                @endphp
                                @if ($count > 1)
                                    <div class="col-sm-6">
                                        @for ($i=$index; $i<$count/2; $i++)
                                            <div class="card p-1">
                                                <a href="{{url('/get_image?img_path='.$photo_path[$i]->photo)}}" data-toggle="lightbox" data-title="{{$photo_path[$i]->location_name}}" data-gallery="gallery">
                                                <img src="{{url('/get_image?img_path='.$photo_path[$i]->photo)}}" 
                                                     style="width: 600px; height: 600px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;"
                                                     class="img-fluid mb-2" 
                                                     alt="white sample"/>
                                                </a>
                                                <div class="caption text-center">
                                                    {{\Carbon\Carbon::parse($photo_path[$i]->created_at)->format('d F Y, H:i T')}}
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="col-sm-6">
                                        @for ($i=ceil($count/2); $i<$count; $i++)
                                            <div class="card p-1">
                                                <a href="{{url('/get_image?img_path='.$photo_path[$i]->photo)}}" data-toggle="lightbox" data-title="{{$photo_path[$i]->location_name}}" data-gallery="gallery">
                                                <img src="{{url('/get_image?img_path='.$photo_path[$i]->photo)}}" 
                                                    style="width: 600px; height: 600px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;"
                                                    class="img-fluid mb-2" 
                                                    alt="white sample"/>
                                                </a>
                                                <div class="caption text-center">
                                                    {{\Carbon\Carbon::parse($photo_path[$i]->created_at)->format('d F Y, H:i T')}}
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                @elseif ($count === 1)
                                    <div class="col-sm-6">               
                                        <div class="card p-1">
                                            <a href="{{url('/get_image?img_path='.$photo_path[$index]->photo)}}" data-toggle="lightbox" data-title="{{$photo_path[$index]->location_name}}" data-gallery="gallery">
                                            <img src="{{url('/get_image?img_path='.$photo_path[$index]->photo)}}" 
                                                style="width: 300px; height: 300px; object-fit: cover; display: block; margin-left: auto; margin-right: auto;"
                                                class="img-fluid mb-2" 
                                                alt="white sample"/>
                                            </a>
                                            <div class="caption text-center">
                                                {{\Carbon\Carbon::parse($photo_path[$index]->created_at)->format('d F Y, H:i T')}}
                                            </div>
                                        </div>                         
                                    </div>
                                @else
                                    <div class="col-sm-12 text-center">
                                        <h3>-- Belum Ada Foto --</h3>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('extra-js')
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
        })
    </script>
@endpush
