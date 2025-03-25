<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Department;

class ProductFilter extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    /* Wire models */
    public $nombre;
    public $codigo_barra;
    public $id_department;
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public $orgsParent; // Propiedad para almacenar el valor de 'tipo' desde el primer return

    public function mount($orgs)
    {
        $org=session()->put('misDatos',$orgs);
        $this->orgsParent = $orgs; // Almacena el valor de 'tipo' desde el primer return
    }
    public function setDepartment($id)
    {
        $this->id_department = $id;
        $this->render();
    }
    public function render()
    {
        $org=session()->get('misDatos');
        if(isset($org->records)){
            $org=$org->records;
        }
        /*if(count($org)<2){
            $this->orgsParent=$org[0]->Name;
        }else{
            $this->orgsParent="";
        }*/
        $this->orgsParent="";
        
        $products = Product::when($this->nombre, function ($query) {
            $query->where('name', 'ILIKE', "%$this->nombre%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->codigo_barra, function ($query) {
            $query->where('codigo_barra', 'ILIKE', "%$this->codigo_barra%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->id_department, function ($query) {
            $query->where('id_department', '=', $this->id_department );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->orderBy('name', 'ASC')/*->when($this->id_brand, function ($query) {
            $query->where('id_brand', '=', $this->id_brand );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })*/->paginate(20); // Obtener todos los brinksend de la tabla

        //$brands = Brand::all();
        $departments = Department::all();
        $parameters = collect(['nombre' => $this->nombre == '' ? null : $this->nombre, 'codigo_barra' => $this->codigo_barra == '' ? null : $this->codigo_barra, 'id_department' => $this->id_department == '' ? null : $this->id_department]);

        return view('livewire.productfilter', compact('products','departments','parameters'));
    }

}
