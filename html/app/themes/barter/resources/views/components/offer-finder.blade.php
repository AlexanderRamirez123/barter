@set($categories, get_categories([
    "hide_empty" => false,
    'p__in'  => ["27","22"]
]))

<div class="tarjetas-hero is-full-width btn-g font-w is-500 has-padding-40 has-text-dark flex-column" style="background: white">        
    <div class="is-flex has-text-dark align-items-center is-uppercase has-margin-bottom-70 justify-space-between font-w is-500" style="color: #E3452B !important">
        <span> 
        BUSCAR 
        <strong>OFERTAS</strong> </span>

        <span 
            onclick="showModalCrearOperacion()"
            width="25" 
            height="25" 
            style="flex:none;cursor:pointer" 
            data-feather="plus-square" 
            class="has-margin-left-15">
        </span>
    </div>

    <div class="level">
        <input type="radio" id="tipo_ope" name="tipo" value="venta" class="input is-primary" style="height: 25px;width: 25px;margin-right: 10px;">
        <b class="is-uppercase is-size-7">VENDER</b>
        
        <input type="radio" id="tipo_ope" name="tipo" value="compra" class="input is-primary" style="height: 25px;width: 25px;margin-right: 10px;">
        <b class="is-uppercase is-size-7">COMPRAR</b>
    </div>  

    <b class="is-uppercase is-size-7">Moneda:</b>
    <div class="select is-fullwidth">
        <select id="moneda" name="moneda">
            <option value="BITCOIN">Bitcoin</option>
            <option value="ETHEREUM">Ethereum</option>
            <option value="DOGECOIN">Dogecoin</option>
            <option value="COINTRADER">CoinTrader</option>
        </select>
    </div>
    <br>

    <b class="is-uppercase is-size-7">Método de Pago:</b>
    <div class="select is-multiple is-fullwidth">
        <select multiple name="metodos[]">
            @foreach ($categories as $item)                                
                <option value="{{ $item->slug }}">{{ $item->name }}</option>
            @endforeach
        </select>
    </div>        

    <button class="button is-primary is-outlined is-fullwidth has-margin-top-30" onclick="buscar()">BUSCAR AHORA</button>
</div>

<script>

    function buscar()
    {
        let tipo = document.querySelector('input[name="tipo"]:checked').value;
        let moneda = document.getElementById("moneda").value;
        let url = "/dashboard/?tipo=" + tipo +  "&moneda=" + moneda;
        window.location.href = url;
    }
</script>