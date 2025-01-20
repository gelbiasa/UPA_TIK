@extends('layouts.template')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white text-center">
        <h3 class="card-title">Sub Menu: {{ $page->title }}</h3>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <a href="{{ url('daftarMenu') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="text-center mb-4">
            @if(!empty($page->thumbnail))
                <img src="{{ asset('storage/' . $page->thumbnail) }}" alt="Thumbnail" class="img-fluid mx-auto d-block rounded shadow-sm" style="max-height: 300px;">
            @else
                <div class="alert alert-warning" role="alert">
                    Thumbnail tidak tersedia.
                </div>
            @endif
        </div>
        <div class="content-container p-3 bg-light rounded shadow-sm">
            {!! nl2br(e($page->content)) !!}
        </div>
    </div>
</div>

<style>
    .card {
        margin: 20px auto;
        border-radius: 12px;
        overflow: hidden;
        max-width: 800px;
    }

    .card-header {
        font-size: 1.5rem;
        font-weight: bold;
        border-bottom: none;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
        border: 2px solid #ddd;
        border-radius: 8px;
    }

    .content-container {
        font-size: 1rem;
        line-height: 1.6;
        color: #333;
    }

    .alert {
        font-size: 1rem;
        padding: 15px;
        text-align: center;
        margin: 10px auto;
    }

    @media (max-width: 576px) {
        .card {
            margin: 10px;
        }

        .card-header {
            font-size: 1.25rem;
        }
    }
</style>
@endsection
