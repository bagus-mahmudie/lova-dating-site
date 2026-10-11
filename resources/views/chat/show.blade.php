@extends('layouts.app')
@section('title', 'Chat')

@section('content')
<div id="chatApp"
     class="chat-page container py-3"
     data-messages-url="{{ route('chat.messages', $other) }}"
     data-send-url="{{ route('chat.send', $other) }}"
     data-auth-id="{{ auth()->id() }}"
     data-other-name="{{ $other->name }}"
     data-other-avatar="{{ $other->avatar_url }}"
>
  <div class="chat-card rounded-2xl">
    <div class="chat-header">
      <a href="{{ route('chat.index') }}" class="chat-back" aria-label="Kembali">
        <i class="bi bi-chevron-left"></i>
      </a>
      <img class="chat-avatar" src="{{ $other->avatar_url }}" alt="{{ $other->name }}">
      <div class="chat-title">
        <div class="name">{{ $other->name }}</div>
      </div>
    </div>

    <div id="chatThread" class="chat-thread" aria-live="polite"></div>

    <form id="chatForm" class="chat-form" autocomplete="off">
      @csrf
      <input type="text" name="body" class="chat-input form-control" placeholder="Tulis pesan..." maxlength="2000">
      <button class="chat-send btn btn-primary" type="submit" aria-label="Kirim">
        <i class="bi bi-send"></i>
      </button>
    </form>
  </div>
</div>
@endsection
