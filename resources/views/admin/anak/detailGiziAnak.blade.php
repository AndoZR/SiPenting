@extends('admin.app')
@section('title', 'Grafik Gizi Anak')
@section('sub-title', 'Dashboard / Grafik Gizi Anak')

@push('css')

@endpush

@section('content')
<main>
    <div class="container-fluid px-4">
        {{-- Radar Chart --}}
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Gizi
            </div>
            <div class="card-body">
                {{-- Radar Chart Canvas --}}
                <canvas id="giziChart" width="1000" height="500"></canvas>
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
    const labels = {!! json_encode($labels) !!}; // tanggal
    const dataSeries = {!! json_encode($series) !!}; // array nilai_gizi
    const statusLabel = ['Kurang', 'Normal', 'Kelebihan'];

    const kategori = ['Makanan Pokok', 'Lauk Pauk', 'Sayur-sayuran', 'Buah-buahan', 'Minuman'];
    const warna = ['#FF6384', '#36A2EB', '#4BC0C0', '#FF9F40', '#9966FF'];

    const datasets = [];

    for (let i = 0; i < kategori.length; i++) {
        const dataset = {
            label: kategori[i],
            data: dataSeries.map(d => d[i]), // ambil nilai ke-i dari tiap tanggal
            borderColor: warna[i],
            backgroundColor: warna[i],
            fill: false,
            tension: 0.3
        };
        datasets.push(dataset);
    }

    const config = {
        type: 'line',
        data: {
            labels: labels, // Tanggal
            datasets: datasets // Tiap komponen makanan sebagai satu garis
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Perkembangan Gizi Anak'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const statusIndex = context.raw - 1;
                            const status = statusLabel[statusIndex] ?? '-';
                            return `${context.dataset.label}: ${status}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 4,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return statusLabel[value - 1];
                        }
                    },
                    title: {
                        display: true,
                        text: 'Status Gizi'
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
        document.getElementById('giziChart'),
        config
    );
</script>

@endpush