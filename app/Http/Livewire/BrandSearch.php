<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Brand;

class BrandSearch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    /* Wire models */
    public $descripcion;
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
        
        $brands = Brand::when($this->descripcion, function ($query) {
            $query->where('descripcion', 'ILIKE', "%$this->descripcion%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->orderBy('descripcion', 'ASC')/*->when($this->orgsParent, function ($query) {
            $query->where('sucursal', 'like', "%$this->orgsParent%" );
        }, function ($query) {
            $query->where(function ($query) {
            });
        })*/->paginate(20); // Obtener todos los brinksend de la tabla

        return view('livewire.brandsearch', compact('brands'));
    }

}
