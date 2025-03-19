{{-- <div class="row ">
    <div class="col">
        <input type="hidden" class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
</div> --}}

<div class="table-responsive">
    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="entrega">Nombre</label>
            <input type="text" class="form-control" wire:model="nombre" placeholder="Escribe descripción para buscar...">
        </div>
        <div class="col-md-3 mb-3">
            <label for="entrega">Código de Barra</label>
            <input type="text" class="form-control" wire:model="codigo_barra" placeholder="Escribe código para buscar...">
        </div>
        <div class="col-md-3 mb-3">
            <label for="entrega">Categoria</label>
            <select class="form-control" wire:model="id_category" name="id_category" id="id_category">
                <option value=""></option>
                @if (isset($categories))
                    @foreach ($categories as $category)
                        @if($category->id!=0)
                            <option value="{{ $category->id }}">{{ $category->descripcion }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label for="entrega">Marca</label>
            <select class="form-control" wire:model="id_brand" name="id_brand" id="id_brand">
                <option value=""></option>
                @if (isset($brands))
                    @foreach ($brands as $brand)
                        @if($brand->id!=0)
                            <option value="{{ $brand->id }}">{{ $brand->descripcion }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <table class="table table-bordered">
        <thead id="miTablaPersonalizada">
            <th>Nombre</th>
            <th>Categoria</th>
            <th>Marca</th>
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
                        {{ $data->brand->descripcion??'' }} 
                    </td>
                    <td>
                        {{ $data->codigo_barra }} 
                    </td>
                    <td style="width:10% !important;">
                        <a href="{{ route('product.edit', $data->id) }}" class="btn btn-warning btn-block my-1" style="width: 100% !important;">Editar</a>
                        <!--<form action="{{ route('product.delete', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block my-1" style="width: 100% !important;" onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarjeta?');">Eliminar</button>
                        </form>-->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4 float-end">
        {{ $products->links('pagination::bootstrap-4') }}
    </div>
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
</style>
