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

        // Warna yang bisa dipakai, sesuaikan sendiri jika mau
        const CHART_COLORS = [
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

        // Fungsi buat warna background dengan alpha
        function transparentize(color, opacity = 0.5) {
            // expects color like 'rgb(r, g, b)'
            return color.replace('rgb', 'rgba').replace(')', `, ${opacity})`);
        }

        // Build datasets dengan warna
        const datasets = @json($datasets).map((ds, i) => {
            const color = CHART_COLORS[i % CHART_COLORS.length];
            return {
                label: ds.label,
                data: ds.data,
                borderColor: color,
                backgroundColor: transparentize(color, 0.2),
                pointBackgroundColor: color,
                fill: true
            };
        });

        const data = {
            labels: labels,
            datasets: datasets
        };

        const config = {
            type: 'radar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Radar Gizi Anak per Desa'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const datasetLabel = context.dataset.label || '';
                                const labelName = context.label || context.chart.data.labels[context.dataIndex];
                                const value = context.raw !== undefined ? context.raw : context.parsed;
                                return `${labelName} (${datasetLabel}): ${value}`;
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        angleLines: { display: true },
                        suggestedMin: 0,
                        suggestedMax: 3
                    }
                }
            }
        };

        const ctx = document.getElementById('giziRadarChart').getContext('2d');
        new Chart(ctx, config);
    });
</script>
@endpush