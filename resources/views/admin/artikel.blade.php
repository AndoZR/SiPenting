@extends('admin.app')
@section('title', 'Artikel')
@section('sub-title', 'Dashboard / Artikel')

@push('css')
    {{-- datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('src/compiled/css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/app-dark.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('src/compiled/css/iconly.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('src/compiled/css/table-datatable.css') }}">
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
                <table class="table table-striped" id="table-artikel">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Video</th>
                            <th>Dibuat</th>
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

<!-- Modal Create Artikel -->
<div class="modal fade" id="modal-create-artikel" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Artikel</h5>
          <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
              <i data-feather="x"></i>
          </button>
        </div>
        <form id="form-create-artikel">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="judul">Judul <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="judul" id="judul" placeholder="Isi Judul" autofocus autocomplete="off">
                  <div class="invalid-feedback judul_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
                  <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Isi Deskripsi" autofocus autocomplete="off" rows="4"></textarea>
                  <div class="invalid-feedback deskripsi_error"></div>
                </div>                
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="gambar">Gambar <span class="text-danger">*</span></label>
                  <input type="file" class="form-control" name="gambar" id="gambar" placeholder="Isi gambar" autofocus autocomplete="off">
                  <div class="invalid-feedback gambar_error"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="video">Url Video (Opsional)</label>
                  <input type="text" class="form-control" name="video" id="video" placeholder="Isi Url" autofocus autocomplete="off">
                  <div class="invalid-feedback video_error"></div>
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

{{-- liat modal image --}}
<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-labelledby="modalBerkas" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body d-flex justify-content-center align-items-center">
              <!-- Image with size constraints -->
              <img id="modalImageContent" src="" alt="Image" style="max-width: 100%; max-height: 80vh; object-fit: contain;">
          </div>
          <div class="modal-footer">
              <button type="button" class="btn" data-bs-dismiss="modal">
                  <i class="bx bx-x d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Tutup</span>
              </button>
          </div>
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
        });

        var idArtikel;
        let url;
        let urlTest = '{{ route('artikel.viewArtikel') }}';

        let tableArtikel = $('#table-artikel').DataTable({
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
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: '<i class="fas fa-plus mr-2"></i> Tambah Data',
                            className: 'btn btn-primary mb-2 mt-2',
                            action: function(e, dt, node, config) {
                                $('#modal-create-artikel').modal('show');
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
                    data: 'judul',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 2,
                    data: 'deskripsi',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                  targets: 3,
                  data: 'gambar',
                  className: 'text-center align-middle',
                  render: function(data, type, row, meta) {
                        if (data) {
                            // Assuming 'data' contains the image filename
                            let imageUrl = `/storage/artikel/${data}`;
                            return `<a title="${data}" href="#" class="btn btn-info btn-artikel" data-artikel="${ data }">Lihat</a>`;
                        } else {
                            return 'No Image';  // Placeholder text if no image is available
                        }
                    }
                },
                {
                  targets: 4,
                  data: 'url_video',
                  className: 'text-center align-middle',
                  render: function(data, type, row, meta) {
                        if (data) {
                            return `<a title="${data}" href="${data}" target="_blank" class="btn btn-info">Lihat</a>`;
                        } else {
                            return 'No Video';  // Placeholder text if no image is available
                        }
                    }
                },
                {
                    targets: 5,
                    data: 'created_at',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        if (type === 'display' && data) {
                            // Create a new Date object from the ISO string
                            const date = new Date(data);
                            // Format the date as needed
                            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                            return date.toLocaleDateString('id-ID', options); // 'id-ID' for Indonesian locale, adjust as needed
                        }
                        return data;
                    }
                },
                {
                    targets: 6,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        $button = `<button class="btn btn-warning btn-edit" title="Ubah">Ubah</button>
                        <br><button class="btn btn-danger btn-delete" title="Hapus">Hapus</button><br>`;
                        return $button;
                    }
                },
            ],
        });


        // Event delegation to handle clicks on dynamically generated buttons
        $(document).on('click', '.btn-artikel', function(event) {
            event.preventDefault(); // Prevent default link behavior

            var fileName = $(this).data('artikel'); // Get the filename from data attribute
            var fileUrl = "{{ url('/storage/artikel') }}/" + fileName; // Create the file URL

            // Update modal content with the image
            $('#modal-image').find('.modal-title').text('Image Preview'); // Set modal title
            $('#modal-image').find('img#modalImageContent').attr('src', fileUrl); // Set image source
            $('#modal-image').modal('show'); // Show the modal
        });

        // Submit Form Create art
        $('#form-create-artikel').submit(function(e) {
            e.preventDefault();

            if(idArtikel !== undefined){
                url = "{{ route('artikel.updateArtikel', ['id' => ':id']) }}";
                url = url.replace(':id', idArtikel)
            }else{
                url = "{{ route('artikel.storeArtikel') }}";
            }

            var formData = new FormData($("#form-create-artikel")[0]);

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
                    $('#modal-create-artikel').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Tersimpan!',
                        text: response.meta.message,
                    });
                    tableArtikel.ajax.reload();
                    idArtikel = undefined;
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

        // Edit Data art
        $('#table-artikel tbody').on('click', '.btn-edit', function() {
            var data = tableArtikel.row($(this).parents('tr')).data();
            idArtikel = data.id;
            
            // set form action
            $('input[name="judul"]').val(data.judul);
            $('textarea[name="deskripsi"]').val(data.deskripsi);
            $('input[name="video"]').val(data.url_video);

            // show modal
            $('#modal-create-artikel').modal('show');
        });

        // Hapus Data test
        $('#table-artikel tbody').on('click', '.btn-delete', function() {
            var data = tableArtikel.row($(this).parents('tr')).data();
            let urlDestroy = "{{ route('artikel.deleteArtikel', ['id' => ':id']) }}"
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
                    tableArtikel.ajax.reload();
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