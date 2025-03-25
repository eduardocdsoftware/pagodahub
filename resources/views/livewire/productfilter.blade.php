{{-- <div class="row ">
    <div class="col">
        <input type="hidden" class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
</div> --}}

<div class="table-responsive">
    <div class="row">
        <div class="col-md-6 col-sm-6 mb-3">
            <label for="entrega">Nombre</label>
            <input type="text" class="form-control" wire:model="nombre" placeholder="Escribe descripción para buscar...">
        </div>
        <div class="col-md-6 col-sm-6 mb-3">
            <label for="entrega">Código de Barra</label>
            <input type="text" class="form-control" wire:model="codigo_barra" placeholder="Escribe código para buscar...">
        </div>
        <div class="col-md-4 mb-3 d-none">
            <label for="entrega">Departmento</label>
            <select class="form-control" wire:model="id_department" name="id_department" id="id_department">
                <option value=""></option>
                @if (isset($departments))
                    @foreach ($departments as $department)
                        @if($department->id!=0)
                            <option value="{{ $department->id }}">{{ $department->descripcion }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>

    <hr class="mb-4">  

    <!-- Contenedor de Categorias -->
    @if (!isset($parameters['nombre']) && !isset($parameters['codigo_barra']) && !isset($parameters['id_department']))
        <div id="container-departments">
            <div class="row px-4">
                @foreach ($departments as $department)
                    
                    <div class="col-sm-4 col-md-3 px-1" style="cursor: pointer;">
                        <div class="m-2 px-2 py-5 text-center container-department text-uppercase border border-dark rounded text-black" wire:click="setDepartment('{{ $department->id }}')" onclick="changeDepartment('{{ $department->id }}')">
                            <p class="mb-0">{{ $department->descripcion }}</p>
                        </div>
                    </div>
                
                @endforeach
            </div>
        </div>
    @endif

    <!-- Contenedor de Productos -->
    @if (isset($parameters['nombre']) || isset($parameters['codigo_barra']) || isset($parameters['id_department']))
        <div id="container-products">
            <div class="row px-4">
                @foreach ($products as $product)
                
                    <div class="col-md-3 px-1" style="cursor: pointer;">
                        <div class="m-2 px-2 py-4 text-center container-product text-uppercase border border-dark rounded text-black" onclick="location.href='{{ route('product.details', $product->id) }}'">
                            <div class="height-descripcion">
                                <p class="mb-1">
                                    @if (isset($product->id_brand) && $product->id_brand > 0) <span class="font-weight-bold font-color-brand">{{$product->brand->descripcion}}</span> @endif
                                    <span class="font-weight-bold">{{$product->name}}</span>
                                </p>
                                <p class="mb-4">
                                    @if (isset($product->presentacion) && $product->presentacion != '') <span class="font-weight-bold font-color-brand">{{$product->presentacion}}</span> @endif
                                        <span class="font-weight-bold">{{$product->peso_volumen}}</span>
                                </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <img class="img-fluid" style="width: -webkit-fill-available; height: 200px;" src="{{ asset('storage/imgproduct/'.( $product->base64_img ? $product->base64_img : 'img.png' ) ) }}"/>
                            </div>
                            <p class="mt-4 mb-0 font-weight-bold">CÓDIGO DE BARRAS</p>
                            <p class="mb-1 h4 font-weight-bold">
                                @if (isset($product->codigo_barra) && $product->codigo_barra != '') 
                                    {{ $product->codigo_barra }}
                                @else
                                    S/R
                                @endif
                            </p>
                            <div class="height-precio my-3">
                                <p class="h5 mb-0 font-weight-bold">PRECIO: {{ $product->price }}</p>
                            </div>   
                        </div>
                    </div>
                
                @endforeach
            </div>
            <div class="mt-4 float-end">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif

    <!--<table class="table table-bordered">
        <thead id="miTablaPersonalizada">
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Código de Barra</th>
            <th style="width:10% !important;">Acciones</th>
        </thead>
        <tbody>
            @foreach ($products as $data)
                <tr>
                    
                    <td>
                        {{ $data->name }}
                    </td>
                    <td>
                        {{ $data->category->descripcion??'' }} 
                    </td>
                    <td>
                        {{ $data->codigo_barra }} 
                    </td>
                    <td style="width:10% !important;">
                        <a href="{{ route('product.details', $data->id) }}" class="btn btn-warning btn-block my-1" style="width: 100% !important;">Ver</a>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>-->
</div>
<style>
    #miTablaPersonalizada th {
        width: 100px;
        /* overflow: auto; */
        border: 1px solid;
    }

    table {
        table-layout: fixed;
    }

    .container-product{
        background-color: #E6E6E6; 
        border-width: 4px !important; 
        border-color: #d3cfca !important;
    }

    .container-department{
        font-weight: bold; 
        background-color: #E6E6E6; 
        border-width: 4px !important; 
        border-color: #d3cfca !important;
    }

    .table-responsive{
        overflow: hidden;
    }
    .font-weight-bold{
        font-weight: bold;
    }
    .font-color-brand{
        color: #7e7d7b;
    }
    .height-descripcion{
        height: 100px;
    }

    .container {
        max-width: 100% !important;
    }

    @media only screen and (max-width: 770px) {
        #container-departments .row,  #container-products .row{
            padding-right: 0px !important;
            padding-left: 0px !important;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script type="text/javascript">

    function changeDepartment(id) {

        $('#id_department').val(id).trigger( "change");

    }

    $(document).ready(function(){

    });

</script>
