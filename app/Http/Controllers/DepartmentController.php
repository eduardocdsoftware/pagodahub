<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
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

                    // Combinar las respuestas en un único array
                    $response->records = array_merge($response1->records, $response2->records);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs=$response->records;
                        
                    }
            }
        }
        session()->put('misDatos', $orgs);
        //return view('tdc', ['orgs' => $orgs,  'permisos' => $user]);
        return view('department.index', ['orgs' => $orgs,  'permisos' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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

                    // Combinar las respuestas en un único array
                    $response->records = array_merge($response1->records, $response2->records);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs=$response->records;
                        
                    }
            }
        }
        session()->put('misDatos', $orgs);
        //return view('tdc', ['orgs' => $orgs,  'permisos' => $user]);
        return view('department.create', ['orgs' => $orgs,  'permisos' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Department = new Department;
        $Department->descripcion=$request->descripcion; 
        //$Department->sucursal=$request->AD_Org_ID;        
        $Department->save();
        return redirect()->back()->with('mensaje', 'Departmento ha sido creado exitosamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Department = Department::find($id);

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

                    // Combinar las respuestas en un único array
                    $response->records = array_merge($response1->records, $response2->records);
                    //tabla rv_gh_org  campo AD_Org_ID
                    // Verifica si la consulta fue exitosa
                    if ($response && isset($response->records[0])) {
                        // Agrega el registro de AD_Org al array de resultados
                        $orgs=$response->records;
                        
                    }
            }
        }
        session()->put('misDatos', $orgs);

        return view('department.edit', ['orgs' => $orgs,  'permisos' => $user,  'department' => $Department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $Department = Department::find($request->id);
        $Department->descripcion = $request->descripcion;
        //$Department->sucursal=$request->AD_Org_ID; 
        $Department->save();
        return back()->with('mensaje', 'Departmento ha sido modificado exitosamente');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Department = Department::find($id);
        $Department->delete();
        return back()->with('mensaje', 'Departmento ha sido eliminado exitosamente');

    }

}
