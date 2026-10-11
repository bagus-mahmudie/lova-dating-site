// Js Testimoni Carousel
(function () {
  const ready = (fn) =>
    document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn);

  ready(function () {
    const $c = $('.testi-carousel');
    if (!$c.length || typeof $c.owlCarousel !== 'function') return;

    const getVar = (name, el=document.documentElement) =>
      parseInt(getComputedStyle(el).getPropertyValue(name)) || 0;

    const setPeekVar = (px) => {
      const section = $c.closest('.testi-area').get(0);
      if (section) section.style.setProperty('--peek', px + 'px');
    };

    const computePeek = () => {
      const card = getVar('--card');
      const gap  = getVar('--gap');
      return Math.round(card / 2 + gap);
    };

    let peek = computePeek();
    setPeekVar(peek);

    const gap = getVar('--gap');

    function init(){
      $c.owlCarousel({
        loop: true,
        rewind: false,
        slideBy: 1,
        autoWidth: true,
        margin: gap,
        dots: false,
        nav: false,
        stagePadding: peek,
        smartSpeed: 500,
        autoHeight: true ,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: false
      });
    }

    init();

    $('.testi-nav[data-dir="prev"]').on('click', () => $c.trigger('prev.owl.carousel'));
    $('.testi-nav[data-dir="next"]').on('click', () => $c.trigger('next.owl.carousel'));

    let t;
    $(window).on('resize', function(){
      clearTimeout(t);
      t = setTimeout(function(){
        const newPeek = computePeek();
        if(newPeek !== peek){
          peek = newPeek;
          setPeekVar(peek);
          $c.trigger('destroy.owl.carousel');
          $c.find('.owl-stage, .owl-item').attr('style','');
          init();
        }
      }, 120);
    });
  });
})();

// Js Modal Pop Up SignIn & SignUp (+ klik card blur)
document.addEventListener('DOMContentLoaded', () => {
  const paneIn  = document.querySelector('#authSignIn');
  const paneUp  = document.querySelector('#authSignUp');

  function showPane(which){
    if (which === 'signin') {
      paneIn && paneIn.classList.remove('d-none');
      paneUp && paneUp.classList.add('d-none');
    } else {
      paneUp && paneUp.classList.remove('d-none');
      paneIn && paneIn.classList.add('d-none');
    }
  }

  document.querySelectorAll('.js-auth-link').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const t = a.dataset.authLink;
      showPane(t);
    });
  });

  document.querySelectorAll('.js-switch-top').forEach(btn => {
    btn.addEventListener('click', e => {
      e.preventDefault();
      const target = btn.dataset.switch === 'signup'
        ? document.querySelector('#linkSignUp')
        : document.querySelector('#linkSignIn');
      if (target) target.click();
    });
  });

  document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.querySelector(btn.dataset.target);
      const icon  = btn.querySelector('i');
      if (!input) return;
      input.type = (input.type === 'password') ? 'text' : 'password';
      if (icon) {
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
      }
    });
  });

  const authModalEl = document.getElementById('authModal') || document.getElementById('auth-modal');
  let authModal = null;
  if (authModalEl && window.bootstrap && bootstrap.Modal) {
    authModal = bootstrap.Modal.getOrCreateInstance(authModalEl);
  }

  // fungsi buka modal
  function openAuth() {
    try { if (typeof showPane === 'function') showPane('signin'); } catch (_) {}
    if (authModal) authModal.show();
    else if (authModalEl) { 
      authModalEl.classList.add('is-open');
      authModalEl.setAttribute('aria-hidden', 'false');
    }
  }

  document.addEventListener('click', (e) => {
    if (e.target.closest('#authModal, #auth-modal')) return;
    if (e.target.closest('a, button, input, textarea, select, label,[data-ignore-auth]')) return;

    const card = e.target.closest('.guest-locked');
    if (!card) return;

    e.preventDefault();  
    e.stopPropagation(); 
    openAuth();
  });

  const params = new URLSearchParams(window.location.search);
  if (params.get('login') === '1') {
    openAuth();
    history.replaceState({}, '', window.location.pathname);
  }

});

// Js FAQ accordion
(() => {
  const accordions = document.querySelectorAll('.faq-accordion');
  if (!accordions.length) return;

  accordions.forEach((acc) => {
    acc.querySelectorAll('.faq-item').forEach((item) => {
      const head = item.querySelector('.faq-head');
      if (head) head.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');
    });

    acc.addEventListener('click', (e) => {
      const head = e.target.closest('.faq-head');
      if (!head || !acc.contains(head)) return;

      const item = head.parentElement;
      acc.querySelectorAll('.faq-item.is-open').forEach((opened) => {
        if (opened !== item) {
          opened.classList.remove('is-open');
          opened.querySelector('.faq-head')?.setAttribute('aria-expanded', 'false');
        }
      });
      const open = item.classList.toggle('is-open');
      head.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  });
})();

// Js Active Navbar per Page
(() => {
  const links = document.querySelectorAll('.nav-main .nav-link, .navbar .nav-link, .app-navbar__pill');
  if (!links.length) return;

  const norm = (p) => (p || '/').replace(location.origin, '').replace(/\/+$/, '') || '/';
  const current = norm(location.pathname);

  links.forEach((a) => {
    const href = a.getAttribute('href'); if (!href) return;
    const path = norm(href.startsWith('http') ? new URL(href, location.origin).pathname : href);

    if (path === current) {
      a.classList.add('is-active');
      a.setAttribute('aria-current', 'page');
      return;
    }

    if (a.dataset.prefix === 'startsWith' && current.startsWith(path) && path !== '/') {
      a.classList.add('is-active');
    }
  });
})();

// Js Modal Pop Up Edit Social Media Profile
(function(){
    const openers = document.querySelectorAll('[data-modal-open="social-modal"]');
    const modal   = document.getElementById('social-modal');
    if(!modal) return;

    const closeBtn = modal.querySelector('[data-modal-close]');

    function open()  { modal.classList.add('is-open'); modal.setAttribute('aria-hidden','false'); }
    function close() { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); }

    openers.forEach(btn => btn.addEventListener('click', open));
    closeBtn?.addEventListener('click', close);
    modal.addEventListener('click', (e) => { if(e.target === modal) close(); });

    document.addEventListener('keydown', (e) => { if(e.key === 'Escape' && modal.classList.contains('is-open')) close(); });
})();

// Js Modal Pop Up Edit Preferences Profile
(() => {
  const openers = document.querySelectorAll('[data-modal-open="pref-modal"]');
  const modal   = document.getElementById('pref-modal');
  if (!modal) return;

  const closeBtn = modal.querySelector('[data-modal-close]');

  // ======== hitung tinggi maksimal 6 opsi pertama ========
  function getMaxSixHeight(panel){
    const opts = panel.querySelectorAll('.pref-option');
    if (!opts.length) {
      const ps = getComputedStyle(panel);
      return panel.scrollHeight + parseFloat(ps.paddingTop||0) + parseFloat(ps.paddingBottom||0);
    }
    let h = 0, n = Math.min(opts.length, 6);
    for (let i=0; i<n; i++){
      const el = opts[i];
      const cs = getComputedStyle(el);
      h += el.offsetHeight + parseFloat(cs.marginTop||0) + parseFloat(cs.marginBottom||0);
    }
    const ps = getComputedStyle(panel);
    h += parseFloat(ps.paddingTop||0) + parseFloat(ps.paddingBottom||0);
    return Math.ceil(h + 2); 
  }

  // ======== fungsi buka & tutup modal ========
  const open  = () => { 
    modal.classList.add('is-open');  
    modal.setAttribute('aria-hidden','false'); 

    const firstItem = modal.querySelector('.pref-item.is-open');
    if (firstItem) {
      const panel = firstItem.querySelector('.pref-panel');
      if (panel){
        const cap = getMaxSixHeight(panel);
        panel.style.maxHeight = Math.min(panel.scrollHeight, cap) + 'px';
        panel.style.overflow  = 'auto';
      }
    }
  };

  const close = () => { 
    modal.classList.remove('is-open'); 
    modal.setAttribute('aria-hidden','true'); 
  };

  openers.forEach(btn => btn.addEventListener('click', open));
  closeBtn?.addEventListener('click', close);
  modal.addEventListener('click', (e) => { if (e.target === modal) close(); });
  document.addEventListener('keydown', (e) => { 
    if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); 
  });

  // ======== Accordion logic ========
  const items = Array.from(modal.querySelectorAll('.pref-accordion .pref-item'));

  function normalizeIcon(i){
    if(!i) return;
    if(i.classList.contains('bi-chevron-right') || i.classList.contains('bi-chevron-left')){
      i.classList.remove('bi-chevron-right','bi-chevron-left');
      i.classList.add('bi-chevron-down');
    }
  }
  function setIcon(i, open){
    if(!i) return;
    i.classList.toggle('bi-chevron-down', !open);
    i.classList.toggle('bi-chevron-up',   open);
  }

  function openItem(item){
    if(item.classList.contains('is-open')) return;
    const panel = item.querySelector('.pref-panel');
    const icon  = item.querySelector('.pref-head i');
    if(!panel) return;

    item.classList.add('is-open');
    const cap = getMaxSixHeight(panel);
    panel.style.maxHeight = Math.min(panel.scrollHeight, cap) + 'px';
    panel.style.overflow  = 'auto'; 
    setIcon(icon, true);
  }

  function closeItem(item){
    if(!item.classList.contains('is-open')) return;
    const panel = item.querySelector('.pref-panel');
    const icon  = item.querySelector('.pref-head i');
    if(!panel) return;

    panel.style.maxHeight = panel.scrollHeight + 'px';
    panel.offsetHeight; // reflow
    item.classList.remove('is-open');
    panel.style.maxHeight = '0px';
    panel.style.overflow  = 'hidden';
    setIcon(icon, false);
  }

  modal.addEventListener('click', (e) => {
    const head = e.target.closest('.pref-head');
    if(!head || !modal.contains(head)) return;
    const item = head.closest('.pref-item');
    if(!item) return;

    const isOpen = item.classList.contains('is-open');
    items.forEach(it => { if(it !== item) closeItem(it); });
    isOpen ? closeItem(item) : openItem(item);
  });

  // ======== init state ========
  items.forEach(it => {
    normalizeIcon(it.querySelector('.pref-head i'));
    const panel = it.querySelector('.pref-panel');
    if(!panel) return;

    if(it.classList.contains('is-open')){
      const cap = getMaxSixHeight(panel);
      panel.style.maxHeight = Math.min(panel.scrollHeight, cap) + 'px';
      panel.style.overflow  = 'auto';
      setIcon(it.querySelector('.pref-head i'), true);
    }else{
      panel.style.maxHeight = '0px';
      panel.style.overflow  = 'hidden';
      setIcon(it.querySelector('.pref-head i'), false);
    }
  });

  if(!modal.querySelector('.pref-item.is-open') && items[0]){
    openItem(items[0]);
  }

  // ======== sesuaikan tinggi saat resize ========
  window.addEventListener('resize', () => {
    items.forEach(it => {
      if(it.classList.contains('is-open')){
        const panel = it.querySelector('.pref-panel');
        if(panel){
          const cap = getMaxSixHeight(panel);
          panel.style.maxHeight = Math.min(panel.scrollHeight, cap) + 'px';
        }
      }
    });
  });
})();

