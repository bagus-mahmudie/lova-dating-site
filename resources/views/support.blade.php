@extends('layouts.app')
@section('title', 'Lova | Frequently Asked Questions about Our Services')
@section('content')
{{-- ===== FAQ ===== --}}
<section class="faq-area">
  <div class="container">

    <div class="text-center">
      <h2 class="title-faq">Frequently Asked Questions<br>And Helpful Support</h2>
      <p class="text-faq">
        Explore answers to common questions, discover helpful guides, and access reliable
        support designed to make your dating journey simple, safe, and enjoyable.
      </p>
    </div>

    <div class="faq-accordion" id="faqAccordion">
      {{-- Item 1 --}}
      <div class="faq-item">
        <button class="faq-head" type="button" aria-expanded="false" aria-controls="faq-1">
          <span class="faq-q">Are all profiles on this website genuine?</span>
          <span class="faq-ico" aria-hidden="true"></span>
        </button>
        <div class="faq-body" id="faq-1">
          <div class="faq-body__inner">
            Yes, every profile goes through our verification system to reduce fake accounts and scams. We use photo verification, activity checks, and optional ID verification to ensure authenticity. This way, you can feel confident that the people you interact with are genuine and trustworthy.
          </div>
        </div>
      </div>

      {{-- Item 2 - default OPEN --}}
      <div class="faq-item is-open">
        <button class="faq-head" type="button" aria-expanded="true" aria-controls="faq-2">
          <span class="faq-q">How do you keep users safe while dating online?</span>
          <span class="faq-ico" aria-hidden="true"></span>
        </button>
        <div class="faq-body" id="faq-2">
          <div class="faq-body__inner">
            Your safety is our top priority. We encourage users to verify their profiles, and we provide tools to block or report suspicious accounts. Additionally, we never share your private data publicly, and we give tips for safe conversations and offline meetups. By combining technology with education, we make online dating safer and more enjoyable.
          </div>
        </div>
      </div>

      {{-- Item 3 --}}
      <div class="faq-item">
        <button class="faq-head" type="button" aria-expanded="false" aria-controls="faq-3">
          <span class="faq-q">I'm shy. How can I start a conversation with my match?</span>
          <span class="faq-ico" aria-hidden="true"></span>
        </button>
        <div class="faq-body" id="faq-3">
          <div class="faq-body__inner">
            We understand that starting a conversation can feel difficult. That’s why we provide icebreaker features and conversation prompts directly on the website to help you. You can also look at your match’s hobbies or profile interests for natural topics. Even a simple “Hi, I love the music you listed on your profile” can open the door to a meaningful chat.
          </div>
        </div>
      </div>

      {{-- Item 4 --}}
      <div class="faq-item">
        <button class="faq-head" type="button" aria-expanded="false" aria-controls="faq-4">
          <span class="faq-q">Is my personal information secure on this website?</span>
          <span class="faq-ico" aria-hidden="true"></span>
        </button>
        <div class="faq-body" id="faq-4">
          <div class="faq-body__inner">
            Absolutely. We use strong encryption to protect your personal information and follow international privacy standards. Your details such as email, phone number, and location are never displayed publicly. You are always in full control of what you want to share, giving you peace of mind while you focus on building real connections.
          </div>
        </div>
      </div>

      {{-- Item 5 --}}
      <div class="faq-item">
        <button class="faq-head" type="button" aria-expanded="false" aria-controls="faq-5">
          <span class="faq-q">How can I delete my account if I no longer want to use it?</span>
          <span class="faq-ico" aria-hidden="true"></span>
        </button>
        <div class="faq-body" id="faq-5">
          <div class="faq-body__inner">
            Deleting your account is simple and always your choice. Just go to Settings → Account → Delete Account and follow the steps. Once deleted, your profile and data will be permanently removed from our website. We believe every user should have full control over their dating journey at any time.
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection
