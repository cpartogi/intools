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

      <table id="table-user" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Verified At</th>
            <th>Active</th>
            <th>Role</th>
          </tr>
        </thead>
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

<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- AdminLTE App -->
<script>
  var token = $('meta[name="csrf-token"]').attr('content')
  $('#table-user').DataTable({
    "processing": true,
    "serverSide": true,
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true,
    "autoWidth": true,
    "columns": [{
        "data": "no",
        render: function(data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        }
      },
      {
        "data": "name"
      },
      {
        "data": "email"
      },
      {
        "data": "email_verified_at"
      },
      {
        "data": "is_active",
        "render": function(data, type, row, meta) {
          if (data == 0) {
            return `<input type="checkbox" data-id="` + row.id + `" class="tgl-active" data-toggle="toggle">`
          }
          return `<input type="checkbox" data-id="` + row.id + `" class="tgl-active" checked data-toggle="toggle">`
        }
      },
      {
        "data": "role"
      },

    ],
    ajax: {
      url: '{{route("getUser")}}',
    },
    "drawCallback": function(settings) {

      $('html, body').animate({
        scrollTop: $('#table-user').offset().top
      }, 'fast');

      $('.role').select2();
      $('.tgl-active').bootstrapToggle()
      initActiveSwicthAction()
      initRoleAction()
    },
  });

  function initRoleAction() {
    $('.edit-user').click(function() {

      roleID = $(this).attr('data-iduser');
      if ($(this).html() == "Edit") {

        $(document).find(".role[data-iduser='" + roleID + "']").attr('disabled', false);

        $(this).html("Save");
      } else if ($(this).html() == "Save") { // save behavior

        console.log($(document).find(".role[data-iduser='" + roleID + "']").val())

        $.ajax({
          type: "POST",
          url: '{{route("update_user_role")}}',
          data: {
            "_token": token,
            "id": roleID,
            "roles": $(document).find(".role[data-iduser='" + roleID + "']").val()
          },
          success: function(data) {
            Toast.fire({
              type: 'success',
              title: 'Role Untuk User Telah Diupdate'
            })
          },
          error: function(err) {
            Toast.fire({
              type: 'error',
              title: 'Update Belum Bisa Dilakukan'
            })

            if (err.status == 401) {
              window.location = '{{ url("/oauth2/sign_out") }}'
            }
          }
        });

        $(document).find(".role[data-iduser='" + roleID + "']").attr('disabled', true);

        $(this).html("Edit");
      }

    })
  }

  function initActiveSwicthAction() {
    $('.tgl-active').change(function() {
      var id = $(this).data('id');
      var user = $(document).find("[data-iduser='" + id + "']").prop('checked');
      var is_active = $(this).prop('checked');
      var ajaxurl = '{{route("switch_active")}}';
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          "_token": token,
          "id": id,
          "is_active": $(this).prop('checked')
        },
        success: function(data) {
          Toast.fire({
            type: 'success',
            title: 'Status User Telah diubah'
          })
        },
        error: function(err) {
          Toast.fire({
            type: 'error',
            title: 'Status User Belum Bisa Diubah'
          })
          $(document).find("[data-iduser='" + id + "']").prop('checked', !is_active);


          if (err.status == 401) {
            window.location = '{{ url("/oauth2/sign_out") }}'
          }
        }
      });
    })
  }
</script>
@endpush