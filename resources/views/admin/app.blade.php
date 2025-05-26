<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <link rel="icon" href="{{ asset('src/img/logo.png') }}" type="image/png">
        <title>@yield('title')</title>

        @stack('css')
        
        <link href="{{ asset('src/admin/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
  @include('admin.navbar')
  <div id="layoutSidenav">
    @include('admin.sidebar')
      <div id="layoutSidenav_content">
          <main>
              <div class="container-fluid px-4">
                  <h1 class="mt-4">@yield('sub-title')</h1>
                  <ol class="breadcrumb mb-4">
                      <li class="breadcrumb-item active">@yield('sub-title')</li>
                  </ol>
                  <div class="row">
                    @yield('content')
                  </div>
              </div>
          </main>
          @include('admin.footer')
      </div>
  </div>
</body>
      
@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('src/admin/js/scripts.js') }}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script> --}}
<script src="{{ asset('src/admin/assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('src/admin/assets/demo/chart-bar-demo.js') }}"></script>

</html>