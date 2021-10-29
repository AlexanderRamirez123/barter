{{--  --}}
@script('https://cdn.jsdelivr.net/npm/bulma-toast@2.4.1/dist/bulma-toast.min.js')

<script>

bulmaToast.setDefaults({
  duration: 6000,
  position: 'bottom-right',
  dismissible: true,
  pauseOnHover: true
})

const startWebsocket = () => {
    const userid = "{{wp_get_current_user()->ID}}"
    const username = "{{wp_get_current_user()->display_name}}"

    let server ="{{get_field('chat_server', 'option')}}";

    let socket = new WebSocket(server)

    socket.onopen = function (e) {
 
        let message = {
            "procedure" : "LOGIN",
            "user_id" : userid,
            "user_name" : username,
        }
        socket.send(JSON.stringify(message))
    }


    // mensaje recibido - muestra el mensaje en div#messages
    socket.onmessage = function (event) {
        let data = JSON.parse(event.data) 
        console.log(data)
        if(data.data?.type == "notification")
        bulmaToast.toast({ message: toastBuilder(data.data.message, data.data.permalink ), type: 'is-success' })
    }

    socket.onclose = function (event) {
        if (event.wasClean) {
            console.warn(`[close] Connection closed - Cleanly, code=${event.code} reason=${event.reason}`)
        } else {
            // ej. El proceso del servidor se detuvo o la red está caída
            // event.code es usualmente 1006 en este caso
            console.warn('[close] Connection Closed')
        }
        ws = null
        setTimeout(startWebsocket, 5000)
    }

    socket.onerror = function (error) {
        console.error(`[error] ${error.message}`)
    }
}
startWebsocket()

const toastBuilder = (message, url) => {
    return   `
        <div class="has-text-weight-bold">
            <p>${message}</p>
        </div>
        <a href="${url}" class="button is-small is-dark">Ir a la transacción</a>
    `
}

</script>