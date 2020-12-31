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
        <h3 class="card-title">Shipment Pricing</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form id="domestic" onsubmit="event.preventDefault();">
        <div class="row">
          <div class="col-12 col-sm-6">
            <div class="form-group">
              <label>Pilih Asal :</label>
              <select id="asal" name="asal" class="area form-control select2-success" data-dropdown-css-class="select2-success" style="width: 100%;" required>
                <option disabled selected>Pilih Asal</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-6">
            <div class="form-group">
              <label>Pilih Tujuan :</label>
              <select id="tujuan" name="tujuan" class="area form-control select2-success" data-dropdown-css-class="select2-success" style="width: 100%;" required>
                <option disabled selected>Pilih Tujuan</option>
              </select>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="item_value">Item Value</label>
              <input type="number" class="form-control" id="item_value" name="item_value" placeholder="Enter Item Value" required>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="item_length">Length</label>
              <input type="number" class="form-control" id="item_length" name="item_length" placeholder="Enter Length" required>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="item_width">Width</label>
              <input type="number" class="form-control" id="item_width" name="item_width" placeholder="Enter Width" required>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="item_height">Height</label>
              <input type="number" class="form-control" id="item_height" name="item_height" placeholder="Enter Height" required>
            </div>
            <!-- /.form-group -->
          </div>

          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="item_weight">Weight</label>
              <input type="number" class="form-control" id="item_weight" name="item_weight" placeholder="Enter Weight" required>
            </div>
            <!-- /.form-group -->
          </div>

          <button type="submit" class="btn btn-block btn-primary">DAPATKAN HARGA</button>
        </div>
        </form>
        <br>
        <table id="table-shipment-pricing" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color: #D3D3D3;" cellspacing="0" cellpadding="0">
              <th width="100px">Logistic</th>
              <th width="100px">Service</th>
              <th width="100px">Price</th>
              <th width="100px">Last Update</th>
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
  // function getRates(id) {
  //     var ajaxurl = '{{route("getRates", ":id")}}';
  //     ajaxurl = ajaxurl.replace(':id', id);
  //     $.ajax({
  //         type: "GET",
  //         url: ajaxurl,
  //         success: function (data) {
  //           $('#rate').html("<option selected=\"selected\">Pilih Service</option>");
  //             $.each(data, function (i, item) {
  //                 $('#rate').append($('<option>', {
  //                     value: item.id,
  //                     text: item.name
  //                 }));
  //             });
  //         }
  //     });
  // }

  // $('#logistic').on('select2:select', function (e) {
  //     var data = e.params.data;
  //     getRates(data.id)
  //     document.getElementById("rate").disabled = false;
  // });  
  
  // function getLogistic() {
  //     var ajaxurl = '{{route("list_logistic")}}';
  //     $.ajax({
  //         type: "GET",
  //         url: ajaxurl,
  //         success: function (data) {
  //             $.each(data, function (i, item) {
  //                 $('#logistic').append($('<option>', {
  //                     value: item.id,
  //                     text: item.name
  //                 }));
  //             });
  //         }
  //     });
  // }
  // getLogistic()
  
  $('.area').select2({
  ajax: {
    url: '{{route("list_area")}}',
    dataType: 'json'
  }
});
$(document).ready(function() {
    $('#domestic').validate({
      rules: {
        asal: {
          required: true,
        },
        tujuan: {
          required: true,
        },
        item_value: {
          required: true,
        },
        item_length: {
          required: true,
        },
        item_height: {
          required: true,
        },
        item_width: {
          required: true,
        },
      },
      messages: {
        asal: {
          required: "masukan asal",
        },
        tujuan: {
          required: "masukan tujuan",
        },
        item_value: {
          required: "masukan harga barang",
        },
        item_length: {
          required: "masukan panjang barang",
        },
        item_height: {
          required: "masukan tinggi barang",
        },
        item_width: {
          required: "masukan berat barang",

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
      },
      submitHandler: function (){
        var origin_area_id        = $("#asal").val();
        var destination_area_id   = $("#tujuan").val();
        var item_value            = $('#item_value').val();
        var item_length           = $('#item_length').val();
        var item_width            = $('#item_width').val();
        var item_height           = $('#item_height').val();
        var item_weight           = $('#item_weight').val();
        
        $.fn.dataTable.ext.errMode = 'none';

        $('#table-shipment-pricing').DataTable().clear().destroy()
        $('#table-shipment-pricing').DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            lengthChange: false,
            searching: false,
            ordering: false,
            info: true,
            autoWidth: true,
            ajax: {
                url: '{{route("search_shipment_pricing")}}?&origin_area_id=' + origin_area_id + 
                '&destination_area_id=' + destination_area_id + 
                '&item_value=' + item_value + '&item_length=' + item_length + '&item_width=' + item_width + 
                '&item_height=' + item_height + '&item_weight=' + item_weight,
            },
            "columns": [
                { "data": "logistic.name" },
                { "data": "rate.name" },
                { "data": "final_price",
                  render: $.fn.dataTable.render.number( ',', '.', 2 )},
                { "data": "updated_at" }, // TODO: set the value of last update
            ],
            columnDefs: [
            {
                targets: 2,
                className: 'text-right'
            }
          ]
            
        });
      }
    });
  });
  
  // $("#domestic").on("submit", function (event) {
      
  // });
  </script>
@endpush