// Js Modal Pop Up Edit FactFile
(() => {
  const openers = document.querySelectorAll('[data-modal-open="factfile-modal"]');
  const modal   = document.getElementById('factfile-modal');
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(btn => btn.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });
})();

// Js Modal Open / Close
(() => {
  const modalId = 'edit-profile-modal';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal   = document.getElementById(modalId);
  if(!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(b => b.addEventListener('click', open));
  modal.querySelector('[data-modal-close]')?.addEventListener('click', close);
  modal.addEventListener('click', e => { if(e.target === modal) close(); });
  document.addEventListener('keydown', e => { if(e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  // Image preview (avatar & cover)
  function bindPreview(inputId, key){
    const input   = document.getElementById(inputId);
    if(!input) return;
    const ph      = modal.querySelector(`[data-preview="${key}"]`);
    const imgPrev = modal.querySelector(`[data-preview-img="${key}"]`);

    input.addEventListener('change', () => {
      const file = input.files && input.files[0];
      if(!file) return;
      const url = URL.createObjectURL(file);
      if(imgPrev){ imgPrev.src = url; imgPrev.style.display = 'block'; }
      if(ph) ph.style.display = 'none';
    });
  }
  bindPreview('avatar-input','avatar');
  bindPreview('cover-input','cover');
})();

// Js Modal "About Full"
(() => {
  const modalId = 'about-modal';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal   = document.getElementById(modalId);
  if (!modal) return;

  const target  = modal.querySelector('#about-full');

  function open(e) {
    e?.preventDefault();
    const src = document.querySelector('.ov-about-text');
    if (src && target) {
      target.textContent = src.textContent.trim(); // ambil teks full
    }
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
  }

  function close(e) {
    e?.preventDefault();
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
  }

  openers.forEach(btn => btn.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(e); });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) close(e);
  });
})();

// Js Show "Read More" only if About has more than 3 lines
(() => {
  const text = document.querySelector('.ov-about-text');
  const link = document.querySelector('.about-read-more');
  if (!text || !link) return;

  // cek apakah konten melebihi tinggi tampilan (yang sudah di-clamp 3 baris)
  const update = () => {
    // jika konten penuh (scrollHeight) lebih besar dari tinggi tampilan (clientHeight),
    // berarti ada lebih dari 3 baris -> tampilkan Read More
    const show = text.scrollHeight > text.clientHeight + 1;
    link.classList.toggle('d-none', !show);
  };

  // jalankan setelah render; ulangi saat resize agar responsif
  requestAnimationFrame(update);
  window.addEventListener('resize', () => setTimeout(update, 120));
})();

// Js Like and Dislike
(function(){
  const ready = (fn) => document.readyState !== 'loading'
    ? fn()
    : document.addEventListener('DOMContentLoaded', fn);

  function findCard(el){ return el.closest && el.closest('.js-card'); }
  function getStateFromCard(card){ return !!card.querySelector('.js-like-btn.is-liked'); }

  function readCountFrom(el){
    if (!el) return 0;
    const n = parseInt(String(el.textContent || '').replace(/[^0-9-]/g,''), 10);
    return Number.isFinite(n) ? n : 0;
  }

  function writeCountAll(card, next){
    card.querySelectorAll('[data-like-count]').forEach(c=> { c.textContent = String(next); });
  }

  function setButtonsState(card, liked){
    card.querySelectorAll('.js-like-btn').forEach(b=>{
      b.classList.toggle('is-liked', liked);
      b.setAttribute('aria-pressed', String(liked));
      b.disabled = false;
    });
  }

  function setStateForCard(card, liked){
    setButtonsState(card, liked);
    // ambil angka dari counter pertama sebagai dasar
    const first = card.querySelector('[data-like-count]');
    const base = readCountFrom(first);
    const next = liked ? base + 1 : Math.max(0, base - 1);
    writeCountAll(card, next);
  }

  async function toggleLike(btn){
    const url  = btn?.dataset?.likeUrl; if (!url) return;
    const card = findCard(btn); if (!card) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    // kunci semua tombol like di card ini agar tidak spam
    card.querySelectorAll('.js-like-btn').forEach(b=> b.disabled = true);

    const wasLiked   = getStateFromCard(card);
    const optimistic = !wasLiked;
    setStateForCard(card, optimistic); // UI optimistik

    try{
      const res = await fetch(url, {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
          ...(csrf ? {'X-CSRF-TOKEN': csrf} : {})
        }
      });

      // belum login → rollback + coba buka modal login kalau ada
      if (res.status === 401){
        setStateForCard(card, wasLiked);
        try { if (typeof showPane === 'function') showPane('signin'); } catch (_){}
        const authModalEl = document.getElementById('authModal') || document.getElementById('auth-modal');
        if (authModalEl?.classList){
          authModalEl.classList.add('is-open');
          authModalEl.setAttribute('aria-hidden','false');
        }
        return;
      }

      let data = {};
      try { data = await res.json(); } catch (_){}

      if (!res.ok || data?.ok === false){
        // server error → rollback
        setStateForCard(card, wasLiked);
        return;
      }

      // utamakan data server bila ada
      const likedFromServer = (typeof data.liked === 'boolean') ? data.liked : optimistic;
      setButtonsState(card, likedFromServer);

      if (typeof data.count === 'number'){
        writeCountAll(card, data.count);
      }else{
        // kalau server tidak kirim count, hitung dari keadaan saat ini
        const first = card.querySelector('[data-like-count]');
        const after = readCountFrom(first);
        writeCountAll(card, after);
      }
    }catch(_){
      // error jaringan → rollback
      setStateForCard(card, wasLiked);
    }finally{
      card.querySelectorAll('.js-like-btn').forEach(b=> b.disabled = false);
    }
  }

  ready(function(){
    // delegated handler agar tombol di sisi belakang (muncul setelah flip) tetap ter-handle
    document.addEventListener('click', function(e){
      const btn = e.target?.closest?.('.js-like-btn');
      if (!btn) return;
      e.preventDefault();
      e.stopPropagation();
      // cegah double-trigger jika ada listener lain
      if (btn.dataset.handled === '1') return;
      btn.dataset.handled = '1';
      setTimeout(()=> { try{ delete btn.dataset.handled; }catch(_){ btn.removeAttribute('data-handled'); } }, 0);
      toggleLike(btn);
    }, true); // pakai capture supaya aman walau ada listener flip di bubbling
  });
})();

