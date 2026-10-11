@extends('layouts.app')
@section('title','Lova | Messages')
@section('content')
<div id="chatSplit"
     class="chat-2col container"
     data-conv-url="{{ route('chat.conversations') }}"
     data-msg-url-template="{{ route('chat.messages', ['user' => '___ID___']) }}"
     data-send-url-template="{{ route('chat.send', ['user' => '___ID___']) }}"
     data-profile-url-template="{{ url('/u/___ID___') }}"
     data-room-url-template="{{ route('chat.show', ['user' => '___ID___']) }}"
     data-initial-user="{{ $initialUserId ?? '' }}"
     data-prefill="{{ $prefillDummy ?? '' }}"
     data-initial-name="{{ $initialName ?? '' }}"
     data-initial-avatar="{{ $initialAvatar ?? '' }}"
>
  <div class="chat-wrap rounded-2xl">
    <!-- Sidebar -->
    <aside class="chat-sidebar">
      <div class="chat-search">
        <input type="text" class="form-control" placeholder="Search Name" id="chatSearchInput">
      </div>
      <div id="chatConvList" class="chat-conv-list" role="list"></div>
    </aside>

    <!-- Main / Thread -->
    <section class="chat-main">
      <div class="chat-header">
        <div class="chat-peer">
          {{-- <img id="chatPeerAvatar" class="chat-avatar" src="" alt=""> --}}
          <img
            id="chatPeerAvatar"
            class="chat-avatar"
            src=""
            {{-- data-default-avatar="{{ asset('assets/img/icons/user-chat-default.png') }}" --}}
            alt="Message"
          >
          <div class="chat-title">
            <div id="chatPeerName" class="name">Select a conversation</div>
            <div class="sub" id="chatPeerSub"></div>
          </div>
        </div>
        <a id="chatPeerProfile" class="chat-profile-link d-none" href="#">See Profile</a>
      </div>

      <div id="chatThread" class="chat-thread"></div>

      <form id="chatForm" class="chat-form d-none" autocomplete="off">
        @csrf
        <input type="text" name="body" class="chat-input form-control" placeholder="Type a message" maxlength="2000">
        <button type="submit" class="chat-send btn btn-primary"><i class="bi bi-send"></i></button>
      </form>
    </section>
  </div>
</div>
@endsection
