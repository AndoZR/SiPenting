@extends('admin.app')
@section('title', 'Home')
@section('sub-title', 'Home')

@push('css')
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
@endpush

@section('content')
<div class="center-container">
    <h1>Selamat Datang di Dashboard!</h1>
    <img style="width: 300px; height: auto;" src="{{ asset('src/img/dashboard.svg') }}" alt="">
</div>
@endsection

@push('scripts')
@endpush