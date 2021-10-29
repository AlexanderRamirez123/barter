@extends('layouts.app-home')


@global($post)

@php
    $wallet_id = $post->ID;
@endphp

@section('left')

@php
    $user = get_user_by( 'id', get_field("usuario") );
@endphp

<div class="tarjetas-hero is-full-width btn-g font-w is-500 has-padding-40 has-text-dark flex-column" style="background: white">
    <div class="title is-4 is-underlined">Datos</div>
    <ul>
        <li>
            <strong>Fecha de Creación: </strong> <br> {{ get_field("fecha_creacion") }}
        </li>
        <li>
            <strong>Criptomoneda: </strong> <br> {{get_field("criptomoneda")->post_title}}
        </li>
        <li>
            <strong>Saldo: </strong><br> {{ number_format(get_field("saldo"),6) }}
        </li>
        <li>
            <strong>Dueño: </strong> <br> {{get_field("usuario")->display_name}}
        </li>
    </ul>
    <hr>
    <h3>Operaciones</h3>
    <ul>
        <li>
            <button class="button is-success is-large" onclick="ToggleModalRecargar()">Recargar</button>
        </li>
        <li>
            <button class="button is-warning is-large" onclick="ToggleModalRetirar()">Retirar</button>
        </li>
    </ul>
</div>

<div class="modal" id="modalRecarga">
    <div class="modal-background"  onclick="ToggleModalRecargar()"></div>

    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">RECARGAR</p>
            <button class="delete" aria-label="close"  onclick="ToggleModalRecargar()"></button>
        </header>

        <form action='/wp/wp-admin/admin-post.php' method="POST">
            <section class="modal-card-body">
                <div class="container">
                    <div class="column is-12">
                        <input type="hidden" name="action" value="handle_crear_movimiento">

                        <?php wp_nonce_field( 'HandleCrearMovimiento', 'handle_crear_movimiento_nonce' ); ?>
                    
                        <div class="title">Recarga</div>

                        <input class="input" type="hidden" name="tipo" value="Recarga">

                        <input class="input" type="hidden" name="wallet_id" value="{{ $wallet_id }}">

                        <table class="table is-hover is-stripped">
                            <tbody>
                                <tr>
                                    <th>
                                        <p>Monto:</p>
                                    </th>
                                    <td>
                                        <input class="input" type="number" name="monto" step="0.0000001" value="" placeholder="Monto">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <p>Hash:</p>
                                    </th>
                                    <td>
                                        <input class="input" type="text" name="hash" value="" placeholder="Hash">
                                    </td>
                                </tr>
                            </th>
                            </tbody>
                        </table>                          
                    </div>                    
                </div>            
            </section>

            <footer class="modal-card-foot">
                <input type="submit" class="button is-primary" value="Crear Movimiento">
                <button class="button" onclick="ToggleModalRecargar()">Cancel</button>
            </footer>
        </form>
    </div>
</div>

<div class="modal" id="modalRetiro">
    <div class="modal-background" onclick="ToggleModalRetirar()"></div>
    <div class="modal-card">
        <header class="modal-card-head">
        <p class="modal-card-title">RETIRAR</p>
        <button class="delete" aria-label="close" onclick="ToggleModalRetirar()"></button>
        </header>
        <section class="modal-card-body">
        <!-- Content ... -->
        </section>
        <footer class="modal-card-foot">
        <button class="button is-success">Save changes</button>
        <button class="button">Cancel</button>
        </footer>
    </div>
</div>

<script>

    function ToggleModalRecargar()
    {
        document.querySelector("#modalRecarga").classList.toggle("is-active");
        document.querySelector("#modalRecarga").classList.toggle("is-clipped");
    }

    function ToggleModalRetirar()
    {
        document.querySelector("#modalRetiro").classList.toggle("is-active");
        document.querySelector("#modalRetiro").classList.toggle("is-clipped");
    }

</script>

@endsection

@section('content')

<div class="tarjetas-hero is-full-width btn-g font-w is-500 has-padding-40 has-text-dark flex-column" style="background: white">
    @query([
        'post_type' => 'movimientos',
        "meta_key" => "wallet",
        "meta_value" => $wallet_id
    ])
    @posts
        @global($post)
        @include("partials.movimiento")
    @endposts
</div>

@endsection