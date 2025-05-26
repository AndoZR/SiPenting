@extends('admin.app')
@section('title', 'Grafik Berat Badan')
@section('sub-title', 'Dashboard / Grafik Berat Badan')

@push('css')

@endpush

@section('content')
<main>
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Berat Badan Saat Ini
            </div>
            <div class="card-body"><canvas id="myAreaChart2" width="100%" height="30"></canvas></div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>
    </div>
    <div class="mb-4">
        <button onclick="window.history.back()" class="btn btn-primary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </button>
    </div>
</main>

@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        // Area Chart Example
        var data = @json($data);
        
        // Mengakses atribut bbNow dari setiap item dalam array berat_badan
        var bbNowArray = data.berat_badan.map(function(item) {
            return item.bbNow;
        });

        // Mendapatkan nilai tertinggi dari array bbNow
        var maxBbNow = Math.max.apply(null, bbNowArray);


        // Fungsi untuk mendapatkan nama bulan dari tanggal
        function getMonthName(dateString) {
            var date = new Date(dateString);
            return date.toLocaleString('id-ID', { month: 'long', year: 'numeric'}); // 'id-ID' untuk Bahasa Indonesia, 'long' untuk nama bulan lengkap
        }

        // Mengambil semua tanggal dan mengonversinya menjadi nama bulan
        var monthsArray = data.berat_badan.map(function(item) {
            return getMonthName(item.created_at);
        });



        // SETTING GRAPH INPUT ALL DATA IN HERE
        var ctx = document.getElementById("myAreaChart2");
        var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthsArray,
            datasets: [{
                label: "Berat Badan",
                lineTension: 0.3,
                backgroundColor: "rgba(2,117,216,0.2)",
                borderColor: "rgba(2,117,216,1)",
                pointRadius: 5,
                pointBackgroundColor: "rgba(2,117,216,1)",
                pointBorderColor: "rgba(255,255,255,0.8)",
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(2,117,216,1)",
                pointHitRadius: 50,
                pointBorderWidth: 2,
                data: bbNowArray,
            }],
        },
        options: {
            scales: {
            xAxes: [{
                time: {
                unit: 'date'
                },
                gridLines: {
                display: false
                },
                ticks: {
                maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                min: 0,
                max: maxBbNow,
                maxTicksLimit: 5
                },
                gridLines: {
                color: "rgba(0, 0, 0, .125)",
                }
            }],
            },
            legend: {
            display: false
            }
        }
        });
    })
</script>
@endpush