@extends('layouts.master')
@push('css') <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
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
            @if(\Session::has('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{Session::get("success")}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            @if(\Session::has('alert'))
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{Session::get("alert")}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <form id="midtrans-callback-process-form" role="form" method="POST" action="">
            @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="request_payload">Payload</label><br>
                        <div class="card-title">Berikut ini cara untuk mendapatkan payload
                          <ul>
                            <li>Buka database cilientname, masuk ke tabel va_transaction, cari transaction_id yang bermasalah, yaitu sudah settlement tapi saldo tidak bertambah di tabel agent_balance</li>
                            <li>Masuk ke kibana (https://kibana.production-0.cilientname.id) </li>
                            <li>Pilih Discover, pastikan pilih "kubernetes_cluster-*" </li>
                            <li>Di form search, cari berdasarkan transaction id yang bermasalah dengan tanda petik dengan format : "xxxxxxxx". contoh : "db12eef6-eb59-4395-b16f-0a508b582ca3"</li>
                            <li>Cari berdasarkan tanggal transaction id tersebut, gunakan pilihan time range di sebelah kanan atas,  klik tombol Refresh</li>
                            <li>Setelah hasilnya tampil, cari hasil yang menampilkan "is_old":1 dan log_id tidak sama dengan 0 </li>
                            <li>Setelah hasilnya tampil, copy payload dalam format json lalu paste ke form di bawah ini</li>
                          </ul>
                         </div>
                        <textarea class="form-control" id="request_payload" name="request_payload" rows="20" placeholder="Enter Payload"></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                </div>
            </form>
        </div>
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

$('#midtrans-callback-process-form').submit(function(e) {
    e.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var paramObj = {};
    $.each($('#midtrans-callback-process-form').serializeArray(), function(_, kv) {
      paramObj[kv.name] = kv.value;
    });

    var data = $.extend({
      "_token": token,
    }, paramObj);

    $.ajax({
      type: "POST",
      url: '{{route("midtransCallbackProcess.hitApi")}}',
      data,
      success: function(res) {
        Toast.fire({
          type: 'success',
          title: res,
          position: 'bottom',
          timer: 30000,
        });
        $('#midtrans-callback-process-form :input').val('');
      },
      error: function(err) {
        if(err.responseJSON && err.responseJSON.msg) {
          Toast.fire({
            type: 'error',
            title: err.responseJSON.msg,
            position: 'bottom',
            timer: 30000,
          });
        }

        if (err.status == 401) {
          window.location = '{{ url("/oauth2/sign_out") }}'
        }
      }
    });
  });


  $(document).ready(function() {

$('#midtrans-callback-process-form').validate({
  rules: {
    request_payload: {
      required: true
    }
  },
  messages: {
    request_payload: {
      required: "Enter payload"
    }
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
