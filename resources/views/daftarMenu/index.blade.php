@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Menu Utama</h3>
    </div>
    <div class="card-body" style="padding-top: 10px;">
        <div class="row text-center">
            @foreach($menus as $menu)
                <div class="col-md-4">
                    <a href="{{ url('daftarMenu/' . $menu->menus_id . '/subMenu') }}" class="custom-button d-block p-3 mb-2">
                        <i class="nav-icon fas fa-folder fa-2x"></i>
                        <h5>{{ $menu->name }}</h5>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .custom-button {
        background-color: lightblue;
        border: 2px solid black;
        border-radius: 8px; 
        color: black; 
        text-decoration: none; 
        transition: background-color 0.3s, transform 0.3s; 
    }

    .custom-button:hover {
        background-color: blue; 
        transform: scale(0.95);
        color: white; /* Warna ikon saat hover */
    }
</style>

@endsection
