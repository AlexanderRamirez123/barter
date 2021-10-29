<?php

require __DIR__ . "/ChannelAdmin/ChannelController.php";

function get_operations()
{
    //$tipo = $_POST["tipo"];


    $operations = get_posts( array(
        'post_type' => 'operaciones'
    ));

    foreach ($operations as & $elem) 
    {
        //Get Fields
        $elem->user = get_userdata(get_field("usuario", $elem->ID)->ID);
        $elem->tipo = get_field("tipo", $elem->ID);
        $elem->fechaCreacion = get_field("fecha_creacion", $elem->ID);
        $elem->montoMaximo = get_field("monto_maximo", $elem->ID);
        $elem->montoMinimo = get_field("monto_minimo", $elem->ID);
        $elem->mediosDePago = get_field("medios_de_pago", $elem->ID);

        foreach ($elem->mediosDePago as & $medio) {
            $medio->imagen = get_field("imagen", "category_" . $medio->term_id);
        }
    }

    return wp_send_json(
        array(
            "operaciones" => $operations
        )
    );
}

function HandleCrearOperacion()
{
    try 
    {   
        $usuario_id = get_current_user_id();
        $tipo = $_POST["tipo"];
        $monto_maximo = $_POST["monto_maximo"];
        $monto_minimo = $_POST["monto_minimo"];
        $precio = $_POST["precio"];
        $moneda = $_POST["moneda"];

        $metodos = array();

        foreach ($_POST['metodos'] as $metodo)
        {
            $metodos[] = get_category_by_slug($metodo)->term_id;
        }

        $id = wp_insert_post(array(
            'post_title'=> "operacion_ " . $tipo . "_user_" . wp_get_current_user()->user_login . "_dateunix_" . date("Ymd_His"), 
            'post_type'=>'operaciones',
            'post_status' =>'publish'
        ), true);        

        update_field("tipo", $tipo, $id);
        update_field("fecha_creacion", date("Y-m-d H:i:s"), $id);
        update_field("usuario", $usuario_id, $id);
        update_field("monto_maximo", $monto_maximo, $id);
        update_field("monto_minimo", $monto_minimo, $id);
        update_field("precio", $precio, $id);
        update_field("moneda", $moneda, $id);

        update_field("medios_de_pago", $metodos, $id);

        wp_redirect("/dashboard");
    }     
    catch (\Exception $ex) 
    {
        throw $ex;
    }

}

function HandleCrearTransaccion()
{
    try 
    {   
        $operacion_id = $_POST["operacion_id"];
        $monto = $_POST["monto"];


        $current_user = wp_get_current_user();
        $operacion = get_post($operacion_id);
        $operacion_user = App\get_operacion_user($operacion->ID);
        $tipo_operacion = get_field("tipo", $operacion->ID);


        $tipo_transaccion = $tipo_operacion == "compra" ? "venta" : "compra";
        $channel_name = $tipo_transaccion . "_user_" . $current_user->user_login . "_dateunix_" . date("Ymd_His");

        $id = wp_insert_post(array(
            'post_title'=> $channel_name, 
            'post_type'=>'transacciones',
            'post_status' =>'publish'
        ), true);        

        update_field("operacion", $operacion->ID , $id);
        update_field("fecha_creacion", date("Y-m-d H:i:s"), $id);
        update_field("tipo", $tipo_transaccion, $id);
        update_field("monto", $monto, $id);
        update_field("estado", "INICIADA", $id);

        $users = [
            [
                "user_id" => $current_user->ID,
                "user_name" => $current_user->display_name
            ],
            [
                "user_id" => $operacion_user->ID,
                "user_name" => $operacion_user->data->display_name
            ]            
        ];

        $host = "172.17.0.2";
        $db = "chat"; 
        $user = "root"; 
        $pass = "000_amor@wallet23*"; 
        $port = "3306";   

        $channelController = new ChannelController($host, $db, $user, $pass, $port);

        $channel = $channelController->Create($channel_name, $users); 

        update_field("channel_id", $channel->channel_id, $id);

        wp_redirect("/transacciones/" . $channel_name);
    }     
    catch (\Exception $ex) 
    {
        throw $ex;
    }    
}

