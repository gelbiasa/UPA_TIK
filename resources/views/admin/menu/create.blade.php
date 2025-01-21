@extends('layouts.template')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Menu Baru</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ url('/menu') }}" method="POST" enctype="multipart/form-data" id="menuForm">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Menu</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                        value="{{ old('slug') }}" required>
                    @error('slug')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="parent_id">Parent Menu</label>
                    <select name="parent_id" id="parent_id"
                        class="form-control @error('parent_id') is-invalid @enderror">
                        <option value="">-- Tidak ada --</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->menus_id }}" {{ old('parent_id') == $parent->menus_id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="order_number">Urutan</label>
                    <input type="number" name="order_number" id="order_number"
                        class="form-control @error('order_number') is-invalid @enderror"
                        value="{{ old('order_number') }}" required>
                    @error('order_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="thumbnail">Thumbnail</label>
                    <input type="file" name="thumbnail" id="thumbnail"
                        class="form-control @error('thumbnail') is-invalid @enderror"
                        accept="image/jpeg,image/jpg,image/png">
                    @error('thumbnail')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Konten</label>
                    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror">
                        {{ old('content') }}
                    </textarea>
                    @error('content')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content_image">Gambar Content</label>
                    <input type="file" name="content_image" id="content_image"
                        class="form-control @error('content_image') is-invalid @enderror"
                        accept="image/jpeg,image/jpg,image/png">
                    @error('content_image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ url('menu.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            CKEDITOR.replace('content', {
                filebrowserUploadUrl: "{{ url('menu.uploadimage', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form',
                height: 300
            });

            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            nameInput.addEventListener('input', function () {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9-]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
            });
        });
    </script>
@endpush