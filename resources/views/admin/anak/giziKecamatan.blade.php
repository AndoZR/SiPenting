@extends('admin.app')
@section('title', 'Data Gizi')
@section('sub-title', 'Dashboard / Data Gizi Kecamatan')

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
                <table class="table table-striped" id="table-kecamatan">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kecamatan</th>
                            <th>Grafik</th>
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
        let urlTest = '{{ route('anak.daftar-kecamatan-gizi') }}';

        let tableAnak = $('#table-kecamatan').DataTable({
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
                    data: 'name',
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    targets: 2,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        var id = data.id;
                        var url1 = `{{ route('anak.graph-gizi-anak', ['id' => ':id']) }}`;
                        url1 = url1.replace(':id', id);
                        $button = `<a href="${url1}" class="btn btn-success btn-edit" title="Grafik Gizi">Grafik</a>`;
                        return $button;
                    }
                },
                {
                    targets: 3,
                    data: null,
                    className: 'text-center align-middle',
                    render: function(data, type, row, meta) {
                        var id = data.id;
                        var url1 = `{{ route('anak.daftar-desa-gizi', ['id' => ':id']) }}`;
                        url1 = url1.replace(':id', id);
                        $button = `<a href="${url1}" class="btn btn-warning btn-edit" title="Lihat Desa">Desa</a>`;
                        return $button;
                    }
                },
            ],
        });
    })
</script>
@endpush