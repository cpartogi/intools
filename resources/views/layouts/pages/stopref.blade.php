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
        <h3 class="card-title">Location</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">

        <div class="row">

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label>Pilih Negara :</label>
              <select id="negara" class="form-control select2-success" data-dropdown-css-class="select2-success" style="width: 100%;">
                <option selected="selected">Pilih Negara</option>
                @foreach ($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name}}</option>
                @endforeach

              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class=" col-12 col-sm-4">
            <div class="form-group">
              <label>Pilih Provinsi :</label>
              <select id="provinsi" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-success" style="width: 100%;">
                <option selected="selected">Pilih Provinsi</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label>Pilih Kota :</label>
              <select id="kota" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-success" style="width: 100%;">
                <option value="">Pilih Kota</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label>Pilih Kecamatan :</label>
              <select id="suburb" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-success" style="width: 100%;">
                <option value="">Pilih Kecamatan</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label>Pilih Kelurahan :</label>
              <select id="area" class="form-control select2-success" disabled="disabled" data-dropdown-css-class="select2-success" style="width: 100%;">
                <option value="">Pilih Kelurahan</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-12">
            <div class="form-group">
              <label>Pilih Logistik :</label>
              <select id="logistik" class="form-control select2-success" data-dropdown-css-class="select2-success" style="width: 100%;">
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <button id="cari" type="button" class="btn btn-block btn-primary">Cari</button>

        </div>
        <br>
        <table id="table-stop" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color: #D3D3D3;" cellspacing="0" cellpadding="0">
              <th width="100px">Prov</th>
              <th width="100px">Kota</th>
              <th width="100px">Kecamatan</th>
              <th width="100px">Kelurahan</th>
              <th width="100px">StopID</th>
              <th width="100px">StopName</th>
              <th width="100px">Logistic</th>
              <th width="100px">StopRef</th>
              <th width="100px">Aksi</th>

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
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update StopRef</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form>
        <div class="form-group">
          <label for="old_stopref">StopRef Saat Ini</label>
          <input type="text" class="form-control" id="old_stopref" aria-describedby="cek1" placeholder="Current StopRef" readonly="">
          <small id="cek1" class="form-text text-muted">Akan digantikan oleh StopRef baru</small>
        </div>
        <div class="form-group">
          <label for="new_stopref">StopRef Baru</label>
          <input type="text" class="form-control" id="new_stopref" aria-describedby="cek2" placeholder="Current StopRef">
          <small id="cek2" class="form-text text-muted">Akan menggantikan StopRef saat ini</small>
        </div>
        <input type="hidden" value="" id="stop_id" />
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="simpan">Simpan</button>
      </div>
    </div>
  </div>
