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
                            <h4>Producto</h4>
                            <div>
                                <a href="{{ route('product') }}" class="btn btn-primary font-weight-bold text-uppercase" style="font-size: 0.8rem !important;">Volver</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Formulario para envio-->
                    <form name="provider" id="provider" method="post" action="{{route( 'product.update' )}}" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{$product->id}}">

                        <div class="row">
                            <div class="col-md-6 mb-3"> 
                                <div class="col-md-6 mb-3 w-auto">
                                    <label for="recibe">Nombre del Producto</label>
                                    <input type="text" class="form-control text-uppercase required" id="nombre" name="nombre" value="{{$product->name}}">
                                    @error('nombre')
                                        <div class="alert alert-danger mt-1 py-2 show">El campo nombre es obligatorio</div>
                                    @enderror()
                                    <div class="alert alert-danger mt-1 py-2 error d-none" field="nombre">El campo nombre es obligatorio</div>
                                </div>
                                <div class="col-md-6 mb-3 w-auto">
                                    <label for="entrega">Precio </label>
                                    <input type="text" class="form-control" id="precio" name="precio" value="{{$product->price}}">
                                </div> 
                                <div class="col-md-6 mb-3 w-auto">
                                    <p for="buscador" class="card-text">Mostrar en Buscador</p>
                                    <select class="form-control" name="buscador_producto" id="buscador_producto">
                                        <option value="N" {{ $product->product_search == 'N' ? 'selected' : '' }}>NO</option>
                                        <option value="Y" {{ $product->product_search == 'Y' ? 'selected' : '' }}>SI</option>
                                    </select>
                                </div> 
                                <div class="col-md-6 mb-3 w-auto">
                                    <p for="cars" class="card-text">Departamento</p>
                                    <select class="form-control required" name="id_department" id="id_department">
                                        <option value=""></option>
                                        @if (isset($departments))
                                            @foreach ($departments as $department)
                                                @if($department->id!=0)
                                                    <option value="{{ $department->id }}" {{ $department->id == $product->id_department ? 'selected' : '' }}>{{ $department->descripcion }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('id_department')
                                        <div class="alert alert-danger mt-1 py-2 show">El campo departamento es obligatorio</div>
                                    @enderror()
                                    <div class="alert alert-danger mt-1 py-2 error d-none" field="id_department">El campo departamento es obligatorio</div>
                                </div>
                                <div class="col-md-6 mb-3 w-auto">
                                    <p for="cars" class="card-text">Marca</p>
                                    <select class="form-control" name="id_brand" id="id_brand">
                                        <option value=""></option>
                                        @if (isset($brands))
                                            @foreach ($brands as $brand)
                                                @if($brand->id!=0)
                                                    <option value="{{ $brand->id }}" {{ $brand->id == $product->id_brand ? 'selected' : '' }}>{{ $brand->descripcion }}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3 w-auto">
                                    <label for="entrega">Presentación </label>
                                    <input type="text" class="form-control text-uppercase required" id="presentacion" name="presentacion" value="{{$product->presentacion}}">
                                    @error('presentacion')
                                        <div class="alert alert-danger mt-1 py-2 show">El campo presentación es obligatorio</div>
                                    @enderror()
                                    <div class="alert alert-danger mt-1 py-2 error d-none" field="presentacion">El campo presentación es obligatorio</div>
                                </div> 
                                <div class="col-md-6 mb-3 w-auto">
                                    <label for="entrega">Peso/Volumen </label>
                                    <input type="text" class="form-control text-uppercase" id="peso_volumen" name="peso_volumen" value="{{$product->peso_volumen}}">
                                </div>
                                <div class="col-md-6 mb-3 w-auto">
                                    <label for="entrega">Código de Barra </label>
                                    <input type="text" class="form-control text-uppercase required" id="codigo_barra" name="codigo_barra" value="{{$product->codigo_barra}}">
                                    @error('codigo_barra')
                                        <div class="alert alert-danger mt-1 py-2 show">El campo código de barra es obligatorio</div>
                                    @enderror()
                                    <div class="alert alert-danger mt-1 py-2 error d-none" field="codigo_barra">El campo código de barra es obligatorio</div>
                                </div>
                                @if (!empty($product->codigo_barra))
                                    <div class="col-md-6 mb-3 w-auto">
                                        @php
                                            $generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG();
                                        @endphp

                                        <img src="data:image/png;base64,{{ base64_encode($generatorPNG->getBarcode($product->codigo_barra, $generatorPNG::TYPE_CODE_128)) }}">
                                    </div>
                                @endif
                            </div> 
                            <div class="col-md-6 mb-3">
                                <img id="upload_preview" name="upload_preview" style="width: -webkit-fill-available; height: 400px;" src="{{ asset('storage/imgproduct/'.( $product->base64_img ? $product->base64_img : 'img.png' ) ) }}"/>
                                <input id="upload_image" type="file" name="upload_image" onchange="PreviewImage();" class="mt-3" accept="image/*"/>
                                <input type="hidden" id="image" name="image" class="required" value="{{$product->base64_img}}">
                                @error('upload_image')
                                    <div class="alert alert-danger mt-1 py-2 show">El campo imagen es obligatorio</div>
                                @enderror()
                                <div class="alert alert-danger mt-1 py-2 error d-none" field="image">El campo imagen es obligatorio</div>
                            </div>
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
<style>
    .font-weight-bold{
        font-weight: bold;
    }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function enviarFormulario() {
        // Aquí puedes realizar cualquier otra validación antes de enviar el formulario

        let error = false;
        $('div').find('div.show').addClass('d-none');
        $('div').find('div.show').removeClass('show');

        $('.required').each(function(){

            if ($(this).val() == '') {

                error = true;
                $("div[field='" + $(this).attr('id') + "']").removeClass('d-none').addClass('show');

            }

        });

        if (error) {return;}

        $('#confirmModal').modal('hide');
        $('.container-form').addClass('d-none');
        $('.container-loader').removeClass('d-none');

        // Envía el formulario
        document.getElementById('provider').submit();
    }

    function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("upload_image").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("upload_preview").src = oFREvent.target.result;
            document.getElementById("image").value = oFREvent.target.result;
        };
    };

</script>
@endsection