// === Like: delegated handler + sync count kuat di card hasil flip ===
(function () {
  const ready = (fn) =>
    document.readyState !== 'loading'
      ? fn()
      : document.addEventListener('DOMContentLoaded', fn);

  ready(function () {
    const getToken = () => {
      const el = document.querySelector('meta[name="csrf-token"]');
      return el ? el.getAttribute('content') : '';
    };

    const busyCards = new WeakSet(); // refresh per card
    const busyLikes = new WeakSet(); // like per button

    document.addEventListener('click', async (ev) => {
      const target = ev.target;
        if (target.closest && target.closest('.js-like-btn')) {
       return;
      }
      // =========================
      // 1) REFRESH (flip) — PRIORITAS
      // =========================
      const refreshBtn = target.closest?.('.js-refresh-btn');
      if (refreshBtn) {
        const card = refreshBtn.closest('.js-card');
        if (!card || busyCards.has(card)) return;

        const currentId = card.dataset.userId;
        const url = refreshBtn.getAttribute('data-refresh-url') || '/cards/next';
        if (!currentId || !url) return;

        try {
          busyCards.add(card);
          card.classList.add('is-swapping');

           // ==== AMBIL visible_ids DENGAN MEMPERTIMBANGKAN MOBILE VS DESKTOP ====
          // NOTE: sesuaikan breakpoint dgn CSS grid kamu (di sini pakai <768px = mobile)
          const isMobile = window.matchMedia('(max-width: 900px)').matches;
          const grid = document.getElementById('fdoGrid');

          let visibleIds;
          if (isMobile) {
            // MOBILE → anggap hanya 1 kartu yang tampil: kartu yang sedang di-refresh
            visibleIds = [Number(currentId)];
          } else {
            // DESKTOP/TABLET → ambil maks 9 kartu yang benar-benar terlihat (bukan yang di-hide)
            visibleIds = grid
              ? Array.from(grid.querySelectorAll('.js-card[data-user-id]'))
                  .filter(el => el.offsetParent !== null) // exclude display:none / hidden by media query
                  .slice(0, 9)
                  .map(el => Number(el.getAttribute('data-user-id')))
              : [];
          }

        const res = await fetch(url, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': getToken(),
            'Accept': 'text/html',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            current_id: Number(currentId),
            visible_ids: visibleIds,
            mode: isMobile ? 'mobile' : 'desktop'
          })
        });


          if (res.status === 204) {
            // tidak ada kandidat (harusnya tidak terjadi dgn logika random)
            card.classList.remove('is-swapping');
            return;
          }
          if (!res.ok) throw new Error('Gagal memuat kartu berikutnya');

          const html = await res.text();
          const tmp = document.createElement('div');
          tmp.innerHTML = html.trim();
          let newCol = tmp.firstElementChild;
          if (!newCol) throw new Error('Markup kosong');

          const col = card.closest('.js-card-col') || card.parentElement;
          col.replaceWith(newCol);

          const newCard = newCol.querySelector('.js-card');
          if (newCard) {
            newCard.classList.add('is-entering');
            newCard.addEventListener('animationend', () => {
              newCard.classList.remove('is-entering');
            }, { once: true });
          }
        } catch (err) {
          console.error('[cards.next]', err);
          card.classList.remove('is-swapping');
        } finally {
          busyCards.delete(card);
        }
        return; // stop di sini supaya tidak terus ke handler Like
      }

    });
  });
})();

