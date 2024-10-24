{{-- <div class="row ">
    <div class="col">
        <input type="hidden" class="form-control" placeholder="Search" wire:model="searchTerm" />
    </div>
</div> --}}

<div class="table-responsive">
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="recibe">Fecha Ingreso Inicio</label>
            <input type="date" class="form-control" wire:model="fecha_ingreso_inicio" date-format="mm/dd/yyyy">
        </div>
        <div class="col-md-4 mb-3">
            <label for="recibe">Fecha Ingreso Fin</label>
            <input type="date" class="form-control" wire:model="fecha_ingreso_fin" date-format="mm/dd/yyyy">
        </div>
        <div class="col-md-4 mb-3">
            <label for="recibe">Fecha Pago Inicio</label>
            <input type="date" class="form-control" wire:model="fecha_pago_inicio" date-format="mm/dd/yyyy">
        </div>
        <div class="col-md-4 mb-3">
            <label for="recibe">Fecha Pago Fin</label>
            <input type="date" class="form-control" wire:model="fecha_pago_fin" date-format="mm/dd/yyyy">
        </div>
    </div>
    <table class="table table-bordered">
        <thead id="miTablaPersonalizada">
            <th style="max-width:150px">Fecha ingreso
            </th>
            <th style="max-width:150px">
                <div class="input-group" style="width:100%">
                    <span class="input-group-text" id="basic-addon3"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg></span>
                    <input type="text" class="form-control" placeholder="Chequeador" wire:model="chequeador"
                        aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </th>
            <th style="max-width:150px">
                <div class="input-group" style="width:100%">
                    <span class="input-group-text" id="basic-addon3"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg></span>
                    <input type="text" class="form-control" placeholder="responsable" wire:model="responsable_ingreso"
                        aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </th>
            <th style="max-width:150px">
                <div class="input-group" style="width:100%">
                    <span class="input-group-text" id="basic-addon3"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg></span>
                    <input type="text" class="form-control" placeholder="proveedor" wire:model="proveedor"
                        aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </th>
            <th >Monto<br> exento</th>
            <th>Monto 7%</th>
            <th>ITBMS 7%</th>
            <th>Monto 10%</th>
            <th>ITBMS 10%</th>
            <th>Monto 15%</th>
            <th>ITBMS 15%</th>
            <th>Total <br>Neto</th>
            <th>Total <br>ITBMS</th>
            <th>Devolucion</th>            
            <th>Total USD</th>
            <th style="max-width:150px">Fecha pago</th>
            <th style="min-width:250px">
                <div class="input-group" style="width:100%">
                    <span class="input-group-text" id="basic-addon3"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg></span>
                    <input type="text" class="form-control" placeholder="forma de pago" wire:model="forma"
                        aria-label="Username" aria-describedby="basic-addon1">
                </div>
            </th>
            <th style="max-width:150px"></th>
            <th style="max-width:150px">Acciones</th>
        </thead>
        <tbody>
            @foreach ($brinksend as $data)
                <tr>                    
                    <td>{{ date('d-m-Y', strtotime($data->fecha_ingreso)) }}</td>
                    <td>{{ $data->chequeador }}</td>
                    <td>{{ $data->responsable_ingreso }}</td>
                    <td>{{ $data->proveedor }}</td>
                    <td>{{ number_format($data->monto_total, 2) }}</td>
                    <td>{{ number_format($data->monto_7, 2) }}</td>
                    <td>{{ number_format($data->monto_impuesto_7, 2) }}</td>
                    <td>{{ number_format($data->monto_10, 2) }}</td>
                    <td>{{ number_format($data->monto_impuesto_10, 2) }}</td>
                    <td>{{ number_format($data->monto_15, 2) }}</td>
                    <td>{{ number_format($data->monto_impuesto_15, 2) }}</td>
                    <td>{{ number_format($data->monto_total + $data->monto_7 + $data->monto_10 + $data->monto_15, 2) }}</td>
                    <td>{{ number_format($data->monto_impuesto_7 + $data->monto_impuesto_10 + $data->monto_impuesto_15, 2) }}</td>
                    <td>{{ number_format($data->devolucion, 2) }}</td>
                    <td>{{ number_format($data->monto_total + $data->monto_7 + $data->monto_10 + $data->monto_15 + $data->monto_impuesto_7 + $data->monto_impuesto_10 + $data->monto_impuesto_15-$data->devolucion, 2) }}</td>

                    <td>{{ date('d-m-Y', strtotime($data->fecha_pago)) }}</td>
                    <td>
                        @foreach (json_decode($data->forma_pago_multiple, true) as  $forma_pago)
                        <div class="py-2">
                            <strong>* {{ $forma_pago['descripcion_forma_pago'] }}</strong><br>
                            @switch($forma_pago['forma_pago'])
                                @case('caja')
                                     <p class="mx-3">Monto: {{ number_format($forma_pago['valor'], 2) }}</p>
                                    @break
                                @case('tarjeta_credito')
                                    <p class="mx-3 my-0">Tarjeta: {{ $forma_pago['tarjeta'] }}</p>
                                    <p class="mx-3 my-0">Monto: {{ number_format($forma_pago['valor'], 2) }}</p>
                                    @break
                                @case('credito')
                                    <p class="mx-3 my-0">Opción: {{ $forma_pago['credito_options'] }}</p>
                                    <p class="mx-3 my-0">Banco: {{ $forma_pago['banco'] }}</p>
                                    <p class="mx-3 my-0">Comprobante: {{ $forma_pago['comprobante'] }}</p>
                                    <p class="mx-3 my-0">Monto: {{ number_format($forma_pago['valor'], 2) }}</p>
                                    @break
                                @case('banco')

                                    @foreach ($forma_pago['banco_options'] as  $banco_option)

                                        @switch($banco_option['option'])

                                            @case('efectivo')
                                                <p class="mx-3 my-0">Efectivo</p>
                                                <p class="mx-3 my-0">Monto: {{ number_format($banco_option['valor'], 2) }}</p>
                                                <br>
                                                @break
                                            @case('loteria')
                                                <p class="mx-3 my-0">Loteria</p>
                                                <p class="mx-3 my-0">Monto: {{ number_format($banco_option['valor'], 2) }}</p>
                                                <br>
                                                @break
                                            @case('cheque')
                                                <p class="mx-3 my-0">Cheque</p>
                                                <p class="mx-3 my-0">Banco: {{ $banco_option['banco'] }}</p>
                                                <p class="mx-3 my-0">Comprobante: {{ $banco_option['comprobante'] }}</p>
                                                <p class="mx-3 my-0">Monto: {{ number_format($banco_option['valor'], 2) }}</p>
                                                <br>
                                                @break

                                        @endswitch

                                    @endforeach

                                    @break
                            @endswitch
                        </div>
                        @endforeach
                    </td>
                    <td>
                        <center>
                            @if($data->pdf_data!="")
                                <button class="btn btn-primary" onclick="togglePdf({{ $data->id }})">Mostrar/Ocultar PDF</button> 
                            @endif
                            <button class="btn btn-primary" onclick="toggleImage({{ $data->id }})">Mostrar/Ocultar Imagen</button>
                        </center>
                        <script>
                            function showConfirmationPopup(event) {
                                event.preventDefault(); // Evita que el formulario se envíe por defecto
                                console.log("default")
                                // Muestra el popup de confirmación (puedes usar librerías como Bootstrap o implementar tu propio popup)
                                // Aquí hay un ejemplo de cómo mostrar un popup simple utilizando JavaScript nativo:
                                var confirmed = confirm("¿Estás seguro de que deseas eliminar el registro?");
                                
                                if (confirmed) {
                                    // Si el usuario confirma, envía el formulario
                                    document.getElementById("brinkSend_destroy").submit();
                                }
                            }
                            function toggleImage(id) {
                                var image = document.getElementById("imagen_" + id);
                                if (image.style.display === "none") {
                                    image.style.display = "block"; // Muestra la imagen
                                } else {
                                    image.style.display = "none"; // Oculta la imagen
                                }
                            }
                            function togglePdf(id) {
                                var image = document.getElementById("pdf_" + id);
                                if (image.style.display === "none") {
                                    image.style.display = "block"; // Muestra la pdf
                                } else {
                                    image.style.display = "none"; // Oculta la pdf
                                }
                            }
                        </script>
                    </td>
                    <td style="width:10% !important;">
                        <a href="{{ route('invoice.edit', $data->id) }}" class="btn btn-warning btn-block my-1" style="width: 100% !important;">Editar</a>
                        <form action="{{ route('invoice.delete', $data->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block my-1" style="width: 100% !important;" onclick="return confirm('¿Estás seguro de que deseas eliminar este registro?');">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <tr>
                    
                    <!-- Imagen que se mostrará/ocultará -->
                    <td colspan="17">
                        <img src="data:image/jpeg;base64,{{$data->foto}}" alt="Imagen" id="imagen_{{ $data->id }}" style="width:50%; display: none;">
                    </td>
                </tr>
                @if($data->pdf_data!="")
                            
                <tr>
                    
                    <td colspan="17">
                        <!-- Vista para mostrar el PDF -->
                        <embed src="data:application/pdf;base64,{{ $data->pdf_data }}" style="width:50%; display: none;"id="pdf_{{ $data->id }}" height="600" type="application/pdf">

                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="mt-4 mb-4 float-end">
        {{ $brinksend->links('pagination::bootstrap-4') }}
    </div>
    <form name="provider" id="provider" method="post" action="{{route( 'invoice.getExcel' )}}">
        <div class="form-group w-auto">
            <input type="hidden" name="lista" value="{{ json_encode($brinksend) }}">
            @csrf
            <button class="w-100 btn btn-outline-secondary m-0" type="submit" id="button-addon2">Importar</button>
        </div>
    </form>
</div>
<style>
    #miTablaPersonalizada th {
        width: 150px;
        /* overflow: auto; */
        border: 1px solid;
    }

    table {
        table-layout: fixed;
    }
</style>
