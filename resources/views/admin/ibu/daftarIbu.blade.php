@extends('admin.app')
@section('title', 'Daftar Ibu')
@section('sub-title', 'Dashboard / Daftar Ibu')

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
                <table class="table table-striped" id="table-bb">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>Tanggal Lahir</th>
                            <th>BB Pra</th>
                            <th>Tinggi</th>
                            <th>Desa</th>
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

@endsection

@push('scripts')
<script src="{{ asset('src/admin/js/datatables-simple-demo.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>

<script>
    $(document).ready(function () {
        let urlTest = '{{ route('ibu-hamil.ibu-hamil-daftar') }}';

        let tableArtikel = $('#table-bb').DataTable({
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
                    data: 'namaIbu',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 2,
                    data: 'tanggalLahir',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                  targets: 3,
                  data: 'bbPraHamil',
                  className: 'text-center align-middle',
                  render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                  targets: 4,
                  data: 'tinggiBadan',
                  className: 'text-center align-middle',
                  render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 5,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data.village.name;
                    }
                },
                {
                    targets: 6,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        var id = data.id;
                        var url = `{{ route('ibu-hamil.ibu-hamil-graph', ['id' => ':id']) }}`;
                        url = url.replace(':id', id);
                        $button = `<a href="${url}" class="btn btn-warning btn-edit" title="BB Saat Ini">Lihat BB</a>`;
                        return $button;
                    }
                },
            ],
        });
    })
</script>
@endpush