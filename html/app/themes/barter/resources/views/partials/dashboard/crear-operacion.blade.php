@set($categories, get_categories([
    "hide_empty" => false,
    'p__in'  => ["27","22"]
]))

<div class="modal" id="modal-crear-operacion">
    <div class="modal-background"></div>
    
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title is-uppercase"><b>Nueva</b> Operación</p>
            <button class="delete" aria-label="close" onclick="showModalCrearOperacion()"></button>
        </header>

        <section class="modal-card-body">

            <form action='/wp/wp-admin/admin-post.php' class="form is-uppercase" method="POST">

                <input type="hidden" name="action" value="handle_crear_operacion">
                <?php wp_nonce_field( 'handle_crear_operacion', 'handle_crear_operacion_nonce' ); ?>

                <div class="level">
                    <p class="has-margin-right-20">Tipo de Operación:</p>
                    <div class="select is-fullwidth">
                        <select name="tipo">
                        <option value="compra">Compra</option>
                        <option value="venta">Venta</option>
                        </select>
                    </div>
                </div>
                <hr>

                <div class="columns level has-margin-right-20">
                    <div class="column" data-tooltip="Se recomienda 2% por encima/debajo del mercado."><b>Precio </b>por moneda:
                        <div class="select is-fullwidth">
                            <select name="moneda">
                                <option value="BITCOIN">Bitcoin</option>
                                <option value="ETHEREUM">Ethereum</option>
                                <option value="DOGECOIN">Dogecoin</option>
                                <option value="COINTRADER">CoinTrader</option>
                            </select>
                        </div>
                    </div>
                    <div class="column" data-tooltip="Se recomienda 2% por encima/debajo del mercado."><b>Precio </b>por moneda:
                        <fieldset class="field has-addons">
                            <div class="contro"><input class="input number" type="number" step="any" name="precio" value="" placeholder="Precio"></div>
                            <div class="control"><div class="button  is-static">USD</div></div>
                        </fieldset>
                    </div>
                </div>

                <div class="columns level has-margin-right-20">
                    <div class="column" data-tooltip="Es el monto mímimo que aceptas para aprobar una transacción.">Monto <b>Mínimo </b>:
                        <input class="input" type="number" step="any" name="monto_minimo" value="" placeholder="Monto Mínimo">
                    </div>
                    <div class="column" data-tooltip="Es el monto máximo que aceptas para aprobar una transacción.">Monto <b>Máximo </b>:
                        <input class="input" type="number" step="any" name="monto_maximo" value="" placeholder="Monto Máximo">
                    </div>
                </div>
                <hr>
                <div class="level">
                    <p>Métodos de Pago:</p>
                    <div class="select is-multiple">
                        <select multiple name="metodos[]">
                            @foreach ($categories as $item)                                
                                <option value="{{ $item->slug }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="submit" class="button is-primary " value="Registrar">
            </form>
        </section>
    </div>
</div>

<script>
    const showModalCrearOperacion = (operacion_id) => {
        document.querySelector("#modal-crear-operacion").classList.toggle("is-active")
    }
</script>