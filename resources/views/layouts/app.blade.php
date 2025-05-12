<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Honey QR</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pagination.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-dropdown.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-fix.css') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chewy&display=swap');
        
        /* Tailwind directives */
        @tailwind base;
        @tailwind components;
        @tailwind utilities;
        
        :root {
            --sidebar-width: 280px;
            --primary-color: #F2A93B;
            --primary-hover: #E09422;
            --secondary-color: #FFC56E;
            --accent-color: #FFE0A3;
            --background-color: #FFF8E8;
            --card-bg: #FFFFFF;
            --sidebar-bg: #FFF8E8;
            --navbar-bg: #F2A93B;
            --sidebar-header-bg: #F2A93B;
            --honey-color: #F2A93B;
            --honey-dark: #E09422;
            --honey-light: #FFC56E;
            --honey-lighter: #FFE0A3;
            --honey-lightest: #FFF8E8;
            --text-color: #4A3500;
            --text-dark: #332400;
            --heading-color: #4A3500;
            --sidebar-text: #4A3500;
            --navbar-text: #332400;
            --active-nav-text: #E09422;
            --border-color: #FFC56E;
            --link-color: #F2A93B;
            --link-hover: #E09422;
            --table-header-bg: #F2A93B;
            --table-bg: #FFF8E8;
            --table-hover: #FFE0A3;
            --table-stripe: #FFF3D6;
        }

        [data-bs-theme="dark"] {
            --primary-color: #7B68EE;
            --primary-hover: #5D4FD1;
            --secondary-color: #FFD166;
            --accent-color: #FF6B81;
            --background-color: #0F1123;
            --card-bg: #1A1B36;
            --sidebar-bg: #1A1B36;
            --navbar-bg: #252749;
            --sidebar-header-bg: #252749;
            --honey-color: #7B68EE;
            --honey-dark: #5D4FD1;
            --honey-light: #9D8DF1;
            --honey-lighter: #BFB3F5;
            --honey-lightest: #E0DBF9;
            --text-color: #E0E0E0;
            --text-dark: #FFFFFF;
            --heading-color: #FFFFFF;
            --sidebar-text: #E0E0E0;
            --navbar-text: #FFFFFF;
            --active-nav-text: #7B68EE;
            --border-color: #383854;
            --link-color: #7B68EE;
            --link-hover: #5D4FD1;
            --table-header-bg: #252749;
            --table-bg: #1A1B36;
            --table-hover: #252749;
            --table-stripe: #202143;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Chewy', cursive;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-size: 1rem;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Chewy', cursive;
            font-weight: 400;
        }
        
        .btn, .form-control, .nav-link, .dropdown-item, p, span, div, td, th {
            font-family: 'Chewy', cursive;
            font-size: 0.95rem;
        }
        
        /* Mengecualikan judul aplikasi pada sidebar */
        .sidebar-brand span {
            font-family: 'Chewy', cursive;
            font-size: 1.6rem;
            font-weight: 400;
            color: #000000;
        }

        .card {
            background-color: var(--card-bg);
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            border-top-left-radius: 0.5rem !important;
            border-top-right-radius: 0.5rem !important;
            font-weight: 500;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .navbar {
            padding: 0.5rem 1.5rem;
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            width: 100%;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--honey-dark) !important;
        }
        
        .brand-text {
            font-family: 'Chewy', cursive;
        }
        
        .btn {
            border-radius: 0.375rem;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-dark);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            color: var(--text-color);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(186, 255, 57, 0.05);
        }
        
        .form-control, .form-select {
            border-radius: 0.375rem;
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-color);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(186, 255, 57, 0.25);
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        /* Sidebar Styles - Statis tanpa toggler */
        #sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        #sidebar.collapsed {
            width: 70px;
        }
        
        #sidebar.collapsed .nav-link span,
        #sidebar.collapsed .sidebar-brand span,
        #sidebar.collapsed .sidebar-brand img,
        #sidebar.collapsed .dropdown-toggle::after,
        #sidebar.collapsed .badge,
        #sidebar.collapsed .dropdown-menu {
            display: none !important;
        }
        
        #sidebar.collapsed .nav-link {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 70px;
            height: 50px;
            padding: 0;
            margin-bottom: 5px;
            text-align: center;
        }
        
        #sidebar.collapsed .nav-link i {
            font-size: 1.25rem;
            margin: 0;
            display: block;
            color: var(--sidebar-text);
        }
        
        #sidebar.collapsed .nav-link i {
            font-size: 1.25rem;
            margin: 0;
            width: 100%;
        }
        
        #sidebar.collapsed .sidebar-nav {
            padding: 0;
        }
        
        #sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }
        
        #sidebar.collapsed .sidebar-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            margin: 10px auto;
            background-color: var(--honey-color);
            border-radius: 6px;
            z-index: 1000;
            position: relative;
        }
        
        #sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        #sidebar.collapsed .sidebar-brand span,
        #sidebar.collapsed .nav-link span,
        #sidebar.collapsed .sidebar-toggle,
        #sidebar.collapsed .sidebar-header::after,
        #sidebar.collapsed .nav-link::after,
        #sidebar.collapsed .nav-link::before {
            display: none !important;
            width: 0;
            visibility: hidden;
            opacity: 0;
        }
        
        #sidebar.collapsed .sidebar-header {
            padding: 1rem 0;
            justify-content: center;
        }
        
        #sidebar.collapsed .sidebar-brand {
            justify-content: center;
        }

        #sidebar.collapsed .sidebar-nav .nav-link {
            text-align: center;
            padding: 12px 5px;
            display: flex;
            justify-content: center;
        }

        #sidebar.collapsed .sidebar-nav .nav-link i {
            margin: 0;
        #content {
            margin-left: var(--sidebar-width);
            transition: all 0.3s;
            min-height: 100vh;
        }

        /* Hamburger Button Animation */
        .hamburger {
            cursor: pointer;
            width: 30px;
            height: 20px;
            position: relative;
            margin: auto;
        }

        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background-color: var(--navbar-text);
            margin: 4px 0;
            transition: all 0.3s ease;
            position: relative;
            left: 0;
            transform: rotate(0deg);
            transition: .25s ease-in-out;
        }

        .hamburger span:nth-child(1) {
            top: 0px;
            background-color: var(--navbar-text);
        }

        .hamburger span:nth-child(2),
        .hamburger span:nth-child(3) {
            top: 9px;
        }

        .hamburger span:nth-child(4) {
            top: 18px;
        }

        .hamburger.active span:nth-child(1) {
            top: 9px;
            width: 0%;
            left: 50%;
        }

        .hamburger.active span:nth-child(2) {
            transform: rotate(45deg);
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg);
        }

        .hamburger.active span:nth-child(4) {
            top: 9px;
            width: 0%;
            left: 50%;
        }

        /* Sidebar styling untuk format baru */
        :root {
            --sidebar-bg: #FFFFFF;
            --sidebar-foreground: #4A3500;
            --sidebar-border: #e2e8f0;
            --sidebar-accent: #FFE0A3; 
            --sidebar-accent-foreground: #4A3500;
            --sidebar-muted-foreground: #6b7280;
        }
        
        .dark {
            --sidebar-bg: #1F2937;
            --sidebar-foreground: #F9FAFB;
            --sidebar-border: #374151;
            --sidebar-accent: #7B68EE; 
            --sidebar-accent-foreground: #F9FAFB;
            --sidebar-muted-foreground: #9CA3AF;
        }
        
        .bg-sidebar {
            background-color: var(--sidebar-bg);
        }
        
        .text-sidebar-foreground {
            color: var(--sidebar-foreground);
        }
        
        .text-sidebar-muted-foreground {
            color: var(--sidebar-muted-foreground);
        }
        
        .bg-sidebar-accent {
            background-color: var(--sidebar-accent);
        }
        
        .hover\:bg-sidebar-accent\/50:hover {
            background-color: color-mix(in srgb, var(--sidebar-accent) 50%, transparent);
        }
        
        .text-sidebar-accent-foreground {
            color: var(--sidebar-accent-foreground);
        }
        
        .border-sidebar-border {
            border-color: var(--sidebar-border);
        }
        
        .navbar {
            background: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.25rem;
            height: 60px;
        }

        .navbar .navbar-toggler {
            padding: 0.5rem;
            border: none;
            background-color: var(--primary-color);
            color: var(--text-dark);
        }

        /* Dark Mode Toggle */
        #darkModeToggle {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        #darkModeToggle:hover {
            background-color: var(--primary-color);
            color: var(--text-dark);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            #sidebar {
                /* Sidebar tetap terlihat pada mobile */
                z-index: 1030;
                position: fixed;
                height: 100%;
                box-shadow: 3px 0 10px rgba(0,0,0,0.1);
                transition: margin-left 0.3s ease;
            }
            
            /* Sidebar selalu terlihat */
            
            
            #sidebar.show.collapsed {
                width: 70px;
                margin-left: 0;
            }

            #content {
                margin-left: 0 !important;
                width: 100%;
            }

            .overlay {
                display: none;
                position: fixed;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1020;
                opacity: 0;
                transition: all 0.5s ease-in-out;
                top: 0;
                left: 0;
            }

            .overlay.show {
                display: block;
                opacity: 1;
            }
            
            /* Navbar yang lebih kompak untuk mobile */
            .navbar {
                padding: 0.5rem 1rem;
            }
            
            /* Teks di sidebar tetap terlihat di mobile mode */
            #sidebar.show .sidebar-brand span,
            #sidebar.show .nav-link span {
                display: inline-block;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-amber-50 text-gray-800 font-chewy">
    <div id="app" class="min-h-screen w-full relative">
        <!-- Main Content -->
        <div class="w-full">
            
            <!-- Navbar dengan tema honey -->
            <nav class="navbar navbar-expand-lg shadow-sm py-2" style="background: linear-gradient(135deg, var(--honey-primary), var(--honey-secondary)); box-shadow: 0 2px 10px var(--honey-shadow);">
                <div class="container-fluid">
                    <!-- Brand/logo -->
                    <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid me-2" style="height: 40px; width: auto;">
                        <span class="brand-text" style="color: var(--honey-text); font-size: 1.5rem;">Honey QR</span>
                    </a>
                    
                    <!-- Tombol hamburger untuk mobile -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="bi bi-list" style="color: var(--honey-text); font-size: 1.5rem;"></i>
                    </button>
                    
                    <!-- Menu navbar -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            @auth
                            <!-- Dashboard untuk semua pengguna -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                </a>
                            </li>
                            
                            @hasrole('admin')
                            <!-- Menu khusus Admin -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-people me-1"></i> Pengguna
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-person-badge me-1"></i> Guru
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}" href="{{ route('classes.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-building me-1"></i> Kelas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-book me-1"></i> Mata Pelajaran
                                </a>
                            </li>
                            @endhasrole
                            
                            <!-- Menu untuk Admin dan Guru -->
                            @hasanyrole('admin|guru')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('qrcodes.*') ? 'active' : '' }}" href="{{ route('qrcodes.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-qr-code me-1"></i> QR Code
                                </a>
                            </li>
                            @endhasanyrole
                            
                            <!-- Menu untuk Siswa -->
                            @hasrole('siswa')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('qrcodes.scan') ? 'active' : '' }}" href="{{ route('qrcodes.scan') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-qr-code-scan me-1"></i> Scan QR
                                </a>
                            </li>
                            @endhasrole
                            
                            <!-- Menu untuk semua pengguna -->
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}" style="color: var(--honey-text); font-weight: 500;">
                                    <i class="bi bi-calendar-check me-1"></i> Presensi
                                </a>
                            </li>
                            @endauth
                        </ul>
                        
                        <!-- Kanan navbar (user profile & dark mode) -->
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-sm rounded-circle" style="background-color: rgba(255, 255, 255, 0.3); width: 2.5rem; height: 2.5rem;" title="Ganti Mode Tampilan" onclick="toggleDarkMode()">
                                <i class="bi bi-moon-stars-fill" style="font-size: 1rem; color: var(--honey-text);"></i>
                            </button>
                            
                            @auth
                            <div class="dropdown">
                                <a class="text-decoration-none dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--honey-text); background-color: rgba(255, 255, 255, 0.3); padding: 5px 15px; border-radius: 50px;">
                                    <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/36' }}" alt="Profile" class="rounded-circle me-2" style="width: 36px; height: 36px; object-fit: cover; border: 2px solid var(--honey-light);">
                                    <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                                    <small class="ms-2 text-muted" style="opacity: 0.8;">{{ auth()->user()->roles->first()->name }}</small>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow" style="border-radius: 10px; border: 1px solid var(--honey-light);">
                                    <a class="dropdown-item" href="{{ route('users.edit', auth()->id()) }}">
                                        <i class="bi bi-person-circle me-2"></i> Profil
                                    </a>
                                    <a class="dropdown-item" href="{{ route('users.index') }}">
                                        <i class="bi bi-gear me-2"></i> Pengaturan
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk toggle dark mode
            window.toggleDarkMode = function() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                document.documentElement.classList.toggle('dark');
                localStorage.setItem('darkMode', !isDarkMode);
                
                // Update ikon berdasarkan mode
                const darkModeIcon = document.querySelector('.bi-moon-stars-fill');
                if (darkModeIcon) {
                    if (!isDarkMode) {
                        darkModeIcon.classList.remove('bi-moon-stars-fill');
                        darkModeIcon.classList.add('bi-sun-fill');
                    } else {
                        darkModeIcon.classList.remove('bi-sun-fill');
                        darkModeIcon.classList.add('bi-moon-stars-fill');
                    }
                }
            };

            // Inisialisasi dark mode dari localStorage
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
                // Update ikon untuk dark mode
                const darkModeIcon = document.querySelector('.bi-moon-stars-fill');
                if (darkModeIcon) {
                    darkModeIcon.classList.remove('bi-moon-stars-fill');
                    darkModeIcon.classList.add('bi-sun-fill');
                }
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Confirm Delete
            const confirmDeleteForms = document.querySelectorAll('.confirm-delete');
            confirmDeleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
