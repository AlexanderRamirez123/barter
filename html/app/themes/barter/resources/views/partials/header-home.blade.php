<nav class="navbar-home is-spaced is-full-width" role="navigation">
  <div class="container">
    <div class="navbar-brand">
      <a  style="pointer-events: auto;"class="level-item has-margin-right-20 has-padding-5" href="{{home_url()}}">
        <img src="{{home_url('/app/uploads/2021/04/secondary-2.png')}}" width="200">
      </a>
      @include('partials.translator')
    </div>

    <div id="navbar-menu" class="navbar-menu">
      <div class="navbar-start">
      </div>
    </div>
  </div>
  
</nav>

<div class="menu-desplegable">
  <div class="menu-items-des container">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'navbar-item is-flex-desktop', 'echo' => false]) !!}
      @endif
  </div>
</div>