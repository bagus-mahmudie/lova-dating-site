<nav class="app-navbar app-navbar-expand-lg bg-white sticky-top py-3">
  <div class="app-navbar__container">

    {{-- Left: Logo --}}
    <a class="navbar-brand" href="{{ route('free-online-dating') }}">
      <img src="{{ asset('assets/img/icons/logo-brand.png') }}" alt="like">
    </a>

    {{-- Middle: menu pill (muncul hanya setelah login) --}}
    @auth
    <ul class="app-navbar__menu">
      <li>
        <a class="app-navbar__pill {{ request()->routeIs('free-online-dating') ? 'is-active' : '' }}"
           href="{{ route('landing') }}" id="navDiscover" data-nav="discover">
          <img src="{{ asset('assets/img/icons/icon-navbar-1.png') }}" alt="Discover">
          Discover
        </a>
      </li>
      <li>
        <a id="btnLikesModal"
        href="#" data-nav="likes"
        class="app-navbar__pill"
        data-modal-open="likes-modal|likesBubble">
          <img src="{{ asset('assets/img/icons/icon-navbar-2.png') }}" alt="like">
          Like
        </a>
      </li>
      <li>
       <a class="app-navbar__pill {{ request()->routeIs('chat*') ? 'is-active' : '' }}"
          href="{{ route('chat.index') }}" id="navMessages"data-nav="messages">
          <img src="{{ asset('assets/img/icons/icon-navbar-3.png') }}" alt="Message">
          Message
        </a>
      </li>
    </ul>
    @endauth

    {{-- Right: ikon + profil --}}
    <div class="app-navbar__right">
      @auth
        <span class="like-bubble-wrap">
          <button id="navSearchBtn" class="app-navbar__icon app-navbar-search" type="button" aria-label="Search">
            <img src="{{ asset('assets/img/icons/icon-navbar-search.png') }}" alt="Search">
          </button>

          {{-- Bubble Filter --}}
          <div id="navSearchPanel" class="like-bubble notif-bubble bubble-filter d-none" role="dialog" aria-modal="false" style="width:560px;">
            <div class="bubble-arrow" aria-hidden="true"></div>
            <button id="navSearchClose" class="bubble-close" type="button" aria-label="Close">×</button>

            <form id="searchFilterForm" class="p-2 form-filter">
              <div class="pref-accordion" id="searchAccord">

                {{-- Age Range --}}
                <div class="pref-item">
                  <button type="button" class="pref-head">
                    <span>Age Range</span><i class="bi bi-chevron-down"></i>
                  </button>
                  <div class="pref-panel">
                    <label class="pref-option"><input type="radio" name="age_range" value="" checked><span>Any</span></label>
                    <label class="pref-option"><input type="radio" name="age_range" value="18-24"><span>18–24</span></label>
                    <label class="pref-option"><input type="radio" name="age_range" value="25-30"><span>25–30</span></label>
                    <label class="pref-option"><input type="radio" name="age_range" value="31-35"><span>31–35</span></label>
                    <label class="pref-option"><input type="radio" name="age_range" value="36-40"><span>36–40</span></label>
                    <label class="pref-option"><input type="radio" name="age_range" value="41+"><span>41+</span></label>
                  </div>
                </div>

                {{-- Hobbies --}}
                <div class="pref-item">
                  <button type="button" class="pref-head">
                    <span>Hobbies</span><i class="bi bi-chevron-down"></i>
                  </button>
                  <div class="pref-panel">
                    <label class="pref-option"><input type="radio" name="hobby" value="" checked><span>Any</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Travel"><span>Travel</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Music"><span>Music</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Reading"><span>Reading</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Sports"><span>Sports</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Gaming"><span>Gaming</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Foodie"><span>Foodie</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Photography"><span>Photography</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Movies"><span>Movies</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Art & Design"><span>Art & Design</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Animals"><span>Animals</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Hiking"><span>Hiking</span></label>
                    <label class="pref-option"><input type="radio" name="hobby" value="Tech"><span>Tech</span></label>
                  </div>
                </div>

                {{-- Lifestyle --}}
                <div class="pref-item">
                  <button type="button" class="pref-head">
                    <span>Lifestyle</span><i class="bi bi-chevron-down"></i>
                  </button>
                  <div class="pref-panel">
                    <label class="pref-option"><input type="radio" name="lifestyle" value="" checked><span>Any</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Non-smoker"><span>Non-smoker</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Occasionally"><span>Occasionally</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Regular smoker"><span>Regular smoker</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Non-drinker"><span>Non-drinker</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Social drinker"><span>Social drinker</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Regular drinker"><span>Regular drinker</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Omnivore"><span>Omnivore</span></label>
                    <label class="pref-option"><input type="radio" name="lifestyle" value="Vegetarian"><span>Vegetarian</span></label>
                  </div>
                </div>

                {{-- Personality --}}
                <div class="pref-item">
                  <button type="button" class="pref-head">
                    <span>Personality</span><i class="bi bi-chevron-down"></i>
                  </button>
                  <div class="pref-panel">
                    <label class="pref-option"><input type="radio" name="personality" value="" checked><span>Any</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Introvert"><span>Introvert</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Extrovert"><span>Extrovert</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Ambivert"><span>Ambivert</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Adventurous"><span>Adventurous</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Easygoing"><span>Easygoing</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Ambitious"><span>Ambitious</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Creative"><span>Creative</span></label>
                    <label class="pref-option"><input type="radio" name="personality" value="Funny"><span>Funny</span></label>
                  </div>
                </div>

                {{-- Love Language --}}
                <div class="pref-item">
                  <button type="button" class="pref-head">
                    <span>Love Language</span><i class="bi bi-chevron-down"></i>
                  </button>
                  <div class="pref-panel">
                    <label class="pref-option"><input type="radio" name="love_language" value="" checked><span>Any</span></label>
                    <label class="pref-option"><input type="radio" name="love_language" value="Words of Affirmation"><span>Words of Affirmation</span></label>
                    <label class="pref-option"><input type="radio" name="love_language" value="Quality Time"><span>Quality Time</span></label>
                    <label class="pref-option"><input type="radio" name="love_language" value="Acts of Service"><span>Acts of Service</span></label>
                    <label class="pref-option"><input type="radio" name="love_language" value="Receiving Gifts"><span>Receiving Gifts</span></label>
                    <label class="pref-option"><input type="radio" name="love_language" value="Physical Touch"><span>Physical Touch</span></label>
                  </div>
                </div>

              </div>

              <div class="modal-foot p-2">
                <div class="d-flex gap-2 btn-row-eq">
                  <button id="searchFilterSubmit" type="submit" class="btn btn-gradient btn-apply">
                    Apply Filter
                  </button>
                  <button id="searchFilterReset" type="button" class="btn btn-outline-secondary btn-reset">
                    Reset
                  </button>
                </div>
              </div>


            </form>


          </div>
        </span>

        <span class="like-bubble-wrap">
          <button id="navNotifBtn" class="app-navbar__icon" aria-label="Notifications">
            <img src="{{ asset('assets/img/icons/icon-navbar-notif.png') }}" alt="like">
            <span id="navNotifBadge" class="notif-badge d-none">0</span>
          </button>

          <!-- Bubble -->
          <div id="navNotifPanel" class="like-bubble notif-bubble d-none" role="dialog" aria-modal="false">
            <div class="bubble-arrow" aria-hidden="true"></div>
            <button id="navNotifClose" class="bubble-close" type="button" aria-label="Close">×</button>

            <!-- isi dari JS -->
            <div id="notifList" class="notif-list"></div>
          </div>
        </span>

        <div class="app-navbar__profile">
          <img class="app-navbar__avatar" src="{{ auth()->user()?->avatar_url ?? asset('assets/img/icons/avatar-placeholder.png') }}" alt="avatar">
          <div class="app-navbar__profile-text">
            <span class="app-navbar__name">{{ Auth::user()->name ?? 'User' }}</span>
            <span class="app-navbar__plan">Akun Anda</span>
          </div>
          <button class="app-navbar__caret" id="profileCaret" aria-label="Open menu">
            <i class="bi bi-chevron-down"></i>
          </button>

          <div class="app-navbar__dropdown" id="profileDropdown">
            <a href="{{ route('profile') }}"><i class="bi bi-person"></i> Profile</a>
            <form method="POST" action="{{ route('signout') }}">
              @csrf
              <button type="submit" style="color: red"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
          </div>
        </div>
      @endauth

    </div>

  </div>
</nav>

@auth
<script>
  (function () {
    const caret = document.getElementById('profileCaret');
    const dd = document.getElementById('profileDropdown');
    if (!caret || !dd) return;
    caret.addEventListener('click', function (e) {
      e.stopPropagation();
      dd.classList.toggle('is-open');
    });
    document.addEventListener('click', function () {
      dd.classList.remove('is-open');
    });
  })();
</script>
@endauth
