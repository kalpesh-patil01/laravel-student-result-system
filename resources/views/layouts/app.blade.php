<!-- <!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title') | Result Management</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('css/results.css') }}" rel="stylesheet">
</head>
<body>
@php($items = [['dashboard','Dashboard','bi-speedometer2','dashboard'],['students.index','Students','bi-people','students.*'],['classes.index','Classes','bi-building','classes.*'],['subjects.index','Subjects','bi-book','subjects.*'],['marks.index','Marks Entry','bi-pencil-square','marks.*'],['marksheets.index','Marksheets','bi-file-earmark-text','marksheets.*'],['results.index','Class Results','bi-bar-chart','results.*']])
<div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="nav">
  <div class="brand"><i class="bi bi-mortarboard me-2"></i>Result Management</div>
  <nav class="nav flex-column p-2">
    @foreach($items as [$route,$label,$icon,$pattern])
      <a class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>
    @endforeach
  </nav>
</div>
<div class="content">
  <header class="topbar d-flex align-items-center gap-2">
    <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#nav" aria-label="Open menu"><i class="bi bi-list"></i></button>
    <strong>Student Result and Marksheet Management System</strong>
  </header>
  <main class="p-3 p-lg-4">
    <div class="page-head no-print"><h1>@yield('title')</h1>@hasSection('subtitle')<p>@yield('subtitle')</p>@endif</div>
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if($errors->any())<div class="alert alert-danger"><strong>Please fix the following:</strong><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>document.querySelectorAll('form[data-busy]').forEach(f=>f.addEventListener('submit',()=>{const b=f.querySelector('button[type=submit]');if(b){b.disabled=true;b.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving...'}}))</script>
</body>
</html> -->
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title') | Result Management</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;650&family=Source+Serif+4:wght@600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="{{ asset('css/results.css') }}?v=2" rel="stylesheet">
</head>
<body>
@php($items = [['dashboard','Dashboard','bi-grid-1x2','dashboard'],['students.index','Students','bi-people','students.*'],['classes.index','Classes','bi-building','classes.*'],['subjects.index','Subjects','bi-journal-bookmark','subjects.*'],['marks.index','Marks Entry','bi-pencil-square','marks.*'],['marksheets.index','Marksheets','bi-file-earmark-text','marksheets.*'],['results.index','Class Results','bi-bar-chart-line','results.*']])
<div class="offcanvas-lg offcanvas-start sidebar" tabindex="-1" id="nav">
  <div class="brand"><span class="mark"><i class="bi bi-mortarboard"></i></span><span>Result Management<small>Marksheets and results</small></span></div>
  <div class="group">MENU</div>
  <nav class="nav flex-column">
    @foreach($items as [$route,$label,$icon,$pattern])
      <a class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}"><i class="bi {{ $icon }}"></i>{{ $label }}</a>
    @endforeach
  </nav>
  <div class="foot">Student Result and Marksheet Management System</div>
</div>
<div class="content">
  <header class="topbar d-flex align-items-center gap-2">
    <button class="btn btn-outline-secondary btn-sm d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#nav" aria-label="Open menu"><i class="bi bi-list"></i></button>
    <span>{{ now()->format('l, d F Y') }}</span>
  </header>
  <main class="p-3 p-lg-4">
    <div class="page-head no-print"><h1>@yield('title')</h1>@hasSection('subtitle')<p>@yield('subtitle')</p>@endif</div>
    @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
    @if($errors->any())<div class="alert alert-danger"><strong>Please fix the following:</strong><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
    @yield('content')
  </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>document.querySelectorAll('form[data-busy]').forEach(f=>f.addEventListener('submit',()=>{const b=f.querySelector('button[type=submit]');if(b){b.disabled=true;b.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving...'}}))</script>
</body>
</html>
