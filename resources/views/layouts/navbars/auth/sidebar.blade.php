<aside class="sidenav navbar navbar-vertical navbar-expand-xs ps bg-white fixed-start transition-mobile-sidebar" id="sidenav-main"
    style="left: 0px; height: 100vh; overflow-y: none;">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="align-items-center d-flex m-0 navbar-brand text-wrap" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/lifemedia_logo.png') }}" class="navbar-brand-img h-100" alt="...">
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="" id="sidenav-collapse-main" style="overflow: none;">
        <ul class="navbar-nav" style="overflow: none;">

            <li class="nav-item pb-2">
                <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center {{ Route::currentRouteName() == 'dashboard' ? 'bg-gradient-primary text-white' : '' }}">
                        <!-- Menambahkan ikon home Font Awesome -->
                        <i class="fas fa-home {{ Route::currentRouteName() == 'dashboard' ? 'text-white' : 'text-dark' }}"
                            style="font-size: 0.7rem;"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Users</h6>
            </li>
            <li class="nav-item pb-2">
                <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}"
                    href="{{ route('user-management') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center {{ Route::currentRouteName() == 'user-management' ? 'bg-gradient-primary text-white' : '' }}">
                        <!-- Menambahkan ikon users Font Awesome -->
                        <i class="fas fa-user {{ Route::currentRouteName() == 'user-management' ? 'text-white' : 'text-dark' }}"
                            style="font-size: 0.7rem;"></i>
                    </div>
                    <span class="nav-link-text ms-1">User Management</span>
                </a>
            </li>


            <li class="nav-item mt-2">
                <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Data</h6>
            </li>

            <!-- CCTV Panorama -->
            <li class="nav-item pb-2">
                <a class="nav-link {{ Route::currentRouteName() == 'menu-panorama' ? 'active' : '' }}"
                    href="{{ route('menu-panorama') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center {{ Route::currentRouteName() == 'menu-panorama' ? 'bg-gradient-primary text-white' : '' }}">
                        <!-- CCTV Panorama Icon (Gunung) -->
                        <i class="fas fa-earth-americas {{ Route::currentRouteName() == 'menu-panorama' ? 'text-white' : 'text-dark' }}"
                            style="font-size: 0.7rem;"></i>
                    </div>
                    <span class="nav-link-text ms-1">CCTV Panorama</span>
                </a>
            </li>

            <!-- CCTV Sekolah -->
            <li class="nav-item pb-2">
                <a class="nav-link {{ Route::currentRouteName() == 'menu-sekolah' ? 'active' : '' }}"
                    href="{{ route('menu-sekolah') }}">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center {{ Route::currentRouteName() == 'menu-sekolah' ? 'bg-gradient-primary text-white' : '' }}">
                        <!-- CCTV Sekolah Icon (Sekolah) -->
                        <i class="fas fa-book-open {{ Route::currentRouteName() == 'menu-sekolah' ? 'text-white' : 'text-dark' }}"
                            style="font-size: 0.7rem;"></i>
                    </div>
                    <span class="nav-link-text ms-1">CCTV Sekolah</span>
                </a>
            </li>


            <li class="nav-item pb-2 mt-3">
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
                <button type="button"
                    class="nav-link border-0 bg-transparent d-flex align-items-center {{ Route::currentRouteName() == 'logout' ? 'active' : '' }}"
                    id="logoutButton">
                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-danger"></i>
                    </div>
                    <span class="nav-link-text ms-1 text-danger">Logout</span>
                </button>
            </li>

            <script>
                document.getElementById('logoutButton').addEventListener('click', function () {
                    Swal.fire({
                        title: 'Apakah Anda Yakin?',
                        text: "Anda akan Logout dari sini.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Saya Yakin!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('logout-form').submit(); // Submit the form if confirmed
                        }
                    });
                });
            </script>
            

        </ul>
    </div>
</aside>