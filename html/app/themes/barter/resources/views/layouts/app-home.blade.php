@js('dashboard', is_page('dashboard'))

@include('partials.dashboard.crear-operacion')
@include('partials.dashboard.crear-transaccion')

@js('transacciones', is_singular('transacciones'))
@if(!is_singular('transacciones'))
  @include('components.notify')
@endif


<div id="app" class="public-front">
  @include('partials.header-home')
  <span class="pointer-sombra"></span>
  <input type="hidden" id="ajaxurl" value="{{admin_url('admin-ajax.php')}}">
  <div class="dashboard is-fullheight has-padding-top-40">
     <!-- left panel -->
      <div class="dashboard-panel is-two-fifths is-fullheight has-background-light" style="min-height: 100vh">
        @yield('left')
      </div>

      <!-- main section -->
      <div class="dashboard-main is-scrollable scrollContainer has-padding-40 has-background-light">

        <main class="main has-background-light">
          @yield('content')
        </main>

        @include('partials.footer')
      </div>

      <!-- right panel -->
      <div class="dashboard-panel is-two-fifths is-scrollable has-background-light" style="min-height: 100vh">
        @yield('right')
      </div>
  </div>
</div>
