@extends('layouts.app-home')

@section('left')
@include('components.offer-finder')
@endsection

@section('right')
@include('components.account-state')
@endsection

@section('content')

@php
$tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : "COMPRA";
$moneda = isset($_GET["moneda"]) ? $_GET["moneda"] : "BITCOIN";

$arg = [
    'post_type' => 'operaciones',
    "meta_query" => [
        "relation" => "AND",
        [
            'key' => "tipo",
            "value" => $tipo
        ],
        [
            'key' => "moneda",
            "value" => $moneda
        ]             
    ]
];       


$medios = [];
if(isset($_GET["medios"]))
{
    $mediosStr = $_GET["medios"];
    $medios = explode(",", $mediosStr);

    $arg["tax_query"] = [[
            "taxonomy" => "category",
            "terms" => $medios,
],];
}

@endphp


<div class="tarjetas-hero is-full-width btn-g font-w is-500 has-padding-40 has-text-dark flex-column" style="background: white">
    <div class="columns">

        <div class="column is-6">
            <p>
            Compra <strong>{{$moneda}}</strong>  
            <br>De estos vendedores
            </p>

        </div>

        <div class="column is-2"></div>

        <div class="column is-4">
            <div
            class="is-flex has-text-dark align-items-center is-uppercase has-margin-bottom-70 justify-space-between font-w is-500"
            style="color: #E3452B !important">
            <span> 
                NUEVA 
                <strong>OFERTA</strong> </span>
                <span 
                    onclick="showModalCrearOperacion()"
                    width="25" 
                    height="25" 
                    style="flex:none;cursor:pointer" 
                    data-feather="plus-square" 
                    class="has-margin-left-15">
                </span>
            </div>
        </div>

    </div>

    <div class="columns is-hidden">
        <div class="column">
            <div
                class="link has-margin-left-10 is-flex has-text-dark is-uppercase has-margin-bottom-70 justify-space-between font-w is-500">
                <span width="25" height="25" style="flex:none;color:#DD1C00" data-feather="x-square" class="has-margin-left-15"></span>
                
                <span>MEJOR CALIFICACIÓN</span>
            </div>  
        </div>

        <div class="column">
            <div
                class="link has-margin-left-10 is-flex has-text-dark is-uppercase has-margin-bottom-70 justify-space-between font-w is-500">
                <span width="25" height="25" style="flex:none;color:#DD1C00" data-feather="square" class="has-margin-left-15"></span>
                
                <span>ACTIVO</span>
            </div>  
        </div>

        <div class="column">
            <div
                class="link has-margin-left-10 is-flex has-text-dark is-uppercase has-margin-bottom-70 justify-space-between font-w is-500">
                <span width="25" height="25" style="flex:none;color:#DD1C00" data-feather="arrow-down" class="has-margin-left-15"></span>
                
                <span>POPULARIDAD</span>
            </div>  
        </div>
    </div>        


    
    @query($arg)
    @posts
    @global($post)
    @include('partials.card-oferta')
    @endposts


</div>



@endsection
