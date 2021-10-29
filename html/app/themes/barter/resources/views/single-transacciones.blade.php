@extends('layouts.app-home')

@section('left')
@include('components.transaction-status')
@endsection

@section('right')
 @include("components.account-state") 
@endsection

@section('content')

@global($post)
@set($channel_id, get_field('channel_id'))

{{--  --}}
<div class="tarjetas-hero is-full-width btn-g font-w is-500 is-paddingless has-text-dark flex-column is-dark">
    <section class="section hero is-light is-fullheight">
    <div class="hero-body">
        <div class="container">
            <div class="columns">
                <div class="column is-12">

                    <div class="card">

                        @set($operation, get_field('operacion'))
                        @query([
                            'p'         => $operation->ID, // ID of a page, post, or custom type
                            'post_type' => 'operaciones'
                        ])
                        @posts
                        @include('partials.card-oferta')
                        @endposts

                        <div id="chatbox-area">
                            <div class="card-content chat-content">
                                <div id="messages" class="content">
                                    
                                </div>
                            </div>
                            
                            <footer id="chatBox-textbox" class="card-footer has-padding-20">
                                <form name="publish" class="form column is-12">
                                    <fieldset class="field has-addons level">
                                        <div class="control is-expanded">
                                            <input class="input" id="message" type="text">
                                        </div>
                                        <div class="control">
                                            <button class="button is-primary">
                                                Enviar
                                            </button>
                                        </div>
                                    </fieldset>
                                </form>
                            </footer>
                        </div>
                    </div>

                </div>
                <div class="column is-half"></div>
            </div>
        </div>
    </div>
    </section>
</div>


{{--  --}}
@js("permalink", get_the_permalink())
<script>

const startWebsocket = () => {
    const channel_id = "{{$channel_id}}"
    const userid = "{{wp_get_current_user()->ID}}"
    const username = "{{wp_get_current_user()->display_name}}"

    let server ="{{get_field('chat_server', 'option')}}";
 
    let socket = new WebSocket(server);

    socket.onopen = function (e) 
    {
        let message = {
            "procedure" : "GET_CHANNEL",
            "channel_id" : channel_id,
            "user_id" : userid,
            "user_name" : username,
        }
        socket.send(JSON.stringify(message))
    }

    /** Escribiendo eventos del chat */

    const next = document.querySelector('#next')
    next.addEventListener('click',() => {
        let message = {
            "procedure" : "SET_STATE",
            "transaccion_id" : "{{ $post->ID }}",
            "channel_id" : "{{ $channel_id }}",
            "accion" : "SIGUIENTE"
        }
        console.log(message)
        socket.send(JSON.stringify(message))
    })

    document.forms.publish.onsubmit = function (e) {
        e.preventDefault()
        let outgoingMessage = {
            "procedure" : "ADD_MESSAGE",
            "channel_id" : channel_id,
            "user_id" : userid,
            "text" : this.message.value,
            "permalink": window.permalink,
        }
        socket.send(JSON.stringify(outgoingMessage))
        this.message.value = ''
        return false
    }

    // mensaje recibido - muestra el mensaje en div#messages
    socket.onmessage = function (event) {
        let data = JSON.parse(event.data) 
        console.log(data)
        
        
        data.data.messages?.forEach(e => {
            let messageElem = document.createElement('div')
            messageElem.innerHTML = drawMessage(e)
            let container =  document.getElementById('messages')
            container.append(messageElem)
            container.scrollTop = container.scrollHeight
        })
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

    const drawMessage = (e) => {
        let message

        if(userid != e.user_id){
            let date = new Date(e.datetime)
            message =
            `<div data-username="${e.user_name}" class="chat-message-group has-margin-top-10 has-text-left">
                <div class="tag is-dark is-medium">
                    <p>${e.text}</p>
                </div>
                <div class="from has-text-grey is-size-7">A las ${date.getHours()}:${date.getMinutes()}  por <b>${e.user_name}</b></div>
            </div>`
        
        }else{
            let date = new Date(e.datetime)
            message =
            `<div data-username="${e.user_name}" class="chat-message-group has-margin-top-10 has-text-right">
                <div class="tag is-primary is-medium">
                    <p>${e.text}</p>
                </div>
                <div class="from has-text-grey is-size-7">A las ${date.getHours()}:${date.getMinutes()} por <b>${e.user_name}</b></div>
            </div>`
        }

        return message
    }    
}
startWebsocket()

</script>

<style>.content#messages {
  height: 400px;
  overflow-y: scroll;
}</style>

@endsection
