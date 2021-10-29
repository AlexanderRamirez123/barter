@set($categories, get_categories([
    "hide_empty" => false,
    'p__in'  => ["27","22"]
]))

<div class="modal" id="modal-crear-transaccion">
    <div class="modal-background"></div>

    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Nueva Transacción</p>
            <button class="delete" aria-label="close" onclick="showModalCrearTransaccion()"></button>
        </header>

        <section class="modal-card-body">

            <form action='/wp/wp-admin/admin-post.php' method="POST">

                <input type="hidden" name="action" value="handle_crear_transaccion">
                <?php wp_nonce_field( 'handle_crear_transaccion', 'handle_crear_transaccion_nonce' ); ?>

                <input type="hidden" name="operacion_id" id="operacion_id" value="">

                <div class="level">
                    <p>Monto:</p>
                    <input class="input" type="number" step="any" id="monto" name="monto" value="" placeholder="Monto de la transacción">
                </div>

                <input type="submit" class="button is-primary" value="Crear Transacción">
            </form>
        </section>
    </div>
</div>

<script>
    const showModalCrearTransaccion = (operacion_id) => {
        document.querySelector("#operacion_id").value = operacion_id;
        document.querySelector("#modal-crear-transaccion").classList.toggle("is-active")
    }
</script>