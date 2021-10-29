@set($user,wp_get_current_user())

<nav class="navbar">
    <div class="container is-max-widescreen">
      <div id="navMenu" class="navbar-menu">      
        <div class="navbar-end">
            <p>{{$user->display_name}}</p>
        </div>
      </div>
    </div>
  </nav>