// === Likes Bubble (navbar) — Final version ===
document.addEventListener('DOMContentLoaded', () => {
  // tombol bisa ada >1 (atas & bawah), pakai delegated listener
  const LIKE_BTN_SEL = '#btnLikesModal, #btnLikesMobile, .nav-like-btn, [data-like-bubble]';
  let   currentBtn = null; // anchor yang terakhir di-klik
  const clearActive = () => document.querySelectorAll(LIKE_BTN_SEL).forEach(b => b.classList.remove('is-active'));

  // Hilangkan atribut modal bootstrap
 document.querySelectorAll(LIKE_BTN_SEL).forEach(b => {
    b.removeAttribute('data-bs-toggle');
    b.removeAttribute('data-bs-target');
  });

  // Hapus modal lama jika masih ada
  const oldModal = document.getElementById('likesModal');
  if (oldModal) oldModal.remove();

  // Buat bubble element
  const bubble = document.createElement('div');
  bubble.id = 'likesBubble';
  bubble.className = 'likes-bubble is-hidden';
  bubble.setAttribute('role', 'dialog');
  bubble.setAttribute('aria-modal', 'false');

  bubble.innerHTML = `
    <div class="likes-bubble__card">
      <div class="likes-list" id="likesList">
        <div class="likes-empty">Loading…</div>
      </div>
      <button type="button" class="likes-close" aria-label="Close">×</button>
    </div>
    <div class="likes-bubble__arrow" aria-hidden="true"></div>
  `;
  document.body.appendChild(bubble);
  // set CSS var tinggi bottom bar -> dipakai utk offset bottom bubble
  const mbb = document.querySelector('.mobile-bottom-bar');
  if (mbb) {
    const h = Math.round(mbb.getBoundingClientRect().height || 64);
    document.documentElement.style.setProperty('--mbb-h', h + 'px');
  }

  const listEl  = bubble.querySelector('#likesList');
  const closeBtn = bubble.querySelector('.likes-close');
  const arrowEl  = bubble.querySelector('.likes-bubble__arrow');
  const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;

  // ---------------- Helpers ----------------
  const getName = (it) => (
    it.name ||
    it.username ||
    it.user?.name ||
    it.user?.username ||
    it.liker?.name ||
    it.liker?.username ||
    it.liked_by?.name ||
    it.liked_by?.username ||
    it.account?.name ||
    it.account?.username ||
    'Unknown'
  );

  // lokasi avatar: public/assets/img/avatars/{filename}
  const getAvatar = (it) => {
    const name = getName(it);

    // coba ambil kemungkinan field yang menyimpan nama file avatar
    const candidates = [
      it.avatar,
      it.avatar_url,
      it.photo,
      it.photo_url,
      it.image_url,
      it.user?.avatar,
      it.liker?.avatar,
      it.liked_by?.avatar,
      it.account?.avatar,
    ].filter(Boolean);

    let url = candidates[0] || '';

    // Jika absolute URL / sudah diawali /assets, pakai langsung
    if (/^https?:\/\//i.test(url) || url.startsWith('/assets/')) return url;

    // Jika sudah berupa path relatif yang mengandung "assets/img/avatars"
    if (url.includes('assets/img/avatars')) {
      return url.startsWith('/') ? url : `/${url}`;
    }

    // Jika hanya nama file, gabungkan dengan folder avatar lokal
    if (url && !url.startsWith('/')) {
      url = `/assets/img/avatars/${url}`;
      return url;
    }
    // Fallback: inline SVG (huruf awal nama) -> tidak memicu 404
    return (
      'data:image/svg+xml;utf8,' +
      encodeURIComponent(
        `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80">
          <rect width="100%" height="100%" rx="40" ry="40" fill="#e5e7eb"/>
          <text x="50%" y="54%" text-anchor="middle" font-family="Arial,Helvetica,sans-serif"
                font-size="34" fill="#9aa0a6" dy=".1em">${name.slice(0,1).toUpperCase()}</text>
        </svg>`
      )
    );
  };

  // ---------------- State ----------------
 const isOpen = () => !bubble.classList.contains('is-hidden');

  const open = () => {

    document.getElementById('navSearchPanel')?.classList.add('d-none');
    document.getElementById('navNotifPanel')?.classList.add('d-none');

    bubble.classList.remove('is-hidden');
      clearActive(); currentBtn?.classList.add('is-active');
    if (isMobile()) {
      // MOBILE: jadikan bottom-sheet & bersihkan inline style desktop
      bubble.classList.add('is-mobile-open');
      bubble.style.top  = '';
      bubble.style.left = '';
      // (biar CSS `.is-mobile-open` yang atur width=100%)
    } else {
      // DESKTOP: posisikan tepat di bawah tombol
      bubble.classList.remove('is-mobile-open');
      // tunggu 1 frame agar layout sudah update sebelum ukur
      requestAnimationFrame(positionToButton);
    }
  };

  const close = () => {
    bubble.classList.add('is-hidden');
    bubble.classList.remove('is-mobile-open');
    clearActive();
    currentBtn = null;     // ← matikan saat ditutup
    bubble.style.top  = '';
    bubble.style.left = '';
  };

  // ---------------- Posisi sticky (di bawah tombol like) ----------------
  const positionToButton = () => {
    if (isMobile()) return;
    const btnEl = currentBtn || document.querySelector(LIKE_BTN_SEL);
    if (!btnEl) return;
    const rect  = btnEl.getBoundingClientRect();
    const bRect = bubble.getBoundingClientRect();
    const gap = 10;

    let left = rect.left;
    const bubbleW = bubble.offsetWidth || bRect.width || 320; // fallback
    const maxLeft = window.innerWidth - bubbleW - 8;
    if (left > maxLeft) left = Math.max(8, maxLeft);
    if (left < 8) left = 8;

    const top = rect.bottom + gap;

    bubble.style.position = 'fixed';
    bubble.style.top  = `${top}px`;
    bubble.style.left = `${left}px`;

    // panah mengarah ke tengah tombol
    const btnCenter = rect.left + rect.width / 2;
    const bubbleLeft = left;
    const arrowLeft = Math.max(16, Math.min(bubbleW - 16, btnCenter - bubbleLeft));
    arrowEl.style.left = `${arrowLeft - 6}px`;
    arrowEl.style.top  = `-6px`;
  };

  // ---------------- Util tanggal ----------------
  const formatDate = (input) => {
    if (!input) return '';
    const d = new Date(input);
    if (isNaN(d)) return input; // jika backend sudah kirim "Aug 04"
    return d.toLocaleString('en-US', { month: 'short', day: '2-digit' });
  };

  // ---------------- Render list ----------------
  const render = (items = []) => {
    listEl.innerHTML = '';

    if (!items.length) {
      listEl.innerHTML = `<div class="likes-empty">Belum ada yang menyukai profilmu.</div>`;
      listEl.style.maxHeight = '';
      listEl.style.overflowY = '';
      return;
    }

    items.forEach((it) => {
      const row = document.createElement('div');
      row.className = 'likes-item';

      const name = getName(it);
      const avatarUrl = getAvatar(it);
      const date = formatDate(it.date || it.created_at || it.updated_at || '');

      // img diset via JS supaya bisa pasang onerror fallback
      row.innerHTML = `
        <img class="likes-avatar" alt="">
        <div class="likes-main">
          <div class="likes-top">
            <span class="likes-name">${name}</span>
            <span class="likes-date">${date}</span>
          </div>
          <div class="likes-sub">liked your profile</div>
        </div>
      `;

      const imgEl = row.querySelector('.likes-avatar');
      imgEl.src = avatarUrl;
      imgEl.onerror = () => {
        imgEl.onerror = null;
        imgEl.src =
          'data:image/svg+xml;utf8,' +
          encodeURIComponent(
            `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80">
              <rect width="100%" height="100%" rx="40" ry="40" fill="#e5e7eb"/>
              <text x="50%" y="54%" text-anchor="middle" font-family="Arial,Helvetica,sans-serif"
                    font-size="34" fill="#9aa0a6" dy=".1em">${name.slice(0,1).toUpperCase()}</text>
            </svg>`
          );
      };

      if (it.profile_url) {
        row.classList.add('likes-item--link');
        row.addEventListener('click', () => { window.location.href = it.profile_url; });
      }

      listEl.appendChild(row);
    });

    // Maks 4 item terlihat → sisanya scroll
    const cap = 4;
    const all = listEl.querySelectorAll('.likes-item');
    if (all.length > cap) {
      const h = all[0].getBoundingClientRect().height;
      listEl.style.maxHeight = `${Math.round(h * cap + 8)}px`;
      listEl.style.overflowY = 'auto';
    } else {
      listEl.style.maxHeight = '';
      listEl.style.overflowY = '';
    }
  };

  // ---------------- Fetch data ----------------
  const loadLikes = async () => {
    try {
      const res = await fetch('/likes', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) throw new Error('Failed');
      const json = await res.json();
      render(json.items || json || []);
    } catch (e) {
      render([]);
    }
  };

  // ---------------- Events ----------------
  document.addEventListener('click', (e) => {
    const trg = e.target.closest(LIKE_BTN_SEL);
    if (!trg) return;
    e.preventDefault();
    currentBtn = trg; // simpan anchor yang dipakai
    if (isOpen()) close();
    else { open(); loadLikes(); }
  });

  closeBtn.addEventListener('click', close);

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen()) close();
  });

  document.addEventListener('click', (e) => {
    if (!isOpen()) return;
    if (currentBtn && (e.target === currentBtn || currentBtn.contains(e.target))) return;
    if (bubble.contains(e.target)) return;
    close();
  });

  window.addEventListener('scroll', () => {
    if (isOpen() && !isMobile()) positionToButton();
  }, { passive: true });
  window.addEventListener('resize', () => {
    if (isOpen() && !isMobile()) positionToButton();
  });
});

// ==== Free Dating Online: cache grid hanya saat pergi ke /u/* (bukan /profile / reload) ====
(function () {
  const KEY = 'fdo:grid:v1';
  const KEY_INTENT = 'fdo:restore-intent'; // flag: hanya restore bila datang dari /u/*
  const ready = (fn) =>
    document.readyState !== 'loading'
      ? fn()
      : document.addEventListener('DOMContentLoaded', fn);

  function isFdoPage() { return !!document.getElementById('fdoGrid'); }

  function saveGrid() {
    const grid = document.getElementById('fdoGrid');
    if (!grid) return;
    try {
      sessionStorage.setItem(KEY, JSON.stringify({
        html: grid.innerHTML,
        scrollY: window.scrollY || 0,
        ts: Date.now(),
      }));
    } catch (_) {}
  }

  function restoreGrid() {
    const raw = sessionStorage.getItem(KEY);
    if (!raw) return;
    try {
      const payload = JSON.parse(raw);
      const grid = document.getElementById('fdoGrid');
      if (!grid || !payload?.html) return;
      grid.innerHTML = payload.html;
      requestAnimationFrame(() => window.scrollTo(0, +payload.scrollY || 0));
    } catch (_) {}
  }

  function clearGridCache() { sessionStorage.removeItem(KEY); }

  function getPath(href) {
    try { return new URL(href, location.origin).pathname; }
    catch { return href || ''; }
  }

  // /u/* dianggap profil orang lain → boleh cache
  // /profile atau /profile/... → JANGAN cache
  function isOtherProfilePath(pathname) {
    if (!pathname) return false;
    const p = pathname.replace(location.origin, '');
    if (p === '/profile' || p.startsWith('/profile/')) return false; // profil sendiri
    return /^\/u\/.+/.test(p); // sesuaikan kalau prefix kamu beda
  }

  ready(() => {
    if (!isFdoPage()) return;

    // Tandai niat pergi ke /u/* saat user klik linknya.
    // Simpan grid segera, supaya kalau navi cepat pun state tersimpan.
    document.addEventListener('click', (e) => {
      const a = e.target.closest && e.target.closest('a[href]');
      if (!a) return;
      const path = getPath(a.getAttribute('href'));
      if (isOtherProfilePath(path)) {
        sessionStorage.setItem(KEY_INTENT, '1');
        try { saveGrid(); } catch (_) {}
      } else {
        sessionStorage.removeItem(KEY_INTENT);
      }
    }, { capture: true });

    // Kembali dari history (bfcache)? Restore hanya jika intent = /u/*
    window.addEventListener('pageshow', (e) => {
  const fromBFCache = e.persisted === true;

  // deteksi back/forward walau tidak pakai bfcache
  const nav = performance.getEntriesByType('navigation')[0];
  const isBackForward = nav && nav.type === 'back_forward';

  const shouldRestore = sessionStorage.getItem(KEY_INTENT) === '1';

  if ((fromBFCache || isBackForward) && shouldRestore) {
    restoreGrid();
    // sukses restore → bersihkan cache & flag supaya tidak “nempel” ke navigasi lain
    // (cache berikutnya akan diset lagi saat klik link /u/*)
    sessionStorage.removeItem(KEY);
    sessionStorage.removeItem(KEY_INTENT);
  } else {
    // bukan kembali dari /u/* → jangan pakai cache ini
    clearGridCache();
    sessionStorage.removeItem(KEY_INTENT);
  }
});

    // Reload → jangan pernah restore
    const nav = performance.getEntriesByType('navigation')[0];
    if (nav && nav.type === 'reload') {
      sessionStorage.removeItem(KEY_INTENT);
      clearGridCache();
    }
  });
})();


