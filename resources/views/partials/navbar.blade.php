<nav class="navbar navbar-expand-lg bg-white sticky-top py-3">
  <div class="container">
    <a class="navbar-brand" href="{{ route('landing') }}">
      <img src="{{ asset('assets/img/icons/logo-brand.png') }}" alt="like">
    </a>

    @guest
    <button id="navToggle"
            class="app-navbar__toggle d-inline-flex d-lg-none align-items-center justify-content-center"
            type="button" aria-label="Open menu">
      <i class="bi bi-list" style="font-size:22px;"></i>
    </button>
    @endguest

    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-auto gap-lg-4">
        <li class="nav-item">
          <a href="{{ route('landing') }}"
            class="nav-link {{ request()->routeIs('landing') ? 'is-active' : '' }}"
            @if(request()->routeIs('landing')) aria-current="page" @endif>
            Homepage
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('free-online-dating') }}"
            class="nav-link {{ request()->routeIs('free-online-dating') ? 'is-active' : '' }}"
            @if(request()->routeIs('free-online-dating')) aria-current="page" @endif>
            Free Online Dating
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('support') }}"
            class="nav-link {{ request()->routeIs('support*') ? 'is-active' : '' }}"
            @if(request()->routeIs('support*')) aria-current="page" @endif>
            Support
          </a>
        </li>
      </ul>

      @auth
        <form method="post" action="{{ route('signout') }}">
          @csrf
          <button class="btn btn-outline-secondary rounded-pill px-4">Sign out</button>
        </form>
      @else
        <button class="btn btn-grad-navbar" data-bs-toggle="modal" data-bs-target="#authModal">Sign in</button>
      @endauth
      
    </div>
  </div>
</nav>

@include('partials.modal-auth')
