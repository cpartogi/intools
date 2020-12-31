@extends('layouts.master')
@push('css')
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
      <form id="rate-service-form" role="form" method="POST" action="{{ route('rateService.store') }}">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="rate_name">Nama Rate Service</label>
            <input type="text" class="form-control" id="rate_name" name="rate_name" placeholder="Enter Rate Name">
          </div>

          <div class="form-group">
            <label for="rate_desc">Deskripsi Rate Service</label>
            <input type="text" class="form-control" id="rate_desc" name="rate_desc" placeholder="Enter Deskripsi Rate">
          </div>

          <div class="form-group">
            <label for="rate_full">Full Deskripsi Rate Service</label>
            <input type="text" class="form-control" id="rate_full" name="rate_full" placeholder="Enter Full Deskripsi Rate">
          </div>

          <div class="form-group">
            <label for="rate_ref">Rate Ref Service</label>
            <input type="text" class="form-control" id="rate_ref" name="rate_ref" placeholder="Enter Rate Ref">
          </div>

          <div class="form-group">
            <label for="rate_type">Tipe Rate</label>
            <input type="number" class="form-control" id="rate_type" name="rate_type" placeholder="Enter Tipe Rate">
          </div>

          <div class="form-group">
            <label for="show_id">Show ID</label>
            <input type="number" class="form-control" id="show_id" name="show_id" placeholder="Enter Show ID">
          </div>

          <div class="form-group">
            <label>Pilih Logistic :</label>
            <select id="logistik" name="logistic_id" class="form-control select2-info" data-dropdown-css-class="select2-info" style="width: 100%;" required>
              <option value="" selected="selected">Pilih Logistic</option>
            </select>
          </div>

          <div class="form-group">
            <label for="is_inclusive">Is Inclusive</label>
            <select name="is_inclusive" class="form-control" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Inclusive">
              <option value="true">True</option>
              <option value="false">False</option>
            </select>
          </div>

          <div class="form-group">
            <label for="is_pickup_by_agent">Is Pickup By Agent</label>
            <select name="is_pickup_by_agent" class="form-control" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Pickup By Agent">
              <option value="true">True</option>
              <option value="false">False</option>
            </select>
          </div>

          <div class="form-group">
            <label for="is_using_latlong">Is Using Latitude Longitude</label>
            <select name="is_using_latlong" class="form-control" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Using Latitude Longitude">
              <option value="true">True</option>
              <option value="false">False</option>
            </select>
          </div>

          <div class="form-group">
            <label for="fee_for_ma">Fee For MA</label>
            <input type="number" class="form-control" id="fee_for_ma" name="fee_for_ma" placeholder="Enter Fee For MA">
          </div>

          <div class="form-group">
            <label for="fee_for_hub">Fee For Hub</label>
            <input type="number" class="form-control" id="fee_for_hub" name="fee_for_hub" placeholder="Enter Fee For Hub">
          </div>

          <div class="form-group">
            <label for="fee_logistic">Fee Logistic</label>
            <input type="number" class="form-control" id="fee_logistic" name="fee_logistic" placeholder="Enter Fee Logistic">
          </div>

          <div class="form-group">
            <label for="fee_vendor">Fee Vendor</label>
            <input type="number" class="form-control" id="fee_vendor" name="fee_vendor" placeholder="Enter Fee Vendor">
          </div>

          <div class="form-group">
            <label for="max_kg">Max Kg</label>
            <input type="number" class="form-control" id="max_kg" name="max_kg" placeholder="Enter Max Kg">
          </div>

          <div class="form-group">
            <label for="min_kg">Min Kg</label>
            <input type="number" class="form-control" id="min_kg" name="min_kg" placeholder="Enter Min Kg">
          </div>

          <div class="form-group">
            <label for="ppn">PPN</label>
            <input type="number" class="form-control" id="ppn" name="ppn" placeholder="Enter PPN">
          </div>

          <div class="form-group">
            <label for="volumetric">Volumetric</label>
            <input type="number" class="form-control" id="volumetric" name="volumetric" placeholder="Enter Volumetric">
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

    $('#rate-service-form').validate({
      rules: {
        logistic_id: {
          required: true
        },
        rate_name: {
          required: true,
          minlength: 3,
          notOnlySpace: true
        },
        rate_desc: {
          required: true
        },
        rate_full: {
          required: true
        },
        rate_type: {
          required: true
        },
        show_id: {
          required: true
        },
      },
      messages: {
        logistic_id: {
          required: "Masukkan Logistic ID"
        },
        rate_name: {
          required: "Masukkan Nama Rate",
          minlength: "Masukkan Nama Lebih dari 3"
        },
        rate_desc: {
          required: "Masukkan Rate Description"
        },
        rate_full: {
          required: "Masukkan Rate Full Description"
        },
        rate_type: {
          required: "Masukkan Rate Type"
        },
        show_id: {
          required: "Masukkan Show ID"
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

  //Initialize Select2 Elements
  function getLogistik() {
    var ajaxurl = '{{route("list_logistic")}}';
    $.ajax({
      type: "GET",
      url: ajaxurl,
      success: function(data) {
        $.each(data, function(i, item) {
          $('#logistik').append($('<option>', {
            value: item.id,
            text: item.name
          }));
        });
      },
      error: function(err) {
        Toast.fire({
          type: 'error',
          title: 'Belum Bisa Mendapat Logistik'
        })

        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    });
  }

  getLogistik()
  $('#logistik').select2()
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