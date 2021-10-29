<div class="tarjetas-hero has-background-dark box">
    <div class="title is-size-5 has-text-weight-light has-text-primary" style="letter-spacing: 2px;">
        Hola <b>{{wp_get_current_user()->display_name}}</b>, 
        <br>Aquí puedes ver tu 
        <br> Estado de cuenta 
    </div>
    
    <div class="has-max-height-300 no-scrollbar">
        @query([
            'post_type' => 'wallets',
            'author' => wp_get_current_user()->ID
        ])

        <div class="splide is-full-width">
            <div class="splide__arrows is-flex align-items-center">
                <i class="splide__arrow splide__arrow--prev is-relative" data-feather="arrow-left-circle"></i>
                <i class="splide__arrow splide__arrow--next is-relative has-margin-left-15" data-feather="arrow-right-circle"></i>
            </div>
            <div class="splide__track is-full-width" style="overflow: unset;">
                <ul class="splide__list is-flex">
                    @posts
                        <div class="splide__slide column is-flex">
                            <div class="box" style="background: white;">

                                <div class="column">
                                    <strong class="is-size-6">                        
                                    <a href="@permalink">{{ get_field("criptomoneda")->post_name }}</a>                            
                                    </strong>
                                    
                                    <ul> 
                                        <li>
                                            <strong>{{ number_format( get_field("saldo"), 6) }}</strong>
                                        </li>
                                    </ul>                       
                                </div>
                            </div>    
                        </div>
                    @endposts
                </ul>
            </div>
        </div>
    </div>

    
    <div class="title is-size-6 has-margin-top-20 has-text-primary">transacciones recientes</div>
    @query([
        'post_type' => 'transacciones',
        'author' => wp_get_current_user()->ID
    ])

    @posts
    <a href="@permalink" class="card box">
            @php
                $operacion = get_field("operacion");
                $author_id = get_post_field( 'post_author', $operacion->ID );
                $author_name = get_the_author_meta( 'display_name', $author_id );
                $currency = get_field('moneda', $operacion->ID)
            @endphp

            <div class="columns is-gapless is-marginless level is-size-7">
                <div class="column"><b class="has-text-dark">@field("tipo")</b></div>
                <div class="column has-text-right">@field("fecha_creacion")</div>
            </div>
            <div class="columns is-gapless level is-marginless is-size-5">
                <div class="column"><b class="has-text-dark">@field("monto") {{$currency}}</b></div>
            </div>
            <div class="columns is-gapless level is-size-7">
                <div class="column">Operador: <b class="has-text-dark is-uppercase">{{$author_name}}</b></div>
                {{-- <div class="column has-text-right has-text-success"><b>@field("estado")</b></div> --}}
            </div>
    </a>
    @endposts
</div>