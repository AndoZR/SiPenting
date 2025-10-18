@extends('admin.app')
@section('title', 'Home')
@section('sub-title', 'Home')

{{-- @push('css')
<style>
    .center-container {
    position: relative;
    height: 80vh; /* Mengatur tinggi container sesuai tinggi viewport */
}
.center-container img {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%); /* Menyelaraskan gambar di tengah-tengah */
    max-width: 100%; /* Menyesuaikan gambar dengan lebar container */
    height: auto; /* Menjaga rasio aspek gambar */
}

.center-container h1 {
    position: absolute;
    top: 10%; /* Jarak dari atas halaman */
    left: 50%;
    transform: translateX(-50%); /* Menyelaraskan teks di tengah secara horizontal */
    margin: 0;
}
</style>
@endpush --}}

@section('content')
<div class="center-container">
    <h1>Selamat Datang di Dashboard!</h1>
    {{-- <img style="width: 300px; height: auto;" src="{{ asset('src/img/dashboard.svg') }}" alt=""> --}}
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Bar Chart
            </div>
            <div class="card-body"><canvas id="myPenggunaChart" width="100%" height="50"></canvas></div>
            <div class="card-footer small text-muted">Most Updated Data</div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Bar Chart
            </div>
            <div class="card-body"><canvas id="myNikChart" width="100%" height="50"></canvas></div>
            <div class="card-footer small text-muted">Most Updated Data</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    // Set default font & color (Bootstrap style)
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Ambil nilai jumlah pengguna dari Blade
    var jumlahPengguna = {{ $jumlahPengguna }};

    // Bar Chart Example
    var ctx = document.getElementById("myPenggunaChart");
    var myPenggunaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["Jumlah Pengguna"],
            datasets: [{
                label: "Total User",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: [jumlahPengguna],
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    gridLines: { display: false },
                    ticks: { maxTicksLimit: 1 }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        // otomatis menyesuaikan batas atas dari jumlah pengguna
                        max: Math.ceil(jumlahPengguna * 1.2),
                        maxTicksLimit: 5
                    },
                    gridLines: { display: true }
                }],
            },
            legend: { display: false }
        }
    });

var ctx = document.getElementById("myNikChart");
var myNikChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Warga Bondowoso", "Non-Warga"],
        datasets: [{
            label: "Jumlah User",
            backgroundColor: ["#007bff", "#28a745"],
            data: [{{ $jumlahPenggunaNik3511 }}, {{ $jumlahPenggunaNon3511 }}],
        }],
    },
});

</script>
@endpush