@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                Lista de vales consumidos
            </div>
            <div class="card-body">
                @livewire('valepagodasearch', ['orgs' => $orgs])
            </div>
        </div>
    </div>
@endsection