// ===== Chat: open from card =====
(() => {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.js-chat-open, .js-card .card-actions [title="Message"]');
    if (!btn) return;

    const url = btn.dataset.chatUrl;
    const id  = btn.dataset.chatUserId || (btn.closest('.js-card')?.dataset.userId);
    if (url)      { window.location.href = url; return; }
    if (id)       { window.location.href = '/chat/' + encodeURIComponent(id); }
  });
})();

// ===== Chat App (light polling, isolated namespace) =====
(() => {
  const ready = (fn) =>
    document.readyState !== 'loading' ? fn() : document.addEventListener('DOMContentLoaded', fn);

  ready(() => {
    const app = document.getElementById('chatApp');
    if (!app) return;

    const thread   = app.querySelector('#chatThread');
    const form     = app.querySelector('#chatForm');
    const input    = form.querySelector('input[name="body"]');
    const sendBtn  = form.querySelector('.chat-send');

    const messagesUrl = app.dataset.messagesUrl;
    const sendUrl     = app.dataset.sendUrl;
    let   lastId      = 0;
    let   polling     = null;

    const getToken = () => {
      const el = document.querySelector('meta[name="csrf-token"]');
      return el ? el.getAttribute('content') : '';
    };

    const escapeHtml = (s='') => s
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');

    const timeShort = (iso) => {
      try {
        const d = new Date(iso);
        return d.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
      } catch { return ''; }
    };

    // GANTI fungsi ini
const renderMsg = (m) => {
  // 1) Kalau server mengembalikan id, cegah render ulang item yang sama
  if (m.id && thread.querySelector(`.chat-item[data-id="${m.id}"]`)) {
    return; // sudah ada → jangan render lagi
  }

  // 2) Jika (misal) tidak ada id di response, cegah duplikat sederhana:
  //    bandingkan dengan bubble terakhir dari pengirim yang sama.
  if (!m.id) {
    const last = thread.lastElementChild;
    if (last && last.classList.contains(m.is_me ? 'is-me' : 'is-them')) {
      const lastText = last.querySelector('.bubble')?.textContent?.trim() || '';
      if ((m.body || '').trim() === lastText) {
        return; // konten sama persis → abaikan
      }
    }
  }

  const cls = m.is_me ? 'is-me' : 'is-them';
  const html = `
    <div class="chat-item ${cls}" data-id="${m.id ?? ''}">
      <div class="bubble">${escapeHtml(m.body).replace(/\n/g,'<br>')}</div>
      <div class="meta">${timeShort(m.created_at||'')}</div>
    </div>`;
  thread.insertAdjacentHTML('beforeend', html);

  // 3) Naikkan lastId hanya kalau punya id numerik
  if (m.id) lastId = Math.max(lastId, m.id|0);
};


    const atBottom = () => {
      const pad = 40;
      return (thread.scrollHeight - thread.scrollTop - thread.clientHeight) < pad;
    };

    const scrollToBottom = () => {
      thread.scrollTop = thread.scrollHeight + 999;
    };

    const fetchMessages = async (initial=false) => {
      try {
        const url = new URL(messagesUrl, location.origin);
        if (!initial && lastId) url.searchParams.set('after_id', String(lastId));
        const res = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
        if (!res.ok) return;

        const data = await res.json();
        if (!data || !Array.isArray(data.items) || data.items.length === 0) return;

        const stick = atBottom();
        data.items.forEach(renderMsg);
        if (initial || stick) scrollToBottom();
      } catch (_) {}
    };

    const sendMessage = async (text) => {
      try {
        sendBtn.disabled = true;
        const res = await fetch(sendUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getToken(),
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: JSON.stringify({ body: text })
        });
        if (!res.ok) return;
        const m = await res.json();
        renderMsg(m);
        scrollToBottom();
      } catch (_) {
      } finally {
        sendBtn.disabled = false;
      }
    };

    // Initial load
    thread.innerHTML = '';
    fetchMessages(true);

    // Polling ringan
    polling = setInterval(fetchMessages, 2000);

    // Handle submit
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const val = (input.value || '').trim();
      if (!val) return;
      sendMessage(val);
      input.value = '';
      input.focus();
    });

    // Bersih saat unload
    window.addEventListener('beforeunload', () => { if (polling) clearInterval(polling); });
  });
})();

