<div class="card box has-padding-40 has-text-dark has-margin-top-10" 
        style="background: white; width: 80%; margin-left:10%; top:-50px;">

        <div class="columns">

            <div class="column">
                <strong class="is-size-4">                        
                    @field("moneda")
                </strong>
                <br>
                <strong class="is-size-4">
                    {{ number_format(get_field("precio"), 2, ",", ".") }}
                </strong>
            </div>

            <div class="column">
                <strong class="is-size-4">                        
                    @field("tipo")
                </strong>
                <br>
                @author
            </div>

            <div class="column">
                @field("fecha_creacion")
            </div>

            <div class="column">
                <p>@field("monto_minimo") -> @field("monto_maximo")</p>
            </div>

            <div class="column">
                @php
            
                    $medios = get_field("medios_de_pago", $post->ID);

                @endphp
                
                <ul>
                @foreach ($medios as $item)
                    <li>{{$item->name}}</li>
                @endforeach
                </ul>
            </div>

            
        </div>
        @if(is_page('dashboard'))
        <button class="button is-primary is-outlined" onclick="showModalCrearTransaccion({{$post->ID}})">Iniciar Transaccion</button>
        @endif
    </div>