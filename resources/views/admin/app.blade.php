<!DOCTYPE html>
<html lang="en" class="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <!-- Tailwind is included -->
  <link rel="stylesheet" href="{{ asset('src/css/main.css?v=1652870200386') }}">

  <link rel="shortcut icon" href="{{ asset('src/compiled/svg/favicon.svg') }}" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css">
  <link rel="stylesheet" href="{{ asset('src/compiled/css/app.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/app-dark.css') }}"> --}}
  {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/iconly.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('src/compiled/css/table-datatable.css') }}">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-130795909-1"></script>

</head>
<body style="
padding-top: 0px;
">

<div id="app">

@include('admin.navbar')

@include('admin.sidebar')

<section class="is-title-bar">
  <div class="flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0">
    <ul>
      <li>Admin</li>
      <li>@yield('sub-title')</li>
    </ul>
  </div>
</section>

@yield('content')

@include('admin.footer')

</div>

<!-- Scripts below are for demo only -->
<script type="text/javascript" src="{{ asset('src/js/main.min.js?v=1652870200386') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script type="text/javascript" src="{{ asset('src/js/chart.sample.min.js') }}"></script>


<script src="{{ asset('src/compiled/js/app.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')

<!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">

</body>
</html>