// ===== Chat Split View =====
(() => {
  const ready = (fn) => document.readyState !== 'loading'
    ? fn()
    : document.addEventListener('DOMContentLoaded', fn);

  ready(() => {
    const root = document.getElementById('chatSplit');
    if (!root) return;

    // Elements
    const listEl   = root.querySelector('#chatConvList');
    const searchEl = root.querySelector('#chatSearchInput');
    const thread   = root.querySelector('#chatThread');
    const form     = root.querySelector('#chatForm');
    const input    = form.querySelector('input[name="body"]');
    const btnSend  = form.querySelector('.chat-send');
    const peerName = root.querySelector('#chatPeerName');
    const peerSub  = root.querySelector('#chatPeerSub');
    const peerAvt  = root.querySelector('#chatPeerAvatar');
    const peerLink = root.querySelector('#chatPeerProfile');
    const prefillText = root.dataset.prefill || '';
    const initialName   = root.dataset.initialName || '';
    const initialAvatar = root.dataset.initialAvatar || '';

    // ⬇️ tambahkan setelah const peerLink = ...
    const DEFAULT_ICON = (peerAvt?.dataset?.defaultAvatar)
      || '/assets/img/icons/card-placeholder.png'; // ganti jika path ikonmu beda

    const resetHeader = () => {
      if (peerAvt) {
        peerAvt.src = DEFAULT_ICON;
        peerAvt.alt = 'Message';
        peerAvt.style.display = 'block'; // selalu terlihat
      }
      if (peerName) peerName.textContent = 'Select a conversation';
      if (peerSub)  peerSub.textContent  = '';
      if (peerLink) peerLink.classList.add('d-none');
    };

    resetHeader(); // tampilan awal


    // URLs
    const convUrl  = root.dataset.convUrl;
    const msgTpl   = root.dataset.msgUrlTemplate;   // .../chat/{user}/messages
    const sendTpl  = root.dataset.sendUrlTemplate;  // .../chat/{user}/messages
    const profTpl  = root.dataset.profileUrlTemplate || '/u/___ID___'; // ⬅️ baru
    const roomTpl  = root.dataset.roomUrlTemplate || '/chat/___ID___';
    let   currentId = null;
    let   lastId    = 0;
    let   pollTimer = null;

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    const fmtTime = iso => {
      if (!iso) return '';
      const d = new Date(iso);
      return d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
    };
    const escapeHtml = (s='') => s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    // Sidebar rendering
    let convCache = [];
    const renderConversations = (filter='') => {
      const f = filter.trim().toLowerCase();
      listEl.innerHTML = '';
      convCache
        .filter(c => !f || c.name.toLowerCase().includes(f))
        .forEach(c => {
          const unread = c.unread ? `<span class="badge">${c.unread}</span>` : '';
          const html = `
            <button class="chat-conv-item ${c.user_id==currentId?'active':''}" data-id="${c.user_id}" data-profile="${c.profile_url || ''}" data-chat-url="${roomTpl.replace('___ID___', c.user_id)}">
              <img class="avatar" src="${c.avatar||DEFAULT_ICON}" alt="" onerror="this.onerror=null;this.src=DEFAULT_ICON;">
              <div class="meta">
                <div class="top">
                  <span class="name">${escapeHtml(c.name)}</span>
                  <span class="time">${fmtTime(c.last_time)}</span>
                </div>
                <div class="bottom">
                  <span class="preview">${escapeHtml((c.last_text||'').slice(0,40))}</span>
                  ${unread}
                </div>
              </div>
            </button>`;
          listEl.insertAdjacentHTML('beforeend', html);
        });
    };

    const fetchConversations = async () => {
      try {
        const res = await fetch(convUrl, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (!res.ok) return;
        const data = await res.json();
        convCache = data.items || [];
        renderConversations(searchEl.value);
      } catch (_) {}
    };

    // Switch room
const openRoom = async (userId) => {
  currentId = userId;
  lastId = 0;
  thread.innerHTML = '';
  form.classList.remove('d-none');

  const info = convCache.find(c => c.user_id == userId);
  if (info) {
    peerName.textContent = info.name || 'Conversation';

    const def = peerAvt?.dataset?.defaultAvatar || '/assets/img/icons/card-placeholder.png';
    if (peerAvt) {
      peerAvt.src = info.avatar || def;
      peerAvt.alt = info.name || 'Message';
      peerAvt.onerror = () => { peerAvt.onerror = null; peerAvt.src = def; };
    }

    if (peerLink) {
      const url = (info.profile_url && /^https?:\/\//.test(info.profile_url))
        ? info.profile_url
        : profTpl.replace('___ID___', info.user_id);
      peerLink.href = url;
      peerLink.classList.remove('d-none');
    }
  } else {
    // 🔁 Fallback (belum punya pesan → tidak ada di convCache)
    peerName.textContent = initialName || 'Conversation';

    const def = peerAvt?.dataset?.defaultAvatar || '/assets/img/icons/card-placeholder.png';
    if (peerAvt) {
      peerAvt.src = initialAvatar || def;
      peerAvt.alt = initialName || 'Message';
      peerAvt.onerror = () => { peerAvt.onerror = null; peerAvt.src = def; };
    }
    if (peerLink) {
      peerLink.href = profTpl.replace('___ID___', userId);
      peerLink.classList.remove('d-none');
    }
  }

  await loadMessages(true);
  if (pollTimer) clearInterval(pollTimer);
  pollTimer = setInterval(loadMessages, 2000);

  renderConversations(searchEl.value);
};




    

    // Messages
    const messagesUrl = () => msgTpl.replace('___ID___', currentId);
    const sendUrl     = () => sendTpl.replace('___ID___', currentId);

    const atBottom = () => (thread.scrollHeight - thread.scrollTop - thread.clientHeight) < 40;
    const scrollToBottom = () => { thread.scrollTop = thread.scrollHeight + 999; };

    const renderMsg = (m) => {
      const cls = m.is_me ? 'is-me' : 'is-them';
      thread.insertAdjacentHTML('beforeend', `
        <div class="chat-item ${cls}" data-id="${m.id}">
          <div class="bubble">${escapeHtml(m.body).replace(/\n/g,'<br>')}</div>
          <div class="meta">${fmtTime(m.created_at)}</div>
        </div>
      `);
      lastId = Math.max(lastId, m.id|0);
    };

    const loadMessages = async (initial=false) => {
      if (!currentId) return;
      try {
        const url = new URL(messagesUrl(), location.origin);
        if (!initial && lastId) url.searchParams.set('after_id', String(lastId));
        const res = await fetch(url, {headers:{'X-Requested-With':'XMLHttpRequest'}});
        if (!res.ok) return;
        const data = await res.json();
        if (!data.items?.length) return;

        const stick = atBottom();
        data.items.forEach(renderMsg);
        if (initial || stick) scrollToBottom();
      } catch (_) {}
    };

    const sendMessage = async (text) => {
      if (!currentId) return;
      try {
        btnSend.disabled = true;
        const res = await fetch(sendUrl(), {
          method:'POST',
          headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrf(),
            'X-Requested-With':'XMLHttpRequest'
          },
          body: JSON.stringify({body:text})
        });
        if (!res.ok) return;
        const m = await res.json();
        renderMsg(m);
        scrollToBottom();
        fetchConversations(); // refresh preview/unread
      } finally {
        btnSend.disabled = false;
      }
    };

    // Events
    listEl.addEventListener('click', (e) => {
      const item = e.target.closest('.chat-conv-item'); if (!item) return;
      openRoom(item.dataset.id);
    });
    searchEl.addEventListener('input', () => renderConversations(searchEl.value));
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const val = (input.value||'').trim();
      if (!val) return;
      sendMessage(val);
      input.value = '';
      input.focus();
    });

    // Init
// Init
fetchConversations().then(async () => {
  const initial = root.dataset.initialUser;

  if (initial) {
    await openRoom(initial); // ini sudah memanggil loadMessages(true) di dalamnya

    // Prefill cuma kalau:
    // - server memang mengirim prefillText (belum pernah chat), dan
    // - setelah loadMessages, tidak ada pesan (lastId === 0)
    if (prefillText && input && !input.value && lastId === 0) {
      input.value = prefillText;
      input.focus();
      try { input.setSelectionRange(prefillText.length, prefillText.length); } catch (_) {}
    }
  } else {
    resetHeader();
  }
});


    // Refresh daftar percakapan setiap 15 detik
    setInterval(fetchConversations, 15000);
  });
})();

// === Open Chat Split from user card ===
(() => {
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.js-chat-open, [data-chat-user-id]');
    if (!btn) return;

    const id = btn.dataset.chatUserId || btn.closest('[data-chat-user-id]')?.dataset.chatUserId;
    if (!id) return;

    // arahkan ke halaman chat + parameter user
    window.location.href = '/chat?user=' + encodeURIComponent(id);
  });
})();

