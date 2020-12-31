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
      <form id="withdraw-form" role="form" method="POST" action="">
        @csrf
        <div class="card-body">

        <div class="row">
            <div class=" col-12 col-sm-4">
              <div class="form-group">
                <label for="transaction_id">ID Transaksi</label>
                <input type="text" class="form-control" name="transaction_id" placeholder="Enter ID Transaksi">
              </div>
            </div>
            <div class=" col-12 col-sm-4">
              <div class="form-group">
                <label for="agent_id">Agent ID</label>
                <input type="number" class="form-control" name="agent_id" placeholder="Enter Agent ID">
              </div>
            </div>
          </div>

          <div class="row">
            <div class=" col-12 col-sm-4">
              <div class="form-group">
                <label for="account_name">Nama Pemilik Rekening</label>
                <input type="text" class="form-control" name="account_name" placeholder="Enter Nama Pemilik Rekening">
              </div>
            </div>
            <div class=" col-12 col-sm-4">
              <div class="form-group">
                <label for="account_no">Nomor Rekening</label>
                <input type="text" class="form-control" name="account_no" placeholder="Enter Nomor Rekening">
              </div>
            </div>
          </div>

          <div class="row">
          <div class=" col-12 col-sm-4">
              <div class="form-group">
                <label>Pilih Bank</label>
                <select id="bank_id" name="bank_id" class="area form-control select2-success" data-dropdown-css-class="select2-success" style="width: 100%;" required>
                  <option disabled selected>Pilih Bank</option>
                </select>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="branch_name">Nama Kantor Cabang</label>
                <input type="text" class="form-control" name="branch_name" placeholder="Enter Nama Kantor Cabang">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="request_date">Tanggal Permintaan Transfer</label>
                <input type="text" class="form-control datepicker" name="request_date">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-12 col-sm-4">
              <div class="form-group">
                <label for="amount">Jumlah</label>
                <input type="number" class="form-control" name="amount" placeholder="Enter Jumlah">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="log_description">Deskripsi</label>
            <input type="text" class="form-control" name="log_description" placeholder="Enter Deskripsi">
          </div>

          <div class="form-group">
            <label for="notes">Catatan</label>
            <input type="text" class="form-control" name="notes" placeholder="Enter Catatan">
          </div>

        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary btn-submit">Simpan</button>
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

  function getBanks() {
      var ajaxurl = '{{route("bank_list")}}';
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
            $.each(data.results, function (i, item) {
                $('#bank_id').append($('<option>', {
                    value: item.id,
                    text: item.name
                }));
            });
          },
          error: function(err) {
            Toast.fire({
              type: 'error',
              title: 'Belum Bisa Mendapat Bank'
            })


            if (err.status == 401) {
              window.location = '{{ url("/oauth2/sign_out") }}'
            }
          }
      });
  }
  getBanks();
  $('#bank_id').select2()

  $('#withdraw-form').submit(function(e) {
    e.preventDefault();
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    var paramObj = {};
    $.each($('#withdraw-form').serializeArray(), function(_, kv) {
      paramObj[kv.name] = kv.value;
    });

    var data = $.extend({
      "_token": token,
    }, paramObj);
    data.request_date += "T00:00:00Z";

    $.ajax({
      type: "POST",
      url: '{{ route('withdrawDeposit.store') }}',
      data,
      success: function(res) {
        Toast.fire({
          type: 'success',
          title: res,
          position: 'bottom',
          timer: 30000,
        });
        $('#withdraw-form :input').val('');
        $('#bank_id').val('Pilih Bank').trigger('change');
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
    $('#withdraw-form').validate({
      rules: {
        transaction_id: {
          required: true,
        },
        agent_id: {
          required: true,
          digits: true,
        },
        account_name: {
          required: true,
        },
        account_no: {
          required: true,
          digits: true,
        },
        bank_id: {
          required: true,
        },
        branch_name: {
          required: true,
        },
        request_date: {
          required: true,
        },
        amount: {
          required: true,
          number: true,
          max: -1,
        },
        log_description: {
          required: true,
        },
        notes: {
          required: true,
        },
      },
      messages: {
        transaction_id: {
          required: "Masukkan ID Transaksi",
        },
        agent_id: {
          required: "Masukkan Agent ID",
        },
        account_name: {
          required: "Masukkan Nama Pemilik Rekening",
        },
        account_no: {
          required: "Masukkan Nomor Rekening",
          digits: "Masukkan angka"
        },
        bank_id: {
          required: "Pilih Bank",
        },
        branch_name: {
          required: "Masukkan Nama Kantor Cabang",
        },
        request_date: {
          required: "Masukkan Tanggal Permintaan Transfer",
        },
        amount: {
          required: "Masukkan Jumlah",
          number: "Masukkan angka",
          max: "Jumlah harus negative",
        },
        log_description: {
          required: "Masukkan Deskripsi",
        },
        notes: {
          required: "Masukkan Catatan",
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
