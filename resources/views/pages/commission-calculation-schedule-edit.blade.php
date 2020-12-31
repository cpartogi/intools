@extends('layouts.master')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Select2 -->
<link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">


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
      <form id="commission-calculation-schedule-form" role="form" method="POST" action="">
        @csrf
        <div class="card-body">

        <div class="form-group">
            <label for="nama">Order ID</label>
            <input type="text" class="form-control" value="{{$result->order_id}}" name="order_id" disabled>
          </div>

          <div class="form-group">
            <label for="nama">Calculation Date</label>
            <input type="text" class="form-control datepicker" value="{{$result->calculation_date}}" name="calculation_date" placeholder="Enter Calculation Date">
          </div>

          <div class="form-group">
            <label for="is_processed">Is Processed</label>
            <select name="is_processed" class="form-control permission" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select status">
              <option value="1" @if($result->is_processed == "1") selected @endif>1</option>
              <option value="0" @if($result->is_processed == "0") selected @endif>0</option>
            </select>
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
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

<!-- Datepicker -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Input mask -->
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>

<!-- AdminLTE App -->
<script>

  (function() {
    $('.datepicker').datepicker({
      autoclose: true,
      startDate: new Date(),
      format: "yyyy-mm-dd"
    });

    $('.datepicker').mask('0000-00-00',{placeholder: "yyyy-mm-dd", selectOnFocus: true});
  })();

  $('#commission-calculation-schedule-form').submit(function(e) {
    e.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var paramObj = {};
    $.each($('#commission-calculation-schedule-form').serializeArray(), function(_, kv) {
      paramObj[kv.name] = kv.value;
    });

    var data = $.extend({
      "_token": token,
    }, paramObj);

    $.ajax({
      type: "POST",
      url: '{{route("commissionCalculationDate.update", [$result->order_id])}}',
      data,
      success: function(res) {
        Toast.fire({
          type: 'success',
          title: res,
          position: 'bottom',
          timer: 30000,
        });

        setTimeout(function() {
          window.location.href = "{{route('commissionCalculationDate')}}";
        }, 1500);
      },
      error: function(err) {
        var message = "Periksa kembali input";
        if(err.responseJSON && err.responseJSON.msg) {
          message = err.responseJSON.msg;
        }

        Toast.fire({
          type: 'error',
          title: message,
          position: 'bottom',
          timer: 30000,
        });

        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    });
  });

  $(document).ready(function() {
    $('#commission-calculation-schedule-form').validate({
      rules: {
        calculation_date: {
          required: true,
          date: true,
        },
      },
      messages: {
        calculation_date: {
          required: "Masukkan Calculation Date",
          date: "Format date tidak sesuai",
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
</script>

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{$error}}',
    position: 'bottom',
    timer: 30000,
  })
</script>
@endforeach
@endif

@endpush