// Modal album foto
// ===== Album Modal 1 =====
(() => {
  const modalId = 'album-modal-1';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(b => b.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  // preview slot 1
  const input = document.getElementById('album1-input');
  const prev  = document.getElementById('album1-preview');
  if (input && prev) input.addEventListener('change', () => {
    const f = input.files?.[0]; if (!f) return;
    prev.src = URL.createObjectURL(f); prev.style.display = 'block';
  });
})();

// ===== Album Modal 2 =====
(() => {
  const modalId = 'album-modal-2';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(b => b.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  const input = document.getElementById('album2-input');
  const prev  = document.getElementById('album2-preview');
  if (input && prev) input.addEventListener('change', () => {
    const f = input.files?.[0]; if (!f) return;
    prev.src = URL.createObjectURL(f); prev.style.display = 'block';
  });
})();

// ===== Album Modal 3 =====
(() => {
  const modalId = 'album-modal-3';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal = document.getElementById(modalId);
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(b => b.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  const input = document.getElementById('album3-input');
  const prev  = document.getElementById('album3-preview');
  if (input && prev) input.addEventListener('change', () => {
    const f = input.files?.[0]; if (!f) return;
    prev.src = URL.createObjectURL(f); prev.style.display = 'block';
  });
})();

// ===== Notifications bubble (same behaviour as Like bubble) =====
(() => {
  const ready = (fn)=>document.readyState!=='loading'?fn():document.addEventListener('DOMContentLoaded',fn);
  ready(()=> {
    const wrap  = document.querySelector('.like-bubble-wrap');
    const btn   = document.getElementById('navNotifBtn');
    const badge = document.getElementById('navNotifBadge');
    const panel = document.getElementById('navNotifPanel');
    const close = document.getElementById('navNotifClose');
    const list  = document.getElementById('notifList');
    if (!wrap || !btn || !panel || !list) return;

    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

    const timeShort = (iso) => {
      if (!iso) return '';
      const d = new Date(iso);
      return d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
    };

    const tplItem = (n) => {
  let iconPath = '/assets/icons/message.svg'; // default
  if (n.kind === 'welcome') iconPath = '/assets/img/icons/icon-navbar-2.png';
  else if (n.kind === 'match') iconPath = '/assets/img/icons/icon-notif-welcome.png';
  else if (n.kind === 'new_message') iconPath = '/assets/img/icons/icon-navbar-3.png';
  return `
    <a class="notif-item" href="${n.link || '#'}">
      <div class="ico"><img src="${iconPath}" alt="${n.kind}" class="notif-icon"></div>
      <div class="meta">
        <div class="title">${n.title}</div>
        <div class="text">${n.body}</div>
        <div class="date">${timeShort(n.created_at)}</div>
      </div>
      <div class="trail">
        <span class="unread-dot ${n.is_unread ? '' : 'd-none'}"></span>
        <i class="bi bi-chevron-right chev"></i>
      </div>
    </a>`;
};

list.addEventListener('click', (e) => {
  const item = e.target.closest('.notif-item');
  if (!item) return;
  const dot = item.querySelector('.unread-dot');
  if (dot) dot.classList.add('d-none');
  // tidak perlu stopPropagation; biarkan link bekerja
});
const section = (title, items = []) => {
  if (!items.length) return '';
  return `
    <div class="notif-sec-head">${title}</div>
    ${items.map(tplItem).join('')}
  `;
};

// --- helpers tanggal ---
const startOfDay = (d) => new Date(d.getFullYear(), d.getMonth(), d.getDate());

const headerLabel = (dt) => {
  const today = startOfDay(new Date());
  const yday  = new Date(today); yday.setDate(today.getDate() - 1);

  const d0 = startOfDay(dt);

  if (+d0 === +today) return 'Today';
  if (+d0 === +yday)  return 'Yesterday';

  // format tanggal untuk yang lebih lama
  const opts = {
    month: 'short',
    day: '2-digit',
    ...(dt.getFullYear() !== today.getFullYear() ? { year: 'numeric' } : {})
  };
  return dt.toLocaleDateString(undefined, opts); // contoh: "Oct 09" / "Sep 30, 2025"
};

const keyForGroup = (dt) => {
  // kunci YYYY-MM-DD untuk pengelompokan
  const y = dt.getFullYear();
  const m = String(dt.getMonth()+1).padStart(2,'0');
  const d = String(dt.getDate()).padStart(2,'0');
  return `${y}-${m}-${d}`;
};

    // gabung semua item, kelompokkan per hari, dan render
const render = (groups) => {
  // gabung lalu urutkan desc
  const all = [...(groups.today || []), ...(groups.yesterday || [])]
    .filter(x => x && x.created_at)
    .sort((a,b) => new Date(b.created_at) - new Date(a.created_at));

  if (!all.length) {
    list.innerHTML = `<div class="notif-empty">No notifications</div>`;
    return;
  }

  // kelompokkan per tanggal (YYYY-MM-DD)
  const map = new Map();
  for (const n of all) {
    const dt = new Date(n.created_at);
    const key = keyForGroup(dt);
    if (!map.has(key)) map.set(key, { dt, items: [] });
    map.get(key).items.push(n);
  }

  // urutkan key desc berdasarkan tanggal
  const ordered = [...map.values()]
    .sort((a,b) => +startOfDay(b.dt) - +startOfDay(a.dt));

  // render
  let html = '';
  for (const bucket of ordered) {
    html += `<div class="notif-sec-head">${headerLabel(bucket.dt)}</div>`;
    html += bucket.items.map(tplItem).join('');
  }
  list.innerHTML = html;
};


    const load = async () => {
      const res = await fetch('/light-notifications', {headers:{'X-Requested-With':'XMLHttpRequest'}});
      if (!res.ok) return;
      const data = await res.json();
      render(data.groups || {});
      const c = Number(data.unread || 0);
      if (c > 0) { badge.textContent = c; badge.classList.remove('d-none'); }
      else { badge.classList.add('d-none'); }
    };

    const markSeen = async () => {
      await fetch('/light-notifications/seen', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-Requested-With':'XMLHttpRequest','X-CSRF-TOKEN':csrf()},
        body: JSON.stringify({})
      });
    };

    const open = async () => {

      document.getElementById('navSearchPanel')?.classList.add('d-none');
      document.getElementById('likesBubble')?.classList.add('is-hidden');
      document.getElementById('btnLikesModal')?.classList.remove('is-active');

      await load();
      panel.classList.remove('d-none');
      await markSeen();
      badge.classList.add('d-none');
    };
    const closePanel = () => panel.classList.add('d-none');

    btn.addEventListener('click', (e)=>{ e.preventDefault(); e.stopPropagation(); 
      panel.classList.contains('d-none') ? open() : closePanel();
    });
    close.addEventListener('click', closePanel);
    document.addEventListener('click', (e)=>{
      if (!panel.classList.contains('d-none') && !panel.contains(e.target) && e.target!==btn) closePanel();
    });

    // optional: refresh badge berkala biar real-time
    setInterval(load, 30000);
    load();
  });
})();

// Modal Pop Up: Delete Account
(() => {
  const modalId = 'delete-account-modal';
  const openers = document.querySelectorAll(`[data-modal-open="${modalId}"]`);
  const modal   = document.getElementById(modalId);
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  openers.forEach(btn => btn.addEventListener('click', open));
  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  // kirim alasan (opsional) ke server saat submit
  const textarea = modal.querySelector('#del-notes');
  const hidden   = modal.querySelector('#del-notes-hidden');
  const form     = modal.querySelector('form');
  if (form && textarea && hidden) {
    form.addEventListener('submit', () => { hidden.value = textarea.value; });
  }
})();


// Success Modal: open automatically after redirect
(() => {
  const modal = document.getElementById('deleted-modal');
  if (!modal) return;

  const open  = () => { modal.classList.add('is-open');  modal.setAttribute('aria-hidden','false'); };
  const close = () => { modal.classList.remove('is-open'); modal.setAttribute('aria-hidden','true'); };

  modal.querySelectorAll('[data-modal-close]').forEach(b => b.addEventListener('click', close));
  modal.addEventListener('click', e => { if (e.target === modal) close(); });
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('is-open')) close(); });

  // auto show after delete
  if (modal.hasAttribute('data-autoshow')) {
    open();

    // opsional: auto-close setelah 4 detik
    setTimeout(() => { if (modal.classList.contains('is-open')) close(); }, 4000);
  }
})();

