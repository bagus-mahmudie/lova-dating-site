@extends('layouts.app')
@section('title','Lova | My Profile')

@section('content')
@php
use Illuminate\Support\Str;
// (opsional, hanya kalau kamu mau pakai Storage::url langsung tanpa accessor)
// use Illuminate\Support\Facades\Storage;

$u = $user ?? auth()->user();
$age         = $u->age ? $u->age : 28;
$displayName = trim($u->name) ? trim($u->name).', '.$age : 'Lorem ipsum, 28';
$headline    = $u->headline ?: '...';
$location    = $u->location ?: 'Location';
$about       = $u->about ?: '...';
$cover     = $u->cover_image_url;  // <-- accessor
$avatarUrl = $u->avatar_url;       // <-- accessor

$social = is_array($u->social_links) ? $u->social_links : (json_decode($u->social_links ?? '[]', true) ?: []);

$handle = function (?string $url) {
    if (!$url) return null;
    $p = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
    return $p ? '@'.Str::before($p, '/') : null;
};

$split = fn($v) => $v ? array_values(array_filter(array_map('trim', preg_split('/[;,|]+/',$v)))) : [];
$preferences = array_unique(array_merge(
    $split($u->hobby), $split($u->lifestyle), $split($u->personality), $split($u->love_language)
));
 $u = $user ?? auth()->user();
  $isOwner = auth()->check() && auth()->id() === ($u->id ?? null);
@endphp


