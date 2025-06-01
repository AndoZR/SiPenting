<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="{{ asset('src/img/logo.png') }}" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="wrapper">
    <div class="logo">
        <img src="{{ asset('src/img/logo.png') }}" alt="">
    </div>
    <div class="text-center mt-4 name">
        Sipenting
    </div>

    <!-- Tombol Role -->
    <div class="d-flex flex-column px-3 mt-4">
        <span class="mb-2 text-muted text-center">Admin Sebagai:</span>
        <div class="d-flex justify-content-between" style="gap: 1rem;">
            <button type="button" class="btn role-btn active" id="btn-bapeda">Bapeda</button>
            <button type="button" class="btn role-btn inactive" id="btn-puskesmas">Puskesmas</button>
        </div>
    </div>

    <!-- Form Login -->
    <form class="p-3 mt-4" id="form-login" method="POST" action="{{ route('login-web-bapeda') }}">
        @csrf
        <div class="form-field d-flex align-items-center mb-4">
        <span class="fas fa-lock"></span>
        <input type="password" name="password" id="password" placeholder="Masukkan Password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-2">Masuk</button>
    </form>
    </div>
</body>
</html>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  const btnBapeda = document.getElementById('btn-bapeda');
  const btnPuskesmas = document.getElementById('btn-puskesmas');
  const form = document.getElementById('form-login');

  btnBapeda.addEventListener('click', () => {
    form.action = "{{ route('login-web-bapeda') }}";
    btnBapeda.classList.add('active');
    btnBapeda.classList.remove('inactive');
    btnPuskesmas.classList.add('inactive');
    btnPuskesmas.classList.remove('active');
  });

  btnPuskesmas.addEventListener('click', () => {
    form.action = "{{ route('login-web-puskesmas') }}";
    btnPuskesmas.classList.add('active');
    btnPuskesmas.classList.remove('inactive');
    btnBapeda.classList.add('inactive');
    btnBapeda.classList.remove('active');
  });
</script>

<style>
  /* Importing fonts from Google */
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

    .role-btn {
        width: 49%;
        transition: all 0.3s ease;
        font-weight: 600;
    }

    /* Tombol aktif: warna cerah dan jelas */
    .role-btn.active {
        background-color: #ff6b00; /* oranye terang */
        color: white;
        opacity: 1;
    }

    /* Tombol tidak aktif: warna pudar */
    .role-btn.inactive {
        background-color: #ff6b00;
        color: white;
        opacity: 0.5;
    }

  /* Reseting */
  * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
  }

  body {
      background: #ecf0f3;
  }

  .wrapper {
      max-width: 350px;
      min-height: 500px;
      margin: 80px auto;
      padding: 40px 30px 30px 30px;
      background-color: #ecf0f3;
      border-radius: 15px;
      box-shadow: 13px 13px 20px #cbced1, -13px -13px 20px #fff;
  }

  .logo {
      width: 80px;
      margin: auto;
  }

  .logo img {
      width: 100%;
      height: 80px;
      object-fit: cover;
      border-radius: 50%;
      box-shadow: 0px 0px 3px #5f5f5f,
          0px 0px 0px 5px #ecf0f3,
          8px 8px 15px #a7aaa7,
          -8px -8px 15px #fff;
  }

  .wrapper .name {
      font-weight: 600;
      font-size: 1.4rem;
      letter-spacing: 1.3px;
      padding-left: 10px;
      color: #555;
  }

  .wrapper .form-field input {
      width: 100%;
      display: block;
      border: none;
      outline: none;
      background: none;
      font-size: 1.2rem;
      color: #666;
      padding: 10px 15px 10px 10px;
      /* border: 1px solid red; */
  }

  .wrapper .form-field {
      padding-left: 10px;
      margin-bottom: 20px;
      border-radius: 20px;
      box-shadow: inset 8px 8px 8px #cbced1, inset -8px -8px 8px #fff;
  }

  .wrapper .form-field .fas {
      color: #555;
  }

  .wrapper .btn {
      box-shadow: none;
      width: 100%;
      height: 40px;
      background-color: #03A9F4;
      color: #fff;
      border-radius: 25px;
      box-shadow: 3px 3px 3px #b1b1b1,
          -3px -3px 3px #fff;
      letter-spacing: 1.3px;
  }

  .wrapper .btn:hover {
      background-color: #039BE5;
  }

  .wrapper a {
      text-decoration: none;
      font-size: 0.8rem;
      color: #03A9F4;
  }

  .wrapper a:hover {
      color: #039BE5;
  }

  @media(max-width: 380px) {
      .wrapper {
          margin: 30px 20px;
          padding: 40px 15px 15px 15px;
      }
  }
</style>

@if(session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Gagal",
            text: `{{ session('error') }}`,
        });
    </script>
@endif
