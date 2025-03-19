{{-- <div class="row ">
    <div class="col">
        <input type="hidden" class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
</div> --}}

<div class="table-responsive">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="entrega">Descripción</label>
            <input type="text" class="form-control" wire:model="descripcion" placeholder="Escribe descripción para buscar...">
        </div>
    </div>
    <table class="table table-bordered">
        <thead id="miTablaPersonalizada">
            <th>Descripción</th>
            <th style="width:10% !important;">Acciones</th>
        </thead>
        <tbody>
            @foreach ($brands as $data)
                <tr>
                    <td>
                        {{ $data->descripcion }} 
                    </td>
                    <td style="width:10% !important;">
                        <a href="{{ route('brand.edit', $data->id) }}" class="btn btn-warning btn-block my-1" style="width: 100% !important;">Editar</a>
                        <form action="{{ route('brand.delete', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block my-1" style="width: 100% !important;" onclick="return confirm('¿Estás seguro de que deseas eliminar esta marca?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4 float-end">
        {{ $brands->links('pagination::bootstrap-4') }}
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
