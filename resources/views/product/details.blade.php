@extends('layouts.app')
@section('title', 'Page Title')

@section('content')
<div class="p-2 m-0 border-0 bd-example container-form">
    <div class="d-flex">
        <!-- Formulario de búsqueda por proveedor -->
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-9">
                            <h4>Producto</h4>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('product.filter') }}" class="btn btn-primary font-weight-bold">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h2 class="card-title font-weight-bold">{{$product->name}}</h2>
                    <hr class="mb-4">   
                    <div class="card p-2">
                        <div class="row g-0">
                            <div class="col-md-6">
                              <div class="card-body">
                                <h2 class="card-title text-uppercase">
                                    @if (isset($product->id_brand) && $product->id_brand > 0) <span class="font-color-brand">{{$product->brand->descripcion}}</span> @endif
                                    <span class="font-weight-bold">{{$product->name}}</span>
                                </h2>
                                <h4 class="card-title text-uppercase">
                                    @if (isset($product->presentacion) && $product->presentacion != '') <span class="font-color-brand">{{$product->presentacion}}</span> @endif
                                    <span class="font-weight-bold">{{$product->peso_volumen}}</span>
                                </h4>
                                <h4 class="card-title text-uppercase font-weight-bold">
                                    <span class="font-color-brand">Precio: {{$product->price}}</span>
                                </h4>
                                <!--<p class="card-text">Esta es una tarjeta más amplia con texto de apoyo a continuación como introducción natural a contenido adicional. Este contenido es un poco más largo.</p>
                                <p class="card-text"><small class="text-muted">Última actualización hace 3 minutos</small></p>-->  
                                <h3 class="card-title font-weight-bold mt-4 text-uppercase">CÓDIGO DE BARRAS: 
                                    <span class="h2 font-weight-bold">
                                        @if (isset($product->codigo_barra) && $product->codigo_barra != '') 
                                            {{ $product->codigo_barra }}
                                        @else
                                            S/R
                                        @endif
                                    </span>
                                </h3>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <img class="img-fluid rounded-start" style="width: -webkit-fill-available; height: 400px;" src="{{ asset('storage/imgproduct/'.( $product->base64_img ? $product->base64_img : 'img.png' ) ) }}"/>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
    </div>
    </div>
    
</div>
<style>
    .font-weight-bold{
        font-weight: bold;
    }
    .font-color-brand{
        color: #7e7d7b;
    }
</style>
@endsection