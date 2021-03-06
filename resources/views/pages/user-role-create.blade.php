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
      <form id="role-form" role="form" method="POST" action="{{ route('role.store') }}">
        @csrf
        <div class="card-body">

          <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="name" placeholder="Enter Name">
          </div>

          <div class="form-group">
            <label for="slug">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" placeholder="Enter Slug">
          </div>

          <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="Enter Description">
          </div>

          <div class="form-group">
            <label for="permissions">Assign Permissions</label>
            <select name="permissions[]" multiple="multiple" class="form-control permission" style="width: 100%;" data-dropdown-css-class="select2-purple" data-placeholder="Select Permissions">
              @foreach ($permissions as $item)
              <option value="{{$item->id}}">{{$item->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Buat</button>
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
  $('#role-form').validate({
    rules: {
      slug: {
        required: true,
        minlength: 3,
        notOnlySpace: true
      },
      name: {
        required: true,
        // minlength: 3,
        // notOnlySpace: true
      },
    },
    messages: {
      slug: {
        required: "Masukkan Slug",
        minlength: "Masukkan Slug Lebih dari 3",
      },
      name: {
        required: "Masukkan Nama",
        minlength: "Masukkan Nama Lebih dari 3",
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

  $('.permission').select2()
</script>

@if ($errors->any())
@foreach ($errors->all() as $error)
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{$error}}'
  })
</script>
@endforeach
@endif
@endpush