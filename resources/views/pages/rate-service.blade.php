@extends('layouts.master')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Select2 -->
<link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

@endpush
@section('title')
{{ $title }}
@stop

@section('breadcrumbs')
{{ $breadcrumbs }}
@stop
@section("content")

<!-- /.navbar -->

<div class="">

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{$title}}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">

      <div class="row">
        <div class="col-md-12">
          <a href="{{ route('rateService.create') }}" class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> Tambah Rate Service
          </a>
        </div>
      </div>
      <br>

      <table id="table-rate-service" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Logistic Name</th>
            <th>Rate Name</th>
            <th>Rate Ref</th>
            <th>Rate Type</th>
            <th>Min Kg</th>
            <th>Max Kg</th>
            <th>Use Lat Long</th>
            <th>Is Pickup By Agent</th>
            <th>Volumetric</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
</div>
<!-- Main Footer -->
@stop
@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<!-- Select2 https://select2.org/ -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script>
  $('#table-rate-service').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true,
    "autoWidth": true,
    "columns": [{
        "data": "no",
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "data": "logistic.name"
      },
      {
        "data": "name"
      },
      {
        "data": "ref"
      },
      {
        "data": "type_name"
      },
      {
        "data": "min_kg"
      },
      {
        "data": "max_kg"
      },
      {
        "data": "use_geoloc"
      },
      {
        "data": "is_pickup_by_agent"
      },
      {
        "data": "volumetric"
      },
    ],
    ajax: {
      url: '{{route("getRateService")}}',
      error: function(err) {
        $('#table-rate-service_processing').hide();

        Toast.fire({
          type: 'error',
          title: 'Data Rate Service Belum Bisa Di Dapatkan'
        })


        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    },
    "drawCallback": function(settings) {

      $('html, body').animate({
        scrollTop: $('#table-rate-service').offset().top
      }, 'fast');
    },
  });
</script>


@if(\Session::has('alert'))
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{Session::get("alert")}}'
  })
</script>
@endif

@if(\Session::has('success'))
<script>
  Toast.fire({
    type: 'success',
    title: '{{Session::get("success")}}'
  })
</script>
@endif

@endpush