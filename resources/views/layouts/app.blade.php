<!DOCTYPE html>
<html lang="{{ env('APP_LOCALE', 'id') }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Pengaduan') | Aplikasi PTPP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" integrity="sha512-jnSuA4Ss2PkkikSOLtYs8BlYIeeIK1h99ty4YfvRPAlzr377vr3CXDb7sb7eEEBYjDtcYj+AjBH3FLv5uSJuXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    @stack('styles')
</head>
<body>
    <header class="navbar navbar-dark bg-dark">
        <div class="container d-flex justify-content-between align-items-center g-5">
            <span class="text-white me-3">
                {{ Auth::user()->name ?? 'Guest' }}
            </span>
            @if (Auth::user() && Auth::user()->role === 'ITM')
                <nav class="nav">
                    <a class="nav-link text-white" href="{{ route('ptpp-requests.show') }}">
                        Permohonan PTPP
                    </a>
                    <a class="nav-link text-white" href="{{ route('ptpp-approval.itm-approval.show') }}">
                        Persetujuan PTPP
                    </a>
                    <a class="nav-link text-white" href="{{ route('ptpp-requests.report.show') }}">
                        Laporan PTPP
                    </a>
                    <a class="nav-link text-white" href="{{ route('mwt-requests.show') }}">
                        Laporan MWT
                    </a>
                    <a class="nav-link text-white" href="{{ route('users.show') }}">
                        Kelola Pengguna
                    </a>
                </nav>
            @else
                <nav class="nav">
                    <a class="nav-link text-white" href="{{ route('ptpp-requests.show') }}">
                        Permohonan PTPP
                    </a>
                    <a class="nav-link text-white" href="{{ route('mwt-requests.show') }}">
                        Laporan MWT
                    </a>
                </nav>
            @endif
            <form action="{{ route('logout.handle') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    Logout
                </button>
            </form>
        </div>
    </header>
    
    <div class="container g-5 py-4">
        @yield('content')
    </div>
    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <small class="text-muted">© {{ date('Y') }} Hak Cipta Dilindungi.</small>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" integrity="sha512-7Pi/otdlbbCR+LnW+F7PwFcSDJOuUJB3OxtEHbg4vSMvzvJjde4Po1v4BR9Gdc9aXNUNFVUY+SK51wWT8WF0Gg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('scripts')
</body>
</html>