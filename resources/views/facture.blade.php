@extends('layouts.app')
@section('title', 'Page Title')


@section('content')
<div class="p-2 m-0 border-0 bd-example">
    <div class="d-flex">
        <!-- Formulario de búsqueda por proveedor -->
        <div class="container">
            <div class="card">
                <div class="card-header">Filtrar facturas</div>
                <div class="card-body">
                    <!--<form name="provider" id="provider" method="post" action="{{ route('factures.searchByProvider') }}" class="mr-2">
                        <div class="form-group">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="" spellcheck="false" name="provider">
                                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buscar proveedor</button>
                            </div>
                        </div>
                    </form>
                    <!-- Formulario para mostrar facturas a crédito -->
                    <form name="provider" id="provider" method="post" action="{{ route('factures.credit') }}">
                        <div class="form-group w-auto">
                            @csrf
                            <div class="col">
                                <label for="" class="form-label">Sucursal</label>
                                <select class="form-control" name="AD_Org_ID" id="AD_Org_ID">
                                    @if (isset($orgs))
                                        @if ($orgs)
                                            @foreach ($orgs as $org)
                                                @if($org->id!=0)
                                                    <option value="{{ $org->Name }}">{{ $org->Name }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </select>
                            </div>
                            <button class=" w-100 btn btn-outline-secondary m-0" type="submit" id="button-addon2">Mostrar facturas sin pagar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="container">
        <div class="card">
            <div class="card-header">Resumen del dia / Rango Fechas</div>
                <div class="card-body pb-0">
                    <form name="factures" id="factures" method="post" action="{{ route('factures.resume') }}">

                            <!--<div class="form-group ">

                                @csrf
                                <div class="col">
                                    <label for="" class="form-label">Sucursal</label>
                                    <select class="form-control" name="AD_Org_ID" id="AD_Org_ID">
                                        @if (isset($orgs))
                                            @if ($orgs)
                                                @foreach ($orgs as $org)
                                                    @if($org->id!=0)
                                                        <option value="{{ $org->Name }}">{{ $org->Name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" placeholder="" aria-label="" aria-describedby=""
                                        spellcheck="false" data-ms-editor="true" name="day" required>
                                    <button class="btn btn-outline-secondary" type="" id="button-addon2">Buscar</button>
                                </div>

                            </div>
                            -->
                            @csrf
                            <div class="row mb-3">  <!-- Cambiado a row para crear una fila -->
                                <div class="col-md-4">
                                    <label for="" class="form-label">Sucursal</label>
                                    <select class="form-control" name="AD_Org_ID" id="AD_Org_ID">
                                        @if (isset($orgs))
                                            @if ($orgs)
                                                @foreach ($orgs as $org)
                                                    @if($org->id!=0)
                                                        <option value="{{ $org->Name }}">{{ $org->Name }}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Medio de pago</label>
                                    <select class="form-control" name="medio_pago" id="medio_pago">
                                        <option value="" selected></option>
                                        <option value="true">Contado</option>
                                        <option value="false">Credito</option>
                                    </select>  
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Pagada</label>
                                    <select class="form-control" name="pagada" id="pagada">
                                        <option value="" selected></option>
                                        <option value="true">Sí</option>
                                        <option value="false">No</option>
                                    </select>  
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">  <!-- Cambiado a row para crear una fila -->
                                <div class="col-md-4">
                                    <label for="" class="form-label">Fecha Inicio</label>
                                    <input type="date" class="form-control" placeholder="" aria-label="" aria-describedby="" spellcheck="false" data-ms-editor="true" name="day" required>    
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label">Fecha Fin</label>
                                    <input type="date" class="form-control" placeholder="" aria-label="" aria-describedby="" spellcheck="false" data-ms-editor="true" name="day_end">
                                </div>
                                <div class="col-md-4">
                                    <label for="" class="form-label"></label>
                                    <button class="btn btn-outline-secondary mt-auto w-100 h-75" type="submit" >Buscar</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</br>
    
    <div>
        @livewire('App\Http\Livewire\FactureList', ['orgs' => $orgs])
    </div>
</div>

    <style>
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
    </style>
@endsection
