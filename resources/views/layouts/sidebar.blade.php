<aside class="main-sidebar sidebar-dark-primary elevation-4 position-fixed">
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">UPA_TIK</span>
    </a>

    <div class="sidebar">
        <div class="form-inline mt-2">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }} ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header">Data Pengguna</li>
                <li class="nav-item">
                    <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }} ">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Level User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>Data User</p>
                    </a>
                </li>
                <li class="nav-header">Pengaturan Sistem</li>

                <!-- Manajemen Menu -->
                <li class="nav-item">
                    <a href="{{ url('/indukMenu') }}" class="nav-link {{ $activeMenu == 'induk_menu' ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <p>Menu Utama</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/menu') }}" class="nav-link {{ $activeMenu == 'menu' ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <p>Manajamen Menu</p>
                    </a>
                </li>

                <li class="nav-header">Tampilan Daftar Menu</li>

                <li class="nav-item">
                    <a href="{{ url('/daftarMenu') }}" class="nav-link {{ $activeMenu == 'daftar_menu' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bars"></i>
                        <p>Menu</p>
                    </a>
                </li>
                
            </ul>
        </nav>
    </div>
</aside>
