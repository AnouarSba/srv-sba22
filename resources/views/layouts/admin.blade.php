<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>{{ config('app.name', 'Laravel') }}</title>
 
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet"  href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet"  href="{{ asset('dist/css/adminlte.min.css') }}" >

  <livewire:styles />
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
    </div>
     <!-- Navbar -->
  @include('part.nav')
  <!-- /.navbar -->
  
  <!-- Main Sidebar Container -->
  @include('part.sed')
  
  
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      @yield('content')
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
  
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->
<!-- jQuery -->
<script  src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script  src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script  src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<livewire:scripts />
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-livewire-alert::scripts />
<script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script> 
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

@livewireChartsScripts
<x-livewire-alert::flash />
<script type="text/javascript">
  window.livewire.on('userStore', () => {
      $('#exampleModal').modal('hide');
  });
  window.livewire.on('userStore1', () => {
      $('#exampleModal1').modal('hide');
  });
  window.livewire.on('userUpdate', () => {
      $('#updateModal').modal('hide');
  });
  window.livewire.on('add', () => {
      $('#addModal').modal('hide');
  });
  window.livewire.on('add2', () => {
      $('#addModal2').modal('hide');
  });
  window.livewire.on('mines', () => {
      $('#minesModal').modal('hide');
  });
  window.livewire.on('perm', () => {
      $('#permModal').modal('hide');
  });
</script>

</body>
</html>
