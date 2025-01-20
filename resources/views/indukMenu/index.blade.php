@extends('layouts.template') 
 
@section('content') 
  <div class="card card-outline card-primary"> 
      <div class="card-header"> 
        <h3 class="card-title">{{ $page->title }}</h3> 
        <div class="card-tools"> 
          <button onclick="modalAction('{{ url('/indukMenu/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button> 
        </div> 
      </div> 
      <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                </div>
            </div>
        </div>

      <div class= "table-responsive">
        <table class="table table-bordered table-striped table-hover table-sm" id="table_menus">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
      </div>
    </div>
  </div> 
  <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" 
   data-width="75%" aria-hidden="true"></div>
@endsection 
 
@push('css') 
@endpush 
 
@push('js') 
  <script> 
  function modalAction(url = ''){ 
    $('#myModal').load(url,function(){ 
        $('#myModal').modal('show'); 
    }); 
} 
var dataIndukMenu;
    $(document).ready(function() { 
     dataIndukMenu = $('#table_menus').DataTable({ 
          // serverSide: true, jika ingin menggunakan server side proses 
          serverSide: true,      
          ajax: { 
              "url": "{{ url('indukMenu/list') }}", 
              "dataType": "json", 
              "type": "POST",
              "data": function (d) {
                d.level_id = $('#level_id').val();
              }
          }, 
          columns: [ 
            { 
            // nomor urut dari laravel datatable addIndexColumn() 
            data: "DT_RowIndex",             
              className: "text-center", 
              orderable: false, 
              searchable: false     
            },{ 
              data: "name",                
              className: "", 
              orderable: true,     
              searchable: true     
            },{ 
              data: "aksi",                
              className: "", 
              orderable: false,     
              searchable: false     
            } 
          ] 
        }); 
        $('#level_id').on('change', function() {
            dataIndukMenu.ajax.reload();
        });
    }); 
  </script> 
@endpush