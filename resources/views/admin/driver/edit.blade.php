@extends('master')

@section('title', 'Edit Supir')
@section('supir', 'active')
@section('supirmenu', 'menu-open')
@section('supircreate', 'active')

@push('extra-css')
    <style>
        p.parsley-success {
            color: #468847;
            background-color: #DFF0D8;
            border: 1px solid #D6E9C6;
        }
        p.parsley-error {
            color: #B94A48;
            background-color: #F2DEDE;
            border: 1px solid #EED3D7;
        }
        ul.parsley-errors-list {
            list-style: none;
            color: #E74C3C;
            padding-left: 0;
        }
        input.parsley-error,
        textarea.parsley-error,
        select.parsley-error {
            background: #FAEDEC;
            border: 1px solid #E85445;
        }
        .btn-group .parsley-errors-list {
            display: none;
        }
    </style>
    <!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Edit Supir</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Beranda</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/supir')}}">Supir</a></li>
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
            @if ($errors->any())
            <div class="callout callout-danger">
              <h5><i class="icon fas fa-exclamation-triangle"></i>&nbsp;&nbsp;&nbsp;Input Error!</h5>

              <ul>
                @foreach ($errors->all() as $error)
                  <li>                  
                    <p style="margin-bottom: 0px;">{{ $error }}</p>
                  </li>
                @endforeach
              </ul>
            </div>
            @endif
            <div class="card card-navy">
                    <div class="card-header">
                        <h3 class="card-title">Form Edit Supir</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="formAdd" role="form" method="POST" action="{{route('admin.supir.edit', ['id'=>$driver->id])}}" enctype="multipart/form-data" data-parsley-validate>
                      @csrf
                      <div class="card-body">                      
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input name="nama" type="text" class="form-control" id="nama" placeholder="Masukkan Nama" value="{{old('nama') ? old('nama') : $driver->name}}" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Masukkan Email" value="{{old('email') ? old('email') : $driver->email}}" required>
                            </div>  
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">                              
                            <div class="form-group">
                              <label for="phone_number">No Telepon</label>
                              <input type="text" class="form-control" name="phone_number" id="phone_number" value="{{old('phone_number') ? old('phone_number') : $driver->phone_number}}" data-inputmask="'mask' : '(+62) 899-9999-99[9][9][9]'" placeholder="(+62) 8__-____-____" data-parsley-pattern="^[(][+]62[)]\s8\d\d?-\d\d\d\d?-\d{2,5}$" data-parsley-pattern-message="Your phone number must be at least 10 characters long." required>
                              <!-- <input name="phone_number" type="number" class="form-control  @error('phone_number') is-invalid @enderror" id="phone_number" placeholder="Masukkan No Telepon" value="{{old('phone_number')}}" required> -->
                            </div>  
                          </div>
                          <div class="col-md-6">                              
                            <div class="form-group">
                              <label for="foto">Foto Profil</label>
                              <div class="input-group">
                                <div class="custom-file">
                                  <input name="foto" type="file" class="custom-file-input" id="foto">
                                  <label class="custom-file-label" for="foto">Choose file</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>          
                        
                        <div class="form-group">
                          <label for="sim">Jenis SIM</label>
                          <div class="row">
                            <div class="col-md-2">                              
                              <div class="custom-control custom-checkbox">
                                @if (in_array('sim a', $licenses))
                                    <input checked name="sim_type[]" class="custom-control-input  @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_a" value="sim a" >
                                @else
                                    <input name="sim_type[]" class="custom-control-input  @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_a" value="sim a" >
                                @endif
                                <label for="sim_a" class="custom-control-label">SIM A</label>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="custom-control custom-checkbox">
                                @if (in_array('sim b i', $licenses))
                                    <input checked name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_b1" value="sim b i">
                                @else
                                    <input name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_b1" value="sim b i">
                                @endif
                                <label for="sim_b1" class="custom-control-label">SIM B1</label>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="custom-control custom-checkbox">
                                @if (in_array('sim b ii', $licenses))
                                    <input checked name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_b2" value="sim b ii">
                                @else
                                    <input name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="checkbox" id="sim_b2" value="sim b ii">
                                @endif
                                <label for="sim_b2" class="custom-control-label">SIM B2</label>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                        <label for="kota">Masukkan Kota</label>
                        <select class="form-control select2bs4" name="kota" style="width: 100%;">
                          <option selected="selected" disabled hidden>- Pilih Kota -</option>
                          @foreach ($kota as $kota)
                            <option value="{{$kota->id}}">{{$kota->kode.' - '.$kota->nama}}</option>
                          @endforeach
                        </select>
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control" rows="3" required>{{old('alamat') ? old('alamat') : $driver->address}}</textarea>
                        </div>

                      </div>
                        <!-- /.card-body -->

                        <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary submitForm">Submit</button>
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
  <!-- jquery.inputmask -->
  <script src="{{ asset('plugins/inputmask/jquery.inputmask.bundle.js')}}"></script>
  <!-- Parsley -->
  <script src="{{ asset('plugins/parsleyjs/dist/parsley.min.js')}}"></script>
  <script src="{{ asset('plugins/validator/validator.js')}}"></script>
  <!-- <script src="{{ asset('assets/vendors/jquery-steps/jquery.steps.js')}}"></script> -->
  <script src="{{ asset('plugins/jquery-validation/jquery.validate.js')}}"></script>
  <!-- bs-custom-file-input -->
  <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
  <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
  <script>
    // $('#formAdd').delegate('.submitForm', 'click', function(e) {
    //   e.preventDefault();
    //   alert('submitDone');
    // });
    $(document).ready(function() {
      init_parsley();
      init_InputMask();
      bsCustomFileInput.init();
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    });

    
    function init_InputMask() {
			
			if( typeof ($.fn.inputmask) === 'undefined'){ return; }
			console.log('init_InputMask');
			
				$(":input").inputmask();
				
    };
    
    /* PARSLEY */
			
		function init_parsley() {
			
			if( typeof (parsley) === 'undefined'){ return; }
			console.log('init_parsley');
			
			$/*.listen*/('parsley:field:validate', function() {
			  validateFront();
			});
			$('#formAdd .submitForm').on('click', function() {
			  $('#formAdd').parsley().validate();
			  validateFront();
			});
			var validateFront = function() {
			  if (true === $('#formAdd').parsley().isValid()) {
				$('.bs-callout-info').removeClass('hidden');
				$('.bs-callout-warning').addClass('hidden');
			  } else {
				$('.bs-callout-info').addClass('hidden');
				$('.bs-callout-warning').removeClass('hidden');
			  }
			};
		  
			
			
			  try {
				hljs.initHighlightingOnLoad();
			  } catch (err) {}
			
		};
  </script>
@endpush
