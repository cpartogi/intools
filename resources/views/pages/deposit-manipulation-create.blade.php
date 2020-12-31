@extends('layouts.master')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.css">
<!-- Select2 -->
<link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="../../dist/css/adminlte.min.css">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>

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

      <form id="despoit-manipulation-form" role="form" method="POST" action="">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="agent_id">Agent ID</label>
            <input type="number" class="form-control" name="agent_id" placeholder="Enter Agent ID">
          </div>

          <div class="form-group">
            <label for="amount">Jumlah</label>
            <input type="number" class="form-control" name="amount" placeholder="Enter Jumlah">
          </div>

          <div class="form-group">
            <label for="description">Deskripsi</label>
            <input type="text" class="form-control" name="description" placeholder="Enter Deskripsi">
          </div>

          <div class="form-group">
            <label for="nama">Order ID</label>
            <input type="text" class="form-control" name="order_id" placeholder="Enter Order ID">
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

<!-- AdminLTE App -->
<script>
  $(document).ready(function() {
    $('#despoit-manipulation-form').validate({
      rules: {
        agent_id: {
          required: true,
          digits: true,
        },
        amount: {
          required: true,
        },
        description: {
          required: true,
        },
      },
      messages: {
        agent_id: {
          required: "Masukkan Agent ID",
          digits: "Agent ID harus angka"
        },
        amount: {
          required: "Masukkan Jumlah"
        },
        description: {
          required: "Masukkan Deskripsi"
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
    })

    $('#despoit-manipulation-form').submit(function(e) {
      e.preventDefault();
      var token = $('meta[name="csrf-token"]').attr('content');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      var paramObj = {};
      $.each($('#despoit-manipulation-form').serializeArray(), function(_, kv) {
        paramObj[kv.name] = kv.value;
      });

      var data = $.extend({
        "_token": token,
      }, paramObj);


      $.ajax({
        type: "POST",
        url: '{{route("depositManipulation.store")}}',
        data,
        success: function(res) {
          Toast.fire({
            type: 'success',
            title: res,
            position: 'bottom',
            timer: 30000,
          });
          $('#despoit-manipulation-form :input').val('');
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
