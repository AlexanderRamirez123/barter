<div class="tarjetas-hero is-full-width btn-g font-w is-500 has-padding-40 has-text-dark flex-column" style="background: white">
    @global($post)

    <a class="button is-primary" href="/dashboard">Dashboard</a>
    <br>

    <strong>Estado: </strong> @field("estado")
    <br>
    <strong>Tipo:</strong>  @field("tipo")
    <br>
    <strong>Monto:</strong>@field("monto")
    <br>
    <strong>Fecha:</strong>@field("fecha_creacion")

    <button class="button is-success" id="next">
        Continuar
    </button>
</div>