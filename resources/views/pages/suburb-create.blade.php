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
      <h3 class="card-title">{{ $title }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
      <form id="suburb-form" role="form" method="POST" action="{{ route('suburb.store') }}">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="nama">Nama Kecamatan</label>
            <input type="text" class="form-control" id="nama" name="name" placeholder="Enter Name">
          </div>

          <div class="form-group">
            <label>Pilih Negara :</label>
            <select id="negara" class="form-control select2-info" data-dropdown-css-class="select2-info" style="width: 100%;" required>
              <option value="228">Indonesia</option>
            </select>
          </div>


          <div class="form-group">
            <label>Pilih Provinsi :</label>
            <select id="provinsi" name="provinsi" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-info" style="width: 100%;" required>
              <option value="" selected="selected">Pilih Provinsi</option>
            </select>
          </div>


          <div class="form-group">
            <label>Pilih Kota :</label>
            <select id="kota" name="city_id" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-info" style="width: 100%;">
              <option value="" selected="selected">Pilih Kota</option>
            </select>
          </div>

          <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="lat" placeholder="Enter Latitude">
          </div>

          <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="lng" placeholder="Enter Longitude">
          </div>

          <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control permission" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select status">
              <option value="true">True</option>
              <option value="false">False</option>
            </select>
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Buat</button>
        </div>
      </form>

    </div>
    <!-- /.card-body -->
  </div>
</div>
<!-- Main Footer -->
@stop
@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<!-- Select2 https://select2.org/ -->
<script src="../../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script>
  $(document).ready(function() {

    $('#suburb-form').validate({
      rules: {
        city_id: {
          required: true,
        },
        provinsi: {
          required: true,
        },
        name: {
          required: true,
          minlength: 3,
          notOnlySpace: true
        },

      },
      messages: {
        city_id: {
          required: "Masukkan Kota"
        },
        provinsi: {
          required: "Masukkan Provinsi"
        },
        name: {
          required: "Masukkan Nama",
          minlength: "Masukkan Nama Lebih dari 3"
        },
      },
      errorElement: 'span',
      errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function(element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });
  });

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
        });
      },
      error: function(err) {
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
    $('#kota').html("").select2()
    getCity($("#provinsi").val());
  });

  $('#kota').select2()
</script>

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{$error}}'
  })
</script>
@endforeach
@endif
@endpush