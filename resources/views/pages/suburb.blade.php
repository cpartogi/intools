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
          <a href="{{ route('suburb.create') }}" class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> Tambah Suburb
          </a>
        </div>
      </div>
      <br>

      <div class="row">

        <div class="col-12 col-sm-4">
          <div class="form-group">
            <label>Pilih Negara :</label>
            <select id="negara" class="form-control select2-info" data-dropdown-css-class="select2-info" style="width: 100%;">
              <option value="228">Indonesia</option>
            </select>
          </div>
          <!-- /.form-group -->
        </div>

        <div class=" col-12 col-sm-4">
          <div class="form-group">
            <label>Pilih Provinsi :</label>
            <select id="provinsi" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-info" style="width: 100%;">
              <option selected="selected">Pilih Provinsi</option>
            </select>
          </div>
          <!-- /.form-group -->
        </div>

        <div class="col-12 col-sm-4">
          <div class="form-group">
            <label>Pilih Kota :</label>
            <select id="kota" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-info" style="width: 100%;">
              <option value="">Pilih Kota</option>
            </select>
          </div>
          <!-- /.form-group -->
        </div>

        <button id="cari" type="button" class="btn btn-block btn-primary">Cari</button>

      </div>
      <br>

      <table id="table-suburb" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Kecamatan</th>
            <th>Nama Kota</th>
            <th>Nama Provinsi</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Status</th>
            <th>Action</th>
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
  $("#cari").prop('disabled', true)

  function getProvince(id) {
    var ajaxurl = '{{route("getProvinces", ":id")}}';
    ajaxurl = ajaxurl.replace(':id', id);
    $.ajax({
      type: "GET",
      url: ajaxurl,
      success: function(data) {
        $.each(data, function(i, item) {
          $('#provinsi').append($('<option>', {
            value: item.id,
            text: item.name
          }));
          $("#provinsi").prop("disabled", false);
        });
      },
      error: function(err) {
        $("#cari").prop('disabled', true)
        Toast.fire({
          type: 'error',
          title: 'Belum Bisa Mendapat Provinsi'
        })


        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    });
  }

  function getCity(id) {
    var ajaxurl = '{{route("getCities", ":id")}}';
    ajaxurl = ajaxurl.replace(':id', id);
    $.ajax({
      type: "GET",
      url: ajaxurl,
      success: function(data) {
        $.each(data, function(i, item) {
          $('#kota').append($('<option>', {
            value: item.id,
            text: item.name
          }));
          $("#kota").prop("disabled", false);
          $("#cari").prop('disabled', false)
        });
      },
      error: function(err) {
        $("#cari").prop('disabled', true)
        Toast.fire({
          type: 'error',
          title: 'Belum Bisa Mendapat Kota'
        })


        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    });
  }

  //Initialize Select2 Elements
  $('#negara').select2()
  $('#provinsi').select2()
  getProvince($("#negara").val());
  $('#provinsi').on('change', function() {
    $('#kota').html("")
    getCity($("#provinsi").val());
  });

  $('#kota').select2()

  $("#cari").on("click", function() {
    var countryID = $("#negara").val();
    var provinceID = $("#provinsi").val();
    var cityID = $("#kota").val();

    $('#table-suburb').DataTable().clear().destroy()
    var t = $('#table-suburb').DataTable({
      "processing": true,
      "serverSide": true,
      "paging": true,
      "order": [],
      "columnDefs": [{
        targets: '_all',
        orderable: false
      }],
      "lengthChange": true,
      "searching": false,
      "info": true,
      "autoWidth": true,
      ajax: {
        url: '{{route("suburb_list")}}',
        data: {
          "countryID": countryID,
          "provinceID": provinceID,
          "cityID": cityID,
        },
        error: function(err) {
          $('#table-suburb_processing').hide();

          Toast.fire({
            type: 'error',
            title: 'Data Suburb Belum Bisa Di Dapatkan'
          })


          if (err.status == 401) {
            window.location = '{{ url("/oauth2/sign_out") }}'
          }
        }
      },
      "columns": [{
          "data": "no",
          render: function(data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          "data": "name"
        },
        {
          "data": "city.name"
        },
        {
          "data": "province.name"
        },
        {
          "data": "lat",
        },
        {
          "data": "lng",
        },
        {
          "data": "status"
        },
        {
          "data": "action",
          "render": function(data, type, row, meta) {

            var routeEdit = '{{ route("suburb.edit", ":id") }}';
            routeEdit = routeEdit.replace(':id', row.id);

            return `<div class="row">
                <div class="col-md-12">
                  <a href="` + routeEdit + `" class="btn btn-block btn-primary edit-suburb">Edit Suburb</a>
                </div>
              </div>`
          }
        },
      ],
      "drawCallback": function(settings) {

        $('html, body').animate({
          scrollTop: $('#table-suburb').offset().top
        }, 'fast');
      },

    });

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