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
        <div class="col-md-12">
          <a href="{{ route('role.create') }}" class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> Tambah User Role
          </a>
        </div>
      </div>
      <br>

      <table id="table-user" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Slug</th>
            <th>Permission</th>
            <th>Action</th>
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
        "data": "slug"
      },
      {
        "data": "perms"
      },
      {
        "data": "action",
        "render": function(data, type, row, meta) {

          var routeEdit = '{{ route("role.edit", ":id") }}';
          routeEdit = routeEdit.replace(':id', row.id);

          var routeDelete = '{{ route("role.delete", ":id") }}';
          routeDelete = routeDelete.replace(':id', row.id);

          return `<div class="row">
                <div class="col-md-6">
                  <a href="` + routeEdit + `" class="btn btn-block btn-primary edit-role">Edit Role</a>
                </div>
                <div class="col-md-6">
                  <form class="col-md-12" action="` + routeDelete + `" method="post">
                    @csrf
                    @method('delete')
                    <a class="btn btn-block btn-danger" href="#" type="button" onclick="confirm('Apakah Anda yakin menghapus data ini?') ? this.parentElement.submit() : ''">
                      Delete Role
                    </a>
                  </form>
                </div>
              </div>`
        }
      },
    ],
    ajax: {
      url: '{{route("getRole")}}',
    },
    "drawCallback": function(settings) {

      $('html, body').animate({
        scrollTop: $('#table-user').offset().top
      }, 'fast');

      $('.permission').select2();
      initPermissionAction()
    },
  });

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function initPermissionAction() {
    $('.edit-perm').click(function() {
      roleID = $(this).attr('data-idrole');
      if ($(this).html() == "Edit Permission") {

        $(document).find(".permission[data-idrole='" + roleID + "']").attr('disabled', false);

        $(this).html("Save");
      } else if ($(this).html() == "Save") { // save behavior
        $(document).find(".permission[data-idrole='" + roleID + "']").attr('disabled', true);

        $.ajax({
          type: "POST",
          url: '{{route("update_role_perm")}}',
          data: {
            "_token": token,
            "id": roleID,
            "perms": $(document).find(".permission[data-idrole='" + roleID + "']").val()
          },
          success: function(data) {
            Toast.fire({
              type: 'success',
              title: 'Permission Untuk Role Telah Diupdate'
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

        $(this).html("Edit Permission");
      }

    })
  }
</script>

@if(\Session::has('alert'))
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{Session::get("alert")}}'
  })
</script>
@endif

@if(\Session::has('success'))
<script>
  Toast.fire({
    type: 'success',
    title: '{{Session::get("success")}}'
  })
</script>
@endif

@endpush