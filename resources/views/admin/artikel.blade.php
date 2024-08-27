@extends('admin.app')
@section('title', 'Artikel')
@section('sub-title', 'Artikel')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive ">
                <table id="table-artikel" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Dibuat</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

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
          </div>
          <div class="modal-footer">
              <button type="button" class="btn" data-bs-dismiss="modal">
                  <i class="bx bx-x d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Batal</span>
              </button>
              <button type="submit" class="button ms-1">
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
<script>
    $(document).ready(function () {
        $('.modal').on('hidden.bs.modal', function(e) {
            $('form').trigger('reset');
            $('*').removeClass('is-invalid');
            $('.custom-file-label').html('Pilih file...');
        });

        var idArtikel;
        let url;
        let urlTest = '{{ route('artikel.index') }}';

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
                            className: 'button light btn-tambah mb-3',
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
                            return `<a title="${data}" href="#" class="button btn-artikel" data-artikel="${ data }">Lihat</a>`;
                        } else {
                            return 'No Image';  // Placeholder text if no image is available
                        }
                    }
                },
                {
                    targets: 4,
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
                    targets: 5,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        url = "";
                        url = url.replace(':id', row.id);
                        $button = `<button class="button btn-sm btn-edit" title="Ubah">Ubah</button>
                        <br><button class="button btn-sm btn-delete" title="Hapus">Hapus</button><br>`;

                        return $button;
                    }
                },
            ],
        });


        // Event delegation to handle clicks on dynamically generated buttons
        $(document).on('click', '.btn-artikel', function(event) {
            event.preventDefault(); // Prevent default link behavior

            var fileName = $(this).data('artikel'); // Get the filename from data attribute
            var fileUrl = "{{ asset('storage/artikel') }}/" + fileName; // Create the file URL

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