</div>
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
  function getProvince(id) {
      var ajaxurl = '{{route("getProvinces", ":id")}}';
      ajaxurl = ajaxurl.replace(':id', id);
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
              $.each(data, function (i, item) {
                  $('#provinsi').append($('<option>', {
                      value: item.id,
                      text: item.name
                  }));
              });
          }
      });
  }

  function getCity(id) {
      var ajaxurl = '{{route("getCities", ":id")}}';
      ajaxurl = ajaxurl.replace(':id', id);
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
            $('#kota').html("<option selected=\"selected\">Pilih Kota</option>");
              $.each(data, function (i, item) {
                  $('#kota').append($('<option>', {
                      value: item.id,
                      text: item.name
                  }));
              });
          }
      });
  }

  function getSuburb(id) {
      var ajaxurl = '{{route("getSuburbs", ":id")}}';
      ajaxurl = ajaxurl.replace(':id', id);
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
            $('#suburb').html("<option selected=\"selected\">Pilih Kecamatan</option>");
              $.each(data, function (i, item) {
                  $('#suburb').append($('<option>', {
                      value: item.id,
                      text: item.name
                  }));
              });
          }
      });
  }

  function getArea(id) {
      var ajaxurl = '{{route("getAreas", ":id")}}';
      ajaxurl = ajaxurl.replace(':id', id);
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
            $('#area').html("<option selected=\"selected\">Pilih Kelurahan</option>");
              $.each(data, function (i, item) {
                  $('#area').append($('<option>', {
                      value: item.id,
                      text: item.name
                  }));
              });
          }
      });
  }
  //Initialize Select2 Elements
  $('#negara').select2()
  $('#negara').on('select2:select', function (e) {
      var data = e.params.data;
      document.getElementById("provinsi").disabled = false;
      getProvince(data.id)
  });

  $('#provinsi').select2()
  $('#provinsi').on('select2:select', function (e) {
      var data = e.params.data;
      getCity(data.id)
      document.getElementById("kota").disabled = false;
  });


  $('#kota').on('select2:select', function (e) {
      var data = e.params.data;
      getSuburb(data.id)
      document.getElementById("suburb").disabled = false;
  });

  $('#suburb').on('select2:select', function (e) {
      var data = e.params.data;
      getArea(data.id)
      document.getElementById("area").disabled = false;
  });

  $('#kota').select2()
  $('#suburb').select2()
  $('#area').select2()


  function getLogistik() {
      var ajaxurl = '{{route("list_logistic")}}';
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
              $.each(data, function (i, item) {
                  $('#logistik').append($('<option>', {
                      value: item.id,
                      text: item.name
                  }));
              });
          }
      });
  }
  getLogistik()
  $('#logistik').select2()
  // Initialize datatables

  $("#cari").on("click", function () {
      var area_id     = $("#area").val();
      var suburb_id   = $("#suburb").val();
      var logistic_id = $("#logistik").val();
      var prov_name   = $('#provinsi').select2('data')[0].text;
      var city_name   = $('#kota').select2('data')[0].text;
      var suburb_name = $('#suburb').select2('data')[0].text;
      if(Number.isInteger(area_id) === false){
        area_id = "";
      }
      $.fn.dataTable.ext.errMode = 'none';

      $('#table-stop').DataTable().clear().destroy()
      $('#table-stop').DataTable({
          processing: true,
          serverSide: true,
          paging: true,
          lengthChange: false,
          searching: false,
          ordering: false,
          info: true,
          autoWidth: true,
          ajax: {
              url: '{{route("list_stopref")}}?&logistic_id=' + logistic_id + '&suburb_id=' + suburb_id+ '&area_id=' + area_id,
          },
          "columns": [
              { 
                "data": "prov_name", "render": function (data, type, row, meta) {
                      return prov_name
                  }
              },
              { 
                "data": "city_name", "render": function (data, type, row, meta) {
                      return city_name
                  }
              },
              { 
                "data": "suburb_name", "render": function (data, type, row, meta) {
                      return suburb_name
                  }
              },
              {
                  "data": "stop.area.name", "render": function (data, type, row, meta) {
                      if (data == null) {
                          return 'IS_SUBURB'
                      }
                      return data
                  }
              },
              { "data": "stop.id" },
              { "data": "stop.name"},
              { "data": "logistic.name" },
              { "data": "stop_ref" },
              {
                  "data": "stop.id", "render": function (data, type, row, meta) {
                      if (data > 0) {
                          console.log(row);
                          var stopid = row.stop.id;
                          var stopref = row.stop_ref;
                          return `<button class="btn btn-default updStopRef" data-toggle="modal" onclick="updateStopRef('`+stopid+`','`+stopref+`')" stopref="`+row.stop_ref+`" data-target="#updateModal">UPDATE</button>`;
                      }
                      return null;
                  }
              },
          ],
          
      }).on( 'error.dt', function ( e, settings, techNote, message ) {
        alert('Data tidak ditemukan');
        return true;
      });
  });
  function updateStopRef(e,f){
    $('#old_stopref').val(f);
    $('#stop_id').val(e);
  }
  $('#updateModal').on('hidden.bs.modal', function () {
   $('#old_stopref').val('');
   $('#new_stopref').val('');
   $('#stop_id').val('');
  })

  $('#simpan').on('click',function(){
    var logistic_id = $("#logistik").val();
    var old_stopref = $("#old_stopref").val();
    var new_stopref = $("#new_stopref").val();
    var stop_id     = $("#stop_id").val();
    var ajaxurl = '{{route("update_stopref")}}?logistic_id=' + logistic_id + "&old_stopref=" + old_stopref + "&new_stopref=" + new_stopref+ "&stop_id=" + stop_id;
      $.ajax({
          type: "GET",
          url: ajaxurl,
          success: function (data) {
              if(data.metadata.status == 'OK'){
                alert("Update berhasil.");
                $('#updateModal').modal().hide();
                $('#cari').click();
                $('.modal-backdrop').hide();
              }
              else{
                alert("Update gagal.");
              }
          }
      });
  })
  </script>
@endpush
