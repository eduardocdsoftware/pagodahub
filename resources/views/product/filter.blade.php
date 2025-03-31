@extends('layouts.app')
@section('title', 'Page Title')

@if (session('mensaje'))
    <div class="alert alert-success">{{ session('mensaje') }}</div>
@endif

@section('content')
<div class="p-2 m-0 border-0 bd-example">
    <div class="d-flex">
        <div class="container">
	        <div class="card">
	            <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between">
                            <h4>Lista de productos</h4>
                            <div>
                                <a href="{{ route('product.filter') }}" class="btn btn-primary font-weight-bold">
                                <svg fill="#ffffff" viewBox="0 0 32.00 32.00" id="icon" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff" stroke-width="0.00032" class="no-mobile-hidden" width="20" height="20"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <defs> <style> .cls-1 { fill: none; } </style> </defs> <path d="M22.5,9A7.4522,7.4522,0,0,0,16,12.792V8H14v8h8V14H17.6167A5.4941,5.4941,0,1,1,22.5,22H22v2h.5a7.5,7.5,0,0,0,0-15Z"></path> <path d="M26,6H4V9.171l7.4142,7.4143L12,17.171V26h4V24h2v2a2,2,0,0,1-2,2H12a2,2,0,0,1-2-2V18L2.5858,10.5853A2,2,0,0,1,2,9.171V6A2,2,0,0,1,4,4H26Z"></path> <rect id="_Transparent_Rectangle_" data-name="<Transparent Rectangle>" class="cls-1" width="32" height="32"></rect> </g></svg>
                                <span class="mobile-hidden text-uppercase" style="font-size: 0.8rem !important;">Volver</span>
                                </a>
                            </div>
                        </div>
                    </div>
	            </div>
	            <div class="card-body">
	                @livewire('App\Http\Livewire\ProductFilter', ['orgs' => $orgs, 'session' => $session])
	            </div>
	        </div>
    	</div>
    </div>
</div>

<style>
    .campo input {
        margin-bottom: 10px;
    }
    
    table {
        font-family: arial, sans-serif;
        background-color: white;
        text-align: left;
        border-collapse: collapse;
        width: 100%;
    }
    .table th {
        max-width: 100px; /* Establece el ancho máximo deseado */
        text-overflow: ellipsis; /* Agrega puntos suspensivos (...) si el contenido es demasiado largo */
        white-space: nowrap; /* Evita que el texto se divida en varias líneas */
    }
    th,
    td {
        padding: 1px;

    }
    

    thead {
        background-color: #246355;
        border-bottom: solid 5px #0F362D;
        color: white;
    }

    #theadtotal {
        background-color: #1b6453;
        border-bottom: solid 2.5px #268c74;
        border-top: solid 2.5px #268c74;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #ddd;
    }

    tr:hover td {
        background-color: #369681;
        color: white;
    }

    #imagenesPrevias {
        display: center;
        flex-wrap: wrap;
    }

    #imagenesPrevias img {
        max-width: 75%;
        height: auto;
        margin: 5px;
        border: 1px solid;
    }
    .divider {
    width: 15px;
    }

    .font-weight-bold{
        font-weight: bold;
    }

    @media only screen and (max-width: 770px) {
        .mobile-hidden {
            display: none;
        }
    }

    @media only screen and (min-width: 770px) {
        .no-mobile-hidden {
            display: none;
        }
    }
</style>
@endsection