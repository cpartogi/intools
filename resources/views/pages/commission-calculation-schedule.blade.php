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
        <div class="row">
          <div class="col-md-12">
            <a href="{{ route('commissionCalculationDate.create') }}" class="btn btn-primary">
              <i class="fa fa-plus" aria-hidden="true"></i> Tambah Schedule
            </a>
          </div>
        </div>

        <br>

        <div class="row">
          <div class="col-12 col-sm-4">
            <div class="form-group">
              <label for="order_id">Order ID</label>
              <input type="text" class="form-control" id="order_id" name="order_id" placeholder="Enter Item Value">
            </div>
            <!-- /.form-group -->
          </div>

          <button id="cari" type="button" class="btn btn-block btn-primary">LIHAT JADWAL</button>

        </div>
        <br>
        <table id="table-commission-calculation" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color: #D3D3D3;" cellspacing="0" cellpadding="0">
              <th width="100px" style="display:none"></th>
              <th width="100px">Order ID</th>
              <th width="100px">AWB Number</th>
              <th width="100px">Agent ID</th>
              <th width="100px">Calculation Date</th>
              <th width="100px">Is Processed</th>
              <th width="100px">Shipment Status</th>
              <th width="100px">Created Date</th>
              <th width="100px">Created By</th>
              <th width="100px">Updated Date</th>
              <th width="100px">Updated By</th>
              <th width="100px">Action</th>
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

  $("#order_id").keyup(function(e) {
    if(e.keyCode == 13) findCommissionCalculationSchedule();
  });
  $("#cari").on("click", findCommissionCalculationSchedule);

  function findCommissionCalculationSchedule() {
      var order_id = $('#order_id').val();

      $.fn.dataTable.ext.errMode = 'none';

      $('#table-commission-calculation').DataTable().clear().destroy()
      $('#table-commission-calculation').DataTable({
          processing: true,
          serverSide: true,
          paging: false,
          lengthChange: false,
          searching: false,
          ordering: false,
          bInfo: false,
          autoWidth: true,
          ajax: {
              url: '{{route("get_commission_calculation_date")}}?&order_id=' + order_id
          },
          "columns": [
              { "data": "" },
              { "data": "order_id" },
              {
                "data": "awb_number",
                "render": function(data, type, row, meta) {
                  if(data == "") return "-";

                  return data;
                }
              },
              { "data": "agent_id" },
              { "data": "calculation_date"},
              { "data": "is_processed" },
              { "data": "shipment_status.name" },
              { "data": "created_date" },
              { "data": "created_by" },
              { "data": "updated_date" },
              { "data": "updated_by" },
              {
                "data": "action",
                "render": function(data, type, row, meta) {
                  var routeEdit = '{{ route("commissionCalculationDate.edit", ":id") }}';
                  routeEdit = routeEdit.replace(':id', row.order_id);

                  return `<div class="row">
                      <div class="col-md-12">
                        <a href="` + routeEdit + `" class="btn btn-block btn-primary">Edit</a>
                      </div>
                    </div>`
                  }
              },
          ],
          columnDefs: [
            {
              "targets": 0,
              "visible": false
            }
          ]
      });
  }
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
