@extends('master')

@section('title', 'Edit Tarif')
@section('masterdata', 'active')
@section('datamenu', 'menu-open')
@section('tarif', 'active')

@push('extra-css')
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Tarif</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{url('admin/masterdata/tarif')}}">Tarif</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Form Edit Tarif</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tipe</label>
                                    <input type="text" class="form-control" id="exampleInputEmail1" name="tipe" placeholder="Masukkan tipe">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Jarak/Waktu (km/jam)</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="jarakwaktu" placeholder="Masukkan jarak atau waktu (km/jam)">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tarif</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" name="tarif" placeholder="Masukkan tarif">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
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
@endpush

