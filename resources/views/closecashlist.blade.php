@extends('layouts.app')

@section('content')

<div class="container">
    
    <div class="card">
      <div class="card-header">
        Listado de Cierres de Cajas
      </div>
      <div class="card-body">
        @livewire('closecashsearch')
      </div>
    </div>
        
</div>
    

@endsection
