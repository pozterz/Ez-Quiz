<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
</head>
<body id="app-layout">
  @if(Auth::user()->type === 'student')
    <section class="hero is-info is-bold">
  @elseif(Auth::user()->type === 'teacher')
    <section class="hero is-primary is-bold">
  @endif
    <div class="hero-head">
     @include('layouts.navbar_menu') 
    </div>
    <div class="hero-body">
      @yield('content')
    </div>
  </section>
  @include('footer')
  @yield('js')
</body>
</html>
