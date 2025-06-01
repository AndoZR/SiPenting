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
                            <th>Name</th>
                            <th>Nomor</th>
                            <th>Daerah</th>
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
<div class="modal fade" id="modal-akun" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Atur Akun</h5>
          <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
              <i data-feather="x"></i>
          </button>
        </div>
        <form id="form-akun">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                  <div class="form-group">
                  <label for="nama">Nama Puskesmas <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nama" id="nama" autofocus autocomplete="off">
                  <div class="invalid-feedback nama_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <div class="form-group">
                  <label for="nomor">Nomor Kontak <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="nomor" id="nomor" autofocus autocomplete="off">
                  <div class="invalid-feedback nomor_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <div class="form-group">
                  <label for="kec">Kecamatan <span class="text-danger">*</span></label>
                  <select class="form-select" name="kec" id="kec">
                    @foreach ($dataKecamatan as $kecamatan)
                        <option class="" value="{{ $kecamatan->id }}">{{ $kecamatan->name }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback kec_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label for="password">Password <span class="text-danger">*</span></label>
                <div class="form-group input-group">
                  <input type="text" class="form-control" name="password" id="password" autofocus autocomplete="off">
                  <div class="invalid-feedback password_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                  <label for="confirm_password">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="form-group input-group">
                  <input type="text" class="form-control" name="confirm_password" id="confirm_password" autofocus autocomplete="off">
                  <div class="invalid-feedback confirm_password_error"></div>
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

        const dataKecamatan = @json($dataKecamatan);

        var idAkunPuskesmas;
        let url;
        let urlView = '{{ route('bapeda.viewAkunPuskesmas') }}';

        let tableAkunPuskesmas = $('#table-akun-puskesmas').DataTable({
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: true,
            responsive: true,
            ajax: {
                url: urlView,
                type: "GET"
            },
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: '<i class="fas fa-plus mr-2"></i> Tambah Data',
                            className: 'btn btn-primary mb-2 mt-2',
                            action: function(e, dt, node, config) {
                                $('#modal-akun').modal('show');
                            }
                        }
                    ]
                },
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
                    data: 'name',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 2,
                    data: 'nomor',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 3,
                    data: 'districts.name',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 4,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        $button = `<button class="btn btn-warning btn-pw" title="Ubah">Ubah</button>
                        <button class="btn btn-danger btn-hapus" title="Hapus">Hapus</button>`;
                        return $button;
                    }
                },
            ],
        });

        // open modal
        $('#table-akun-puskesmas tbody').on('click', '.btn-pw', function() {
            var data = tableAkunPuskesmas.row($(this).parents('tr')).data();
            idAkunPuskesmas = data.id;

            // set form action
            $('input[name="nama"]').val(data.name);
            $('#kec').val(data.districts.id); // akan memilih option dengan value sesuai id kecamatan
            $('input[name="nomor"]').val(data.nomor);

            // show modal
            $('#modal-akun').modal('show');
        });

        // Submit Form Create/edit akun
        $('#form-akun').submit(function(e) {
            e.preventDefault();

            if(idAkunPuskesmas !== undefined){
                url = "{{ route('bapeda.changePassword', ['id' => ':id']) }}";
                url = url.replace(':id', idAkunPuskesmas)
            }else{
                url = "{{ route('bapeda.addPuskesmas') }}";
            }

            var formData = new FormData($("#form-akun")[0]);

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
                    $('#modal-akun').modal('hide');
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

        // Hapus Data
        $('#table-akun-puskesmas tbody').on('click', '.btn-hapus', function() {
            var data = tableAkunPuskesmas.row($(this).parents('tr')).data();
            let urlDestroy = "{{ route('bapeda.hapus-akun-puskesmas', ['id' => ':id']) }}"
            urlDestroy = urlDestroy.replace(':id', data.id);

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: urlDestroy,
                    beforeSend: function() {
                    },
                    success: function(data) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data berhasil dihapus!',
                    })
                    tableAkunPuskesmas.ajax.reload();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                    switch (xhr.status) {
                        case 500:
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Server Error!',
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
                }
            });
        });
    })
</script>
@endpush