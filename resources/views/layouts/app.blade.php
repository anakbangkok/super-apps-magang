<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="assets/" data-template="vertical-menu-template-free">

    <head>
      <meta charset="utf-8" />
      <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
      <title>@yield('title', 'Pengguna')</title>
      <!-- Boxicons CSS -->
      <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
      <!-- Favicon -->
      <link rel="icon" type="image/x-icon"
          href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQklJ0c2uKkN9rFBBfFTqESoy2S6dBxAsZIpQ&s" />
  
  
      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com" />
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
      <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
          rel="stylesheet" />
          <!-- Tambahkan link Google Fonts di sini -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        .app-brand-text {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
            color: #333;
            text-transform: capitalize; /* Membuat huruf pertama menjadi kapital */
        }
    </style>
  
      <!-- Icons -->
      <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
  
      <!-- Core CSS -->
      <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
      <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
          class="template-customizer-theme-css" />
      <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  
      <!-- Vendors CSS -->
      <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
      <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
  
      <!-- DataTables CSS -->
      <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
  
      <!-- Select2 CSS -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  
      <!-- Helpers -->
      <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
      <script src="{{ asset('assets/js/config.js') }}"></script>
  </head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('assets/img/logo/logo-rumah-mesin.png') }}" alt="App Logo"
                                style="width: 50px; height: auto;" />
                        </span>
                        </head>
                        <body>
                            <!-- Tulis teks dalam huruf kecil untuk melihat efeknya -->
                            <span class="app-brand-text demo menu-text fw-bolder ms-2">super apps</span>
                        </body>
                        </html>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Halaman Utama</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Pages</span></li>
                    <!-- Kehadiran (Attendance) -->
                    <li class="menu-item {{ request()->routeIs('kehadirans.index') ? 'active' : '' }}">
                        <a href="{{ route('kehadirans.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-task"></i>
                            <div data-i18n="Tables">Absensi</div>
                        </a>
                    </li>

                    <!-- Profile Settings -->
                    <li class="menu-item {{ request()->is('profile') ? 'active' : '' }}">
                        <a href="{{ route('profile.update') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Tables">Pengaturan Profil</div>
                        </a>
                    </li>

                     <!-- Aktivitas Harian -->
                    <li class="menu-item {{ request()->routeIs('journals.index')|| request()->routeIs('journals.create') ? 'active' : '' }}">
                      <a href="{{ route('journals.index') }}" class="menu-link">
                          <i class="menu-icon tf-icons bx bx-notepad"></i>
                          <div>Aktivitas Harian</div>
                      </a>
                  </li>
                  
                    <!-- Pengajuan izin -->
                    <li class="menu-item {{ request()->is('pengajuan-izin') ? 'active' : '' }}">
                        <a href="pengajuan-izin" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-calendar"></i>
                            <div>Pengajuan Izin</div>
                        </a>
                    </li>

                    <!-- Feedback -->
                    <li class="menu-item {{ request()->is('feedback') ? 'active' : '' }}">
                        <a href="{{ route('feedback.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-message-square-dots"></i>
                            <div>Masukan</div>
                        </a>
                    </li>



                    {{-- <!-- Misc -->
              <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>
              <li class="menu-item">
                  <a href="https://github.com/anakbangkok/super-apps-magang/issues" target="_blank" class="menu-link">
                      <i class="menu-icon tf-icons bx bx-support"></i>
                      <div data-i18n="Support">Dukungan</div>
                  </a>
              </li> --}}
                </ul>
                <!-- Misc -->
                <div class="logout-container py-2">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="menu-link d-flex align-items-center">
                        <i class="menu-icon tf-icons bx bx-power-off"></i>
                        <div data-i18n="Logout" class="ms-2">Keluar</div>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </aside>



            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Cari -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Cari..."
                                    aria-label="Cari..." />
                            </div>
                        </div>
                        <!-- /Cari -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset(auth()->user()->profile_photo ? 'storage/' . auth()->user()->profile_photo : 'assets/img/avatars/default.jpg') }}"
                                            alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset(auth()->user()->profile_photo ? 'storage/' . auth()->user()->profile_photo : 'assets/img/avatars/default.jpg') }}"
                                                            alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>

                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-semibold d-block">{{ auth()->user()->name }}</span>
                                                    <small class="text-muted">
                                                        {{ auth()->user()->is_admin ? 'Admin' : 'User' }}
                                                        <!-- Dynamic Role -->
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <!-- <li>
                      <a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li> -->
                                    <!-- <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li> -->
                                    <a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bx bx-power-off me-2"></i>
                                        <span class="align-middle">Keluar</span>
                                    </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>

                        </ul>
                        </li>
                        <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content -->
                <main class="py-4">
                    @yield('content')
                </main>
                <!-- / Content -->


                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/custom-script.js') }}"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <style>
        .menu-item.active .menu-link {
            background-color: #f10c0c;
            /* Change this to your desired color */
            color: #ffffff;
            /* Text color */
        }

        .menu-item.active .menu-icon {
            color: #F4473A;
            /* Change icon color when active */
        }

        .logout-container {
            border-top: 1px solid #ddd;
            /* Optional: add a top border for separation */
            padding: 1rem;
            /* Add padding for spacing */
            margin-top: auto;
            /* Push the logout button to the bottom */
        }

        .logout-container .menu-link {
            color: #f00;
            /* Change the color of the logout link */
        }

        .logout-container .menu-icon {
            color: #f00;
            /* Change icon color */
        }
    </style>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2').select2({
                placeholder: "Pilih Penugasan", // Placeholder jika ingin ditambahkan
                allowClear: true // Memungkinkan user untuk menghapus pilihan
            });
        });
    </script>

   

</body>

</html>