// === Search Filter Bubble & Fetch ===
(function () {
  const btn   = document.getElementById('navSearchBtn');
  const panel = document.getElementById('navSearchPanel');
  const close = document.getElementById('navSearchClose');
  const form  = document.getElementById('searchFilterForm');
  if (!btn || !panel || !form) return;

  const grid   = document.getElementById('fdoGrid'); // grid di free-online-dating
  const submit = document.getElementById('searchFilterSubmit');
  const reset  = document.getElementById('searchFilterReset');

  // ---------- Bubble open/close ----------
  const openBubble = () => {
  // tutup Likes & Notif jika sedang terbuka
  document.getElementById('likesBubble')?.classList.add('is-hidden');
  document.getElementById('btnLikesModal')?.classList.remove('is-active');
  document.getElementById('navNotifPanel')?.classList.add('d-none');

    panel.classList.remove('d-none');
    panel.setAttribute('aria-hidden', 'false');
    setTimeout(openDefaultSection, 0); // buka Age Range setiap kali panel muncul
  };
  const hideBubble = () => {
    panel.classList.add('d-none');
    panel.setAttribute('aria-hidden', 'true');
  };

  btn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (panel.classList.contains('d-none')) openBubble(); else hideBubble();
  });
  close && close.addEventListener('click', hideBubble);
  document.addEventListener('click', (e) => {
    if (!panel.contains(e.target) && !btn.contains(e.target)) hideBubble();
  });

  // ---------- Accordion (sinkron dgn Update Preferences) ----------
  function getPanelEl(item){
    // tahan banting terhadap variasi class
    return item.querySelector('.pref-panel, .pref-body, .accordion-panel, .pref-content')
        || item.querySelector(':scope > div:last-child')
        || null;
  }

  function getMaxSixHeight(panelEl){
    const opts = panelEl.querySelectorAll('.pref-option');
    if (!opts.length) {
      const ps = getComputedStyle(panelEl);
      return panelEl.scrollHeight + parseFloat(ps.paddingTop||0) + parseFloat(ps.paddingBottom||0);
    }
    let h = 0, n = Math.min(opts.length, 6);
    for (let i=0; i<n; i++){
      const el = opts[i];
      const cs = getComputedStyle(el);
      h += el.offsetHeight + parseFloat(cs.marginTop||0) + parseFloat(cs.marginBottom||0);
    }
    const ps = getComputedStyle(panelEl);
    h += parseFloat(ps.paddingTop||0) + parseFloat(ps.paddingBottom||0);
    return Math.ceil(h + 2);
  }

  function openItem(item){
    if (item.classList.contains('is-open')) return;
    const pnl = getPanelEl(item); if (!pnl) return;
    item.classList.add('is-open');
    const cap = getMaxSixHeight(pnl);
    pnl.style.maxHeight = Math.min(pnl.scrollHeight, cap) + 'px';
    pnl.style.overflow  = 'auto';
  }

  function closeItem(item){
    if (!item.classList.contains('is-open')) return;
    const pnl = getPanelEl(item); if (!pnl) return;
    pnl.style.maxHeight = pnl.scrollHeight + 'px'; // smooth collapse
    pnl.offsetHeight; // reflow
    item.classList.remove('is-open');
    pnl.style.maxHeight = '0px';
    pnl.style.overflow  = 'hidden';
  }

  // Buka Age Range, lainnya tutup
  function openDefaultSection(){
    const all = panel.querySelectorAll('.pref-accordion .pref-item');
    all.forEach(it => {
      it.classList.remove('is-open');
      const pnl = getPanelEl(it);
      if (pnl){ pnl.style.maxHeight = '0px'; pnl.style.overflow = 'hidden'; }
    });
    const ageInput = panel.querySelector('.pref-item input[name="age_range"]');
    const ageItem  = ageInput ? ageInput.closest('.pref-item') : all[0];
    if (ageItem) openItem(ageItem);
  }

  // Klik header → hanya satu item terbuka
  panel.addEventListener('click', (e) => {
    const head = e.target.closest('.pref-head');
    if (!head || !panel.contains(head)) return;
    const item = head.closest('.pref-item'); if (!item) return;
    const isOpen = item.classList.contains('is-open');
    panel.querySelectorAll('.pref-item.is-open').forEach(it => { if (it !== item) closeItem(it); });
    isOpen ? closeItem(item) : openItem(item);
  });

  // Init awal: default ke Age Range
  (function initAccord(){ openDefaultSection(); })();

  // ---------- Reset ----------
  reset && reset.addEventListener('click', () => {
    form.querySelectorAll('input[type="radio"][value=""]').forEach(r => { r.checked = true; });
    openDefaultSection(); // balik ke Age Range
  });

  // ---------- Submit filter → fetch HTML cards → replace grid ----------
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!grid) return;

    try {
      submit && (submit.disabled = true);
      const fd = new FormData(form);
      const params = new URLSearchParams();
      ['age_range','hobby','lifestyle','personality','love_language'].forEach(k => {
        const v = (fd.get(k) || '').toString().trim();
        if (v) params.set(k, v);
      });
      params.set('limit', '9'); // <= batasi hasil 9 card

      const url = '/free-online-dating/filter' + (params.toString() ? ('?' + params.toString()) : '');
      const res = await fetch(url, { method: 'GET', headers: { 'X-Requested-With':'XMLHttpRequest' }});
      const html = await res.text();

      grid.innerHTML = html;   // replace grid content
      hideBubble();            // tutup panel setelah apply
    } catch (err) {
      console.error('Filter fetch error', err);
    } finally {
      submit && (submit.disabled = false);
    }
  });
})();

(function () {
  const btn = document.getElementById('navToggle');
  const drawer = document.getElementById('mobileDrawer');
  const dim = document.getElementById('mobileDim');
  const closeBtn = document.getElementById('drawerClose');

  if (!btn || !drawer || !dim || !closeBtn) return;

  function open() {
    drawer.classList.add('is-open');
    dim.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function close() {
    drawer.classList.remove('is-open');
    dim.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  btn.addEventListener('click', open);
  closeBtn.addEventListener('click', close);
  dim.addEventListener('click', close);
  document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') close(); });
})();

// ===== Mobile-first: URL room utk setiap item + handler klik =====
(function () {
  const root = document.getElementById('chatSplit');
  if (!root) return;

  const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;
  const roomTpl = root.dataset.roomUrlTemplate; // dari index.blade.php

  // Helper: panggil ini saat kamu membuat item list di JS-mu
  // el = elemen root kartu; userId = id lawan chat
  window.attachRoomUrlToItem = function (el, userId) {
    if (!el || !roomTpl || !userId) return;
    const url = roomTpl.replace('___ID___', userId);
    el.setAttribute('data-chat-url', url);
    el.style.cursor = 'pointer';
  };

  // Delegasi klik pada list: mobile => redirect ke room
  const list = document.getElementById('chatConvList');
  if (!list) return;

  // Ambil URL dari item yang diklik
  function getRoomUrlFrom(target) {
    // prioritas: atribut data-chat-url di item root
    const item = target.closest('[data-chat-url]');
    if (item) return item.getAttribute('data-chat-url');

    // kalau item berupa <a href="/chat/{id}">
    const a = target.closest('a[href]');
    if (a && /\/chat\//.test(a.getAttribute('href'))) return a.getAttribute('href');

    return null;
  }

  list.addEventListener('click', function (e) {
    const url = getRoomUrlFrom(e.target);
    if (!url) return;

    if (isMobile()) {
      e.preventDefault();
      e.stopPropagation();
      window.location.assign(url);
    }
    // desktop: biarkan handler lama (load ke pane kanan) berjalan
  }, true);
})();

// === Open chat from card: MOBILE => langsung ROOM, DESKTOP => tetap split ===
(() => {
  const isMobile = () => window.matchMedia('(max-width: 991.98px)').matches;

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.js-chat-open, [data-chat-user-id]');
    if (!btn) return;

    const id      = btn.dataset.chatUserId || btn.closest('[data-chat-user-id]')?.dataset.chatUserId;
    const urlHint = btn.dataset.chatUrl;                 // optional: langsung /chat/{id}
    const prefill = btn.dataset.prefill || '';
    const name    = btn.dataset.name || '';
    const avatar  = btn.dataset.avatar || '';
    if (!id && !urlHint) return;

    e.preventDefault();

    if (isMobile()) {
      // MOBILE → langsung masuk ROOM (/chat/{id}?prefill=...)
      let url = urlHint || ('/chat/' + encodeURIComponent(id));
      const qp = new URLSearchParams();
      if (prefill) qp.set('prefill', prefill);
      if (name)    qp.set('name', name);
      if (avatar)  qp.set('avatar', avatar);
      const qs = qp.toString();
      if (qs) url += (url.includes('?') ? '&' : '?') + qs;
      window.location.assign(url);
    } else {
      // DESKTOP/TABLET → tetap halaman split (list + thread)
      // lewatkan user sebagai query agar pane kanan terbuka
      const url = '/chat?user=' + encodeURIComponent(id);
      window.location.assign(url);
    }
  });
})();

document.addEventListener("DOMContentLoaded", function () {
  // ambil semua input yang bisa invalid
  const inputs = document.querySelectorAll(".form-control");

  inputs.forEach(input => {
    // ketika user klik / fokus ulang
    input.addEventListener("focus", () => {
      if (input.classList.contains("is-invalid")) {
        input.classList.remove("is-invalid");
      }
    });

    // juga hilangkan bubble saat user mulai mengetik
    input.addEventListener("input", () => {
      if (input.classList.contains("is-invalid")) {
        input.classList.remove("is-invalid");
      }
    });
  });
});