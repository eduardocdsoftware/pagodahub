@extends('layouts.app')
@section('title', 'Page Title')


@if (session('mensaje'))
    <div class="alert alert-success">{{ session('mensaje') }}</div>
@endif
@section('content')
<div class="justify-content-center d-none container-loader" style="align-content: center; min-height: 100vh;">
    <div class="d-flex justify-content-center">
        <div class="spinner-border" style="width: 4rem; height: 4rem;" role="status">
        </div>
    </div>
    <strong class="d-flex justify-content-center mt-3" style="font-size: 1.5rem;">Procesando la información, por favor espere...</strong>
</div>
<div class="p-2 m-0 border-0 bd-example container-form">
    <div class="d-flex">
        <!-- Formulario de búsqueda por proveedor -->
        <div class="container">
            <div class="card">

                 
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-between">
                            <h4>Categoria</h4>
                            <div>
                                <a href="{{ route('category') }}" class="btn btn-primary font-weight-bold">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Formulario para envio-->
                    <form name="provider" id="provider" method="post" action="{{route( 'category.update' )}}">
                        <input type="hidden" name="id" value="{{$category->id}}">
                        <div class="col-md-6 mb-3">
                            <label for="descripcion">Nombre de la Categoria </label>
                            <input type="text" class="form-control text-uppercase" id="descripcion" name="descripcion" value="{{$category->descripcion}}" required>
                        </div>                   
                        <div class="form-group w-auto">
                            @csrf
                            <button class=" w-100 btn btn-outline-secondary m-0" type="button" id="button-addon2" onclick="enviarFormulario()">Actualizar</button>
                        </div>
                        
                    </form>
                    <hr class="mb-4">                    
                </div>
            </div>
    </div>
    </div>
    
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function enviarFormulario() {
        // Aquí puedes realizar cualquier otra validación antes de enviar el formulario

        $('#confirmModal').modal('hide');
        $('.container-form').addClass('d-none');
        $('.container-loader').removeClass('d-none');

        // Envía el formulario
        document.getElementById('provider').submit();
    }

</script>
@endsection