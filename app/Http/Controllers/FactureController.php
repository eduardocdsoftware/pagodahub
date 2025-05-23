<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Models\marketshopping;
use App\Models\Cheque;
use DB;
use PDF;
class FactureController extends Controller
{
    public function index()
    {
        $APIController = new APIController();
        ////////////
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        /*$user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        
        $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $user->records[0]->AD_Org_ID->id);
        */$user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');

        // Inicializa un array para almacenar los AD_Org_ID
        $orgs = []; // Inicializa un array para almacenar los registros de AD_Org
        
        foreach ($user->records as $record) {
            $orgId = $record->AD_Org_ID->id;
            $response = $APIController->getModel('RV_GH_Org', '', 'AD_Org_ID eq ' . $orgId);
            
            if(isset($response->records[0]->Parent_ID)){
                if($response->records[0]->Parent_ID->id!==0){
                    // Consulta el registro de AD_Org para el AD_Org_ID actual
                    $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $response->records[0]->Parent_ID->id);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs[] = $response->records[0];
                    }
                }else{
                    // Consulta el registro de AD_Org para el AD_Org_ID actual
                    $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $orgId);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs[] = $response->records[0];
                    }
                }
            }else{
                    // Primera solicitud para AD_Org_ID = 1000009
                    $response1 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000009');

                    // Segunda solicitud para AD_Org_ID = 1000008
                    $response2 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000008');

                    // Tercera solicitud para AD_Org_ID = 1000012
                    $response3 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000012');

                    // Combinar las respuestas en un único array
                    $response->records = array_merge($response1->records, $response2->records, $response3->records);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs=$response->records;
                        
                    }
            }
        }
        session()->put('misDatos', $orgs);
        $day = date('Y-m-d'); // Obtener la fecha actual
        $presupuesto = "no asignado para el dia";
        $vuelto=0;
        $calculo = marketshopping::where('shoppingday', $day)->whereNotNull('budget')->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget+$calculo[0]->carton;
            $vuelto=$calculo[0]->vuelto;
            $comprasdeldia = Facture::where('fecha', $day)->get();
            
            foreach ($comprasdeldia as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;

                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                if($presupuesto == "no asignado para el dia"){
                    $presupuesto=0;
                }
                $presupuesto-=$check->monto;
            }
        }
        return view('facture', compact('presupuesto','vuelto','orgs')); // Pasar los facturas a la vista
    }


    public function store(Request $request)
    {   
  
        // Buscar si existe un registro con el mismo número de factura
        $existingFacture = Facture::where('id_compra', $request->NFactura)->first();
        if ($existingFacture) {
            // Si se encuentra un registro con el mismo número de factura, devuelve un mensaje de error
            $request->session()->flash('mensaje', 'El numero de factura '.$request->NFactura.' ya existe');
            
            return redirect()->back();
        }
        $productos = $request->product;
        $cantidadRecibida =$request->differenceFactura;
        $precio = $request->price;
        $unidadMedida =$request->unit;
        $cantidadFact =$request->quantity;
        $contador=0;
        for ($i=0; $i <= count($cantidadRecibida); $i++) { 
            if(isset($cantidadRecibida[$i]) &&$cantidadRecibida[$i]==0){
                array_splice($productos, $i, 1);
                array_splice($cantidadRecibida, $i, 1);
                array_splice($precio, $i, 1);
                array_splice($unidadMedida, $i, 1);
                array_splice($cantidadFact, $i, 1);
                $i--;
            }
        }
        $registro = new Facture();
        $registro->id_market =$request->id;
        $registro->id_compra = $request->NFactura;
        $registro->fecha = $request->fecha_registro;
        $registro->proveedor = $request->proveedor;
        $registro->monto_abonado = $request->abono;
        $registro->medio_de_pago = $request->metodo;
        $registro->diferencia =$request->diff;
        $registro->Total_compra =$request->sumdifac;
        $registro->descripcion=$request->observaciones;
        $registro->vuelto=$request->pfinal;
        $registro->pagada=false;
        $registro->carton=$request->cart;
        $registro->fecha_pago="Sin pagar";
        if($request->metodo==="true"){
            $registro->pagada=true;
            //$registro->fecha_pago=$request->fecha_registro;
            $registro->fecha_pago=$request->fecha_pago;
        }
        if (isset($request->cart)||isset($request->vuelto_p) ) {
            $compradeldia = marketshopping::where('id', $request->id)->first();
            
            if($compradeldia){
                if($request->cart!=null&&$request->cart!=""&&$request->cart!="NaN"){
                    $compradeldia->carton+=$request->cart;
                }if($request->vuelto_p!=null&&$request->vuelto_p!=""&&$request->vuelto_p!="NaN"){
                    $compradeldia->vuelto=$request->vuelto_p;
                }
                    $compradeldia->save();
            }
        
        }
        $registro->Factured_quantity =json_encode($cantidadRecibida);
        $registro->price =json_encode($precio);
        $registro->product = json_encode($productos);
        $registro->units = json_encode($unidadMedida);
        
        if (!empty($request->archivosimg) && is_array($request->archivosimg) && count($request->archivosimg) > 0) {
            $registro->file = $request->archivosimg[0];
        }
        $registro->total = $request->sumdifac;
        $comprasdeldia = marketshopping::where('id', $request->id)->orderBy('created_at', 'asc')->get();
        $registro->sucursal = $comprasdeldia[0]->sucursal;
        $registro->save(); // Guardar el nuevo registro en la base de datos   
        
        // Asignar los valores del formulario a las propiedades del modelo
        
        
        $presupuesto=0;
        $carton =0;
        $vuelto=0;
        if ($comprasdeldia->count() > 0) {
            $carton =$comprasdeldia[0]->carton;
            $vuelto=$comprasdeldia[0]->vuelto;
            $presupuesto = $comprasdeldia[0]->budget;
            $productos = json_decode($comprasdeldia[0]->product);
            $quantity = json_decode($comprasdeldia[0]->quantity);
            $facturas = Facture::where('id_market', $comprasdeldia[0]->id)->get();
            for ($i = 0; $i < $facturas->count(); $i++) {
                $Fproductos = json_decode($facturas[$i]->product);
                $Fquantity = json_decode($facturas[$i]->Factured_quantity);
        
                // Comparar los elementos de los arreglos y restar las cantidades correspondientes
                foreach ($productos as $posicion => $producto) {
                    if (in_array($producto, $Fproductos)) {
                        $fPosicion = array_search($producto, $Fproductos);
                        $quantity[$posicion] -= $Fquantity[$fPosicion];
                    }
                }
            }
            $comprasdeldia[0]->quantity = json_encode($quantity);
        }
        $sucursal=$comprasdeldia[0]->sucursal;
        $facturas = Facture::where('fecha', $request->input('fecha_registro'))->where('sucursal', 'ilike', "%$sucursal%" )->get();
        
        if($facturas->count() > 0) {
            foreach ($facturas as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;
                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
                /*
                if($factura->carton>0){
                    $carton =$factura->carton;
                }
                */
            }
        }
        $cheques = Cheque::where('fecha',$request->input('fecha_registro'))->where('sucursal', 'ilike', "%$sucursal%" )->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }
        $APIController = new APIController();
        ////////////
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        /*$user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        
        $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $user->records[0]->AD_Org_ID->id);
        */$user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');

        // Inicializa un array para almacenar los AD_Org_ID
        $orgs = []; // Inicializa un array para almacenar los registros de AD_Org
        
        foreach ($user->records as $record) {
            $orgId = $record->AD_Org_ID->id;
            $response = $APIController->getModel('RV_GH_Org', '', 'AD_Org_ID eq ' . $orgId);
            
            if(isset($response->records[0]->Parent_ID)){
                if($response->records[0]->Parent_ID->id!==0){
                    // Consulta el registro de AD_Org para el AD_Org_ID actual
                    $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $response->records[0]->Parent_ID->id);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs[] = $response->records[0];
                    }
                }else{
                    // Consulta el registro de AD_Org para el AD_Org_ID actual
                    $response = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq ' . $orgId);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs[] = $response->records[0];
                    }
                }
            }else{
                    // Primera solicitud para AD_Org_ID = 1000009
                    $response1 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000009');

                    // Segunda solicitud para AD_Org_ID = 1000008
                    $response2 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000008');

                    // Tercera solicitud para AD_Org_ID = 1000012
                    $response3 = $APIController->getModel('AD_Org', '', 'AD_Org_ID eq 1000012');

                    // Combinar las respuestas en un único array
                    $response->records = array_merge($response1->records, $response2->records, $response3->records);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs=$response->records;
                        
                    }
            }
        }
        session()->put('misDatos', $orgs);
        return view('marketinvoice', compact('comprasdeldia','presupuesto','carton','facturas','vuelto','orgs'));
        //return redirect()->back()->with('success', 'Registro creado exitosamente'); // Redirigir a la vista principal con un mensaje de éxito
    }

    
    public function searchByProvider(Request $request)
    {
        $providerName = $request->input('provider');
        $query = Facture::query();
        if (!empty($providerName)) {
            $query->whereRaw("LOWER(REPLACE(proveedor, ' ', '')) LIKE '%' || LOWER(REPLACE(?, ' ', '')) || '%'", [$providerName])
                ->where('pagada', false);
        }else{
            $query->where('pagada', false);
        }
        $facturas = $query->get();

        $day = date('Y-m-d'); // Obtener la fecha actual
        $presupuesto = "no asignado para el dia";
        $calculo = marketshopping::where('shoppingday', $day)->where('shoppingday', $day)->whereNotNull('budget')->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget+$calculo[0]->carton;
            $comprasdeldia = Facture::where('fecha', $day)->get();
            
            foreach ($comprasdeldia as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;

                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }
        
        return view('factureFilter', compact('facturas','presupuesto','providerName'))->with('ok',true);
    }

    public function getAllCredit(Request $request)
    {
        $sucursal=$request->AD_Org_ID;
        //RECORDATORIO QUE TODOS LOS CREDITOS ESTAN EN LA DB CON VALOR 1
        $facturas = Facture::where('pagada', false)->where('sucursal', 'ilike', "%$sucursal%" )->get();
        $day = date('Y-m-d'); // Obtener la fecha actual
        $presupuesto = "no asignado para el dia";
        $calculo = marketshopping::where('shoppingday', $day)->where('shoppingday', $day)->where('sucursal', 'ilike', "%$sucursal%" )->whereNotNull('budget')->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget+$calculo[0]->carton;
            $comprasdeldia = Facture::where('fecha', $day)->where('sucursal', 'ilike', "%$sucursal%" )->get();
            
            foreach ($comprasdeldia as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;

                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->where('sucursal', 'ilike', "%$sucursal%" )->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    if($presupuesto == "no asignado para el dia"){
                        $presupuesto=0;
                    }
                    $presupuesto-=$check->monto;
                }
        }
        $providerName="";
        $orgs=$sucursal;
        return view('factureFilter', compact('facturas','presupuesto','providerName','orgs'))->with('refresh', true);
    }
    public function borrar($id)
    {
        // Buscar la factura por su ID 
        $facture = facture::where('id', $id)->delete();
        if (!$facture) {
            return redirect()->back()->with('error', 'La factura no existe');
        }

        return redirect()->back()->with('success', 'La factura ha sido borrada exitosamente');
    }
    public function eliminar(Request $request)
    {   
        // Buscar la factura por su ID y eliminarla directamente
        $facture = facture::where('id', $request->id)->first();
        $facture->delete();
        
        if (!$facture) {
            return redirect()->back()->with('error', 'La factura no existe');
        }

       

        return redirect()->back()->with('success', 'La factura ha sido borrada exitosamente');
    }

    public function show($id)
    {
        // Buscar la factura por su ID 
        $comprasdeldia = Facture::find($id);
        $presupuesto = "no asignado para el dia";
        $calculo = marketshopping::where('id', $comprasdeldia->id_market)->whereNotNull('budget')->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget+$calculo[0]->carton;
            $compras = Facture::where('fecha', $calculo[0]->shoppingday)->get();
            
            foreach ($compras as $fact) {
                if($fact->medio_de_pago){
                    $presupuesto-=$fact->total;

                }
                if(!$fact->medio_de_pago){
                    $presupuesto-=$fact->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$calculo[0]->shoppingday)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }
        $presupuesto+=$comprasdeldia->total;
        return view('editmarketinvoice', compact('comprasdeldia','presupuesto'));
    }

    public function downloadPdf(Request $request)
    {
        $APIController = new APIController();
         $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        $permisos2 = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        
        //request->montoParcial
        $metodoPago="Presupuesto";
        $codigo="000000";
        $fechaPago=date('Y-m-d');
        $presupuesto = "no asignado para el dia";
        $calculo=[];
        $fechaExpedicion=$fechaPago;
        $banco="";
        $ispresupuesto=false;
        if($request->banco){
            $banco=$request->banco;
        }
        //revisa si se paga con el presupuesto
        if($request->pagoPresupuesto){
            $pagoPresupuesto=$request->pagoPresupuesto;  
            $calculo = marketshopping::where('shoppingday', $fechaPago)->where('sucursal',$request->sucursal)->get();
            $ispresupuesto=true;
        }else{
            $metodoPago=$request->metodoPago;
            $codigo=$request->codigo;
            $fechaExpedicion=$request->fechaExpedicion;
            //verifica si se paga con el presupuesto de un dia anterior
            if($metodoPago==="Dia anterior"){
                $codigo="000000";
                $fechaPago=$request->fechaPago;
                $calculo = marketshopping::where('shoppingday', $fechaPago)->where('sucursal',$request->sucursal)->get();
                $ispresupuesto=true;
                $fechaExpedicion=$fechaPago;
            }
        }
        $idCompras = $request->input('factura_ids');
        $idCompras = explode(',', $idCompras);
        //busca las facturas que se seleccionaron para pagar
        $resultados = DB::table('factures')->whereIn('id_compra', $idCompras)->get();
        
        //valida si hay un presupuesto asignado ese dia y si no manda error
        if($ispresupuesto===true){
            if ($calculo->count()>0) {
                $presupuesto=$calculo[0]->budget+$calculo[0]->carton;
                //descuenta del presupuesto las facturas que toca cancelar
                $comprasdeldia = Facture::where('fecha', $calculo[0]->shoppingday)->where('id_market', $calculo[0]->id)->where('sucursal',$request->sucursal)->get();            
                foreach ($comprasdeldia as $factura) {
                    if($factura->medio_de_pago){
                        $presupuesto-=$factura->total;
                    }
                    if(!$factura->medio_de_pago){
                        $presupuesto-=$factura->monto_abonado;
                    }
                }
            }else{
                $providerName="";
                $facturas=Facture::where('pagada', false)->where('sucursal',$request->sucursal)->get();
                $orgs=$request->sucursal;
                return view('factureFilter', compact('facturas','presupuesto','providerName','permisos2','orgs'))->withErrors("No se puede proceder con el pago de la factura porque no existe un presupuesto para ese dia");
            }

            $cheques = Cheque::where('fecha',$fechaPago)->where('pago_presupuesto',true)->where('sucursal',$request->sucursal)->get();
            if ($cheques->count()>0) {
                foreach ($cheques as $check) {
                    if($presupuesto == "no asignado para el dia"){
                        $presupuesto=0;
                    }
                    $presupuesto-=$check->monto;
                }
            }
            $deuda=0;
            if($request->pagoParcial==true){
                $deuda=$request->montoParcial;
            }else{
                foreach ($resultados as $resultado) {
                    $deuda+=$resultado->Total_compra-$resultado->monto_abonado;
                }
            }
            if($deuda>$presupuesto){
                //la deuda no se puede pagar con el presupuesto
                $providerName="";
                $facturas=$resultados;
                $orgs=$request->sucursal;
                return view('factureFilter', compact('facturas','presupuesto','providerName','permisos2','orgs'))->withErrors("No se puede proceder con el pago de la factura porque el presupuesto es menor al monto a cancelar");
            }
        }
        $pagoPresupuesto=false;
        if($metodoPago==='Dia anterior'||$metodoPago==='Presupuesto'){
            $pagoPresupuesto=true;
        }
        //por cada c0digo se genera un registro

        if (count($idCompras)>0) {
            if($request->pagoParcial==true){
                $factura = Facture::where('id_compra', $idCompras[0])->where('sucursal',$request->sucursal)->first();
                $monto=$request->montoParcial;
                $deuda=$request->montoParcial;
                if($factura){
                    /*if($request->monto===0||$request->monto===null){
                        $monto=$factura->total-$factura->monto_abonado;
                        //$factura->monto_abonado=$factura->total;
                    }*/
                    //if($request->monto!==0){
                        //$monto=$request->monto;
                        $factura->monto_abonado+=$monto;
                    //}
                    if($factura->monto_abonado>=$factura->total){
                        $factura->pagada=true;
                        $factura->fecha_pago=date('Y-m-d');
                    }
                    $factura->save();
                }else{
                    $orgs=$request->sucursal;
                    return view('factureFilter', compact('facturas','presupuesto','providerName','permisos2','orgs'))->withErrors("No se puede proceder con el pago de la factura porque no existe la factura ".$idCompras[$i]);
                }
                $cheque = new Cheque();
                $cheque->fecha = $fechaPago;
                $cheque->id_factura = $factura->id_compra;
                $cheque->pago_presupuesto = $pagoPresupuesto;
                $cheque->monto = $monto;
                $cheque->tipo=$metodoPago;
                $cheque->codigo=$codigo;
                $cheque->banco=$banco;
                $cheque->fechaExpedicion=$fechaExpedicion;
                $cheque->sucursal=$request->sucursal;
                $cheque->save();
                
            }else{
                for ($i=0; $i < count($idCompras); $i++) { 
                    $factura = Facture::where('id_compra', $idCompras[$i])->first();
                    
                    $monto=0;
                    if($factura){
                        if($request->monto===0||$request->monto===null){
                            $monto=$factura->total-$factura->monto_abonado;
                            //$factura->monto_abonado=$factura->total;
                        }
                        /*if($request->monto!==0){
                            $monto=$request->monto;
                            $factura->monto_abonado+=$request->monto;
                            dd($request->monto);
                        }*/
                        //if($factura->monto_abonado>=$factura->total){
                            $factura->pagada=true;
                            $factura->fecha_pago=date('Y-m-d');
                        //}
                        $factura->save();
                    }else{
                        return view('factureFilter', compact('facturas','presupuesto','providerName','permisos2'))->withErrors("No se puede proceder con el pago de la factura porque no existe la factura ".$idCompras[$i]);
                    }
                    $cheque = new Cheque();
                    $cheque->fecha = $fechaPago;
                    $cheque->id_factura = $factura->id_compra;
                    $cheque->pago_presupuesto = $pagoPresupuesto;
                    $cheque->monto = $monto;
                    $cheque->tipo=$metodoPago;
                    $cheque->codigo=$codigo;
                    $cheque->banco=$banco;
                    $cheque->fechaExpedicion=$fechaExpedicion;
                    $cheque->sucursal=$request->sucursal;
                    $cheque->save();
                    
                }
            }
        }
        if($request->pagoParcial==true){
            $pdf = PDF::loadView('download-pdf_compras', ['resultados' => $resultados,'metodoPago'=>$metodoPago,'codigo'=>$codigo,'banco'=>$banco,'fecha_expedicion'=>$fechaExpedicion,'montoParcial'=>$deuda,'permisos2'=>$permisos2]);
            return $pdf->download("factura.pdf");
        }else{
            $pdf = PDF::loadView('download-pdf_compras', ['resultados' => $resultados,'metodoPago'=>$metodoPago,'codigo'=>$codigo,'banco'=>$banco,'fecha_expedicion'=>$fechaExpedicion,'montoParcial'=>0,'permisos2'=>$permisos2]);
            return $pdf->download("factura.pdf");
        }
    }
    public function pagar(Request $request)
    {
        $idCompras = $request->input('facturas_ids');
        $idCompras = explode(',', $idCompras);
        $pagoPresupuesto=false;
        if($request->pagoPresupuesto){
            $pagoPresupuesto=$request->pagoPresupuesto;
        }
        $monto=0;
        if($request->monto){
            $monto=$request->monto;
        }
        if (count($idCompras)>0) {
            
            for ($i=0; $i < count($idCompras); $i++) { 
                
                $factura = Facture::where('id_compra', $idCompras[$i])->first();
                
                if($monto===0){
                    $monto=($factura->total-$factura->monto_abonado);
                }
                if($monto!==0){
                    $factura->monto_abonado+=$monto;
                    if($factura->monto_abonado>=$factura->total){
                        $factura->pagada=true;
                        $factura->fecha_pago=date('Y-m-d');
                    }
                }

                $factura->save();
                
                $cheque = Cheque::create([
                    'fecha' => date('Y-m-d'),
                    'id_factura' => $factura->id_compra,
                    'pago_presupuesto' => $pagoPresupuesto,
                    'monto' => $monto,
                ]);
            }
            # code...
        }
        $facturas = Facture::all(); // Obtener todos los facturas de la tabla
        $presupuesto = "no asignado para el dia";
        $day = date('Y-m-d'); // Obtener la fecha actual
        $calculo = marketshopping::where('shoppingday', $day)->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget+$calculo[0]->carton;;
            $comprasdeldia = Facture::where('fecha', $day)->get();
            
            foreach ($comprasdeldia as $factura) {
                if($actura->medio_de_pago){
                    $presupuesto-=$factura->total;
                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }
        

        return view('facture', compact('facturas','presupuesto')); // Pasar los facturas a la vista
    }

    public function update(Request $request)
    {
        // Obtener el registro existente de la base de datos
        $registro = Facture::findOrFail($request->id);
        
        // Actualizar los valores del registro con los datos del formulario        
        $registro->Total_compra =$request->sumdifac;
        
        //$registro->vuelto =$registro->vuelto;
        $registro->vuelto=$request->pfinal;
        $registro->Factured_quantity =json_encode($request->differenceFactura);
        $registro->price =json_encode($request->price);
        $registro->carton=$request->carton;
        $registro->fecha_pago=$request->fecha_pago;


        // Actualizar el registro relacionado en la tabla marketshopping
        /*
        $updateMarket = marketshopping::where('id_compra', $registro->id_compra)->first();
        $updateMarket->id_compra = $request->NFactura;
        $updateMarket->save();
        */
        $registro->save(); // Guardar los cambios en la base de datos

        // Actualizar el registro relacionado en la tabla marketshopping
        $updateMarket = marketshopping::where('id', $registro->id_market)->first();
        $updateMarket->carton = Facture::where('id_market', $registro->id_market)->sum('carton');
        $updateMarket->save();

        $facturas = Facture::all(); // Obtener todos los facturas de la tabla
        $day = date('Y-m-d'); // Obtener la fecha actual
        $presupuesto = "no asignado para el dia";
        $calculo = marketshopping::where('shoppingday', $day)->whereNotNull('budget')->get();
        if ($calculo->count()>0) {
            $presupuesto=$calculo[0]->budget;
            $comprasdeldia = Facture::where('fecha', $day)->get();
            
            foreach ($comprasdeldia as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;

                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }
        return redirect()->back()->with('success', 'La factura ha sido borrada exitosamente'); // Pasar los facturas a la vista
    }

    public function resume(Request $request){
        $sucursal=$request->AD_Org_ID;
        $calculo = marketshopping::where('shoppingday', $request->day)->orderBy('shoppingday', 'desc')->where('sucursal', 'ilike', "%$sucursal%" )->get();
        $vueltoEntregado= 0;
        $facturas = Facture::where('fecha', $request->day)->orderBy('fecha', 'desc')->where('sucursal', 'ilike', "%$sucursal%" )->get();
        $cantidadProductos=0;
        $tFactura=0;        
        $abonado=0;
        $tEfectivo=0;
        $tCredito=0;
        $deuda=0;
        foreach ($facturas as $f) {
            $cantidades=json_decode($f->Factured_quantity);
            foreach ($cantidades as $cantidad) {
                $cantidadProductos+=$cantidad;
            }
            $tFactura+=$f->total;
            $abonado+=$f->monto_abonado;
            if($f->medio_de_pago==true){
                $tEfectivo+=$f->total;
            }
            if($f->medio_de_pago==false){
                $tCredito+=$f->total;
                $deuda+=$f->total-$f->monto_abonado;
            }
        }
        
        $pagosAnteriores=0;
        $cheques = Cheque::where('fecha',$request->day)->where('pago_presupuesto',true)->where('sucursal', 'ilike', "%$sucursal%" )->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                $pagosAnteriores+=$check->monto;
            }
        }
        
        $fecha=$request->day;
        $presupuesto=0;
        $carton=0;
        if(count($calculo)){
            $presupuesto=$calculo[0]->budget;
            $carton=$calculo[0]->carton;
            $vueltoEntregado=$calculo[0]->vuelto;
        }
        
        $tComprado=0;
        $vuelto=$presupuesto+$carton-$tEfectivo-$abonado-$pagosAnteriores;
        $cheques = Cheque::where('fecha',$request->day)->where('sucursal', 'ilike', "%$sucursal%" )->get();
        return view('factureResume',compact('fecha',
        'cantidadProductos',
        'presupuesto',
        'facturas',
        'carton',
        'tComprado',
        'tFactura',
        'tEfectivo',
        'tCredito',
        'abonado',
        'pagosAnteriores',
        'vuelto',
        'vueltoEntregado',
        'cheques',
        'deuda',
        'sucursal'
        ));
    }
    public function resumePdf(Request $request){
        $calculo = marketshopping::where('shoppingday', $request->day)->where('sucursal', 'ilike', "%$request->sucursal%" )->orderBy('shoppingday', 'desc')->get();
        $vueltoEntregado= 0;
        $facturas = Facture::where('fecha', $request->day)->where('sucursal', 'ilike', "%$request->sucursal%" )->orderBy('fecha', 'desc')->get();
        $cantidadProductos=0;
        $tFactura=0;        
        $abonado=0;
        $tEfectivo=0;
        $tCredito=0;
        $deuda=0;
        foreach ($facturas as $f) {
            $cantidades=json_decode($f->Factured_quantity);
            foreach ($cantidades as $cantidad) {
                $cantidadProductos+=$cantidad;
            }
            $tFactura+=$f->total;
            $abonado+=$f->monto_abonado;
            if($f->medio_de_pago==true){
                $tEfectivo+=$f->total;
            }
            if($f->medio_de_pago==false){
                $tCredito+=$f->total;
                $deuda+=$f->total-$f->monto_abonado;
            }
        }
        
        $pagosAnteriores=0;
        $cheques = Cheque::where('fecha',$request->day)->where('pago_presupuesto',true)->where('sucursal', 'ilike', "%$request->sucursal%" )->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                $pagosAnteriores+=$check->monto;
            }
        }
        
        $fecha=$request->day;
        $presupuesto=0;
        $carton=0;
        if(count($calculo)){
            $presupuesto=$calculo[0]->budget;
            $carton=$calculo[0]->carton;
            $vueltoEntregado=$calculo[0]->vuelto;
        }
        
        $tComprado=0;
        $vuelto=$presupuesto+$carton-$tEfectivo-$abonado-$pagosAnteriores;
        $cheques = Cheque::where('fecha',$request->day)->where('sucursal', 'ilike', "%$request->sucursal%" )->get();
        $pdf = PDF::loadView('download-pdf_resumen_del_dia', ['fecha'=>$request->day,
        'cantidadProductos'=>$cantidadProductos,
        'presupuesto'=>$presupuesto,
        'facturas'=>$facturas,
        'carton'=>$carton,
        'tComprado'=>$tComprado,
        'tFactura'=>$tFactura,
        'tEfectivo'=>$tEfectivo,
        'tCredito'=>$tCredito,
        'abonado'=>$abonado,
        'pagosAnteriores'=>$pagosAnteriores,
        'vuelto'=>$vuelto,
        'vueltoEntregado'=>$vueltoEntregado,
        'cheques'=>$cheques,
        'deuda'=>$deuda]);
        return $pdf->download("resumen-".$request->day.".pdf");
        
    }
}
