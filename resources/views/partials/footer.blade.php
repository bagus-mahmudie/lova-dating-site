<footer class="site-footer">
  <div class="container">
    <div class="foot-grid">
      {{-- Col 1: Brand + deskripsi + sosmed --}}
      <div class="foot-brand">
        <div class="brand-line">
          <img src="{{ asset('assets/img/logo-navbar.png') }}" alt="like">
          <span class="footer-brand">Logo</span>
        </div>
        <p class="foot-text">
          Connecting singles across Indonesia to build real, meaningful, and safe relationships.
          Your journey to love starts here with trust and authenticity.
        </p>

        <div class="socials">
          <a href="#" aria-label="Phone"><i class="bi bi-telephone-fill"></i></a>
          <a href="#" aria-label="Email"><i class="bi bi-envelope-fill"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
          <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
        </div>
      </div>

      {{-- Col 2: Address --}}
      <div class="foot-col">
        <h6 class="foot-title">Address</h6>
        <address class="foot-addr">
          Jl. Merdeka No. 123, Jakarta,<br>
          Indonesia
        </address>
      </div>

      {{-- Col 3: Subscribe --}}
      <div class="foot-col">
        <h6 class="foot-title">Subscribe</h6>
        <p class="foot-small">Subscribe for tips, stories, and updates on finding real love online.</p>

        <form action="#" class="footer-subscribe" onsubmit="return false">
          <span class="ico"><i class="bi bi-envelope"></i></span>
          <input type="email" placeholder="Your Email" aria-label="Your Email">
          <button type="submit" class="send" aria-label="Send">
            <i class="bi bi-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>

    <p class="footer-about">
      We connect singles through verified profiles, safe interactions, and meaningful matches. Our mission is
      to make dating simple, secure, and authentic, helping you find real love and lasting happiness online.
    </p>

    <hr class="footer-line">

    <div class="footer-copy">
      All Rights Reserved ©2025 Dating Indonesia | PT. Dating Indonesia
    </div>
  </div>
</footer>
