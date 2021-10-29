

<nav class="navbar is-spaced is-full-width" role="navigation" data-aos data-start="top 10%">
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
      <div class="navbar-end">
        <div class="navbar-item">
          <div class="buttons is-flex align-items-center">
            <a style="pointer-events: auto;background: transparent;border:none;" href="/ingreso" class="has-text-white has-margin-right-30 button is-marginless is-paddingless">Inicia sesion</a>
            <a style="pointer-events: auto;" href="/ingreso" class="button is-outlined is-light is-marginless">
              Regístrate
            </a>
            
            <div class="open-menu has-margin-left-40" style="pointer-events: auto;cursor:pointer" >
              <svg id="Component_2_3" data-name="Component 2 – 3" xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 44 45">
                <circle id="Ellipse_1" data-name="Ellipse 1" cx="7" cy="7" r="7" fill="#fff"/>
                <circle id="Ellipse_4" data-name="Ellipse 4" cx="7" cy="7" r="7" transform="translate(0 31)" fill="#fff"/>
                <circle id="Ellipse_2" data-name="Ellipse 2" cx="7" cy="7" r="7" transform="translate(30)" fill="#fff"/>
                <circle id="Ellipse_3" data-name="Ellipse 3" cx="7" cy="7" r="7" transform="translate(30 31)" fill="#fff"/>
              </svg>
  
              <span class="has-text-white" data-feather="x" width="35" height="35" > </span>
            </div>
                      
          </div>
        </div>
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