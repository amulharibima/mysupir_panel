@extends('master')

@section('title', 'Lokasi Supir')
@section('supir', 'active')
@section('supirmenu', 'menu-open')
@section('lokasisupir', 'active')

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
            <h1 class="m-0 text-dark">Lokasi Supir</h1>
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
                <h3 class="card-title">Lokasi Supir</h3>

                <div class="card-tools">
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-3">
                <div id="map" style="width: 100%; height: 600px"></div>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQH2xukeAxGOnOh0aZILgqOS9AQExUCxs"></script>
    <script>
        var map;
        var InforObj = [];
        var centerCords = {};

        var markersOnMap = [
          @foreach($driver as $d)
            {
              nama: "{{$d->name}}",
              LatLng: {
                  lat: {{$d->location->latitude}},
                  lng: {{$d->location->longitude}}
              }
            },
          @endforeach
        ];

        window.onload = function () {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos){
              centerCords = {
                  lat: pos.coords.latitude,
                  lng: pos.coords.longitude
              };
              initMap();
            });
          } else { 
            alert("Geolocation is not supported by this browser.");
          }
        };

        function addMarkerInfo() {
            for (var i = 0; i < markersOnMap.length; i++) {
                var contentString = 
                  `<div id="content">
                    <h5>${markersOnMap[i].nama}</h5>
                    <a href="https://www.google.com/maps/place/${markersOnMap[i].LatLng.lat},${markersOnMap[i].LatLng.lng}/@${markersOnMap[i].LatLng.lat},${markersOnMap[i].LatLng.lng},15z" target="_blank">Lokasi di map</a>
                  </div>`;

                const marker = new google.maps.Marker({
                    position: markersOnMap[i].LatLng,
                    map: map
                });

                const infowindow = new google.maps.InfoWindow({
                    content: contentString,
                    maxWidth: 200
                });

                marker.addListener('click', function () {
                    closeOtherInfo();
                    infowindow.open(marker.get('map'), marker);
                    InforObj[0] = infowindow;
                });
                // marker.addListener('mouseover', function () {
                //     closeOtherInfo();
                //     infowindow.open(marker.get('map'), marker);
                //     InforObj[0] = infowindow;
                // });
                // marker.addListener('mouseout', function () {
                //     closeOtherInfo();
                //     infowindow.close();
                //     InforObj[0] = infowindow;
                // });
            }
        }

        function closeOtherInfo() {
            if (InforObj.length > 0) {
                /* detach the info-window from the marker ... undocumented in the API docs */
                InforObj[0].set("marker", null);
                /* and close it */
                InforObj[0].close();
                /* blank the array */
                InforObj.length = 0;
            }
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: centerCords
            });
            addMarkerInfo();
        }

    </script>
@endpush