<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')

</head>
<body id="app-layout">
{{-- */$i=rand(0,1);/* --}}
@if($i%2)
  <section class="hero is-info is-large is-bold">
@else
  <section class="hero is-primary is-large is-bold">
@endif
  <!-- Hero header: will stick at the top -->
  <div class="hero-head">
    @include('layouts.navbar_menu') 
  </div>
    @include('banner')
 </section>

  @yield('content')

  @include('footer')

  @yield('js')
</body>
</html>
