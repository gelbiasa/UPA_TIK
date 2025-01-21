@extends('layouts.template')

@section('content')
<div class="card shadow-lg border-0">
    <div class="card-header bg-primary text-white text-center py-4">
        <h3 class="card-title mb-0">Sub Menu: {{ $page->title }}</h3>
    </div>
    <div class="card-body p-4">
        <div class="d-flex justify-content-start mb-4">
            <a href="{{ url('daftarMenu') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="text-center mb-4">
            @if(!empty($page->thumbnail))
                <img src="{{ asset('storage/' . $page->thumbnail) }}" alt="Thumbnail" class="img-fluid rounded shadow-sm"
                    style="max-height: 300px;">
            @else
                <div class="alert alert-warning" role="alert">
                    Thumbnail tidak tersedia.
                </div>
            @endif
        </div>

        <div class="content-container bg-light p-4 rounded shadow-sm">
            <div class="content-text mb-4">
                <div class="alert alert-warning">
                    {!! nl2br(e($page->content)) !!}
                </div>
            </div>

            <div class="text-center">
                @if(!empty($page->content_image))
                    <img src="{{ asset('storage/' . $page->content_image) }}" alt="Content Image"
                        class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                @else
                    <div class="alert alert-warning" role="alert">
                        Gambar Content tidak tersedia.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="jarak bg-light p-4 rounded shadow-sm"></div>

<style>
    .card {
        margin: auto;
        border-radius: 15px;
        overflow: hidden;
        max-width: 900px;
    }

    .jarak {
        margin-top: 20px;
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
        border-radius: 10px;
    }

    .content-container {
        font-size: 1rem;
        line-height: 1.8;
        color: #333;
    }

    .alert {
        font-size: 1rem;
        padding: 15px;
        text-align: center;
        margin: 10px auto;
    }

    .btn {
        font-size: 1rem;
        padding: 10px 20px;
        border-radius: 8px;
    }

    @media (max-width: 576px) {
        .card {
            margin: 10px;
        }

        .card-header {
            font-size: 1.5rem;
        }

        .btn {
            padding: 8px 16px;
        }
    }
</style>
@endsection