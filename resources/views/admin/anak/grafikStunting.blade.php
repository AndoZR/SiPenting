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
                <canvas id="lineChartRataStunting" width="1000" height="500"></canvas>
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
        // Ambil data dari PHP
        const labels = @json($labels);
        const rawDatasets = @json($datasets);
        const kategoriLabels = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];

        // Warna preset supaya beda tiap desa
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

        // Fungsi buat bikin warna transparan
        function transparentize(color, opacity = 0.3) {
            return color.replace('rgb', 'rgba').replace(')', `, ${opacity})`);
        }

        // Siapkan datasets untuk Chart.js
        const datasets = rawDatasets.map((ds, i) => {
            const color = COLORS[i % COLORS.length];
            return {
                label: ds.label,
                data: ds.data,
                borderColor: color,
                backgroundColor: transparentize(color),
                fill: false,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6,
                spanGaps: true, // agar garis putus-putus tidak muncul saat ada null
            };
        });

        const data = {
            labels: labels,
            datasets: datasets,
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Rata-rata Nilai Stunting per Desa per Tanggal'
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: false,
                        callbacks: {
                            title: function(context) {
                                return 'Tanggal: ' + context[0].label;
                            },
                            label: function(context) {
                                const desa = context.dataset.label;
                                const nilai = context.parsed.y;
                                const kategoriLabels = ['Sangat Pendek', 'Pendek', 'Normal', 'Tinggi'];
                                let kategoriText = '';
                                if (nilai >= 1 && nilai <= 4) {
                                    kategoriText = kategoriLabels[nilai - 1];
                                } else {
                                    kategoriText = '-';
                                }
                                return desa + ': ' + nilai + ' (' + kategoriText + ')';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                        },
                        title: {
                            display: true,
                            text: 'Nilai Stunting (1-4)',
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal',
                        }
                    }
                }
            }
        };

        const ctx = document.getElementById('lineChartRataStunting').getContext('2d');
        new Chart(ctx, config);
    });
</script>
@endpush