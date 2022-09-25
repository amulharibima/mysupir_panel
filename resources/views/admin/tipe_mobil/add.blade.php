@extends('master')

@section('title', 'Tambah Tipe Mobil')
@section('tipemobil', 'active')
@section('tipemenu', 'menu-open')
@section('tipecreate', 'active')

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
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Buat Tipe Mobil Baru</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{url('admin/home')}}">Beranda</a></li>
              <li class="breadcrumb-item"><a href="{{url('admin/tipemobil')}}">Tipe </a></li>
              <li class="breadcrumb-item active">Tambah</li>
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
                        <h3 class="card-title">Form Tambah Tipe Mobil</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form id="formAdd" role="form" method="POST" action="{{route('admin.tipe.add')}}" enctype="multipart/form-data" data-parsley-validate>
                      @csrf
                      <div class="card-body">                      
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Tipe Mobil</label>
                                <input name="nama" type="text" class="form-control" id="nama" placeholder="Masukkan Tipe Mobil" value="{{old('nama')}}" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="foto">Foto Ikon</label>
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
                              <div class="custom-control custom-radio">
                                <input name="sim_type[]" class="custom-control-input  @error('sim_type') is-invalid @enderror" type="radio" id="sim_a" value="sim a" >
                                <label for="sim_a" class="custom-control-label">SIM A</label>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="custom-control custom-radio">
                                <input name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="radio" id="sim_b1" value="sim b i">
                                <label for="sim_b1" class="custom-control-label">SIM B1</label>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="custom-control custom-radio">
                                <input name="sim_type[]" class="custom-control-input @error('sim_type') is-invalid @enderror" type="radio" id="sim_b2" value="sim b ii">
                                <label for="sim_b2" class="custom-control-label">SIM B2</label>
                              </div>
                            </div>
                          </div>
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
  <script>
    // $('#formAdd').delegate('.submitForm', 'click', function(e) {
    //   e.preventDefault();
    //   alert('submitDone');
    // });
    $(document).ready(function() {
      init_parsley();
      init_InputMask();
      bsCustomFileInput.init();
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
