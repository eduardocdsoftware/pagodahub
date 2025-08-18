<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        
    </title>
</head>
<body>

    <div class="p-2 m-0 border-0 bd-example">
        
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3">
                <div class="col">   
                    <h4>Fecha: {{isset($fecha)?($fecha):'' }}
                    </h4>
                    <h4>Sucursal: {{isset($sucursal)?($sucursal):'' }}
                    </h4>             
                    <h4>Presupuesto inicial: {{isset($presupuesto)?($presupuesto):0 }}
                    </h4>
                </div>
                
            </div>
            @foreach ($comprasdeldia as $ind =>$data)
            @if($data->budget!==null)
                <div class="form-group w-50">
                    @csrf            
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Lista de productos: {{ $cantidad }} 
                        </div>
                        <br>
                        <center>                    
                            <div class="p-4 m-0 border-0">
                                
                                    <div class="table-responsive table-responsive-sm">
                                        <table name="table[]" class="table table-bordered border-success">
                                            <thead>
                                                <tr>
                                                    <th style="max-width: 50px;">#</th>
                                                    <th>Producto</th>
                                                    <th>Unidad</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (json_decode($data->product) as $index => $product)

                                                    @if ($loop->index < count(json_decode($data->product)) )
                                                        <tr>
                                                            <td style="max-width: 50px;">{{ $index + 1 }}</td>
                                                            <td>
                                                                {{$product}}
                                                            </td>
                                                            <td>
                                                                {{ json_decode($data->unit)[$index] }}
                                                            </td>
                                                            <td>
                                                                {{ isset(json_decode($data->quantity)[$index]) ? json_decode($data->quantity)[$index] : '0' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                            
                            </div>
                        </center> 
                        <br>
                    </div>
            @endif 
            @endforeach
            
        </div>
        <style>
            .centrado {
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }

    table {
        font-family: Arial, sans-serif;
        background-color: white;
        text-align: left;
        border-collapse: collapse;
        width: 100%;
    }

    table th,
    table td {
        padding: 7px;
        text-align: center;
    }

    table thead {
        background-color: #246355;
        border-bottom: 5px solid #0F362D;
        color: white;
    }

    table th {
        max-width: 100px;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size:15px;
    }

    table tr:nth-child(even) {
        background-color: #ddd;
    }

    table tr:hover td {
        background-color: #369681;
        color: white;
    }

    table thead th[colspan="12"],
    table thead th[colspan="5"],
    table thead th[colspan="3"] {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    table thead th[colspan="12"],
    table thead th[colspan="5"],
    table thead th[colspan="3"],
    table td[colspan="2"] {
        text-align: center;
    }

    table td[colspan="2"] {
        font-weight: bold;
    }

    table tr td:first-child {
        font-weight: bold;
    }

    table tr:last-child td {
        font-weight: bold;
    }

    .table-divider {
        width: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    label {
        font-weight: bold;
    }

    input[type="checkbox"],
    input[type="text"] {
        margin-top: 5px;
    }

    button {
        margin-top: 10px;
    }
        </style>
</body>
