@extends('layouts.app')
@section('title', 'Lova | Find Your Perfect Match & Start Dating Today')
@section('content')
<section class="py-4">
  <div class="container">
    <div  id="fdoGrid" class="row g-3 g-lg-4">

      @foreach ($users as $u)
        @include('partials.user-card', ['u' => $u])
      @endforeach

    </div>

  </div>
</section>
@endsection
