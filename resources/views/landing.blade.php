@extends('layouts.app')
@section('title', 'Lova | Online Dating Platform for Meaningful Connections')
@section('content')

<section class="hero-area">
  <div class="container">
    <div class="row align-items-start g-5">
      {{-- Left text --}}
      <div class="col-lg-6">
        <p class="hero-lead"><span class="accent">Find Your Perfect Match</span> </p>
        <h1 class="hero-title">
          Begin Dating, <span class="accent"> Discover</span> Your True Love
        </h1>
        <p class="hero-lead hero-mob">Join thousands finding love safely, easily, and truly meaningful connections</p>

        <a class="btn btn-grad rounded-pill px-4"
                 href="{{ route('free-online-dating') }}">
                Start free today <i class="bi bi-arrow-right"></i>
      </a>
      </div>

      {{-- Right visuals --}}
      <div class="col-lg-6 back-door">
        <div class="hero-wrap">
          {{-- Front card with inner frame --}}
          <div class="hero-card front-card tilt-front">
            <div class="inner">
              <div class="card-img-wrap">
                <img src="{{ asset('assets/img/girl-1.png') }}" alt="profile 1">

                <!-- Lokasi kiri bawah -->
                <div class="card-location">
                    <span class="dot"></span> Jakarta, Indonesia
                </div>

                <!-- Aksi kanan tengah -->
                <div class="card-actions">
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-1.png') }}" alt="like">
                    </button>
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-2.png') }}" alt="chat">
                    </button>
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-3.png') }}" alt="refresh">
                    </button>
                </div>
              </div>

              <div class="card-text ">
                <div class="card-title fw-semibold" style="color: #fff">Lorem ipsum 28</div>
                <p class="card-desc">Lorem ipsum dolor sit amet consectetur. Ipsum a nec aliquet dictum auctor…</p>
                <div class="d-flex gap-2 flex-wrap">
                  <span class="tag">Fashion & Style</span>
                  <span class="tag">Music</span>
                  <span class="tag">Traveling</span>
                </div>
              </div>
            </div>
          </div>

          {{-- Back card --}}
          <div class="hero-card back-card tilt-back">
            <div class="inner">
              <div class="card-img-wrap">
                <img src="{{ asset('assets/img/girl-2.png') }}" alt="profile 1">

                <!-- Lokasi kiri bawah -->
                <div class="card-location">
                    <span class="dot"></span> Jakarta, Indonesia
                </div>

                <!-- Aksi kanan tengah -->
                <div class="card-actions">
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-1.png') }}" alt="like">
                    </button>
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-2.png') }}" alt="chat">
                    </button>
                    <button class="action">
                    <img src="{{ asset('assets/img/icon-card-3.png') }}" alt="refresh">
                    </button>
                </div>
              </div>

              <div class="card-text ">
                <div class="card-title fw-semibold" style="color: #707070">Lorem ipsum 28</div>
                <p class="card-desc" style="color: #707070">Lorem ipsum dolor sit amet consectetur. Ipsum a nec aliquet dictum auctor…</p>
                <div class="d-flex gap-2 flex-wrap">
                  <span class="tag" style="color: #707070; background:#f5f5f5">Fashion & Style</span>
                  <span class="tag" style="color: #707070; background:#f5f5f5">Music</span>
                  <span class="tag" style="color: #707070; background:#f5f5f5">Traveling</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>

    </div>
    
    <div class="row g-4 features-row">
      <div class="col-lg-8">
        <div class="row g-4">

          <div class="col-md item">
            <div class="d-flex gap-3 align-items-center">
              <img src="{{ asset('assets/img/icon1.png') }}" alt="" width="65px">
              <div>
                <div class="title">Perfect Match</div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                 <div class="desc">Find someone truly meant for you.</div>
              </div>
            </div>
          </div>
          <div class="col-md item">
            <div class="d-flex gap-3 align-items-center">
              <img src="{{ asset('assets/img/icon2.png') }}" alt="" width="65px">
              <div>
                <div class="title">10K Members</div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                 <div class="desc">Join over 10K active singles today</div>
              </div>
            </div>
          </div>
          <div class="col-md item">
            <div class="d-flex gap-3 align-items-center">
              <img src="{{ asset('assets/img/icon3.png') }}" alt="" width="65px">
              <div>
                <div class="title">Verified & Safe</div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                 <div class="desc">Connect with trusted, verified profiles.</div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>