<section class="container profile-screen">
  {{-- ===== ROW ATAS: Cover (kiri) + Social card (kanan) ===== --}}
  <div class="profile-top-grid">
    {{-- Cover + overlay --}}
    <div class="profile-header">
      <div class="profile-cover">
        <img src="{{ $user->cover_image_url ?? asset('assets/img/cover-default.png') }}" alt="cover">
        <div class="cover-actions is-pills">
          @if(!$isOwner)
          <a class="cover-pill readonly" href="#" aria-label="Likes">
            <span class="pill-ico"><img src="{{ asset('assets/img/icons/icon-navbar-2.png') }}" alt="like"></span>
            <span class="js-like-count" data-like-count>{{ $u->likes_count ?? 0 }} Likes</span>
          </a>
          <button class="cover-pill action js-chat-open" 
                  title="Message" 
                  aria-label="Message"
                  data-chat-user-id="{{ $u->id }}">
            <span class="pill-ico">
              <img src="{{ asset('assets/img/icons/icon-navbar-3.png') }}" alt="chat">
            </span>
            <span class="pill-text">Message</span>
          </button>
          @endif
          @if($isOwner)
          <button class="ov-btn ov-btn-delete" type="button" title="Delete Profile"
         data-modal-open="delete-account-modal" aria-haspopup="dialog">
            <i class="bi bi-trash"></i>
          </button>
          <button class="ov-btn ov-btn-edit" type="button" data-modal-open="edit-profile-modal" title="Edit Profile">
            <i class="bi bi-pencil"></i>
          </button>
          @endif
        </div>
      </div>

      {{-- Overlay hitam: Avatar | Location+Name+Job | About Us --}}
      <div class="profile-overlay-wrap">
        <div class="profile-overlay">
          <div class="overlay-grid">
            {{-- (1) Avatar di dalam overlay --}}
            <div class="ov-avatar {{ $avatarUrl ?? asset('assets/img/icons/card-placeholder.png') }}">

            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $u->name }}"
                    referrerpolicy="no-referrer" loading="lazy">
            @else
                <span class="ph-ico"></span>
            @endif
            </div>

            {{-- (2) Location + Name, Age, Job --}}
            <div class="ov-main">
              <div class="ov-location pill">
                <i class="bi bi-geo-alt"></i> {{ $location }}
              </div>
              <div class="ov-name">{{ $displayName }}</div>
              <div class="ov-job">{{ $u->job ?: $headline }}</div>
            </div>

            {{-- (3) About Us --}}
            <div class="ov-about">
              <div class="ov-about-title">About Me</div>
              <div class="ov-about-text">{{ $about }}</div>
              <a href="#" class="about-read-more d-none" data-modal-open="about-modal">Read More</a>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    {{-- Social media (kanan) --}}
    <aside class="profile-card sm-card">
      @php
        $social = is_array($social ?? null) ? $social : (json_decode($user->social_links ?? '[]', true) ?: []);

        $igLink  = $social['instagram'] ?? null;
        $fbLink  = $social['facebook']  ?? null;
        $ttLink  = $social['tiktok']    ?? null;

        // Username untuk prefilling form (tanpa @ / path)
        $igUser = $igLink ? ltrim(parse_url($igLink, PHP_URL_PATH) ?? '', '/') : '';
        $fbUser = $fbLink ? ltrim(parse_url($fbLink, PHP_URL_PATH) ?? '', '/') : '';
        $ttUser = $ttLink ? ltrim(parse_url($ttLink, PHP_URL_PATH) ?? '', '/') : '';
        $ttUser = $ttUser ? ltrim($ttUser, '@') : '';
      @endphp

      <div class="card-head">
        <h4>Social media</h4>
        {{-- tombol pembuka modal --}}
        @if($isOwner)
        <button class="icon-btn" type="button"
                data-modal-open="social-modal" aria-label="Edit social media">
          <i class="bi bi-pencil"></i>
        </button>
        @endif
      </div>
      <div class="card-body">
        @if($igLink || $fbLink || $ttLink)
          <div class="socials-list socials-compact">
            {{-- 1) Instagram --}}
            @if($igLink)
              <a class="soc-card" href="{{ $igLink }}" target="_blank" rel="noopener">
                <span class="soc-ico"><img src="{{ asset('assets/img/icons/social-instagram.png') }}" alt="like"></span>
                <div>
                  <small>Instagram</small>
                  <span class="handle">{{ '@'.$igUser }}</span>
                </div>
              </a>
            @endif
            {{-- 2) TikTok --}}
            @if($ttLink)
              <a class="soc-card" href="{{ $ttLink }}" target="_blank" rel="noopener">
                <span class="soc-ico"><img src="{{ asset('assets/img/icons/social-tiktok.png') }}" alt="like"></span>
                <div>
                  <small>Tiktok</small>
                  <span class="handle">{{ '@'.$ttUser }}</span>
                </div>
              </a>
            @endif
            {{-- 3) Facebook --}}
            @if($fbLink)
              <a class="soc-card" href="{{ $fbLink }}" target="_blank" rel="noopener">
                <span class="soc-ico"><img src="{{ asset('assets/img/icons/social-facebook.png') }}" alt="like"></span>
                <div>
                  <small>Facebook</small>
                  <span class="handle">{{ '@'.$fbUser }}</span>
                </div>
              </a>
            @endif
          </div>
        @else
          <div class="text-muted">No social links yet.</div>
        @endif
      </div>
    </aside>
  </div>

  {{-- ===== ROW BAWAH: Factfile | Album | Preferences ===== --}}
  <div class="profile-bottom-grid">
    {{-- Factfile --}}
    <section class="profile-card">
      <div class="card-head">
        <h4>Factfile</h4>
        @if($isOwner)
        <button class="icon-btn" type="button" data-modal-open="factfile-modal" aria-label="Edit Factfile">
          <i class="bi bi-pencil"></i>
        </button>
        @endif
      </div>
      <div class="card-body">
        <ul class="fact-list">
          <li><span class="fi-ico"><img src="{{ asset('assets/img/icons/icon-fact-1.png') }}" alt="like"></span><span class="fi-label">Gender</span><b>{{ $u->gender ?: '-' }}</b></li>
          <li><span class="fi-ico"><img src="{{ asset('assets/img/icons/icon-fact-2.png') }}" alt="like"></span><span class="fi-label">Religion</span><b>{{ $u->religion ?: '-' }}</b></li>
          <li><span class="fi-ico"><img src="{{ asset('assets/img/icons/icon-fact-3.png') }}" alt="like"></span><span class="fi-label">Languages</span><b>{{ $u->languages ?: '-' }}</b></li>
        </ul>
      </div>
    </section>

    {{-- Album Photo --}}
    <section class="profile-card">
      <div class="card-head"><h4>Album Photo</h4></div>
        <div class="card-body">
            @php
              $photos   = $u->album_photos ?? [];
              $isOwner  = auth()->check() && auth()->id() === $u->id;
              $filled   = array_values(array_filter($photos)); // hanya URL yang ada
            @endphp

            @if(!$isOwner)
            {{-- Pengunjung --}}
            @if(count($filled) === 0)
              <p class="text-muted m-0">No album photos yet.</p>
            @else
              <div class="album-grid">
                @foreach($filled as $url)
                  <div class="album-card">
                    <img class="album-img" src="{{ $url }}" alt="Album photo">
                  </div>
                @endforeach
              </div>
            @endif

            @else
              {{-- Pemilik akun: 3 slot seperti sebelumnya --}}
              <div class="album-grid">
                {{-- Slot 1 --}}
                <div class="album-card">
                  @if(!empty($photos['1']))
                    <img class="album-img" src="{{ $photos['1'] }}" alt="Album 1">
                    <div class="album-actions">
                      <form action="{{ route('profile.album.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                        @csrf @method('DELETE')
                        <input type="hidden" name="slot" value="1">
                        <button type="submit" class="icon-btn" title="Delete"><i class="bi bi-trash"></i></button>
                      </form>
                      <button type="button" class="icon-btn" title="Edit" data-modal-open="album-modal-1"><i class="bi bi-pencil"></i></button>
                    </div>
                  @else
                    <button type="button" class="album-plus" data-modal-open="album-modal-1"><span>+</span></button>
                  @endif
                </div>

                {{-- Slot 2 --}}
                <div class="album-card">
                  @if(!empty($photos['2']))
                    <img class="album-img" src="{{ $photos['2'] }}" alt="Album 2">
                    <div class="album-actions">
                      <form action="{{ route('profile.album.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                        @csrf @method('DELETE')
                        <input type="hidden" name="slot" value="2">
                        <button type="submit" class="icon-btn" title="Delete"><i class="bi bi-trash"></i></button>
                      </form>
                      <button type="button" class="icon-btn" title="Edit" data-modal-open="album-modal-2"><i class="bi bi-pencil"></i></button>
                    </div>
                  @else
                    <button type="button" class="album-plus" data-modal-open="album-modal-2"><span>+</span></button>
                  @endif
                </div>

                {{-- Slot 3 --}}
                <div class="album-card">
                  @if(!empty($photos['3']))
                    <img class="album-img" src="{{ $photos['3'] }}" alt="Album 3">
                    <div class="album-actions">
                      <form action="{{ route('profile.album.delete') }}" method="POST" onsubmit="return confirm('Hapus foto ini?');">
                        @csrf @method('DELETE')
                        <input type="hidden" name="slot" value="3">
                        <button type="submit" class="icon-btn" title="Delete"><i class="bi bi-trash"></i></button>
                      </form>
                      <button type="button" class="icon-btn" title="Edit" data-modal-open="album-modal-3"><i class="bi bi-pencil"></i></button>
                    </div>
                  @else
                    <button type="button" class="album-plus" data-modal-open="album-modal-3"><span>+</span></button>
                  @endif
                </div>
              </div>
            @endif

        </div>
    </section>

    {{-- Preferences --}}
    <section class="profile-card">
      <div class="card-head">
        <h4>Preferences</h4>
        @if($isOwner)
        <button class="icon-btn" type="button"
                data-modal-open="pref-modal" aria-label="Edit preferences">
          <i class="bi bi-pencil"></i>
        </button>
        @endif
      </div>
      <div class="card-body">
        <div class="chip-wrap">
          @forelse($preferences as $pref)
            <span class="chip">{{ $pref }}</span>
          @empty
            <span class="text-muted">No preferences yet.</span>
          @endforelse
        </div>
      </div>
    </section>

  </div>
