{{-- <div class="row ">
    <div class="col">
        <input type="hidden" class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
</div> --}}

<div class="table-responsive">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="entrega">Nombre</label>
            <input type="text" class="form-control" wire:model="nombre" placeholder="Escribe descripción para buscar...">
        </div>
        <div class="col-md-3 mb-3">
            <label for="entrega">Código de Barra</label>
            <input type="text" class="form-control" wire:model="codigo_barra" placeholder="Escribe código para buscar...">
        </div>
        <div class="col-md-3 mb-3">
            <label for="entrega">Departamento</label>
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
        <!--<div class="col-md-3 mb-3">
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
        </div>-->
    </div>
    <table class="table table-bordered">
        <thead id="miTablaPersonalizada">
            <th>Nombre</th>
            <th>Departamento</th>
            <!--<th>Marca</th>-->
            <th>Presentación</th>
            <th>Código de Barra</th>
            <th style="width:15% !important;" class="text-center"><span class="mobile-hidden">Acciones</span></th>
        </thead>
        <tbody>
            @foreach ($products as $data)
                <tr>
                    
                    <td>
                        {{ strtoupper($data->name) }}
                    </td>
                    <td>
                        {{ strtoupper($data->department->descripcion??'') }} 
                    </td>
                    <!--<td>
                        {{ $data->brand->descripcion??'' }} 
                    </td>-->
                    <td>
                        {{ strtoupper($data->presentacion??'') }} 
                    </td>
                    <td>
                        {{ strtoupper($data->codigo_barra) }} 
                    </td>
                    <td style="width:15% !important;">
                        <a href="{{ route('product.edit', $data->id) }}" class="btn btn-warning btn-block my-1" style="width: 100% !important;">
                            <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 494.936 494.936" xml:space="preserve" class="no-mobile-hidden"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M389.844,182.85c-6.743,0-12.21,5.467-12.21,12.21v222.968c0,23.562-19.174,42.735-42.736,42.735H67.157 c-23.562,0-42.736-19.174-42.736-42.735V150.285c0-23.562,19.174-42.735,42.736-42.735h267.741c6.743,0,12.21-5.467,12.21-12.21 s-5.467-12.21-12.21-12.21H67.157C30.126,83.13,0,113.255,0,150.285v267.743c0,37.029,30.126,67.155,67.157,67.155h267.741 c37.03,0,67.156-30.126,67.156-67.155V195.061C402.054,188.318,396.587,182.85,389.844,182.85z"></path> <path d="M483.876,20.791c-14.72-14.72-38.669-14.714-53.377,0L221.352,229.944c-0.28,0.28-3.434,3.559-4.251,5.396l-28.963,65.069 c-2.057,4.619-1.056,10.027,2.521,13.6c2.337,2.336,5.461,3.576,8.639,3.576c1.675,0,3.362-0.346,4.96-1.057l65.07-28.963 c1.83-0.815,5.114-3.97,5.396-4.25L483.876,74.169c7.131-7.131,11.06-16.61,11.06-26.692 C494.936,37.396,491.007,27.915,483.876,20.791z M466.61,56.897L257.457,266.05c-0.035,0.036-0.055,0.078-0.089,0.107 l-33.989,15.131L238.51,247.3c0.03-0.036,0.071-0.055,0.107-0.09L447.765,38.058c5.038-5.039,13.819-5.033,18.846,0.005 c2.518,2.51,3.905,5.855,3.905,9.414C470.516,51.036,469.127,54.38,466.61,56.897z"></path> </g> </g> </g></svg>
                            <span class="px-2 mobile-hidden font-weight-bold text-uppercase" style="font-size: 0.8rem !important;">Editar</span>
                        </a>
                        <form action="{{ route('product.delete', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block my-1" style="width: 100% !important;" onclick="return confirm('¿Estás seguro de que deseas eliminar esto producto?');">
                                <svg fill="#ffffff" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 482.428 482.429" xml:space="preserve" stroke="#ffffff" class="no-mobile-hidden"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <path d="M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098 c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117 h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828 C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879 C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096 c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266 c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979 V115.744z"></path> <path d="M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07 c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z"></path> <path d="M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07 c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z"></path> <path d="M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07 c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z"></path> </g> </g> </g></svg>
                                <span class="px-2 mobile-hidden font-weight-bold text-uppercase" style="font-size: 0.8rem !important;">Eliminar</span>
                            </button>
                        </form>
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

    .table-responsive{
        overflow: hidden;
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
