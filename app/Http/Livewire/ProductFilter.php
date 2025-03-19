<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class ProductFilter extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    /* Wire models */
    public $nombre;
    public $codigo_barra;
    public $id_category;
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
    public function setCategory($id)
    {
        $this->id_category = $id;
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
            $query->where('name', 'like', "%$this->nombre%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->codigo_barra, function ($query) {
            $query->where('codigo_barra', 'like', "%$this->codigo_barra%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->id_category, function ($query) {
            $query->where('id_category', '=', $this->id_category );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })/*->when($this->id_brand, function ($query) {
            $query->where('id_brand', '=', $this->id_brand );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })*/->paginate(20); // Obtener todos los brinksend de la tabla

        //$brands = Brand::all();
        $categories = Category::all();
        $parameters = collect(['nombre' => $this->nombre == '' ? null : $this->nombre, 'codigo_barra' => $this->codigo_barra == '' ? null : $this->codigo_barra, 'id_category' => $this->id_category == '' ? null : $this->id_category]);

        return view('livewire.productfilter', compact('products','categories','parameters'));
    }

}