function HandleCrearMovimiento()
{
    try 
    {          
        $condicion1 = isset( $_POST['handle_crear_movimiento_nonce'] );
        $condicion2 = wp_verify_nonce( $_POST['handle_crear_movimiento_nonce'], 'HandleCrearMovimiento' ); 

        if($condicion1 && $condicion2)
        {
            $tipo = $_POST["tipo"];
            $wallet_id = $_POST["wallet_id"];
            $monto = $_POST["monto"];
            $hash = $_POST["hash"];

            if(strtolower($tipo) == "recarga")
            {
                try 
                {
                    // Se valida la presencia del hash
                    if($tipo === "Recarga" && $hash == null)
                        throw new \Exception("Hash missing.");

                    // Valido que el hash no haya sido utilizado previamente
                    $used = new WP_Query([
                        "post_type" => "movimientos",
                        "meta_key" => "hash",
                        "meta_value" => $hash
                    ]); 
                    if(count($used->posts) !== 0){
                        throw new \Exception("Hash used.");
                    }

                    // Obtén la wallet y luego la moneda, según la 
                    // segundo define el parámetro de $coin y de $network
                    $walletObj = get_post($wallet_id);
                    $criptomoneda = get_field("criptomoneda", $walletObj->ID);
                    $coin = strtolower((string)get_field("diminutivo", $criptomoneda->ID));
                    $network = strtolower((string)get_field("network", $criptomoneda->ID));
                        
                    // Revisamos cada sección de la transacción                    
                    $amountFound = null;
                    $addressFound = null;

                    if(strtolower($coin) !== "ctd"){
                        $url = "https://api.blockcypher.com/v1/$coin/$network/txs/$hash";

                        // Se consulta con BlockCypher la información de la transacción
                        $txs = \App\fetcher($url);

                        // Obtenemos la wallet esperada
                        $expectedAddress = (string)get_field("wallet_{$coin}_{$network}", 'option');
                        
                        // Este factor depende de la moneda. 
                        // Las derivadas a BTC usan el valor 100000000 para convetir el monto en satochis
                        $factor = 100000000;

                        foreach ($txs->outputs as $section) {
                            $receivedAmount = number_format( (float)( $section->value / $factor ), 10 );
                            $expectedAmount = number_format( (float)$monto, 10);
                            
                            // Validamos que el monto sea el correcto
                            if( $receivedAmount === $expectedAmount ){
                                $amountFound = $receivedAmount;

                                // Validar si el address se encuentra en el arreglo
                                foreach ($section->addresses as $address) {
                                    if( $address === $expectedAddress ){
                                        $addressFound = $address;
                                    }
                                }
                            }
                        }                       
                    }else{
                        //Verificación cointrader
                        $res = file_get_contents("https://ctd-api.intraders.com.co/api/hash/$hash");    
                        $transaction_information = json_decode($res);

                        $addressFound = $transaction_information->data->destinatario;
                        $amountFound = $transaction_information->data->monto;
                    }
                    
                    if($amountFound === null){
                        throw new \Exception("Wrong amount on transaction. Movement creation cancelled.");
                    } 
                    
                    if($addressFound === null){
                        throw new \Exception("Wrong address on transaction. Movement creation cancelled.");
                    } 

                    // Habiendo pasado las validaciones se crea el movimiento con éxito.
                    $id = wp_insert_post(array(
                        'post_title'=> "mov-" . \App\GenerateRandom(), 
                        'post_type'=>'movimientos',
                        'post_status' =>'publish',
                        "post_author" => wp_get_current_user()->ID
                    ), true);        

                    update_field("tipo", $tipo , $id);
                    update_field("fecha_creacion", date("Y-m-d H:i:s"), $id);
                    update_field("wallet", $wallet_id, $id);
                    update_field("monto", $monto, $id);
                    update_field("hash", $hash, $id);
                    update_field("estado", "CREADA", $id);

                    // Recalculamos el saldo de la wallet.
                    $balance = GetBalance($wallet_id);
                    update_field("saldo", $balance, $wallet_id);

                    wp_redirect("/wallet/" . get_post($wallet_id)->post_title);
                } 
                catch (\Exception $ex) 
                {
                    throw $ex;
                }
            }

            if(strtolower($tipo) == "retiro")
            {
                // Obtengo el address a donde el cliente quiere enviar su dinero
                // Creo el bloque
            }
        }
        else
        {
            throw new \Exception("Error de nounce provisto.");
        }
    }     
    catch (\Exception $ex) 
    {
        throw $ex;
    }

}

function GetBalance($wallet_id){
    $movimientos = new WP_Query([
        "post_type" => "movimientos",
        "meta_key" => "wallet",
        "meta_value" => $wallet_id
    ]);

    $sumDeposits = 0;
    $sumWithdraws = 0;

    foreach ($movimientos->posts as $post) {
        $tipo = (string)get_field("tipo", $post->ID);
        $monto = get_field("monto", $post->ID);

        if(strtolower($tipo) === "recarga"){
            $sumDeposits += (float)$monto;
        }else{            
            $sumWithdraws += (float)$monto;
        }
    }

    $total = $sumDeposits - $sumWithdraws;

    return $total;
}

function GetMetodosDePago()
{
    $cats = get_categories([
        "parent" => 34,
        "hide_empty" => false
    ]);
    
    return 0;
}