</section>

{{-- Modal Social Media --}}
<div id="social-modal" class="modal-backdrop" aria-hidden="true">
  <div class="modal-sheet" role="dialog" aria-modal="true" aria-labelledby="socialModalTitle">
    <form method="POST" action="{{ route('profile.social.update') }}" class="modal-form">
      @csrf
      @method('PATCH')

      <div class="modal-head">
        <h5 id="prefModalTitle">Social Media</h5>
        <button type="button" class="modal-close" data-modal-close aria-label="Close">×</button>
      </div>

      <div class="modal-body">
        <label class="modal-label">Instagram</label>
        <input type="text" name="instagram" class="form-control"
               placeholder="Add your Instagram username"
               value="{{ old('instagram', $igUser ? ltrim($igUser,'@') : '') }}">

        <label class="modal-label">Tiktok</label>
        <input type="text" name="tiktok" class="form-control"
               placeholder="Add your TikTok username"
               value="{{ old('tiktok', $ttUser ? ltrim($ttUser,'@') : '') }}">

        <label class="modal-label">Facebook</label>
        <input type="text" name="facebook" class="form-control"
                placeholder="Add your Facebook username"
                value="{{ old('facebook', $fbUser ? ltrim($fbUser,'@') : '') }}">
      </div>

      <div class="modal-foot">
        <button type="submit" class="btn-gradient w-100">
          Update <span style="margin-left:8px">→</span>
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Preferences --}}
@php
  $u = $user ?? auth()->user();
  $hobbyOld       = old('hobby', $u->hobby);
  $lifestyleOld   = old('lifestyle', $u->lifestyle);
  $personalityOld = old('personality', $u->personality);
  $loveOld        = old('love_language', $u->love_language);
