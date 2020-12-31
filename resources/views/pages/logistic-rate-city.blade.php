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

        <div class="col-12 col-sm-12">
          <div class="form-group">
            <label>Pilih Logistik :</label>
            <select id="logistik" class="form-control select2-success" data-dropdown-css-class="select2-info" style="width: 100%;">
            </select>
          </div>
          <!-- /.form-group -->
        </div>

        <button id="cari" type="button" class="btn btn-block btn-primary">Cari</button>

      </div>
      <br>

      <table id="table-hub" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>ID</th>
            <th>Nama Kota</th>
            <th>Nama Logistic</th>
            <th>Nama Rate</th>
            <th>Hubless Status</th>
            <th>Implant Status</th>
            <th>Destination Status</th>
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
          $("#cari").prop('disabled', false)
        });
      },
      error: function(err) {
        $("#cari").prop('disabled', true)
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
  // Initialize datatables

  $("#cari").on("click", function() {
    var city_id = $("#kota").val();
    var logistic_id = $("#logistik").val();
    $('#table-hub').DataTable().clear().destroy()
    var t = $('#table-hub').DataTable({
      processing: true,
      serverSide: true,
      paging: true,
      lengthChange: false,
      searching: false,
      "order": [],
      "columnDefs": [{
        targets: '_all',
        orderable: false
      }],
      info: true,
      autoWidth: false,
      ajax: {
        url: '{{route("list_lrc")}}?&logistic_id=' + logistic_id + '&city_id=' + city_id,
        error: function(err) {
          $('#table-hub_processing').hide();

          Toast.fire({
            type: 'error',
            title: 'Data Hub Blm Bisa didapatkan'
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
          "data": "id"
        },
        {
          "data": "city.name"
        },
        {
          "data": "logistic.name"
        },
        {
          "data": "rate.name"
        },
        {
          "data": "hubless_enabled",
          "render": function(data, type, row, meta) {
            if (data == 0) {
              return `<input type="checkbox" data-id="` + row.id + `" class="tgl-hubless" data-toggle="toggle">`
            }
            return `<input type="checkbox" data-id="` + row.id + `" class="tgl-hubless" checked data-toggle="toggle">`
          }
        },
        {
          "data": "implant_enabled",
          "render": function(data, type, row, meta) {
            if (data == 0) {
              return `<input type="checkbox" data-idimplant="` + row.id + `" class="tgl-implant" data-toggle="toggle">`
            }
            return `<input type="checkbox" data-idimplant="` + row.id + `" class="tgl-implant" checked data-toggle="toggle">`
          }
        },
        {
          "data": "destination_enabled",
          "render": function(data, type, row, meta) {
            if (data == 0) {
              return `<input type="checkbox" data-iddest="` + row.id + `" class="tgl-destination" data-toggle="toggle">`
            }
            return `<input type="checkbox" data-iddest="` + row.id + `" class="tgl-destination" checked data-toggle="toggle">`
          }
        },
      ],
      "drawCallback": function(settings) {
        $('.tgl-hubless').bootstrapToggle()
        $('.tgl-implant').bootstrapToggle()
        $('.tgl-destination').bootstrapToggle()
        initLRCSwicthAction()
        // initDestSwicthAction()

        $('html, body').animate({
          scrollTop: $('#table-hub').offset().top
        }, 'fast');
      },

    });

  });

  function initLRCSwicthAction() {
    $('.tgl-hubless').change(function() {
      var id = $(this).data('id');
      var implant = $(document).find("[data-idimplant='" + id + "']").prop('checked');
      var dest = $(document).find("[data-iddest='" + id + "']").prop('checked');
      var ajaxurl = '{{route("switch_lrc")}}';
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          "id": id,
          "prop_destination": dest,
          "prop_implant": implant,
          "prop_hubless": $(this).prop('checked'),
          "type": "HUBLESS"
        },
        success: function(data) {
          Toast.fire({
            type: 'success',
            title: 'Berhasil Mengubah Data Logistic Rate City'
          })
        },
        error: function(err) {
          Toast.fire({
            type: 'error',
            title: 'Status Hubless Belum Bisa Diubah'
          })


          if (err.status == 401) {
            window.location = '{{ url("/oauth2/sign_out") }}'
          }
        }
      });
    })

    $('.tgl-implant').change(function() {
      var id = $(this).data('idimplant');
      var hubless = $(document).find("[data-id='" + id + "']").prop('checked');
      var dest = $(document).find("[data-iddest='" + id + "']").prop('checked');
      var ajaxurl = '{{route("switch_lrc")}}';
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          "id": id,
          "prop_destination": dest,
          "prop_implant": $(this).prop('checked'),
          "prop_hubless": hubless,
          "type": "IMPLANT"
        },
        success: function(data) {
          Toast.fire({
            type: 'success',
            title: 'Berhasil Mengubah Data Logistic Rate City'
          })
        },
        error: function(err) {
          Toast.fire({
            type: 'error',
            title: 'Status Implant Belum Bisa Diubah'
          })


          if (err.status == 401) {
            window.location = '{{ url("/oauth2/sign_out") }}'
          }
        }
      });
    })

    $('.tgl-destination').change(function() {
      var id = $(this).data('iddest');
      var hubless = $(document).find("[data-id='" + id + "']").prop('checked');
      var implant = $(document).find("[data-idimplant='" + id + "']").prop('checked');
      var ajaxurl = '{{route("switch_lrc")}}';
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          "id": id,
          "prop_destination": $(this).prop('checked'),
          "prop_implant": implant,
          "prop_hubless": hubless,
          "type": "DESTINATION"
        },
        success: function(data) {
          Toast.fire({
            type: 'success',
            title: 'Berhasil Mengubah Data Logistic Rate City'
          })
        },
        error: function(err) {
          Toast.fire({
            type: 'error',
            title: 'Status Destination Belum Bisa Diubah'
          })


          if (err.status == 401) {
            window.location = '{{ url("/oauth2/sign_out") }}'
          }
        }
      });
    })
  }
</script>
@endpush