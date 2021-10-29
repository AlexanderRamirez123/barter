<div class="tarjetas-hero is-full-width has-background-white font-w is-500 has-padding-40 has-text-primary flex-column">
  <form action="/dashboard" class="form">
    <div class="is-flex align-items-center has-margin-bottom-70 is-uppercase justify-space-between font-w is-500">
      <span>Compra cripto <strong class="has-text-dark">Ahora</strong></span><span width="25" height="25"
        style="flex:none;" data-feather="plus-square" class="has-margin-left-15"></span>
    </div>
    <h3 class="is-size-4 font-w is-600">
      Compra <strong class="has-text-dark">Cripto</strong> con tu Tarjeta, <br> o con <strong
        class="has-text-dark">otras Cripto</strong>
    </h3>
    <div class="columns">
      <div class="column is-paddingless has-margin-right-15 my-6 is-6">
        <div class="box tj-l has-padding-10 has-cursor-pointer is-small-text">
          <span class="has-margin-bottom-10 is-inline-flex">Voy a comprar</span>
          <div class="is-flex align-items-center justify-space-between">
            <fieldset class="field has-addons">
              <div class="control">
                <input name="buying" data-currency="bitcoin" id="buy-currency" class="currency-input input is-small" type="number" step="any"
                   step="any" placeholder="0.00000000">
              </div>
              <div class="control">
                <div class="select is-coin is-small" data-input="buy-currency">
                  <img class="image is-16x16 badge"
                    src="https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579" alt="">
                  <select class="has-coin">
                    <option data-icon="https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579"
                      value="bitcoin">BTC</option>
                    <option data-icon="https://assets.coingecko.com/coins/images/279/large/ethereum.png?1595348880"
                      value="ethereum">ETH</option>
                    <option data-icon="https://assets.coingecko.com/coins/images/5/large/dogecoin.png?1547792256"
                      value="dogecoin">DOGE</option>
                    <option data-icon="@asset('images/ctd.png')" value="cointrader">Cointrader</option>
                    <option data-icon="@asset('images/usd.png')" value="usd">USD</option>
                  </select>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
      </div>
      <div class="column is-paddingless has-margin-right-15 my-6 is-6">
        <div class="box tj-l has-padding-10 has-cursor-pointer is-small-text">
          <span class="has-margin-bottom-10 is-inline-flex">Pagando con</span>
          <fieldset class="field has-addons">
            <div class="control">
              <input disabled data-currency="usd" id="pay-currency" class="currency-input input is-small" type="number" step="any" min="1"
                step="any" placeholder="0.00000000">
            </div>
            <div class="control">
              <div class="select is-coin is-small" data-input="pay-currency">
                <img class="image is-16x16 badge" src="@asset('images/usd.png')" alt="">
                <select class="has-coin">
                  <option data-icon="@asset('images/usd.png')" value="usd">USD</option>
                  <option data-icon="https://assets.coingecko.com/coins/images/1/large/bitcoin.png?1547033579"
                    value="bitcoin">BTC</option>
                  <option data-icon="https://assets.coingecko.com/coins/images/279/large/ethereum.png?1595348880"
                    value="ethereum">ETH</option>
                  <option data-icon="https://assets.coingecko.com/coins/images/5/large/dogecoin.png?1547792256"
                    value="dogecoin">DOGE</option>
                  <option data-icon="@asset('images/ctd.png')" value="cointrader">Cointrader</option>
                </select>
              </div>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
    <p class="has-margin-bottom-30 has-text-dark is-size-7"><strong>Importante:</strong> Los valores autocalculados son
      aproximados. </p>
    <button type="submit" class="button is-outlined is-primary is-uppercase">COMPRAR AHORA</button>
  </form>
</div>

<style>
  .select.is-coin::after {
    display: none !important;
  }

  .select.is-coin .badge {
    position: absolute;
    top: 50%;
    right: 10px;
    z-index: 999;
  }

</style>
<script>
  // Estado de precios
  let market = ''

  fetch(
      'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=100&page=1&sparkline=false'
      )
    .then(response => response.json())
    .then(data => market = data)
    .then(function(){
        market.push({
            "id": "cointrader",
            "current_price": 1,
        },{
            "id": "usd",
            "current_price": 1,
        })
    })

  // Calculadora de precios 
  const amountInputs = document.querySelectorAll('.currency-input')

  amountInputs.forEach(input => {
    input.addEventListener('change', (e) => {
        updateCurrencies(input)
    })
  })

  //Actualizar valores

  function updateCurrencies(input) {
    const buy = document.querySelector('#buy-currency')
    const pay = document.querySelector('#pay-currency')

    let enter = input.dataset.currency
    if (input.id === 'pay-currency') {
      buy.value = calcRates(input.value, enter, buy.dataset.currency)
    } else {
      pay.value = calcRates(input.value, enter, pay.dataset.currency)
    }
  }

  //Calcular Rates

  function calcRates(amount, enter, out) {
    const from = filterById(enter)[0].current_price, to = filterById(out)[0].current_price
    let value = amount*from/to
    return value
  }

  // Selector de monedas 
  const currencySelectors = document.querySelectorAll('.is-coin')

  currencySelectors.forEach(coin => {
    coin.addEventListener('change', (e) => {
      const index = e.target.options.selectedIndex
      const icon = e.target.options[index].dataset.icon
      const currency = e.target.options[index].value
      const input = document.getElementById(coin.dataset.input)
      setIcon(coin, icon)
      setCurrency(input, currency)
      updateCurrencies(document.getElementById('buy-currency'))
    })
  })

  //Setear el ícono

  function setIcon(coin, icon) {
    const badge = coin.querySelector('.badge')
    badge.src = icon
  }

  //Setear el currency en el input

  function setCurrency(input, currency) {
    input.dataset.currency = currency
  }

  // filtro de id para el json

  function filterById(id) {
    return market.filter(
        function(market){ return market.id == id }
    )
  }

</script>
