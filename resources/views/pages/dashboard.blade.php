@extends('layouts.master')

@section('title')
{{ $title }}
@stop

@section('breadcrumbs')
{{ $breadcrumbs }}
@stop


@section('content')


<div class="card">
  <div class="card-body">
    <div class="row">
      <div class="col-12">
        <h2>Welcome to cilientname internal tools.</h2>
        <hr />
        <br />
        <h4>English</h4>
        <p>Welcome to cilientname Intools, we hope tools provided can help you in your daily work.</p>
        <p>If you have ideas about tools, or improvement, please contact Product or Tech team.</p>
        <br />
        <h4>Bahasa Indonesia</h4>
        <p>Selamat datang di cilientname Intools. Semoga tools-tools disini dapat membantu pekerjaan Anda sehari-hari.</p>
        <p>Jika anda punya ide-ide untuk pengembangan intools, mohon hubungi tim Product atau Tech team.<p>
            <br /><br />
            Thank you for using Intools.
      </div>
    </div>
  </div>
</div>

@stop
@push('scripts')
@if(\Session::has('alert'))
<script>
  Toast.fire({
    type: 'error',
    title: 'Terjadi Kesalahan {{Session::get("alert")}}'
  })
</script>
@endif
@endpush