{{-- Tips & Featured Product --}}
{{-- <section class="tips-area">
  <div class="container">
    <div class="banner">
      <img src="{{ asset('assets/img/banner.png') }}" alt="Tip 2">
    </div>
    <p class="tips-kicker"><span class="accent">Smart dating Insights</span></p>
    <h2 class="tips-title">
      Simple Dating Tips to Create
      <span class="accent">Meaningful and Lasting</span> Connections
    </h2>

    <div class="tips-grid">
      <div class="tips-list">
        <article class="tips-card">
          <img src="{{ asset('assets/img/advice-1.png') }}" alt="Tip 1">
          <div class="tips-meta">
            <h5 class="tips-card-title">Lorem ipsum mauris cursus purus auctor…</h5>
            <p class="tips-card-desc">Vivamus habitant morbi tristique senectus et netus…</p>
            <a href="#" class="btn-gradient btn-tips">Read More <i class="bi bi-arrow-right"></i></a>
          </div>
        </article>

        <article class="tips-card">
          <img src="{{ asset('assets/img/advice-2.png') }}" alt="Tip 2">
          <div class="tips-meta">
            <h5 class="tips-card-title">Lorem ipsum mauris cursus purus auctor…</h5>
            <p class="tips-card-desc">Felis bibendum ut tristique et egestas quis ipsum…</p>
            <a href="#" class="btn-gradient btn-tips">Read More <i class="bi bi-arrow-right"></i></a>
          </div>
        </article>

        <article class="tips-card">
          <img src="{{ asset('assets/img/advice-3.png') }}" alt="Tip 3">
          <div class="tips-meta">
            <h5 class="tips-card-title">Lorem ipsum mauris cursus purus auctor…</h5>
            <p class="tips-card-desc">Nibh cras pulvinar mattis nunc sed blandit libero…</p>
            <a href="#" class="btn-gradient btn-tips">Read More <i class="bi bi-arrow-right"></i></a>
          </div>
        </article>
      </div>

      <aside class="tips-feature">
        <div class="feat-visual">
          <img src="{{ asset('assets/img/samsung.png') }}" alt="Galaxy Z Fold4">
        </div>
      </aside>
    </div>
  </div>
</section> --}}

