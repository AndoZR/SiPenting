@extends('admin.app')
@section('title', 'Grafik Berat Badan')
@section('sub-title', 'Dashboard / Grafik BB')

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
                <canvas id="giziRadarChart" width="800" height="800"></canvas>
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
$(document).ready(function () {
    const labels = @json($labels);

    const CHART_COLORS = [
        'rgba(255, 99, 132, 0.3)',   // merah pastel
        'rgba(54, 162, 235, 0.3)',   // biru pastel
        'rgba(75, 192, 192, 0.3)',   // hijau pastel
        'rgba(255, 205, 86, 0.3)',   // kuning pastel
        'rgba(153, 102, 255, 0.3)',  // ungu pastel
    ];

    const datasets = @json($datasets).map((ds, i) => ({
        label: ds.label,
        data: ds.data,
        backgroundColor: CHART_COLORS[i % CHART_COLORS.length],
        borderColor: CHART_COLORS[i % CHART_COLORS.length].replace('0.3', '1'),
        borderWidth: 1,
        borderRadius: 8,
        barPercentage: 0.7,
        categoryPercentage: 0.5,
    }));

    const config = {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: { top: 20, bottom: 20 }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Rata-rata Gizi Anak per Desa',
                    font: { size: 16, weight: 'bold' }
                },
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.7)',
                    callbacks: {
                        label: function (context) {
                            const value = context.raw?.toFixed(2);
                            let kategori = '';
                            if (value < 1.5) kategori = 'Kurang';
                            else if (value < 2.5) kategori = 'Normal';
                            else kategori = 'Berlebihan';
                            return `${context.dataset.label}: ${value} (${kategori})`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Kategori Gizi'
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: { size: 12 }
                    }
                },
                y: {
                    min: 1,
                    max: 3,
                    ticks: {
                        stepSize: 1,
                        callback: function (value) {
                            const map = { 1: 'Kurang', 2: 'Normal', 3: 'Berlebihan' };
                            return map[value] ?? '';
                        },
                        font: { size: 12 }
                    },
                    title: {
                        display: true,
                        text: 'Status Gizi'
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)',
                        borderDash: [5, 5],
                        drawBorder: false, // biar bar gak nempel di border
                    }
                }
            }
        }
    };

    const ctx = document.getElementById('giziRadarChart').getContext('2d');
    new Chart(ctx, config);
});


</script>
@endpush