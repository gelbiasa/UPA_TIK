@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">Pengaturan Menu</h3>
      <div class="card-tools">
        <a href="{{ url('/menu/create') }}" class="btn btn-sm btn-success mt-1"><i class="fas fa-plus"></i> Tambah Menu</a>
      </div>
    </div>
    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <table class="table table-bordered table-striped table-hover table-sm" id="menu_table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Slug</th>
            <th>Urutan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($menus as $menu)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $menu->name }}</td>
              <td>{{ $menu->slug }}</td>
              <td>{{ $menu->order_number }}</td>
              <td>
                @if($menu->parent_id !== null) <!-- Hanya tampilkan tombol aksi jika menu memiliki parent_id -->
                  <a href="{{ url('/menu/' . $menu->menus_id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                  <form method="POST" action="{{ url('/menu/' . $menu->menus_id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin menghapus menu ini?');">Hapus</button>
                  </form>
                @endif
              </td>
            </tr>
            @foreach($menu->children as $submenu)
              <tr>
                <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
                <td>&nbsp;&nbsp;&nbsp;{{ $submenu->name }}</td>
                <td>{{ $submenu->slug }}</td>
                <td>{{ $submenu->order_number }}</td>
                <td>
                  <a href="{{ url('/menu/' . $submenu->menus_id . '/edit') }}" class="btn btn-warning btn-sm">Edit</a>
                  <form method="POST" action="{{ url('/menu/' . $submenu->menus_id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin menghapus submenu ini?');">Hapus</button>
                  </form>
                </td>
              </tr>
            @endforeach
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
