@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sub Menu dari "{{ $parentMenu->name }}"</h3>
    </div>
    <div class="card-body" style="padding-top: 10px;">
        <div class="mb-4">
            <a href="{{ url('daftarMenu') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="row text-center">
            @foreach($subMenus as $subMenu)
                @if($subMenu->page) {{-- Cek apakah ada relasi page --}}
                    <div class="col-md-4">
                        <a href="{{ url('daftarMenu/content/' . $subMenu->page->pages_id) }}"
                            class="custom-button d-block p-3 mb-2">
                            <i class="fas fa-sitemap fa-2x"></i>
                            <h5>{{ $subMenu->name }}</h5>
                        </a>
                    </div>
                @endif
            @endforeach

        </div>
    </div>
</div>

<style>
    .custom-button {
        background-color: #c8e572;
        border: 2px solid black;
        border-radius: 8px;
        color: black;
        text-decoration: none;
        transition: background-color 0.3s, transform 0.3s;
    }

    .custom-button:hover {
        background-color: #6e8039;
        transform: scale(0.95);
        color: white;
        /* Warna ikon saat hover */
    }
</style>

@endsection