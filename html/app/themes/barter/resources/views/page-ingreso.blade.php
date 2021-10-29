@extends('layouts.app')

@section('content')

@include('partials.page-header-small')
<div class="container">
    <div class="tarjetas-hero is-full-width has-background-white font-w is-500 has-padding-40 has-text-primary flex-column">
        <div class="columns"> 
            <div class="column">
                <h2 class="title is-4"><span class="has-text-weight-light">Regístrate</span> Ahora</h2>
                <br>
                <form action='/wp/wp-admin/admin-post.php' method="POST">
    
                    <input type="hidden" name="action" value="handle_register">
                    <?php wp_nonce_field( 'handle_register', 'handle_register_nonce' ); ?>
    
                    <p class="username">
                        <label>Nombre de Usuario:</label>
                        <input class="input" type="text" name="usuario" value="" placeholder="Usuario">
                    </p>
    
                    <p class="password">
                        <label>Contraseña:</label>
                        <input class="input" type="password" name="clave" value="" placeholder="Clave">
                    </p>
    
                    <p class="correo">
                        <label>Correo:</label>
                        <input class="input" type="email" name="correo" value="" placeholder="Correo">
                    </p>

                    <button type="submit" class="button is-primary has-margin-top-20">Regístrate Ahora</button>
                </form>  
                <hr>    
            </div>
            <div class="column ">    
                @include("components.passport")
                <h2 class="title is-4"><span class="has-text-weight-light">¿Ya tienes una cuenta?</span> Ingresa ahora</h2>
                <br>
                <form name="loginform" id="loginform" action="/wp/wp-login.php" method="post">
                    
                    <p class="login-username">
                        <label for="user_login">Nombre de usuario o dirección de correo</label>
                        <input type="text" name="log" id="user_login" class="input" value="" size="20">
                    </p>
                    <p class="login-password">
                        <label for="user_pass">Contraseña</label>
                        <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
                    </p>
                    <div class="login-submit buttons  has-margin-top-20">
                        <input type="hidden" name="redirect_to" value="/dashboard">
                        <button class="button is-primary is-outlined">Ingresa Ahora</button> 
                    </div>
                    
                    
                </form>  
            </div>
        </div>
    </div>
    
</div>

@endsection
