<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Department;

class ProductSearch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    /* Wire models */
    public $nombre;
    public $codigo_barra;
    public $id_department;
    //public $id_brand;
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
        
        $products = Product::where('product_search', '=', 'Y')->when($this->nombre, function ($query) {
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
        })/*->when($this->id_brand, function ($query) {
            $query->where('id_brand', '=', $this->id_brand );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })*/->orderBy('id', 'DESC')->paginate(20); // Obtener todos los brinksend de la tabla

        $brands = Brand::all();
        $departments = Department::all();
        
        return view('livewire.productsearch', compact('products','departments','brands'));
    }

}
