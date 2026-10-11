<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Find Your Perfect Match')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>

  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
@guest
  @include('partials.navbar')
@endguest

@auth
  @include('layouts.navbar')
@endauth

<main>
  @yield('content')

  @guest
  <div id="mobileDim" class="mobile-dim"></div>

  <aside id="mobileDrawer" class="mobile-drawer" role="dialog" aria-modal="true" aria-labelledby="mobileMenuTitle">
    <div class="drawer-head">
      <div class="brand-line">
        <img src="{{ asset('assets/img/icons/logo-brand.png') }}" alt="logo" height="20">
        <span id="mobileMenuTitle" class="navbar-brand ms-2 fw-semibold">Logo</span>
      </div>
      <button id="drawerClose" class="drawer-close" aria-label="Close menu">×</button>
    </div>

    <nav class="drawer-nav">
      <a class="drawer-link" href="{{ route('landing') }}">Homepage</a>
      <a class="drawer-link" href="{{ route('free-online-dating') }}">Free Online Dating</a>
      @if(Route::has('advice'))
        <a class="drawer-link" href="{{ route('advice') }}">Dating Advice</a>
      @endif
      <a class="drawer-link" href="{{ route('support') }}">Support</a>

    </nav>
  </aside>
@endguest

@auth
  <nav class="mobile-bottom-bar " role="navigation" aria-label="Primary">
    <a class="mbb-item {{ request()->routeIs('free-online-dating') ? 'is-active' : '' }}"
       href="{{ route('free-online-dating') }}" aria-label="Discover">
      <span class="ico">
        <img src="{{ asset('assets/img/icons/icon-navbar-1.png') }}" alt="">
      </span>
      
    </a>
    <a id="btnLikesModal"
        href="#" data-nav="likes"
        class="mbb-item"
        data-modal-open="likes-modal|likesBubble">
          <img src="{{ asset('assets/img/icons/icon-navbar-2.png') }}" alt="like">
        </a>
    

    <a class="mbb-item {{ request()->routeIs('chat.*') ? 'is-active' : '' }}"
       href="{{ route('chat.index') }}" aria-label="Messages">
      <span class="ico">
        <img src="{{ asset('assets/img/icons/icon-navbar-3.png') }}" alt="">
      </span>
      
    </a>
  </nav>
@endauth


</main>

@include('partials.footer')
@include('partials.modal-likes')

@push('modals')
  @include('partials.modal-auth')
@endpush

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery + Owl Carousel JS -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  <script src="{{ asset('assets/js/main.js') }}"></script>

  @if($errors->any() || session('open_auth'))
    <script> new bootstrap.Modal('#authModal').show(); </script>
  @endif
  @stack('scripts')
  @stack('modal-auth')
</body>
</html>
