@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <div class="card w-auto">
            <div class="card-header">
                Consultar Deudor
            </div>
            <div class="card-body">
                <form name="loans_search" id="loans_search" method="get" action="{{ route('loans.search') }}">
                    @csrf
                    <div class="row gy-2 gx-3 align-items-center">
                        <div class="col-md">
                            <label>Sucursal</label>
                            <select class="form-control w-100" name="AD_Org_ID" id="AD_Org_ID">
                                @if (isset($orgs))
                                    @if ($orgs->{'records-size'} > 0)
                                        @foreach ($orgs->records as $org)
                                            <option value="{{ $org->id }}"
                                                {{ isset($request) ? ($org->id == $request->AD_Org_ID ? __('selected') : __('')) : __('') }}>
                                                {{ $org->Name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endif
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row gy-2 gx-3 align-items-center">
                        <div class="col-md">
                            <label>Nro. Identificación</label>
                            <input name="cedula" id="cedula" value="" type="text" class="form-control"
                                placeholder="" onchange="busqueda()" onkeyup="busqueda()">
                        </div>
                        <div class="col-md">
                            <label>Nombre</label>
                            <input name="nombre" id="nombre" value="" type="text" class="form-control"
                                placeholder="" onchange="busqueda()" onkeyup="busqueda()">
                        </div>
                        <div class="col-md">
                            <label> </label>
                            <button type="submit" class="form-control btn btn-primary">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br>
        @if (isset($usuario))
            <div class="card w-auto">
                <div class="card-body shadow p-1 mb-1 bg-body-tertiary rounded">
                    @if ($usuario->isNotEmpty())
                        {{--  Modal Inicio --}}
                        @if (count($usuario) > 1)
                            <div class="modal" tabindex="-1" style="display: block">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title">Coincidencias de busqueda {{ count($usuario) }}</h1>
                                        </div>
                                        <div class="modal-body">
                                            <h1></h1>
                                            <form name="loans_search" id="loans_search" method="get"
                                                action="{{ route('loans.search') }}">
                                                @csrf
                                                {{--  <select class="form-select form-select-lg mb-3"
                                                    aria-label=".form-select-lg example" name="cedula">
                                                    <option selected="">Selecione un deudor</option>
                                                    @foreach ($usuario as $data)
                                                        <option value="{{ $data->cedula }}">
                                                            {{ $data->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select> --}}
                                                @foreach ($usuario as $data)
                                                    <center>
                                                        <button type="submit" name="cedula" value="{{ $data->cedula }}"
                                                            type="button"
                                                            class="btn btn-outline-dark w-50">{{ $data->nombre }}</button><br><br>
                                                    </center>
                                                @endforeach


                                                {{--   <button type="submit" class="form-control btn btn-primary">Buscar</button> --}}
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach ($usuario as $data)
                                <br>
                                <div class="container bg-primary p-2 text-dark bg-opacity-10 shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                                    <h2> Datos Deudor </h2>
                                    <div class="row row-cols-2 row-cols-sm-2  row-cols-md-4">
                                        <div class="col">
                                            <p class="lh-1">Nombre del deudor:</p>
                                            <p class="lh-1">Cédula o RUC:</p>
                                        </div>
                                        <div class="col">
                                            <p class="lh-1">{{ $data->nombre }}</p>
                                            <p class="lh-1">{{ $data->cedula }}</p>
                                        </div>
                                        <div class="col">
                                            <p class="lh-1">Total de prestamos:</p>
                                            <p class="lh-1">Total de pagos:</p>
                                            <p class="lh-1">Saldo pendiente:</p>
                                        </div>
                                        <div class="col">
                                            @if (isset($loan_view[0]->sum))
                                                <p class="lh-1 ">$
                                                    @php
                                                        echo number_format($loan_view[0]->sum, 2, ',', ' ');
                                                    @endphp
                                                </p>
                                            @else
                                                <p class="lh-1">$ 0,00</p>
                                            @endif

                                            @if (isset($payment_view[0]->sum))
                                                <p class="lh-1">$
                                                    @php
                                                        echo number_format($payment_view[0]->sum, 2, ',', ' ');
                                                    @endphp
                                                </p>
                                            @else
                                                <p class="lh-1">$ 0,00</p>
                                            @endif
                                            @if (isset($usuario_monto))
                                                @foreach ($usuario_monto as $info)
                                                    @if (isset($usuario_payment[0]->sum))
                                                        <p class="lh-1">$

                                                            @php
                                                                echo number_format($info->sum - $usuario_payment[0]->sum, 2, ',', ' ');
                                                            @endphp

                                                        </p>
                                                    @else
                                                        <p class="lh-1">$
                                                            @php
                                                                echo number_format($info->sum - 0, 2, ',', ' ');
                                                            @endphp
                                                        </p>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="col">
                                        </div>
                                        <div class="col">
                                        </div>
                                    </div>
                                    <div class="row row-cols-2 row-cols-sm-2 ">

                                    </div>
                                    <br>

                                    <button id="Nuevo" class="btn btn-success w-100 mb-1">Nuevo
                                        prestamo</button>
                                    <form name="loans_store_new" id="loans_store_new" method="POST"
                                        action="{{ route('loans.store_new') }}" style="display: block;">
                                        @csrf
                                        <div class="card card-body  border-success shadow p-3 mb-5 bg-body-tertiary rounded">
                                            <input type="hidden" name="cedula_user" value="{{ $data->cedula }}"
                                                type="text" class=" form-control text-left  w-100 " placeholder=""
                                                required>
                                            <input type="hidden" name="nombre_user" value="{{ $data->nombre }}"
                                                type="text" class=" form-control text-left  w-100 " placeholder=""
                                                required>
                                            <input type="hidden" name="loans_users_id" value="{{ $data->id }}"
                                                type="text" class=" form-control text-left  w-100 " placeholder=""
                                                required>
                                            <h1 class="modal-title fs-5 text-success" id="bpartnerModalLabel">Nuevo
                                                prestamo</h1>

                                            <label>Fecha</label>
                                            <input id="min-max" name="fechanuevoprestamo" type="date"
                                                class="form-control" min="2022-01-01" max="2100-12-31" required>
                                            <script>
                                                // Obtener la fecha actual
                                                var hoy = new Date();
                                                // Restar 3 días a la fecha actual
                                                var tresDiasAtras = new Date(hoy.getTime() - (0 * 24 * 60 * 60 * 1000));
                                                // Establecer el atributo min en el campo de fecha con la fecha de hace 3 días
                                                document.getElementById("min-max").max = hoy.toISOString().split("T")[0];
                                                document.getElementById("min-max").min = tresDiasAtras.toISOString().split("T")[0];
                                            </script>
                                            <label>Monto</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">$</span>
                                                <input name="monto" type="number" placeholder="0.00" step="0.01"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)"
                                                    required>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <label>Frecuencia</label>
                                                    <select id="fre" class="form-select" name="frecuencia"
                                                        onchange="deuda()" onkeyup="deuda()">
                                                        <option selected="" value="---">No Aplica
                                                        </option>
                                                        <option value="Diario">Diario</option>
                                                        <option value="Semanal">Semanal</option>
                                                        <option value="Quincenal">Quincenal</option>
                                                        <option value="Mensual">Mensual</option>
                                                    </select>
                                                </div>
                                                <div class="col">
                                                    <label>Monto cuota</label>
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">$</span>
                                                        <input id="cuo" name="cuota" type="number"
                                                            placeholder="0.00" step="0.01" class="form-control"
                                                            aria-label="Amount (to the nearest dollar)" value="0">
                                                    </div>
                                                </div>
                                            </div>
                                            <label>Adjuntar Foto Recibo</label>
                                            <input class=" subirimagen form-control" type="file" id="filePicker"
                                                placeholder="Recibo" name="FileCedula" value="0"
                                                onchange="imgsize()" onkeyup="imgsize()" accept=".png, .jpg, .jpeg"
                                                required>
                                            <textarea style="display:none;" name="filecedula" id="base64textarea" placeholder="Base64 will appear here"
                                                cols="50" rows="15"></textarea>
                                            <br>
                                            <label>Firma</label>
                                            <div>
                                                <center>
                                                    @include('/canvas/tablero3')
                                                    <input type="hidden" id="myText3" name="firmanuevoprestamo"
                                                        value="Firma No File" required>

                                                </center>
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="collapse">Cerrar</button>
                                            </div> --}}
                                                <div class="col">
                                                    <button onclick="allfuncion()" type="submit"
                                                        class="btn btn-primary w-100">Guardar</button>
                                                </div>

                                            </div>
                                        </div>
                                    </form>


                                    <br>
                                    <button id="Pago" class="btn btn-primary w-100 mb-1">Pago Prestamo</button>
                                    <form name="loans_update" id="loans_update" method="POST"
                                        action="{{ route('loans.update') }}">
                                        @csrf
                                        <div class="card card-body border-info shadow p-3 mb-5 bg-body-tertiary rounded">
                                            <input type="hidden" name="cedula_user" value="{{ $data->cedula }}"
                                                type="text" class=" form-control text-left  w-100 " placeholder=""
                                                required>
                                            <input type="hidden" name="nombre_user" value="{{ $data->nombre }}"
                                                type="text" class=" form-control text-left  w-100 " placeholder=""
                                                required>
                                            <h1 class="modal-title fs-5 text-primary" id="bpartnerModalLabel">Pago
                                                Prestamo
                                            </h1>
                                            <label>Fecha</label>
                                            <input id="min-max2" name="datepayment" type="date" value="" class="form-control"
                                                required>

                                            <script>
                                                // Obtener la fecha actual
                                                var hoy = new Date();
                                                // Restar 3 días a la fecha actual
                                                var tresDiasAtras = new Date(hoy.getTime() - (0 * 24 * 60 * 60 * 1000));
                                                // Establecer el atributo min en el campo de fecha con la fecha de hace 3 días
                                                document.getElementById("min-max2").max = hoy.toISOString().split("T")[0];
                                                document.getElementById("min-max2").min = tresDiasAtras.toISOString().split("T")[0];
                                            </script>
                                            <br>
                                            <input name="loans_users_id" type="hidden" value="{{ $usuario[0]->id }}"
                                                class="form-control">
                                            <label>Seleccionar un prestamo</label>
                                            <select id="cc" class="form-select"
                                                aria-label="Default select example" required onchange="deuda()"
                                                onkeyup="deuda()">
                                                @if (isset($usuario_monto))
                                                    @foreach ($usuario_monto as $info)
                                                        <option selected value="all">Abono
                                                            Global</option>
                                                    @endforeach
                                                @endif
                                                {{-- @if (isset($usuario_loans))
                                                        @foreach ($usuario_loans as $info)
                                                            <option value="{{ $info->id }}">
                                                                Fecha: {{ $info->fechanuevoprestamo }} -----
                                                                Monto: {{ $info->monto }}
                                                            </option>
                                                        @endforeach
                                                    @endif --}}
                                            </select>
                                            <br>
                                            <div hidden class="input-group mb-3">
                                                <span hidden class="input-group-text">$</span>
                                                <input hidden name="loans_id" class="form-control" type="text"
                                                    placeholder="Disabled input" id="xyz">
                                            </div>
                                            <label>Monto a Pagar</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">$</span>
                                                <input name="amount" type="number" placeholder="0.00" step="0.01"
                                                    class="form-control" aria-label="Amount (to the nearest dollar)">
                                            </div>
                                            <br>
                                            <label>Adjuntar Foto *Pago*</label>
                                            <input class=" subirimagen2 form-control" type="file" id="filePicker2"
                                                placeholder="Recibo" name="filee" value="0" onchange="imgsize2()"
                                                onkeyup="imgsize2()" accept=".png, .jpg, .jpeg" required>
                                            <textarea style="display:none;" name="file" id="base64textarea2" placeholder="Base64 will appear here"
                                                cols="50" rows="15"></textarea>
                                            <br>
                                            <br>
                                            <label>Firma *Pago*</label>
                                            <div>
                                                <center>
                                                    @include('canvas/tablero4')
                                                    <input type="hidden" id="myText4" name="signature"
                                                        value="Firma No File" required>
                                                </center>
                                            </div>
                                            <div class="row">
                                                {{-- <div class="col">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                            </div> --}}
                                                <div class="col">
                                                    <button onclick="allfuncion()" type="submit"
                                                        class="btn btn-primary w-100">Guardar</button>
                                                </div>

                                            </div>

                                        </div>
                                    </form>

                                </div>
                            @endforeach
                            <div class="card w-auto">
                                <div class="card-header">
                                    Estado de cuenta de prestamos
                                </div>
                                <div class="card-body">
                                    @livewire('loanssearch')
                                </div>
                            </div>
                        @endif
                    @else
                        <form name="loans_newuser" id="loans_newuser" method="POST"
                            action="{{ route('loans.newuser') }}">
                            @csrf

                            <div class="container bg-success p-2 text-dark bg-opacity-10 shadow p-3 mb-5 bg-body-tertiary rounded">
                                <h2> Creacion Deudor </h2>
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-2">
                                    <div class="co-md">
                                        <label>Nombre del Deudor</label>
                                        <input name="nombre" value="{{ $nombre }}" type="text"
                                            class=" form-control text-left  w-100 " placeholder="" required>
                                    </div>
                                    <div class="col-md">
                                        <label>Cédula o RUC</label>
                                        <input name="cedula" value="{{ $cedula }}" type="text"
                                            class=" form-control text-left  w-100 " placeholder="" required>
                                    </div>
                                    <div class="col-md">
                                        <label>Telefono</label>
                                        <input name="telefono" value="" type="text"
                                            class=" form-control text-left  w-100 " placeholder="" required>
                                    </div>
                                    <div class="col-md">
                                        <label>Solicitante</label>
                                        <input name="solicitante" value="" type="text"
                                            class=" form-control text-left  w-100 " placeholder="" required>
                                    </div>
                                    <div class="col-md">
                                        <label>Direccion</label>
                                        <input name="direccion" value="" type="text"
                                            class=" form-control text-left  w-100 " placeholder="" required><br>
                                    </div>
                                    <div class="col-md">
                                        <label>Adjuntar Foto Cedula</label>
                                        <input class=" subirimagen form-control" type="file" id="filePicker"
                                            placeholder="Cedula" name="" value="0" onchange="imgsize()"
                                            onkeyup="imgsize()" accept=".png, .jpg, .jpeg" required>
                                        <textarea style="display:none;" name="fotocedula" id="base64textarea" placeholder="Base64 will appear here"
                                            cols="50" rows="15"></textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-success my-auto active w-100 mb-1">Guardar</button>
                                <a href="{{ url('loans_search') }}" class="btn btn-secondary active my-auto w-100 mb-1"
                                    role="button" aria-pressed="true">Cancelar</a>
                            </div>
                        </form>
                        <br>
                        <div class="card w-auto">
                            <div class="card-header">
                                Estado de cuenta de prestamos
                            </div>
                            <div class="card-body">
                                @livewire('loanssearch')
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif





        <script>
            window.onload = function() {
                deuda();
                var today = new Date();

                var day = today.getDate();
                var month = today.getMonth() + 1;
                var year = today.getFullYear();
                console.log(`${year}-${month}-${day}`);
                document.getElementById('loans_store_new').style.display = 'none';
                document.getElementById('loans_update').style.display = 'none';
            }

            function allfuncion() {
                b64img3();
                b64img4();
            }

            function ocultar() {

            }

            function busqueda() {


                if (document.getElementById("cedula").value == "") {
                    //alert("blanco");
                    document.getElementById("nombre").removeAttribute("disabled", "")
                } else {
                    //alert("lleno");
                    document.getElementById("nombre").value = "";
                    document.getElementById("nombre").setAttribute("disabled", "");
                }

                if (document.getElementById("nombre").value == "") {
                    //alert("blanco");
                    document.getElementById("cedula").removeAttribute("disabled", "")
                } else {
                    //alert("lleno");
                    document.getElementById("cedula").value = "";
                    document.getElementById("cedula").setAttribute("disabled", "");
                }
            }

            /* function imgsize() {
                                var imgsize = document.getElementsByClassName("subirimagen")[0].files[0].size;
                                if (imgsize > 5000000) {
                                    alert('El archivo supera los 5Mb.');
                                    document.getElementsByClassName("subirimagen").filePicker.value = "";
                                }
                            }

                            function imgsize2() {
                                var imgsize2 = document.getElementsByClassName("subirimagen2")[0].files[0].size;
                                if (imgsize2 > 5000000) {
                                    alert('El archivo supera los 5Mb.');
                                    document.getElementsByClassName("subirimagen2").filePicker2.value = "";
                                }
                            }
                 */


            function deuda() {
                document.getElementById("xyz").value = document.getElementById("cc").value;
                const cambio = document.getElementById("cuo");
                if ('---' == document.getElementById("fre").value) {

                    cambio.setAttribute('disabled');
                    //cambio.classList.replace("text-success", "text-danger");
                } else {
                    cambio.removeAttribute('disabled');
                    cambio.setAttribute('enable');
                }

            }
            var handleFileSelect = function(evt) {
                var files = evt.target.files;
                var file = files[0];
                if (files && file) {
                    var reader = new FileReader();
                    reader.onload = function(readerEvt) {
                        var binaryString = readerEvt.target.result;
                        document.getElementById("base64textarea").value = btoa(binaryString);
                        document.getElementById("base64textarea2").value = btoa(binaryString);
                    };
                    reader.readAsBinaryString(file);
                }
            };
            if (window.File && window.FileReader && window.FileList && window.Blob) {
                document.getElementById('filePicker').addEventListener('change', handleFileSelect, false);
                document.getElementById('filePicker2').addEventListener('change', handleFileSelect, false);
            } else {
                alert('The File APIs are not fully supported in this browser.');
            }


            ////
            const formulario = document.getElementById('loans_store_new');
            const boton = document.getElementById('Nuevo');

            boton.addEventListener('click', () => {
                if (formulario.style.display === 'none') {
                    formulario.style.display = 'block';
                    /* boton.textContent = 'Ocultar formulario'; */
                } else {
                    formulario.style.display = 'none';
                    /* boton.textContent = 'Mostrar formulario'; */
                }
            });


            const formulario2 = document.getElementById('loans_update');
            const boton2 = document.getElementById('Pago');

            boton2.addEventListener('click', () => {
                if (formulario2.style.display === 'none') {
                    formulario2.style.display = 'block';
                    /*  boton2.textContent = 'Ocultar formulario'; */
                } else {
                    formulario2.style.display = 'none';
                    /* boton2.textContent = 'Mostrar formulario'; */
                }
            });
            ////
        </script>
    </div>
@endsection
