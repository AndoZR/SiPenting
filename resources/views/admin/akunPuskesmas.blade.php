@extends('admin.app')
@section('title', 'Akun Puskesmas')
@section('sub-title', 'Dashboard / Akun Puskesmas')

@push('css')
    {{-- datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('src/compiled/css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/app-dark.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/iconly.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('src/compiled/css/table-datatable.css') }}">
    <style>
        .scrollable-description {
            max-height: 100px !important; /* Batas tinggi cell deskripsi */
            /* max-width: 300px !important; */
            overflow-y: auto !important; /* Menampilkan scrollbar vertikal jika teks terlalu panjang */
            overflow-x: hidden !important; /* Menyembunyikan scrollbar horizontal */
            word-wrap: break-word !important; /* Memastikan teks terbungkus di dalam cell */
            display: block;
        }
    </style>
     
@endpush

@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable
            </div>
            <div class="card-body">
                <table class="table" id="table-akun-puskesmas">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ganti Password -->
<div class="modal fade" id="modal-ganti_pw" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Setelah tersimpan, Ingat password ada tambahan "puskesmas_" di awal</h5>
          <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
              <i data-feather="x"></i>
          </button>
        </div>
        <form id="form-ganti-pw">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <label for="pw">Password Baru <span class="text-danger">*</span></label>
                <div class="form-group input-group">
                  <span class="input-group-text" id="basic-addon1">puskesmas_</span>
                  <input type="text" class="form-control" name="pw" id="pw" autofocus autocomplete="off">
                  <div class="invalid-feedback pw_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <label for="confirm_pw">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="form-group input-group">
                  <span class="input-group-text" id="basic-addon1">puskesmas_</span>
                  <input type="text" class="form-control" name="confirm_pw" id="confirm_pw" autofocus autocomplete="off">
                  <div class="invalid-feedback confirm_pw_error"></div>
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

@endsection

@push('scripts')
<script src="{{ asset('src/admin/js/datatables-simple-demo.js') }}"></script>

{{-- <script src="{{ asset('src/compiled/js/app.js') }}"></script> --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>

<script>
    $(document).ready(function () {
        $('.modal').on('hidden.bs.modal', function(e) {
            $('form').trigger('reset');
            $('*').removeClass('is-invalid');
            $('.custom-file-label').html('Pilih file...');
            idAkunPuskesmas = undefined;
        });

        var idAkunPuskesmas;
        let url;
        let urlTest = '{{ route('akun_puskesmas.viewAkunPuskesmas') }}';

        let tableAkunPuskesmas = $('#table-akun-puskesmas').DataTable({
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: urlTest,
                type: "GET"
            },
            columnDefs: [
                {
                    targets: 0,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    targets: 1,
                    data: 'username',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 2,
                    data: 'name',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 3,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        $button = `<button class="btn btn-warning btn-pw" title="Ubah">Ganti Password</button>`;
                        return $button;
                    }
                },
            ],
        });

        // open modal
        $('#table-akun-puskesmas tbody').on('click', '.btn-pw', function() {
            var data = tableAkunPuskesmas.row($(this).parents('tr')).data();
            idAkunPuskesmas = data.id;
            // show modal
            $('#modal-ganti_pw').modal('show');
        });

        // Submit Form Create art
        $('#form-ganti-pw').submit(function(e) {
            e.preventDefault();
            url = "{{ route('akun_puskesmas.changePassword', ['id' => ':id']) }}";
            url = url.replace(':id', idAkunPuskesmas)

            // Ambil nilai dari input
            var pw = $('#pw').val();
            var confirmPw = $('#confirm_pw').val();

            // Reset pesan error sebelumnya
            $('.pw_error').text('');
            $('.confirm_pw_error').text('');
            $('#pw, #confirm_pw').removeClass('is-invalid');

            // Cek kecocokan password
            if (pw !== confirmPw) {
                // Tampilkan error
                $('#confirm_pw').addClass('is-invalid');
                $('.confirm_pw_error').text('Password tidak cocok.');
                return; // Hentikan proses submit
            }

            var formData = new FormData($("#form-ganti-pw")[0]);

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
                    $('#modal-ganti_pw').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Tersimpan!',
                        text: response.meta.message,
                    });
                    tableAkunPuskesmas.ajax.reload();
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
        });
    })
</script>
@endpush