@endphp

<div id="pref-modal" class="modal-backdrop" aria-hidden="true">
  <div class="modal-sheet pref-modal" role="dialog" aria-modal="true" aria-labelledby="prefModalTitle">
    <form method="POST" action="{{ route('profile.preferences.update') }}" class="modal-form">
      @csrf
      @method('PATCH')

      <div class="modal-head">
        <h5 id="prefModalTitle">Preferences</h5>
        <button type="button" class="modal-close" data-modal-close aria-label="Close">×</button>
      </div>

      <div class="modal-body">
        <div class="pref-accordion">

          {{-- Hobbies --}}
          <section class="pref-item is-open">
            <button class="pref-head" type="button">
              Hobbies <i class="bi bi-chevron-down"></i>
            </button>
            <div class="pref-panel">
              @foreach ([
                'Travel','Music','Reading','Sports','Gaming','Foodie',
                'Photography','Movies','Art & Design','Animals',
                'Hiking','Tech'
              ] as $opt)
                <label class="pref-option">
                  <input type="radio" name="hobby" value="{{ $opt }}" @checked($hobbyOld===$opt)>
                  <span>{{ $opt }}</span>
                </label>
              @endforeach
            </div>
          </section>

          {{-- Lifestyle --}}
          <section class="pref-item">
            <button class="pref-head" type="button">
              Lifestyle <i class="bi bi-chevron-down"></i>
            </button>
            <div class="pref-panel">
              @foreach ([
                'Non-smoker','Occasionally','Regular smoker',
                'Non-drinker','Social drinker','Regular drinker',
                'Omnivore','Vegetarian'
              ] as $opt)
                <label class="pref-option">
                  <input type="radio" name="lifestyle" value="{{ $opt }}" @checked($lifestyleOld===$opt)>
                  <span>{{ $opt }}</span>
                </label>
              @endforeach
            </div>
          </section>

          {{-- Personality --}}
          <section class="pref-item">
            <button class="pref-head" type="button">
              Personality <i class="bi bi-chevron-down"></i>
            </button>
            <div class="pref-panel">
              @foreach ([
                'Introvert','Extrovert','Ambivert','Adventurous',
                'Easygoing','Ambitious','Creative','Funny'
              ] as $opt)
                <label class="pref-option">
                  <input type="radio" name="personality" value="{{ $opt }}" @checked($personalityOld===$opt)>
                  <span>{{ $opt }}</span>
                </label>
              @endforeach
            </div>
          </section>

          {{-- Love Language --}}
          <section class="pref-item">
            <button class="pref-head" type="button">
              Love Language <i class="bi bi-chevron-down"></i>
            </button>
            <div class="pref-panel">
              @foreach ([
                'Words of Affirmation','Quality Time',
                'Acts of Service','Receiving Gifts','Physical Touch'
              ] as $opt)
                <label class="pref-option">
                  <input type="radio" name="love_language" value="{{ $opt }}" @checked($loveOld===$opt)>
                  <span>{{ $opt }}</span>
                </label>
              @endforeach
            </div>
          </section>

        </div> {{-- /.pref-accordion --}}
      </div>

      <div class="modal-foot">
        <button type="submit" class="btn-gradient w-100">
          Update <span style="margin-left:8px">→</span>
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Edit Profile --}}
@php($u = $user ?? auth()->user())
<div id="edit-profile-modal" class="modal-backdrop" aria-hidden="true">
  <div class="modal-sheet" role="dialog" aria-modal="true" aria-labelledby="editProfileTitle">
    <form method="POST" action="{{ route('updateInformation') }}" enctype="multipart/form-data" class="modal-form">
      @csrf @method('PATCH')

      <div class="modal-head">
        <h5 id="editProfileTitle">Edit Profile Information</h5>
        <button type="button" class="modal-close" data-modal-close aria-label="Close">×</button>
      </div>

      <div class="modal-body">
        {{-- Avatar --}}
        <label class="modal-label">Upload profile photo</label>
        <label class="avatar-inline-upload is-avatar" for="avatar-input">
          <input id="avatar-input" name="avatar" type="file" accept="image/*" hidden>
          <div class="avatar-thumb">
          {{-- Placeholder inisial (muncul saat belum ada foto) --}}
          <span class="avatar-initial" data-preview="avatar">
            {{ strtoupper(\Illuminate\Support\Str::substr($u->name ?: 'A', 0, 1)) }}
          </span>

          {{-- Preview gambar (muncul saat ada foto / setelah pilih file) --}}
          <img class="avatar-img" data-preview-img="avatar"
              src="{{ $u->avatar_url ?? '' }}" alt=""
              style="{{ $u->avatar_url ? '' : 'display:none' }}">
        </div>

        <span class="btn-soft">Click to replace</span>
        </label>

        {{-- Cover --}}
        <label class="modal-label">Upload cover photo</label>
        <label class="upload-box is-large" for="cover-input">
          <input id="cover-input" name="cover_image" type="file" accept="image/*" hidden>
          <div class="upload-inner" data-preview="cover">
            <span class="upload-ico"><i class="bi bi-camera"></i></span>
            <b>Add Photo</b>
            <small>Upload your best photos to complete your profile.</small>
          </div>
          <img class="upload-preview" data-preview-img="cover" src="{{ $u->cover_image_url ?? '' }}" alt="" style="{{ $u->cover_image_url ? '' : 'display:none' }}">
        </label>

        {{-- Fields --}}
        <label class="modal-label">Name</label>
        <input class="form-control" type="text" name="name" value="{{ old('name', $u->name) }}" placeholder="Enter full name" maxlength="16" oninput="this.value = this.value.slice(0, 16)">

        <label class="modal-label">Age</label>
        <input class="form-control" type="number" name="age" value="{{ old('age', $u->age) }}" min="16" max="120" placeholder="Enter your age">

        <label class="modal-label">Occupation</label>
        <input class="form-control" type="text" name="job" value="{{ old('job', $u->job) }}" placeholder="Enter your job" maxlength="57" oninput="this.value = this.value.slice(0, 57)" >

        <label class="modal-label">Location</label>
        <input class="form-control" type="text" name="location" value="{{ old('location', $u->location) }}" placeholder="Enter your city">

        <label class="modal-label">About Us</label>
        <textarea class="form-control" rows="5" name="about" placeholder="Write about you">{{ old('about', $u->about) }}</textarea>
      </div>

      <div class="modal-foot">
        <button type="submit" class="btn-gradient w-100">
          Update <span style="margin-left:8px">→</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ==== Modal: Delete Account (ringan, sesuai mockup) ==== -->
