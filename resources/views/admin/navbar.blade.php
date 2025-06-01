<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="index.html">Sipenting Admin</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                {{-- <li><a class="dropdown-item" href="#!">Settings</a></li>
                <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                <li><hr class="dropdown-divider" /></li> --}}
                @if (auth('puskesmas')->check())
                <li><a class="dropdown-item btn-nomor" href="#">Nomor Kontak</a></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('logout-web') }}">Logout</a></li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Modal Create Artikel -->
<div class="modal fade" id="modal-nomor" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ganti Nomor Kontak</h5>
        </div>
        <form id="form-ganti-nomor">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="nomor">Nomor Kontak <span class="text-danger">*</span></label>
                  <input value="{{ Auth::user()->nomor }}" type="text" class="form-control" name="nomor" id="nomor" placeholder="Silahkan Ganti Nomor Sesuai" autofocus autocomplete="off" value="{{ Auth::user()->nomor }}">
                  <div class="invalid-feedback nomor_error"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn" data-bs-dismiss="modal">
                  <i class="bx bx-x d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Batal</span>
              </button>
              <button type="submit" class="btn btn-primary ms-1">
                  <i class="bx bx-check d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Simpan</span>
              </button>
          </div>
        </form>
      </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Event delegation to handle clicks on dynamically generated buttons
        $(document).on('click', '.btn-nomor', function(event) {
            event.preventDefault(); // Prevent default link behavior
            
            $('#modal-nomor').modal('show'); // Show the modal
        });

        // Submit Form Create art
        $('#form-ganti-nomor').submit(function(e) {
            e.preventDefault();
            url = "{{ route('puskesmas.ganti-Nomor-Puskesmas') }}";

            var formData = new FormData($("#form-ganti-nomor")[0]);

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('*').removeClass('is-invalid');
                },
                success: function(response) {
                    $('#modal-nomor').modal('hide');
                    // Update nilai input dan tombol dengan data baru
                    if (response.data && response.data.nomor) {
                        $('#nomor').val(response.data.nomor);

                        // Update tombol dengan nomor terbaru (supaya klik selanjutnya ambil yang baru)
                        $('.btn-nomor').attr('data-nomor', response.data.nomor);
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Tersimpan!',
                        text: response.meta.message,
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    switch (xhr.status) {
                        case 422:
                        var errors = xhr.responseJSON.meta.message;
                        var message = '';
                        $.each(errors, function(key, value) {
                            message = value;
                            $('*[name="' + key + '"]').addClass('is-invalid');
                            $('.invalid-feedback.' + key + '_error').html(value);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: message,
                        })
                        break;
                        default:
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan!',
                        })
                        break;
                    }
                }
            });
        })
    })
</script>