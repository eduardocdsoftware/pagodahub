<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\presupuestoBank;

class PresupuestoBankSearch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $fecha_inicio;
    public $fecha_fin;
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
        if(count($org)<2){
            $this->orgsParent=$org[0]->Name;
        }else{
            $this->orgsParent="";
        }

        
        $brinksend = presupuestoBank::when($this->fecha_inicio, function ($query) {
            $query->where('fecha', '>=', $this->fecha_inicio);
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->fecha_fin, function ($query) {
            $query->where('fecha', '<=', $this->fecha_fin);
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->when($this->orgsParent, function ($query) {
            $query->where('sucursal', $this->orgsParent);
        }, function ($query) {
            $query->where(function ($query) {
            });
        })->orderBy('fecha', 'desc')->paginate(20); // Obtener todos los brinksend de la tabla

        return view('livewire.presupuestoBanksearch', compact('brinksend'));

    }

}