<div id="delete-account-modal" class="modal-lite" aria-hidden="true">
  <div class="modal-lite__dialog" role="dialog" aria-modal="true" aria-labelledby="del-head">
    <div class="modal-lite__head">
      <h5 id="del-head">Are you sure?</h5>
      <button type="button" class="modal-lite__close" data-modal-close aria-label="Close">
        <i class="bi bi-x"></i>
      </button>
    </div>

    <div class="modal-body">
      <p style="margin-bottom:12px">
        Deleting your account will permanently remove all data, messages, and matches.
        Once deleted, your profile cannot be restored and the action cannot be undone.
      </p>

      <label class="modal-label" for="del-notes">Additional Information (Optional):</label>
      <textarea id="del-notes" class="form-control" rows="4" placeholder=""></textarea>
    </div>

    <div class="modal-foot">
      <form method="POST" action="{{ url('/profile/delete') }}">
        @csrf
        @method('DELETE')
        <input type="hidden" name="reason" id="del-notes-hidden">
        <button type="submit" class="btn btn-gradient" id="del-submit">
          Delete <span aria-hidden="true">→</span>
        </button>
      </form>
    </div>
  </div>
</div>

{{-- Modal: Edit Factfile --}}
@php($u = $user ?? auth()->user())
<div id="factfile-modal" class="modal-backdrop" aria-hidden="true">
  <div class="modal-sheet" role="dialog" aria-modal="true">
    <form class="modal-form" method="POST" action="{{ route('profile.factfile.update') }}">
      @csrf
      <div class="modal-head">
        <h5>Factfile</h5>
        <button type="button" class="modal-close" data-modal-close><i class="bi bi-x"></i></button>
      </div>

      <div class="modal-body">
        <label class="modal-label">Gender</label>
        <input name="gender" class="form-control" placeholder="Enter your gender"
               value="{{ old('gender', $u->gender) }}">

        <label class="modal-label">Religion</label>
        <input name="religion" class="form-control" placeholder="Enter your Religion"
               value="{{ old('religion', $u->religion) }}">

        <label class="modal-label">Languages</label>
        <input name="languages" class="form-control" placeholder="Enter your Languages"
               value="{{ old('languages', $u->languages) }}">
      </div>

      <div class="modal-foot">
        <button class="btn-gradient w-100" type="submit">Update <span style="margin-left:8px">→</span></button>
      </div>
    </form>
  </div>
