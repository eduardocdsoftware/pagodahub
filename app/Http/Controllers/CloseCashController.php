<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\closecash;
use App\Models\User;
use FontLib\Table\Type\name;
use Illuminate\Database\Console\DumpCommand;
use Laravel\Ui\Presets\React;
use PDF;

use App\Models\marketshopping;
use App\Models\Cheque;
use App\Models\Facture;

class CloseCashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $APIController = new APIController();
        $response = $APIController->getModel('AD_Org', '', 'issummary eq true');
        $orgs =  $response;
        
        session()->put('misDatos', $orgs);
    }

    public function import(Request $request)
    {
        $APIController = new APIController();
        $misDatos = session()->get('misDatos');
        $orgs = $misDatos;
        $response = $APIController->getModel(
            'RV_GH_CloseCash_Sum',
            '',
            'datetrx eq ' . $request->DateTrx . ' and parent_id eq ' . $request->AD_Org_ID
        );
        $docstatus = 'CO';
        $closecashlist = $APIController->getModel(
            'RV_GH_CloseCash',
            '',
            "datetrx eq '" . $request->DateTrx . "' and parent_id eq " . $request->AD_Org_ID . " and  docstatus eq '" . $docstatus . "'",
            'ba_name asc'
        );

        $list = closecash::where('DateTrx', $request->DateTrx)->where('AD_Org_ID', $request->AD_Org_ID)->get();
        
        $dia = $request->DateTrx;
        $organizacion = $request->AD_Org_ID;
        session()->put('dia', $dia);
        session()->put('organizacion', $organizacion);


        //////
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        $user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        //dd($closecashlist->records); //el chiste se hace con ba_value
        //NetTotal=montoContado
        $panaderia = (object) [
            'ba_name'=>'Total panaderia',
            'u_name'=>'------------',
            'BeginningBalance' => 0,
            'SubTotal' => 0,
            'NetTotal' => 0,
            'XAmt' => 0,
            'DifferenceAmt' => 0
        ];
        
        $caja = (object) [
            'ba_name'=>'Total cajas',
            'u_name'=>'------------',
            'BeginningBalance' => 0,
            'SubTotal' => 0,
            'NetTotal' => 0,
            'XAmt' => 0,
            'DifferenceAmt' => 0
        ];
        
        $pagaTodo = (object) [
            'ba_name'=>'Total pagatodo',
            'u_name'=>'------------',
            'BeginningBalance' => 0,
            'SubTotal' => 0,
            'NetTotal' => 0,
            'XAmt' => 0,
            'DifferenceAmt' => 0
        ];
        $lastIndex=null;
        foreach ($closecashlist->records as $index =>$cajas) {
            switch ($cajas->ba_value) {
                case 'panaderia':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                    
                case '1000004':
                    $pagaTodo->BeginningBalance += $cajas->BeginningBalance;
                    $pagaTodo->SubTotal += $cajas->SubTotal;
                    $pagaTodo->NetTotal += $cajas->NetTotal;
                    $pagaTodo->XAmt += $cajas->XAmt;
                    $pagaTodo->DifferenceAmt += $cajas->DifferenceAmt;
                    $lastIndex=$index;
                    break;
                case 'Caja 5_Panaderia':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                case 'Caja 10':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                case 'Principal Panaderia Susy':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                case 'Caja Panaderia':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                case 'Dulceria Susy Fortuna P':
                    $panaderia->BeginningBalance += $cajas->BeginningBalance;
                    $panaderia->SubTotal += $cajas->SubTotal;
                    $panaderia->NetTotal += $cajas->NetTotal;
                    $panaderia->XAmt += $cajas->XAmt;
                    $panaderia->DifferenceAmt += $cajas->DifferenceAmt;
                    break;
                default:
                    if ($cajas->ba_value !== '1000004') {
                        $caja->BeginningBalance += $cajas->BeginningBalance;
                        $caja->SubTotal += $cajas->SubTotal;
                        $caja->NetTotal += $cajas->NetTotal;
                        $caja->XAmt += $cajas->XAmt;
                        $caja->DifferenceAmt += $cajas->DifferenceAmt;
                    }
                    break;
            }
        }
        $tDia = (object) [
            'ba_name'=>'Total del día',
            'u_name'=>'------------',
            'BeginningBalance' => $panaderia->BeginningBalance+$caja->BeginningBalance+$pagaTodo->BeginningBalance,
            'SubTotal' => $panaderia->SubTotal+$caja->SubTotal+$pagaTodo->SubTotal,
            'NetTotal' => $panaderia->NetTotal+$caja->NetTotal+$pagaTodo->NetTotal,
            'XAmt' => $panaderia->XAmt+$caja->XAmt+$pagaTodo->XAmt,
            'DifferenceAmt' => $panaderia->DifferenceAmt+$caja->DifferenceAmt+$pagaTodo->DifferenceAmt
        ];
        $total=[$caja,$pagaTodo,$panaderia,$tDia];
        $closecashlist->records[] = $panaderia;
        $closecashlist->records[] = $tDia;
        //dd($closecashlist->records,$posicion);
        if($lastIndex===null){
           $lastIndex = count($closecashlist->records)-2;
        }

        $closecashlist->records = array_merge(
            array_slice($closecashlist->records, 0, $lastIndex + 1),
            [$pagaTodo],
            array_slice($closecashlist->records, $lastIndex + 1)
        );
        $closecashlist->records = array_merge(
            array_slice($closecashlist->records, 0, $lastIndex),
            [$caja],
            array_slice($closecashlist->records, $lastIndex)
        );
        $day = $request->DateTrx;
        
        $presupuesto=0;
        $comprasdeldia = marketshopping::where('shoppingday', $day)->orderBy('created_at', 'asc')->get();
        
        if($comprasdeldia->count() > 0) {
            $presupuesto=$comprasdeldia[0]->budget;
        }
        if ($comprasdeldia->count() > 1) {
            $presupuesto = $comprasdeldia[0]->budget;
            $productos = json_decode($comprasdeldia[0]->product);
            $quantity = json_decode($comprasdeldia[0]->quantity);
        
            for ($i = 1; $i < $comprasdeldia->count(); $i++) {
                $Fproductos = json_decode($comprasdeldia[$i]->product);
                $Fquantity = json_decode($comprasdeldia[$i]->quantity);
        
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
        
        $facturas = Facture::where('fecha', $day)->get();
        $carton =0;
        if($facturas->count() > 0) {
            foreach ($facturas as $factura) {
                if($factura->medio_de_pago){
                    $presupuesto-=$factura->total;
                }
                if(!$factura->medio_de_pago){
                    $presupuesto-=$factura->monto_abonado;
                }
                
                if($factura->carton>0){
                    $carton =$factura->carton;
                }
                
            }
        }
        foreach ($comprasdeldia as $posicion => $compra) {
            foreach ($facturas as $posicion_diferente => $factura) {
                if ($compra->id_compra === $factura->id_compra) {
                    $comprasdeldia[$posicion]->factura = $factura;
                }
            }
        }
        $cheques = Cheque::where('fecha',$day)->where('pago_presupuesto',true)->get();
        if ($cheques->count()>0) {
            foreach ($cheques as $check) {
                    $presupuesto-=$check->monto;
                }
        }



        foreach ($user->records  as $usuario) {
            //dump($user);
            if(isset($usuario->PAGODAHUB_closecash)){
                foreach ($usuario->PAGODAHUB_closecash as $acceso) {
                    if ($acceso->Name == 'closecash') {
                        return view('closecash', ['orgs' => $orgs, 
                                                'closecashsumlist' => $response, 
                                                'request' => $request, 
                                                'closecashlist' => $closecashlist, 
                                                'list' => $list, 
                                                'permisos' => $user,
                                                'totales'=> $total,
                                                'vuelto'=> $presupuesto
                                                ]);
                        break;
                    }
                }            
                return redirect()->route('home');
            }
        }
        return redirect()->route('home');
        ////////////

    }

    public function show(Request $request)
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
        foreach ($user->records  as $usuario) {
            if(isset($usuario->PAGODAHUB_closecash)){
                foreach ($usuario->PAGODAHUB_closecash as $acceso) {
                    if ($acceso->Name == 'closecash') {
                        
                        
                        session()->put('misDatos', $orgs);
                        return view('closecash', ['orgs' => $orgs, 'request' => $request, 'permisos' => $user]);
                        break;
                    }
                }
                return redirect()->route('home');
            }
        }
        return redirect()->route('home');
        ////////////
    }

    public function store(Request $request)
    {
        $todo = new closecash;
        //dump($todo);
        $todo->x_oneamtSistema                =    $request->x_oneamtSistema;
        $todo->x_fiveamtSistema              =    $request->x_fiveamtSistema;
        $todo->x_tenamtSistema                =    $request->x_tenamtSistema;
        $todo->x_twentyamtSistema             =    $request->x_twentyamtSistema;
        $todo->x_fiftyamtSistema             =    $request->x_fiftyamtSistema;
        $todo->x_hundredamtSistema            =    $request->x_hundredamtSistema;
        $todo->yappySistema                    =    $request->yappySistema;
        $todo->otrosSistema                  =    $request->otrosSistema;
        $todo->valespagodaSistema            =    $request->valespagodaSistema;
        $todo->CheckAmtSistema                =    $request->CheckAmtSistema;
        $todo->LotoAmtSistema                =    $request->LotoAmtSistema;
        $todo->CardAmtSistema                =    $request->CardAmtSistema;
        $todo->CashAmtSistema                =    $request->CashAmtSistema;
        $todo->CoinRollSistema                =    $request->CoinRollSistema;
        $todo->InvoiceAmtSistema                =    $request->InvoiceAmtSistema;
        $todo->VoucherAmtSistema                =    $request->VoucherAmtSistema;
        $todo->GrantAmtSistema                =    $request->GrantAmtSistema;
        $todo->x_oneamtFiscalizadora         =    $request->x_oneamtFiscalizadora;
        $todo->x_fiveamtFiscalizadora        =    $request->x_fiveamtFiscalizadora;
        $todo->x_tenamtFiscalizadora            =    $request->x_tenamtFiscalizadora;
        $todo->x_twentyamtFiscalizadora        =    $request->x_twentyamtFiscalizadora;
        $todo->x_fiftyamtFiscalizadora        =    $request->x_fiftyamtFiscalizadora;
        $todo->x_hundredamtFiscalizadora        =    $request->x_hundredamtFiscalizadora;
        $todo->yappyFiscalizadora            =    $request->yappyFiscalizadora;
        $todo->otrosFiscalizadora            =    $request->otrosFiscalizadora;
        $todo->otrosprimeroFiscalizadora            =    $request->otrosprimeroFiscalizadora;
        $todo->valespagodaFiscalizadora        =    $request->valespagodaFiscalizadora;
        $todo->CheckAmtFiscalizadora            =    $request->CheckAmtFiscalizadora;
        $todo->LotoAmtFiscalizadora            =    $request->LotoAmtFiscalizadora;
        $todo->CardValeFiscalizadora            =    $request->CardValeFiscalizadora;
        $todo->CardVisaFiscalizadora            =    $request->CardVisaFiscalizadora;
        $todo->CardMasterFiscalizadora        =    $request->CardMasterFiscalizadora;
        $todo->CardAEFiscalizadora            =    $request->CardAEFiscalizadora;
        $todo->CashAmtFiscalizadora            =    $request->CashAmtFiscalizadora;
        $todo->CoinRollFiscalizadora            =    $request->CoinRollFiscalizadora;
        $todo->InvoiceAmtFiscalizadora        =    $request->InvoiceAmtFiscalizadora;
        $todo->VoucherAmtFiscalizadora        =    $request->VoucherAmtFiscalizadora;
        $todo->GrantAmtFiscalizadora            =    $request->GrantAmtFiscalizadora;
        $todo->totalPanaderiaFiscalizadora   =    $request->totalPanaderiaFiscalizadora;
        $todo->totalPagatodoFiscalizadora    =    $request->totalPagatodoFiscalizadora;
        $todo->totalsuperFiscalizadora        =    $request->totalsuperFiscalizadora;
        $todo->dineroTaxiFiscalizadora        =    $request->dineroTaxiFiscalizadora;
        $todo->vueltoMercadoFiscalizadora    =    $request->vueltoMercadoFiscalizadora;
        $todo->comentariosFiscalizadora        =    $request->comentariosFiscalizadora;
        $todo->x_oneamtGerente                 =    $request->x_oneamtGerente;
        $todo->x_fiveamtGerente                 =    $request->x_fiveamtGerente;
        $todo->x_tenamtGerente                =    $request->x_tenamtGerente;
        $todo->x_twentyamtGerente             =    $request->x_twentyamtGerente;
        $todo->x_fiftyamtGerente             =    $request->x_fiftyamtGerente;
        $todo->x_hundredamtGerente            =    $request->x_hundredamtGerente;
        $todo->yappyGerente                    =    $request->yappyGerente;
        $todo->otrosGerente                  =    $request->otrosGerente;
        $todo->otrosprimeroGerente                  =    $request->otrosprimeroGerente;
        $todo->valespagodaGerente             =    $request->valespagodaGerente;
        $todo->CheckAmtGerente               =    $request->CheckAmtGerente;
        $todo->LotoAmtGerente                 =    $request->LotoAmtGerente;
        $todo->CardValeGerente                 =    $request->CardValeGerente;
        $todo->CardVisaGerente                =    $request->CardVisaGerente;
        $todo->CardMasterGerente                =    $request->CardMasterGerente;
        $todo->CardAEGerente                 =    $request->CardAEGerente;
        $todo->CashAmtGerente                =    $request->CashAmtGerente;
        $todo->CoinRollGerente                =    $request->CoinRollGerente;
        $todo->InvoiceAmtGerente                =    $request->InvoiceAmtGerente;
        $todo->VoucherAmtGerente                =    $request->VoucherAmtGerente;
        $todo->GrantAmtGerente                =    $request->GrantAmtGerente;
        $todo->totalPanaderiaGerente            =    $request->totalPanaderiaGerente;
        $todo->totalPagatodoGerente             =    $request->totalPagatodoGerente;
        $todo->totalsuperGerente                =    $request->totalsuperGerente;
        $todo->dineroTaxiGerente                =    $request->dineroTaxiGerente;
        $todo->vueltoMercadoGerente            =    $request->vueltoMercadoGerente;
        $todo->comentariosGerente            =    $request->comentariosGerente;
        $todo->DateTrx                        =    $request->DateTrx;
        $todo->AD_Org_ID                        =    $request->AD_Org_ID;
        $todo->Fileclosecash                    =    $request->Fileclosecash;
        $todo->CardClaveFiscalizadora        =    $request->CardClaveFiscalizadora;
        $todo->CardClaveGerente                 =    $request->CardClaveGerente;
        $todo->valeAmt                         =    $request->valeAmt;
        $todo->valeAmtFiscalizadora             = $request->valeAmtFiscalizadora;
        $todo->valeAmtGerente                   = $request->valeAmtGerente;
        $todo->CardBACFiscalizadora             = $request->CardBACFiscalizadora;
        $todo->CardBACGerente                   = $request->CardBACGerente;
        $todo->InvoiceAmtPropiasFiscalizadora   = $request->InvoiceAmtPropiasFiscalizadora;
        $todo->InvoiceAmtPropiasGerente         = $request->InvoiceAmtPropiasGerente;
        $todo->check_fis                        = $request->check_fis;
        $todo->check_ger                        = $request->check_ger;
        $todo->check_x_oneamtGerente            =  $request->check_x_oneamtGerente;
        $todo->check_x_fiveamtGerente           =  $request->check_x_fiveamtGerente;
        $todo->check_x_tenamtGerente            =  $request->check_x_tenamtGerente;
        $todo->check_x_twentyamtGerente         =  $request->check_x_twentyamtGerente;
        $todo->check_x_fiftyamtGerente          =  $request->check_x_fiftyamtGerente;
        $todo->check_x_hundredamtGerente        =  $request->check_x_hundredamtGerente;
        $todo->check_x_yappyGerente             =  $request->check_yappyGerente;
        $todo->check_x_otrosGerente             =  $request->check_otrosGerente;
        $todo->check_x_otrosprimeroGerente      =  $request->check_otrosprimeroGerente;
        $todo->check_x_valespagodaGerente       =  $request->check_valespagodaGerente;
        $todo->check_x_CheckAmtGerente          =  $request->check_CheckAmtGerente;
        $todo->check_x_LotoAmtGerente           =  $request->check_LotoAmtGerente;
        $todo->check_x_valeAmtGerente           =  $request->check_valeAmtGerente;
        $todo->check_x_CardClaveGerente         =  $request->check_CardClaveGerente;
        $todo->check_x_CardValeGerente          =  $request->check_CardValeGerente;
        $todo->check_x_CardVisaGerente          =  $request->check_CardVisaGerente;
        $todo->check_x_CardMasterGerente        =  $request->check_CardMasterGerente;
        $todo->check_x_CardAEGerente            =  $request->check_CardAEGerente;
        $todo->check_x_CardBACGerente           =  $request->check_CardBACGerente;
        $todo->check_x_CashAmtGerente           =  $request->check_CashAmtGerente;
        $todo->check_x_CoinRollGerente          =  $request->check_CoinRollGerente;
        $todo->check_x_InvoiceAmtGerente        =  $request->check_InvoiceAmtGerente;
        $todo->check_x_InvoiceAmtPropiasGerente =  $request->check_InvoiceAmtPropiasGerente;
        $todo->check_x_VoucherAmtGerente        =  $request->check_VoucherAmtGerente;
        $todo->check_x_GrantAmtGerente          =  $request->check_GrantAmtGerente;
        $todo->check_x_totalPanaderiaGerente    =  $request->check_totalPanaderiaGerente;
        $todo->check_x_totalPagatodoGerente     =  $request->check_totalPagatodoGerente;
        $todo->check_x_totalsuperGerente        =  $request->check_totalsuperGerente;
        $todo->check_x_dineroTaxiGerente        =  $request->check_dineroTaxiGerente;
        $todo->check_x_vueltoMercadoGerente     =  $request->check_vueltoMercadoGerente;
        $todo->efectivo_sistema                 =  $request->efectivo_sistema;
        $todo->otros_sistema                    =  $request->otros_sistema;
        $todo->sub_total_super_sistema          =  $request->sub_total_super_sistema;
        $todo->monto_contado_sistema            =  $request->monto_contado_sistema;
        $todo->monto_x_sistema                  =  $request->monto_x_sistema;
        $todo->SencilloSupervisoraFiscalizadora =  $request->SencilloSupervisoraFiscalizadora;
        $todo->SencilloSupervisoraGerente       =  $request->SencilloSupervisoraGerente;
        $todo->check_SencilloSupervisoraGerente =  $request->check_SencilloSupervisoraGerente;
        $todo->vueltoEntregadoFiscalizadora =  $request->vueltoEntregadoFiscalizadora;
        $todo->vueltoEntregadoGerente =  $request->vueltoEntregadoGerente;
        //dump($request->SencilloSupervisoraFiscalizadora,$request->SencilloSupervisoraGerente);
        $filename = $request->Fileclosecash;    //$request->file('Fileclosecash');//->store('public/Fileclosecash');

        $misDatos = session()->get('misDatos');
        $orgs = $misDatos;

        $APIController = new APIController();
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        $user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        foreach ($user->records  as $usuario) {
            if(isset($usuario->PAGODAHUB_closecash)){
                foreach ($usuario->PAGODAHUB_closecash as $acceso) {
                    if ($acceso->Name == 'closecash') {

                        if ($filename == null) {
                            //dd($todo);
                            $todo->save();
                            return view('closecash', ['orgs' => $orgs, 'permisos' => $user]);
                        } else {
                            //dd($todo);
                            /*  $filename = $request->file('Fileclosecash')->store('public/Fileclosecash'); */
                            $todo->Fileclosecash = $filename;
                            $todo->save();
                            return view('closecash', ['orgs' => $orgs, 'permisos' => $user]);
                        }
                    }
                }
                return redirect()->route('home');
            }
        }
        return redirect()->route('home');
    }

    public function edit(Request $request)
    {
        $todo = new closecash;
        $todo->id = $request->id;
        $todo = closecash::find($todo->id);
        $todo->x_oneamtSistema                =    $request->x_oneamtSistema;
        $todo->x_fiveamtSistema              =    $request->x_fiveamtSistema;
        $todo->x_tenamtSistema                =    $request->x_tenamtSistema;
        $todo->x_twentyamtSistema             =    $request->x_twentyamtSistema;
        $todo->x_fiftyamtSistema             =    $request->x_fiftyamtSistema;
        $todo->x_hundredamtSistema            =    $request->x_hundredamtSistema;
        $todo->yappySistema                    =    $request->yappySistema;
        $todo->otrosSistema                  =    $request->otrosSistema;
        $todo->valespagodaSistema            =    $request->valespagodaSistema;
        $todo->CheckAmtSistema                =    $request->CheckAmtSistema;
        $todo->LotoAmtSistema                =    $request->LotoAmtSistema;
        $todo->CardAmtSistema                =    $request->CardAmtSistema;
        $todo->CashAmtSistema                =    $request->CashAmtSistema;
        $todo->CoinRollSistema                =    $request->CoinRollSistema;
        $todo->InvoiceAmtSistema                =    $request->InvoiceAmtSistema;
        $todo->VoucherAmtSistema                =    $request->VoucherAmtSistema;
        $todo->GrantAmtSistema                =    $request->GrantAmtSistema;
        $todo->x_oneamtFiscalizadora         =    $request->x_oneamtFiscalizadora;
        $todo->x_fiveamtFiscalizadora        =    $request->x_fiveamtFiscalizadora;
        $todo->x_tenamtFiscalizadora            =    $request->x_tenamtFiscalizadora;
        $todo->x_twentyamtFiscalizadora        =    $request->x_twentyamtFiscalizadora;
        $todo->x_fiftyamtFiscalizadora        =    $request->x_fiftyamtFiscalizadora;
        $todo->x_hundredamtFiscalizadora        =    $request->x_hundredamtFiscalizadora;
        $todo->yappyFiscalizadora            =    $request->yappyFiscalizadora;
        $todo->otrosFiscalizadora            =    $request->otrosFiscalizadora;
        $todo->valespagodaFiscalizadora        =    $request->valespagodaFiscalizadora;
        $todo->CheckAmtFiscalizadora            =    $request->CheckAmtFiscalizadora;
        $todo->LotoAmtFiscalizadora            =    $request->LotoAmtFiscalizadora;
        $todo->CardValeFiscalizadora            =    $request->CardValeFiscalizadora;
        $todo->CardVisaFiscalizadora            =    $request->CardVisaFiscalizadora;
        $todo->CardMasterFiscalizadora        =    $request->CardMasterFiscalizadora;
        $todo->CardAEFiscalizadora            =    $request->CardAEFiscalizadora;
        $todo->CashAmtFiscalizadora            =    $request->CashAmtFiscalizadora;
        $todo->CoinRollFiscalizadora            =    $request->CoinRollFiscalizadora;
        $todo->InvoiceAmtFiscalizadora        =    $request->InvoiceAmtFiscalizadora;
        $todo->VoucherAmtFiscalizadora        =    $request->VoucherAmtFiscalizadora;
        $todo->GrantAmtFiscalizadora            =    $request->GrantAmtFiscalizadora;
        $todo->totalPanaderiaFiscalizadora   =    $request->totalPanaderiaFiscalizadora;
        $todo->totalPagatodoFiscalizadora    =    $request->totalPagatodoFiscalizadora;
        $todo->totalsuperFiscalizadora        =    $request->totalsuperFiscalizadora;
        $todo->dineroTaxiFiscalizadora        =    $request->dineroTaxiFiscalizadora;
        $todo->vueltoMercadoFiscalizadora    =    $request->vueltoMercadoFiscalizadora;
        $todo->comentariosFiscalizadora        =    $request->comentariosFiscalizadora;
        $todo->x_oneamtGerente                 =    $request->x_oneamtGerente;
        $todo->x_fiveamtGerente                 =    $request->x_fiveamtGerente;
        $todo->x_tenamtGerente                =    $request->x_tenamtGerente;
        $todo->x_twentyamtGerente             =    $request->x_twentyamtGerente;
        $todo->x_fiftyamtGerente             =    $request->x_fiftyamtGerente;
        $todo->x_hundredamtGerente            =    $request->x_hundredamtGerente;
        $todo->yappyGerente                    =    $request->yappyGerente;
        $todo->otrosGerente                  =    $request->otrosGerente;
        $todo->valespagodaGerente             =    $request->valespagodaGerente;
        $todo->CheckAmtGerente               =    $request->CheckAmtGerente;
        $todo->LotoAmtGerente                 =    $request->LotoAmtGerente;
        $todo->CardValeGerente                 =    $request->CardValeGerente;
        $todo->CardVisaGerente                =    $request->CardVisaGerente;
        $todo->CardMasterGerente                =    $request->CardMasterGerente;
        $todo->CardAEGerente                 =    $request->CardAEGerente;
        $todo->CashAmtGerente                =    $request->CashAmtGerente;
        $todo->CoinRollGerente                =    $request->CoinRollGerente;
        $todo->InvoiceAmtGerente                =    $request->InvoiceAmtGerente;
        $todo->VoucherAmtGerente                =    $request->VoucherAmtGerente;
        $todo->GrantAmtGerente                =    $request->GrantAmtGerente;
        $todo->totalPanaderiaGerente            =    $request->totalPanaderiaGerente;
        $todo->totalPagatodoGerente             =    $request->totalPagatodoGerente;
        $todo->totalsuperGerente                =    $request->totalsuperGerente;
        $todo->dineroTaxiGerente                =    $request->dineroTaxiGerente;
        $todo->vueltoMercadoGerente            =    $request->vueltoMercadoGerente;
        $todo->comentariosGerente            =    $request->comentariosGerente;
        $todo->DateTrx                        =    $request->DateTrx;
        $todo->AD_Org_ID                        =    $request->AD_Org_ID;
        $todo->Fileclosecash                    =    $request->Fileclosecash;
        $todo->CardClaveFiscalizadora        =    $request->CardClaveFiscalizadora;
        $todo->CardClaveGerente                 =    $request->CardClaveGerente;
        $todo->valeAmt                         =    $request->valeAmt;
        $todo->valeAmtFiscalizadora             =    $request->valeAmtFiscalizadora;
        $todo->valeAmtGerente                 =    $request->valeAmtGerente;
        $todo->CardBACFiscalizadora             = $request->CardBACFiscalizadora;
        $todo->CardBACGerente                   = $request->CardBACGerente;
        $todo->InvoiceAmtPropiasFiscalizadora   = $request->InvoiceAmtPropiasFiscalizadora;
        $todo->InvoiceAmtPropiasGerente         = $request->InvoiceAmtPropiasGerente;
        $todo->otrosprimeroFiscalizadora            =    $request->otrosprimeroFiscalizadora;
        $todo->otrosprimeroGerente                  =    $request->otrosprimeroGerente;
        $todo->check_fis                        = $request->check_fis;
        $todo->check_ger                        = $request->check_ger;
        $todo->check_x_oneamtGerente            =  $request->check_x_oneamtGerente;
        $todo->check_x_fiveamtGerente           =  $request->check_x_fiveamtGerente;
        $todo->check_x_tenamtGerente            =  $request->check_x_tenamtGerente;
        $todo->check_x_twentyamtGerente         =  $request->check_x_twentyamtGerente;
        $todo->check_x_fiftyamtGerente          =  $request->check_x_fiftyamtGerente;
        $todo->check_x_hundredamtGerente        =  $request->check_x_hundredamtGerente;
        $todo->check_x_yappyGerente             =  $request->check_yappyGerente;
        $todo->check_x_otrosGerente             =  $request->check_otrosGerente;
        $todo->check_x_otrosprimeroGerente      =  $request->check_otrosprimeroGerente;
        $todo->check_x_valespagodaGerente       =  $request->check_valespagodaGerente;
        $todo->check_x_CheckAmtGerente          =  $request->check_CheckAmtGerente;
        $todo->check_x_LotoAmtGerente           =  $request->check_LotoAmtGerente;
        $todo->check_x_valeAmtGerente           =  $request->check_valeAmtGerente;
        $todo->check_x_CardClaveGerente         =  $request->check_CardClaveGerente;
        $todo->check_x_CardValeGerente          =  $request->check_CardValeGerente;
        $todo->check_x_CardVisaGerente          =  $request->check_CardVisaGerente;
        $todo->check_x_CardMasterGerente        =  $request->check_CardMasterGerente;
        $todo->check_x_CardAEGerente            =  $request->check_CardAEGerente;
        $todo->check_x_CardBACGerente           =  $request->check_CardBACGerente;
        $todo->check_x_CashAmtGerente           =  $request->check_CashAmtGerente;
        $todo->check_x_CoinRollGerente          =  $request->check_CoinRollGerente;
        $todo->check_x_InvoiceAmtGerente        =  $request->check_InvoiceAmtGerente;
        $todo->check_x_InvoiceAmtPropiasGerente =  $request->check_InvoiceAmtPropiasGerente;
        $todo->check_x_VoucherAmtGerente        =  $request->check_VoucherAmtGerente;
        $todo->check_x_GrantAmtGerente          =  $request->check_GrantAmtGerente;
        $todo->check_x_totalPanaderiaGerente    =  $request->check_totalPanaderiaGerente;
        $todo->check_x_totalPagatodoGerente     =  $request->check_totalPagatodoGerente;
        $todo->check_x_totalsuperGerente        =  $request->check_totalsuperGerente;
        $todo->check_x_dineroTaxiGerente        =  $request->check_dineroTaxiGerente;
        $todo->check_x_vueltoMercadoGerente     =  $request->check_vueltoMercadoGerente;
        $todo->efectivo_sistema                 =  $request->efectivo_sistema;
        $todo->otros_sistema                    =  $request->otros_sistema;
        $todo->sub_total_super_sistema          =  $request->sub_total_super_sistema;
        $todo->monto_contado_sistema            =  $request->monto_contado_sistema;
        $todo->monto_x_sistema                  =  $request->monto_x_sistema;
        $todo->SencilloSupervisoraFiscalizadora =  $request->SencilloSupervisoraFiscalizadora;
        $todo->SencilloSupervisoraGerente       =  $request->SencilloSupervisoraGerente;
        $todo->check_SencilloSupervisoraGerente =  $request->check_SencilloSupervisoraGerente;
        $todo->vueltoEntregadoFiscalizadora =  $request->vueltoEntregadoFiscalizadora;
        $todo->vueltoEntregadoGerente =  $request->vueltoEntregadoGerente;
        //dd( $todo);
        $filename = $request->Fileclosecash;    //$request->file('Fileclosecash');//->store('public/Fileclosecash');
        $misDatos = session()->get('misDatos');
        $orgs = $misDatos;

        $APIController = new APIController();
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        $user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');
        foreach ($user->records  as $usuario) {
            if(isset($usuario->PAGODAHUB_closecash)){
                foreach ($usuario->PAGODAHUB_closecash as $acceso) {
                    if ($acceso->Name == 'closecash') {
                        if ($filename == null) {
                            //dd($todo);
                            $todo->save();
                            return view('closecash', ['orgs' => $orgs, 'permisos' => $user]);
                        } else {
                            //dd($todo);
                            /* $filename = $request->file('Fileclosecash')->store('public/Fileclosecash'); */
                            $todo->Fileclosecash = $filename;
                            $todo->save();
                            return view('closecash', ['orgs' => $orgs, 'permisos' => $user]);
                        }
                    }
                }
            }
        }
        return redirect()->route('home');
    }
    public function list(Request $request)
    {
        // Inicializa un array para almacenar los AD_Org_ID
        $orgs = []; // Inicializa un array para almacenar los registros de AD_Org
        $name_user = auth()->user()->name;
        $email_user = auth()->user()->email;
        $APIController = new APIController();
        $user = $APIController->getModel('AD_User', '', "Name eq '$name_user' and EMail eq '$email_user'", '', '', '', 'PAGODAHUB_closecash');

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
        
        $APIController = new APIController();
        $permisos = $APIController->getModel('PAGODAHUB_closecash', 'Name,AD_User_ID', '', '', '', '', '');
        foreach ($permisos->records as $record) {
            $nombreventana = $record->Name;
            $nombreusario = $record->AD_User_ID->identifier;
            $id_name = auth()->user()->name;
            if ($record->Name == "closecash" && $record->AD_User_ID->identifier == $id_name) {
                session()->put('misDatos', $orgs);
                return view('closecashlist', ['request' => $request,'orgs' => $orgs]);
                break;
            }
        }
        return redirect()->route('home');
    }
    public function destroy(Request $request)
    {
        $vale = closecash::find($request->valeid);
        $vale->delete();
        $list = closecash::all();
        return view('closecashlist', ['list' => $list, 'request' => $request]);
    }
    public function downloadPdf(Request $request)
    {
        /*   $pdf= PDF::loadHTML('<h1>Test</h1>'); */
        $APIController = new APIController();
        $dia = session()->get('dia');
        $organizacion = session()->get('organizacion');
        $response = $APIController->getModel(
            'RV_GH_CloseCash_Sum',
            '',
            'datetrx eq ' . $dia . ' and parent_id eq ' . $organizacion
        );
        //dd($response);
        $list = closecash::where('DateTrx', $dia)->where('AD_Org_ID', $organizacion)->get();
        //dd($list);
        $docstatus = 'CO';
        $closecashlist = $APIController->getModel(
            'RV_GH_CloseCash',
            '',
            "datetrx eq '" . $dia . "' and parent_id eq " . $organizacion . " and  docstatus eq '" . $docstatus . "'",
            'ba_name asc'
        );
        $pdf = PDF::loadView('download-pdf', ['closecashsumlist' => $response, 'list' => $list, 'closecashlist' => $closecashlist]);
        if ($organizacion == 1000008) {
            $nameorg = "Mañanitas";
        }
        if ($organizacion == 1000009) {
            $nameorg = "La Doña";
        }
        if ($organizacion == 1000012) {
            $nameorg = "Rio Chico";
        }
        return $pdf->stream("Cierre-" . $nameorg . "-" . $dia . ".pdf");
    }    
}
