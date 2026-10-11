<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-2xl p-3 p-md-4">

      {{-- HEADER + close --}}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>

      {{-- ======= SIGN IN ======= --}}
      <div id="authSignIn" class="auth-pane">
        <h2 class="title-grad">Sign in</h2>
        <p class="text-muted text-center mb-4" style="opacity:.9">
          Sign in now, meet your dream partner before this golden chance disappears!
        </p>

        {{-- FORM SIGN IN (manual) --}}
        <form method="POST" action="{{ route('signin') }}" novalidate class="mb-3">
          @csrf

          {{-- Email --}}
          <label class="form-label">Email Address</label>
          <div class="auth-input mb-3">
            <i class="bi bi-envelope left-ico"></i>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   placeholder="Your Email Address" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-tooltip">Invalid Email</div> @enderror
          </div>

          {{-- Password --}}
          <label class="form-label">Password</label>
          <div class="auth-input mb-3 position-relative">
            <i class="bi bi-lock left-ico"></i>
            <input id="inPass" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 character" required>
            <button type="button" class="toggle-pass" data-target="#inPass" aria-label="Toggle password">
              <i class="bi bi-eye-slash"></i>
            </button>
            @error('password') <div class="invalid-tooltip">Wrong Password</div> @enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="rememberIn" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="rememberIn">Remember me</label>
            </div>
          </div>

          <button class="btn btn-gradient w-100" type="submit">
            Sign in <i class="bi bi-arrow-right ms-2"></i>
          </button>
        </form>

        <div class="auth-divider my-4">Or continue with</div>

        {{-- OAUTH BUTTONS (tetap seperti awal) --}}
        <div class="row g-2">
          <div class="col-4">
            <a href="{{ route('oauth.redirect', 'google') }}" class="oauth-btn d-flex justify-content-center">
              <img src="{{ asset('assets/img/google.png') }}" alt="google">
            </a>
          </div>
          <div class="col-4">
            <a href="{{ route('oauth.redirect', 'facebook') }}" class="oauth-btn d-flex justify-content-center">
              <img src="{{ asset('assets/img/facebook.png') }}" alt="facebook">
            </a>
          </div>
          <div class="col-4">
            <button class="oauth-btn d-flex justify-content-center" disabled>
              <img src="{{ asset('assets/img/apple.png') }}" alt="apple">
            </button>
          </div>
        </div>

        <p class="text-center modal-bawah mt-4 mb-1 small">
          Don't have an account?
          <a href="#" class="link-primary fw-semibold text-decoration-none js-auth-link" data-auth-link="signup" id="linkGoSignUp">Sign Up</a>
        </p>
      </div>

      {{-- ======= SIGN UP ======= --}}
      <div id="authSignUp" class="auth-pane d-none">
        <h2 class="title-grad">Sign up</h2>
        <p class="text-muted text-center mb-4" style="opacity:.9">
          Create your account today, because your perfect match is waiting right now.
        </p>

        {{-- FORM SIGN UP (manual) --}}
        <form method="POST" action="{{ route('signup') }}" novalidate class="mb-3">
          @csrf

          {{-- Email --}}
          <label class="form-label">Email Address</label>
          <div class="auth-input mb-3">
            <i class="bi bi-envelope left-ico"></i>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   placeholder="Your Email Address" value="{{ old('email') }}" required>
            @error('email') <div class="invalid-tooltip">Invalid Email</div> @enderror
          </div>

          {{-- Password --}}
          <label class="form-label">Password</label>
          <div class="auth-input mb-3 position-relative">
            <i class="bi bi-lock left-ico"></i>
            <input id="upPass" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror" placeholder="Min 8 character" minlength="8" required>
            <button type="button" class="toggle-pass" data-target="#upPass" aria-label="Toggle password">
              <i class="bi bi-eye-slash"></i>
            </button>
            @error('password') <div class="invalid-tooltip">Wrong Password</div> @enderror
          </div>

          {{-- Konfirmasi password (sesuai rule confirmed) --}}
          <label class="form-label">Confirm Password</label>
          <div class="auth-input mb-4">
            <i class="bi bi-lock left-ico"></i>
            <input id="confirmPass" type="password" name="password_confirmation" class="form-control" placeholder="Min 8 character" minlength="8" required>
          <button type="button" class="toggle-pass" data-target="#confirmPass" aria-label="Toggle password">
              <i class="bi bi-eye-slash"></i>
            </button>
          </div>

          <button class="btn btn-gradient w-100" type="submit">
            Create account <i class="bi bi-arrow-right ms-2"></i>
          </button>
        </form>

        <div class="auth-divider my-4">Or continue with</div>

        {{-- OAUTH BUTTONS (tetap) --}}
        <div class="row g-2">
          <div class="col-4">
            <a href="{{ route('oauth.redirect', 'google') }}" class="oauth-btn d-flex justify-content-center">
              <img src="{{ asset('assets/img/google.png') }}" alt="google">
            </a>
          </div>
          <div class="col-4">
            <a href="{{ route('oauth.redirect', 'facebook') }}" class="oauth-btn d-flex justify-content-center">
              <img src="{{ asset('assets/img/facebook.png') }}" alt="facebook">
            </a>
          </div>
          <div class="col-4">
            <button class="oauth-btn d-flex justify-content-center" disabled>
              <img src="{{ asset('assets/img/apple.png') }}" alt="apple">
            </button>
          </div>
        </div>

        <p class="text-center modal-bawah mt-4 mb-1 small">
          Already have an account?
          <a href="#" class="link-primary fw-semibold text-decoration-none js-auth-link" data-auth-link="signin" id="linkSignIn">Sign In</a>
        </p>
      </div>

    </div>
  </div>
</div>

