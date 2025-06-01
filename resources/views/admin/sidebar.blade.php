<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Menu</div>
                <a class="nav-link" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="{{ route('artikel.viewArtikel') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Artikel
                </a>
                @if (auth('bapeda')->check())
                <a class="nav-link" href="{{ route('bapeda.viewAkunPuskesmas') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Akun Puskesmas
                </a>
                @endif
                <div class="sb-sidenav-menu-heading">Graph Data</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseIbu" aria-expanded="false" aria-controls="collapseIbu">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Data Ibu Hamil
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseIbu" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('ibu-hamil.ibu-hamil-daftar') }}">Daftar Ibu</a>
                    </nav>
                </div>
                
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAnak" aria-expanded="false" aria-controls="collapseAnak">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Data Anak
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseAnak" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('anak.daftar-anak') }}">Daftar Anak</a>
                    </nav>
                </div>
                <div class="collapse" id="collapseAnak" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('anak.daftar-kecamatan-gizi') }}">Gizi Kecamatan</a>
                    </nav>
                </div>
                <div class="collapse" id="collapseAnak" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="{{ route('anak.daftar-kecamatan-stunting') }}">Stunting Kecamatan</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Masuk Sebagai:</div>
            {{ Auth::guard()->name }}
        </div>
    </nav>
</div>