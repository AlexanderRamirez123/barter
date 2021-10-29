<div id="app" class="is-clipped public-front">
    @include('partials.header')

    <input type="hidden" id="ajaxurl" value="{{admin_url('admin-ajax.php')}}">

    @if (is_home())
    @include('partials.marca')
    @endif

    <span class="pointer-sombra"></span>
    <div class="scrollContainer is-relative" style="background: transparent !important;z-index: 2;">

      @if (is_home())
      <div style="height: 100%;width:100vw;position: fixed;background: #E3492F;z-index: -1">
        <div class="padre-voltis"
          style="z-index: 1;mix-blend-mode: color-burn;position: fixed;top: 0;width: 250vw;height: 100%">
          <div class="bg-hero is-parallax-cover" data-scroll data-scroll-speed="-10" data-aos
            style="mix-blend-mode: color-burn;background: url({{home_url('/app/uploads/2021/04/02.png')}}) center center / cover no-repeat;height:100%;">
          </div>
        </div>
      </div>
      @endif
      <main class="main" style="background: transparent !important;">
        @yield('content')
      </main>

      @hasSection('sidebar')
      <aside class="sidebar">
        @yield('sidebar')
      </aside>
      @endif

      @include('partials.footer')

    </div>
</div>