</div>

<!-- ==== Modal: About Full ==== -->
<div id="about-modal" class="modal-backdrop" aria-hidden="true">
  <div class="modal-sheet" role="dialog" aria-modal="true">
    <form class="modal-form" onsubmit="return false;">
      <div class="modal-head">
        <h5>About Me</h5>
        <button type="button" class="modal-close" data-modal-close><i class="bi bi-x"></i></button>
      </div>
      <div class="modal-body">
        <div id="about-full" class="about-full"></div>
      </div>
    </form>
  </div>
</div>

{{-- ================= Modals ================= --}}
{{-- ===== Modal 1 ===== --}}
<div id="album-modal-1" class="modal-lite" aria-hidden="true">
  <div class="modal-lite__dialog">
    <div class="modal-lite__head">
      <h5>Upload Your Photos</h5>
      <button type="button" class="modal-lite__close" data-modal-close>&times;</button>
    </div>
    <form action="{{ route('profile.album.update') }}" method="POST" enctype="multipart/form-data" id="formAlbum1">
      @csrf
      <input type="hidden" name="slot" value="1">
      <label for="album1-input" class="upload-box-lg">
        <input id="album1-input" name="photo" type="file" accept="image/*" hidden>
        <span class="ub-icon"><i class="bi bi-camera"></i></span>
        <span class="ub-btn">Add Photo</span>
        <small>Upload your best photos to complete your profile.</small>
        <img class="ub-preview" id="album1-preview" alt="" style="display:none;">
      </label>
      <button type="submit" class="btn-gradient w-100 mt-3">Update →</button>
    </form>
  </div>
</div>

{{-- ===== Modal 2 ===== --}}
<div id="album-modal-2" class="modal-lite" aria-hidden="true">
  <div class="modal-lite__dialog">
    <div class="modal-lite__head">
      <h5>Upload Your Photos</h5>
      <button type="button" class="modal-lite__close" data-modal-close>&times;</button>
    </div>
    <form action="{{ route('profile.album.update') }}" method="POST" enctype="multipart/form-data" id="formAlbum2">
      @csrf
      <input type="hidden" name="slot" value="2">
      <label for="album2-input" class="upload-box-lg">
        <input id="album2-input" name="photo" type="file" accept="image/*" hidden>
        <span class="ub-icon"><i class="bi bi-camera"></i></span>
        <span class="ub-btn">Add Photo</span>
        <small>Upload your best photos to complete your profile.</small>
        <img class="ub-preview" id="album2-preview" alt="" style="display:none;">
      </label>
      <button type="submit" class="btn-gradient w-100 mt-3">Update →</button>
    </form>
  </div>
</div>

{{-- ===== Modal 3 ===== --}}
<div id="album-modal-3" class="modal-lite" aria-hidden="true">
  <div class="modal-lite__dialog">
    <div class="modal-lite__head">
      <h5>Upload Your Photos</h5>
      <button type="button" class="modal-lite__close" data-modal-close>&times;</button>
    </div>
    <form action="{{ route('profile.album.update') }}" method="POST" enctype="multipart/form-data" id="formAlbum3">
      @csrf
      <input type="hidden" name="slot" value="3">
      <label for="album3-input" class="upload-box-lg">
        <input id="album3-input" name="photo" type="file" accept="image/*" hidden>
        <span class="ub-icon"><i class="bi bi-camera"></i></span>
        <span class="ub-btn">Add Photo</span>
        <small>Upload your best photos to complete your profile.</small>
        <img class="ub-preview" id="album3-preview" alt="" style="display:none;">
      </label>
      <button type="submit" class="btn-gradient w-100 mt-3">Update →</button>
    </form>
  </div>
</div>

@endsection
