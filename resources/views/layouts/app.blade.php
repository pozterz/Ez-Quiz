<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
</head>
<body id="app-layout">
{{-- */$i=rand(0,1);/* --}}
@if($i%2)
  <section class="hero is-info is-medium is-bold">
@else
  <section class="hero is-primary is-medium is-bold">
@endif
    <div class="hero-head">
     @include('layouts.navbar_menu') 
    </div>
    <div class="hero-body">
       @yield('content')
    </div>
  </section>
    
    @include('footer')
</body>
</html>
