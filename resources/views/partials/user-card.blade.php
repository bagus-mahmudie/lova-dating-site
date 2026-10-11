{{-- resources/views/partials/user-card.blade.php --}}
@php
  $avatarUrl = $u->avatar_url ?? null;
  $isOwner   = auth()->check() && auth()->id() === ($u->id ?? null);
  $isLiked = !empty($u->liked_by_auth);
  $likesCount = (int) ($u->likes_count ?? $u->likesCount ?? 0);
@endphp

<div class="col-12 col-md-6 col-lg-4 js-card-col">
          <div class="hero-card-online back-card-online position-relative cursor-pointer @guest guest-locked @endguest js-card" data-user-id="{{ $u->id }}">
            <div class="inner">

              <div class="card-visual">
                <div class="card-img-wrap">
                  <img src="{{ $u->avatar_url ?? asset('assets/img/icons/card-placeholder.png') }}" alt="{{ $u->name }}">

                  <!-- Lokasi kiri bawah -->
                  <div class="card-location">
                    <span class="dot"></span> {{ $u->location ?? '-' }}
                  </div>

                  <!-- Aksi kanan tengah -->
                  <div class="card-actions">
                    {{-- @php $isOwner = auth()->check() && auth()->id() === $u->id; @endphp --}}
                    {{-- @php $isLiked = !empty($u->liked_by_auth); @endphp --}}
                    <button
                    type="button"
                    class="action js-like-btn {{ $isLiked ? 'is-liked' : '' }}"
                    aria-pressed="{{ $isLiked ? 'true' : 'false' }}"
                    data-like-id="{{ $u->id }}" 
                    data-like-url="{{ route('users.like', $u->id) }}"
                    >
                    <img src="{{ asset('assets/img/icon-card-1.png') }}" alt="like">
                    </button>
                    <button class="action js-chat-open"
                            title="Message"
                            data-chat-user-id="{{ $u->id }}">
                      <img src="{{ asset('assets/img/icon-card-2.png') }}" alt="chat">
                    </button>
                      
                    <button
                        type="button"
                        class="action js-refresh-btn"
                        title="Refresh"
                        data-refresh-url="{{ route('cards.next') }}"
                    >
                      <img src="{{ asset('assets/img/icon-card-3.png') }}" alt="refresh">
                    </button>
                  </div>
                </div>

                <div class="card-text">
                  <div class="card-title fw-semibold text-truncate" style="color:#707070">
                    {{-- {{ $u->name }} @if(!empty($u->age)) ({{ $u->age }}) @endif --}}
                    <span class="card-name">{{ $u->name }}</span>
                    @if(!empty($u->age))
                      <span class="card-age">({{ $u->age }})</span>
                    @endif
                  </div>

                  <p class="card-desc" style="color:#707070">
                    {{ \Illuminate\Support\Str::limit($u->about ?? '', 120) }}
                  </p>

                  @php
                      $hobby       = trim((string) $u->hobby);
                      $lifestyle   = trim((string) $u->lifestyle);
                      $personality = trim((string) $u->personality);
                  @endphp

                  <div class="card-tags">
                      @if(filled($hobby))
                          <span class="tag" style="color:#707070;background:#f5f5f5">{{ $hobby }}</span>
                      @endif
                      @if(filled($lifestyle))
                          <span class="tag" style="color:#707070;background:#f5f5f5">{{ $lifestyle }}</span>
                      @endif
                      @if(filled($personality))
                          <span class="tag" style="color:#707070;background:#f5f5f5">{{ $personality }}</span>
                      @endif
                  </div>
                  
                </div>
              </div>

                @auth
                  <a href="{{ route('users.show', $u) }}" class="stretched-link" aria-label="Buka profil {{ $u->name }}"></a>
                @else
                  <button type="button" class="stretched-link" data-modal-open="login-modal" aria-label="Masuk untuk melihat profil {{ $u->name }}"></button>
                  <div class="lock-badge">Log in to see full profile</div>
                @endauth

            </div>
          </div>
        </div>
