@extends('master')

@section('title', 'Daftar Transaksi')
@section('transaksi', 'active')

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
                    <h1 class="m-0 text-dark">Detail Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/admin/home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{url('/admin/transaksi')}}">Transaksi</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Order Status ({{ ucwords($order->status) }}) </h3><span class="text-muted">&nbsp;&nbsp;({{$order->rating()->first() ? $order->rating()->first()->rating : " - "}}<i class="far fa fa-star"></i>)</span> 
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center ">Order Identifier</span>
                                        <span class="info-box-number text-center  mb-0">{{$order->identifier}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center ">Tipe Order</span>
                                        <span class="info-box-number text-center  mb-0">{{ucwords($order->type)}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center ">Tarif</span>
                                        <span class="info-box-number text-center  mb-0">Rp. @convert($order->transaction->total_price) <span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center ">{{$order->type === 'trip' ? 'Jarak (Km)' : 'Durasi (Jam)'}}</span>
                                        <span class="info-box-number text-center  mb-0">{{$order->type === 'trip' ? $order->total_distance : \Carbon\Carbon::parse($order->finish_datetime)->diffInHours(\Carbon\Carbon::parse($order->start_datetime), true)}} <span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 mt-3">
                            <h4>Lokasi Jemput & Tujuan</h4>
                                <ul class="list-unstyled">
                                    <li>
                                        <a class="btn-link text-secondary"><i class="fas fa-fw fa-map-marker-alt"></i> {{$order->startLocation()->first()->name}}</a>
                                    </li>
                                    @if (!$order->finishLocation()->get()->isEmpty())
                                    <li>
                                        <a class="btn-link text-secondary"><i class="fas fa-fw fa-location-arrow"></i> {{$order->finishLocation()->first()->name}}</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-12 mt-3">
                                <h4>Histori Report Order</h4>
                                @if ($order->initialPhoto()->first())
                                <div class="post">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="{{asset('default_user.png')}}" alt="user image">
                                        <span class="username">
                                            <a>Order Report Mulai Perjalanan</a>
                                        </span>
                                        <span class="description">Shared - {{\Carbon\Carbon::parse($order->initialPhoto()->first()->created_at)->format('H.i T, d F Y')}}</span>
                                    </div>

                                    <div class="col-12 product-image-thumbs">
                                    @foreach ($order->initialPhoto()->first()->photos as $photo)
                                    <a href="{{url('/get_image?img_path='.$photo)}}" data-toggle="lightbox" data-title="Order Report Mulai Perjalanan" data-gallery="gallery">
                                        <div class="product-image-thumb active"><img src="{{url('/get_image?img_path='.$photo)}}" alt="Product Image"></div>
                                    </a>
                                    @endforeach
                                    </div>

                                </div>

                                @if ($order->finalPhoto()->first())
                                <div class="post clearfix">
                                    <div class="user-block">
                                        <img class="img-circle img-bordered-sm" src="{{asset('default_user.png')}}" alt="User Image">
                                        <span class="username">
                                            <a>Order Report Selesai Perjalanan</a>
                                        </span>
                                        <span class="description">Shared - {{\Carbon\Carbon::parse($order->finalPhoto()->first()->created_at)->format('H.i T, d F Y')}}</span>
                                    </div>

                                    <div class="col-12 product-image-thumbs">
                                        @foreach ($order->finalPhoto()->first()->photos as $photo)
                                        <a href="{{url('/get_image?img_path='.$photo)}}" data-toggle="lightbox" data-title="Order Report Selesai Perjalanan" data-gallery="gallery">
                                            <div class="product-image-thumb active"><img src="{{url('/get_image?img_path='.$photo)}}" alt="Product Image"></div>
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @else
                                <p class="text-center">- Histori Belum Tersedia -</p>
                            
                            @endif

                            </div>
                        </div>
                    </div>

                    <!-- INFO DRIVER -->
                    <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
                        <h3 class="text-primary"> Info Supir</h3>
                        <p class=""><i class="fas fa-user"></i> {{$order->driver->name}}</p>
                        <p class=""><i class="fas fa-map-marker-alt"></i> {{$order->driver->address}}.</p>
                        <!-- <br> -->
                        <div class="">
                            <p class="text-sm">Email
                                <b class="d-block">{{$order->driver->email}}</b>
                            </p>
                            <p class="text-sm">No Telepon
                                <b class="d-block">(+62) {{$order->driver->phone_number}}</b>
                            </p>
                        </div>

                        <h5 class=" ">Lisensi Supir</h5>
                        <ul class="list-unstyled">
                            @php $count = $order->driver->licenses()->count();
                                $i = 1;
                            @endphp
                            @foreach ($order->driver->licenses()->get() as $license) 
                            @if($i < $count)                                
                                <li>
                                    <a href="" class="btn-link text-dark"><i class="far fa-fw fa-address-card"></i> {{ucwords($license->name)}}</a>
                                </li>
                            @else
                                <li>
                                    <a href="" class="btn-link text-dark"><i class="fas fa-fw fa-address-card"></i> {{ucwords($license->name)}}</a>
                                </li>
                            @endif
                                @php $i++; @endphp 
                            @endforeach
                        </ul>
                        <div class="text-center mt-5 mb-3">
                            <a href="{{url('/admin/supir/detail/'.$order->driver_id)}}" class="btn btn-sm btn-primary">Detail Supir</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->        
    </section>
    <!-- /.content -->
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
