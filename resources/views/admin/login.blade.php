{{-- resources/views/admin/auth/login.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap + Icons + AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/css/adminlte.min.css">

  <style>
    :root{
      --bb:#FF8C00;
      --bbr:255,140,0;
      --bb-600:#e46e00;
    }
    body{
      background:
        radial-gradient(1000px 500px at -10% -10%, rgba(var(--bbr),.12), transparent 60%),
        radial-gradient(800px 500px at 110% 10%, rgba(var(--bbr),.10), transparent 60%),
        #f7f8fb;
    }
    .login-box{ width: 380px; max-width:92vw; }
    .card-primary.card-outline{ border-top:3px solid var(--bb); }
    .brand-title{ font-weight:800; color:var(--bb); letter-spacing:.3px; cursor:default; text-decoration:none; }
    .btn-primary{ background:var(--bb); border-color:var(--bb); }
    .btn-primary:hover{ background:var(--bb-600); border-color:var(--bb-600); }
    .form-control:focus{ border-color:var(--bb); box-shadow:0 0 0 .2rem rgba(var(--bbr),.15); }
    .input-group-text{ background:#fff; }
  </style>
</head>
<body class="hold-transition min-vh-100 d-flex align-items-center justify-content-center">

  @php
    $homeUrl = \Illuminate\Support\Facades\Route::has('home') ? route('home') : url('/');
  @endphp

  <div class="login-box">
    <div class="card card-outline card-primary shadow-sm">
      <div class="card-header text-center bg-white">
        <span class="h1 brand-title">Admin Panel</span>
        <div class="small text-muted mt-1">Silakan masuk ke dashboard</div>
      </div>

      <div class="card-body">

        {{-- Flash sukses --}}
        @if (session('status'))
          <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif

        {{-- Error validasi --}}
        @if ($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Sesuaikan dengan nama rute POST di routes-mu --}}
        <form action="{{ route('admin.login.attempt') }}" method="POST" novalidate>
          @csrf

          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
              <input type="email" name="email" class="form-control" placeholder="email@domain.com"
                     value="{{ old('email') }}" required autofocus>
            </div>
          </div>

          <div class="mb-2">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Password</span>
              <button class="btn btn-link p-0 small" type="button" id="togglePass">
                <i class="fa-regular fa-eye me-1"></i> Tampilkan
              </button>
            </label>
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
              <input type="password" name="password" class="form-control" id="password" placeholder="••••••••" required>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
              <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            @if(Route::has('password.request'))
              <a class="small text-decoration-none" href="{{ route('password.request') }}">Lupa password?</a>
            @endif
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk
            </button>
          </div>
        </form>

        {{-- Tombol kembali ke website: DIBAWAH --}}
        <div class="text-center mt-3">
          <a href="{{ $homeUrl }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-house me-1"></i> Kembali ke Website
          </a>
        </div>

      </div>
    </div>

    <div class="text-center text-muted small mt-3">
      © {{ date('Y') }} Bina Bordir — Dashboard Admin
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4/dist/js/adminlte.min.js"></script>
  <script>
    // Toggle show/hide password
    document.getElementById('togglePass')?.addEventListener('click', function(){
      const p = document.getElementById('password');
      const show = p.type === 'password';
      p.type = show ? 'text' : 'password';
      this.innerHTML = show
        ? '<i class="fa-regular fa-eye-slash me-1"></i>Sembunyikan'
        : '<i class="fa-regular fa-eye me-1"></i>Tampilkan';
    });
  </script>
</body>
</html>