<!-- Testimonial / Review Section -->
<section class="testi-area">
  <div class="container position-relative">
    <!-- Heading -->
    <div class="row  mb-4 mb-lg-5">
      <div class="col-lg-8">
        <p class="hero-lead"><span class="accent">User Testimonials</span> </p>
        <h2 class="testi-title">Real Stories From <span class="accent">Happy <br>Couples</span> Worldwide</h2>
      </div>

      <!-- Nav arrows -->
      <div class="col-lg-4 d-flex align-items-end justify-content-lg-end gap-2 mt-3 mt-lg-0">
        <button class="testi-nav btn btn-white rounded-circle shadow-sm" type="button" data-dir="prev" aria-label="Previous">
          <i class="bi bi-arrow-left"></i>
        </button>
        <button class="testi-nav btn btn-white rounded-circle shadow-sm" type="button" data-dir="next" aria-label="Next">
          <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

    <!-- Cards / Carousel -->
    <div class="testi-carousel owl-carousel">
      <div class="item">
        <article class="testi-card rounded-3 shadow-sm">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://i.pravatar.cc/80?img=1" class="rounded-circle testi-avatar" alt="Selena">
            <div>
              <div class="testi-card-name">Selena</div>
              <div class="testi-card-role small">Pengusaha</div>
            </div>
            <div class="icon-star ms-auto d-flex align-items-center">
              <i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star empty">★</i>
            </div>
          </div>
          <p class="mb-2 testi-text">I never believed online dating could work until I joined here. The profile verification made me feel safe, and within weeks, I met someone truly compatible. Now we're building a meaningful relationship together <a href="#" class="read-more">Read More...</a></p>
        </article>
      </div>

      <div class="item">
        <article class="testi-card rounded-3 shadow-sm">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://i.pravatar.cc/80?img=2" class="rounded-circle testi-avatar" alt="Selena">
            <div>
              <div class="testi-card-name">Selena</div>
              <div class="testi-card-role small">Pengusaha</div>
            </div>
            <div class="icon-star ms-auto d-flex align-items-center">
              <i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star empty">★</i>
            </div>
          </div>
          <p class="mb-2 testi-text">This platform changed my life completely. I was tired of endless swiping elsewhere, but here everything felt genuine. Verified profiles gave me trust, and I finally found someone who understands me deeply <a href="#" class="read-more">Read More...</a></p>
        </article>
      </div>

      <div class="item">
        <article class="testi-card rounded-3 shadow-sm">
          <div class="d-flex align-items-center gap-3 mb-3">
            <img src="https://i.pravatar.cc/80?img=3" class="rounded-circle testi-avatar" alt="Selena">
            <div>
              <div class="testi-card-name">Selena</div>
              <div class="testi-card-role small">Pengusaha</div>
            </div>
            <div class="icon-star ms-auto d-flex align-items-center">
              <i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star full">★</i><i class="star empty">★</i>
            </div>
          </div>
          <p class="mb-2 testi-text">Finding love online always seemed impossible until now. Thanks to the matching filters, I met someone with shared hobbies and values. We clicked instantly, and I'm happier than I've ever been before <a href="#" class="read-more">Read More...</a></p>
        </article>
      </div>

    </div>

  </div>
</section>

<section class="promo-duo">
  <div class="container">

    <!-- KARTU GRADIEN + FOTO (PAKAI hero-card front/back CARD KAMU) -->
    <div class="promo-top align-items-start">
          <div class="promo-copy">
            <h2 class="promo-copy-title">Match Exactly Who You Want</h2>
            <p class="promo-copy-desc">Set your preferences, find perfect matches, and start meaningful conversations.</p>
          </div>
          <div class="promo-visual">
            <div class="hero-wrap promo-hero-wrap">
              <img src="{{ asset('assets/img/promo-hero.png') }}" alt="profile 2">
            </div>
          </div>
    </div>

    <!-- PANEL CTA GELAP -->
    <div class="promo-cta">
      <div class="cta-inner">
        <h3 class="cta-inner-title">Join Free Online Dating And Start Meeting Love</h3>

        <!-- 2 kolom: tombol + paragraf -->
        <div class="cta-grid">
          <a href="{{ route('free-online-dating') }}" class="btn-gradient" >
            Start Dating Now<i class="bi bi-arrow-right"></i>
          </a>

          <p class="cta-inner-desc">
            Find singles nearby, chat instantly, and enjoy real connections today.
          </p>
        </div>
      </div>
    </div>

  </div>

  {{-- Modal: Account Deleted --}}
  <div id="deleted-modal" class="modal-lite" aria-hidden="true"
      @if(session('account_deleted')) data-autoshow="true" @endif>
    <div class="modal-lite__dialog" role="dialog" aria-modal="true" aria-labelledby="deleted-head">
      <div class="modal-lite__head">
        <h5 id="deleted-head">Profile Deleted</h5>
        <button type="button" class="modal-lite__close" data-modal-close aria-label="Close">
          <i class="bi bi-x"></i>
        </button>
      </div>

      <div class="modal-body" style="text-align:center">
        <div class="badge-gradient-check" aria-hidden="true">
          <i class="bi bi-check"></i>
        </div>
        <p class="deleted-title">Profile Deleted</p>
        <p class="deleted-desc">
          {{ session('deleted_message') ?? 'Your account has been deleted. All data is permanently removed. Thank you for being with us.' }}
        </p>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn btn-gradient" data-modal-close>Close</button>
      </div>
    </div>
  </div>

</section>

@endsection