function handle_register()
{
    try 
    {
        $condicion1 = isset( $_POST['handle_register_nonce'] );
        $condicion2 = wp_verify_nonce( $_POST['handle_register_nonce'], 'handle_register' );

        if ($condicion1 && $condicion2)
        {
            $user = $_POST["usuario"];
            $pass = $_POST["clave"];
            $email = $_POST["correo"];            
            $success = false;
        
            if ( !username_exists( $user )  && !email_exists( $email ) ) 
            {
                $user_id = wp_create_user( $user, $pass, $email );
                $userobj = new WP_User( $user_id );
                $userobj->set_role( 'cliente' );      
                $success = true;
            
                $criptomonedas = new WP_Query([ 
                    'post_type' => 'valores',
                    'category_name' => "criptomonedas"
                ]); 

                $wallets = [];

                foreach ($criptomonedas->posts as $c) 
                {
                    //Se crea un wallet por cada cripto para el usuario
                    $title = \App\GenerateRandom(30);   

                    $id = wp_insert_post(array(
                        'post_title'=> "wallet-$title-$user_id", 
                        'post_type'=>'wallets',
                        'post_status' =>'publish',
                        'post_author' => $user_id
                    ), true);

                    update_field("fecha_creacion", date("Y-m-d H:i:s"), $id);
                    update_field("criptomoneda", $c->ID, $id);
                    update_field("saldo", 0, $id);
                }
        
                wp_redirect(home_url() . "/ingreso?success=" . urlencode($success));
                exit;
            }            
        }
    } 
    catch (\Exception $ex) 
    {
        $error = urlencode($ex->getMessage());
        wp_redirect(home_url() . "/ingreso?success=" . urlencode($success) . "&message=" . urlencode($error));
    }
}

function GetEstadoBySlug($slug)
{
    $estados = get_terms( "estado", [ 'parent' => 0, 'hide_empty' => false ] );

    $res = array_filter($estados, function($elem) use($slug)
    {
        return $elem->slug == $slug;
    });

    return $res;
}


function update_status(/*int $transaccion_id, string $accion*/)
{
    try 
    {
        $transaccion_id = $_POST["transaccion_id"];
        $accion = $_POST["mi_accion"];

        $acciones_disponibles = [
            "SIGUIENTE",
            "CANCELAR",
            "ARBITRAR",
        ];

        // Validación de parámetro $accion
        if(array_search($accion, $acciones_disponibles) === false)
            throw new \Exception("La acción especificada no es válida. Valores disponibles: " . json_encode($acciones_disponibles));
        
        $transaccion = get_post($transaccion_id);
        $estado_actual = get_field("estado", $transaccion_id); 
        $next = "";      

        switch ($estado_actual) 
        {
            case 'INICIADA':
                switch ($accion) 
                {
                    case 'CANCELAR':
                        $next = "CANCELADA_CONFIRMADA";
                        break;
                    default:
                        $next = "PROCESANDO";
                        break;
                }                
                break;

            case 'PROCESANDO':
                switch ($accion) 
                {
                    case 'CANCELAR':
                        $next = "CANCELADA_POR_CONFIRMAR";
                        break;
                    case 'ARBITRAR':
                        $next = "ARBITRAJE";
                        break;
                    default:
                        //Leer el id de la transaccion y guardarlo
                        $next = "PAGADA_POR_CONFIRMAR";
                        break;
                }                
                break;

            case 'PAGADA_POR_CONFIRMAR':
                switch ($accion) 
                {
                    case 'ARBITRAR':
                        $next = "ARBITRAJE";
                        break;
                    default:
                        $next = "PAGADA_CONFIRMADA";
                        break;
                }      
                break;

            case 'CANCELADA_POR_CONFIRMAR':
                switch ($accion) 
                {
                    case 'ARBITRAR':
                        $next = "ARBITRAJE";
                        break;
                    default:
                        $next = "CANCELADA_CONFIRMADA";
                        break;
                }      
                break;
        }

        update_field("estado", $next, $transaccion->ID);

        return wp_send_json(
            [
                "state" => $next
            ]
        );
    } 
    catch (\Exception $ex) 
    {
        throw $ex;
    }
} 


add_action('wp_ajax_nopriv_update_status', 'update_status');
add_action('wp_ajax_update_status', 'update_status');

add_action('wp_ajax_nopriv_get_operations', "get_operations");
add_action('wp_ajax_get_operations', "get_operations");

add_action('admin_post_nopriv_handle_register', 'handle_register');
add_action('admin_post_handle_register', 'handle_register');

add_action('admin_post_nopriv_handle_crear_operacion', 'HandleCrearOperacion');
add_action('admin_post_handle_crear_operacion', 'HandleCrearOperacion');

add_action('admin_post_nopriv_handle_crear_transaccion', 'HandleCrearTransaccion');
add_action('admin_post_handle_crear_transaccion', 'HandleCrearTransaccion');

add_action('admin_post_nopriv_handle_crear_movimiento', 'HandleCrearMovimiento');
add_action('admin_post_handle_crear_movimiento', 'HandleCrearMovimiento');
