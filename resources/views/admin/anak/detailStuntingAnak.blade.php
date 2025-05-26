@extends('admin.app')
@section('title', 'Grafik Stunting Anak')
@section('sub-title', 'Dashboard / Grafik Stunting Anak')

@push('css')

@endpush

@section('content')
<main>
    <div class="container-fluid px-4">
        {{-- Radar Chart --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Stunting
            </div>
            <div class="card-body">
                {{-- Radar Chart Canvas --}}
                <canvas id="stuntingChart" width="1000" height="500"></canvas>
            </div>
            <div class="card-footer small text-muted">Terakhir diperbarui: kemarin pukul 23:59</div>
        </div>

        {{-- Tombol Kembali --}}
        <div class="mb-4">
            <button onclick="window.history.back()" class="btn btn-primary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </button>
        </div>
    </div>
</main>
@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    const labels = {!! json_encode($labels) !!}; // array tanggal
    const dataSeries = {!! json_encode($series) !!}; // array angka (1-4)

    const kategori = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

    const config = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tingkat Stunting',
                data: dataSeries.map(val => parseInt(val)), // ubah string jadi int
                borderColor: '#36A2EB',
                backgroundColor: '#36A2EB',
                fill: false,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Perkembangan Stunting Anak'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const status = kategori[context.raw - 1] ?? '-';
                            return `Stunting: ${status}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 1,
                    max: 4,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return kategori[value - 1] ?? '-';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Status Stunting'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal Input'
                    }
                }
            }
        }
    };

    new Chart(
        document.getElementById('stuntingChart'),
        config
    );
</script>


@endpush