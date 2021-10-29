<?php

/**
 * Theme helpers.
 */

namespace App;

function fetcher($endpoint){
    $json = file_get_contents($endpoint);
    $obj = json_decode($json);
    return $obj;
}

function fetchCoins() {
    $endpoint = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=volume_desc&per_page=100&page=1&sparkline=false';
    return fetcher($endpoint);
}   

function GenerateRandom(int $digits = 30)
{    
    $bytes = random_bytes($digits);
    return bin2hex($bytes);
}

function profitCheckClass($percentage){
    if(is_numeric(substr($percentage, 0, 1))){
        return 'has-text-success';
    }else{
        return 'has-text-primary';
    }
}

function GetMessages($channel_id)
{
    try 
    {
        $res = SocketCall([
            "procedure" => "GET_CHANNEL",
            "channel_id" => $channel_id,
            "user_id" => get_current_user_id(),
            "user_name" => wp_get_current_user()->display_name
        ]);
    
        $obj = json_decode($res);

        if(!$obj->success)
        {
            return new \Exception($obj->message);
        }   
        
        return $obj->data->messages;
    } 
    catch (\Exception $ex) 
    {
        return [
            "success" => false,
            "message" => $ex->getMessage(),
            "data" => json_encode($ex)
        ];
    }
}

function CreateChannel($channel_name, array $users)
{
    try 
    {
        $params = [
            "procedure" => "NEW_CHANNEL",
            "channel_name" => "channel_" . $channel_name,
            "users" => []
        ];

        foreach ($users as $user) 
        {
            if(!array_key_exists("user_id", $user) && !array_key_exists("user_name", $user))
                throw new \Exception("Uno de los usuarios no tiene 'user_id' o 'user_name'");

            $params["users"][] = [
                "user_id" => $user["user_id"],
                "user_name" => $user["user_name"]
            ];
        }

        $res = \App\SocketCall($params, $users);

        return $res;
    } 
    catch (\Exception $ex) 
    {
        return [
            "success" => false,
            "message" => $ex->getMessage()
        ];
    }

}

function get_operacion_user($operacion_id)
{
    $author_id = get_post_field( 'post_author', $operacion_id );
    $user = get_user_by("ID", (int)$author_id);
    return $user;
}

function SocketCall($param)
{
    try 
    {
        $chat_server_url = (string)get_field('chat_server', 'option');
        $client = new \WebSocket\Client($chat_server_url);
        $client->text(json_encode($param));
        $res = $client->receive();
        $client->close();
        return $res;
    } 
    catch (\Exception $ex) 
    {
        throw $ex;
    }

}


function GetMonedas($post_id)
{
    $monedas = get_field_object("moneda", $post_id);
    return $monedas;
}

