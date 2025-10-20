@extends('admin.app')
@section('title', 'Grafik Stunting')
@section('sub-title', 'Dashboard / Grafik Stunting')

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
                <canvas id="barChartRataStunting" width="1000" height="500"></canvas>
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

<script>
$(document).ready(function () {
    const labels = @json($labels);
    const rawDatasets = @json($datasets);
    const kategoriLabels = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

    const COLORS = [
        'rgb(255, 99, 132)',   // merah
        'rgb(54, 162, 235)',   // biru
        'rgb(75, 192, 192)',   // hijau
        'rgb(153, 102, 255)',  // ungu
        'rgb(255, 159, 64)',   // oranye
        'rgb(54, 162, 135)',   // teal
        'rgb(0, 255, 255)',    // cyan
        'rgb(255, 0, 255)',    // magenta
        'rgb(165, 42, 42)',    // coklat
        'rgb(201, 203, 207)'   // abu-abu
    ];

    function transparentize(color, opacity = 0.6) {
        return color.replace('rgb', 'rgba').replace(')', `, ${opacity})`);
    }

    const datasets = rawDatasets.map((ds, i) => {
        const color = COLORS[i % COLORS.length];
        return {
            label: ds.label,
            data: ds.data,
            backgroundColor: transparentize(color, 0.6),
            borderColor: color,
            borderWidth: 1.5,
            borderRadius: 6,
            barPercentage: 0.7,
        };
    });

    const data = { labels, datasets };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Rata-rata Nilai Stunting per Desa per Tanggal',
                    font: { size: 16, weight: 'bold' }
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            return 'Tanggal: ' + context[0].label;
                        },
                        label: function(context) {
                            const desa = context.dataset.label;
                            const nilai = context.raw;
                            const kategori = kategoriLabels[Math.round(nilai) - 1] ?? '-';
                            return `${desa}: ${nilai.toFixed(2)} (${kategori})`;
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
                            const map = {1: 'Sangat Pendek', 2: 'Pendek', 3: 'Normal', 4: 'Tinggi'};
                            return map[value] ?? '';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Status Stunting'
                    },
                    grid: { color: '#ddd' }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    },
                    grid: { display: false },
                    ticks: { autoSkip: false }
                }
            }
        }
    };

    const ctx = document.getElementById('barChartRataStunting').getContext('2d');
    new Chart(ctx, config);
});
</script>
@endpush