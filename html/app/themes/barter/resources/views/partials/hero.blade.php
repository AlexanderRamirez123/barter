
<div class="hero is-fullheight is-relative" style="padding-left: 80px;min-height:80vh">
    <div class="hero-body has-padding-bottom-140">
        <div class="container" data-aos="fade-up">
            <h1 class="title is-1" style="letter-spacing: 6px;">
                <span class="has-text-dark font-w is-400">COMPRA </span></br>
                <span class="has-magin-bottom-10 is-inline-block has-text-dark font-w is-700">CRIPTODIVISAS</span></br>
                <span class="has-text-white font-w is-400">CON </span>
                <span class="has-text-white font-w is-700">USD Y COP</span>
            </h1>
            <p class="column has-text-white is-5 is-paddingless is-small-text">
                Compra bitcoins y otras criptomonedas <strong>con tu tarjeta de crédito o débito al instante y de forma segura</strong> con las comisiones <strong>más bajas del mercado</strong>, las 24 horas del día. 
            </p>
        </div>
    </div>
</div>
<div class="hero"> 
    <div class="hero-body has-padding-bottom-100" style="margin-top: -200px">
        <div class="container">
            <div class="is-flex">
                <div class="splide is-full-width">
                    <div class="splide__arrows is-flex align-items-center">
                        <i class="splide__arrow splide__arrow--prev is-relative" data-feather="arrow-left-circle"></i>
                        <i class="splide__arrow splide__arrow--next is-relative has-margin-left-5" data-feather="arrow-right-circle"></i>
                    </div>
                    <div class="splide__track is-full-width" style="overflow: unset;">
                        <ul class="splide__list is-flex">
                            @set($coins, App\fetchCoins())
                            @foreach ($coins as $coin)
                            <li class="splide__slide column is-flex">
                                <div class="column is-12 has-text-white">
                                    <div class="tag font-w is-700 {{App\profitCheckClass($coin->price_change_percentage_24h)}} has-margin-bottom-10">
                                        {{$coin->price_change_percentage_24h}}%
                                    </div>
                                    <p class="font-w is-600 is-flex align-items-center">
                                        <img class="image is-16x16" src="{{$coin->image}}" alt="">
                                        <span class="has-margin-left-5">
                                            {{$coin->name}}
                                        </span>
                                    </p>
                                    {{-- {{print_r($coin)}} --}}
                                    <p class="is-small-text font-w is-600">USD${{$coin->current_price}}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>