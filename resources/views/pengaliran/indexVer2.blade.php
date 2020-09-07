@extends('adminlte::page')
@section('title', 'Data Pengaliran')
@section('plugins.Datatables', true)
@section('content')

@if(session()->get('success'))
   <div class="alert alert-success" role="alert">
      {{ session()->get('success') }}  
   </div>
@endif
@if (count($errors) > 0)
<div class="alert alert-danger">
   <ul>
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
   </ul>
</div>
@endif
@if($thereIsPengaliran != 1)
<div>
   <a class="btn btn-primary" href="{{ route('pengaliran.create') }}" role="button">Tambah Pengaliran</a>
</div>
@endif


<br>

<div class="table-responsive">
   <table id="table_tanaman" class="table table-striped" style="width:100%">
      <thead style="text-align: center">
        <tr>
          {{-- <th scope="col">No</th> --}}
          <th scope="col">Nama Tanaman</th>
          <th scope="col">Tanggal Tanam</th>
          <th scope="col">Min PPM</th>
          <th scope="col">Max PPM</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
   </table>
</div>

<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
         </div>
      <form id="form_edit" action="{{route('Sunting')}}" method="post" name="form1" enctype="multipart/form-data">
         {{ csrf_field() }}
         <div class="modal-body">
               <input type="hidden" name="id_pengaliran" id="idPengaliran" value="">
               <div class="form-group row">
                  <label for="namaTanaman" class="col-sm-3 col-form-label">Nama Pengaliran</label>
                  <div class="col-sm-9">
                     <input type="text" name="nama_tanaman" class="form-control" id="namaTanaman" required>
                  </div>
               </div>
               <div class="form-group row">
                  <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi Pengaliran</label>
                  <div class="col-sm-9">
                     <input type="text" name="deskripsi" class="form-control" id="deskripsi" value="">
                  </div>
               </div>
               <div class="form-group row">
                  <label for="ppm" class="col-sm-3 col-form-label">PPM</label>
                  <div class="col-sm-9">
                     <input type="text" readonly class="form-control-plaintext" id="ppm" value="">
                  </div>
               </div>
               <div class="form-group row">
                  <label for="tanggalAwal" class="col-sm-3 col-form-label">Tanggal Awal Pengaliran</label>
                  <div class="col-sm-9">
                     <input type="text" readonly class="form-control-plaintext" id="tanggalAwal" value="">
                  </div>
               </div>
               <div class="form-group row">
                  <label for="tanggalAkhir" class="col-sm-3 col-form-label">Tanggal Akhir Pengaliran</label>
                  <div class="col-sm-9">
                     <input type="text" readonly class="form-control-plaintext" id="tanggalAkhir" value="">
                  </div>
               </div>
               <div class="form-group">
                  <label for="keterangan">Keterangan</label>
                  <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
               </div>
               <div class="form-group">
                  <div class="custom-file">
                     <input type="file" name="imageFile[]" class="custom-file-input" id="uploadFile" multiple>
                     <label class="custom-file-label" for="uploadFile">Choose file</label>
                   </div>
               </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
         </div>
      </form>
      </div>
   </div>
</div>
@stop

@section('css')
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

<script>
   $(document).ready(function() {
   bsCustomFileInput.init();

   var  t =  $('#table_tanaman').DataTable({
      "processing": true,
      "serverSide": true,
      "ajax": "{{url('/getAllPengaliran')}}",
      "responsive": true,
		
      "columns" : [
         { 
            "data": "nama_tanaman" 
         },
         { 
            "data": "tanggal_tanam"
         },
         { 
            "data": "min_ppm"
         },
         { 
            "data": "max_ppm"
         },
         { 
            "data": "status"
         },
         { 
            "data": null
         }
      ],
      "columnDefs": [
         {
            "targets": 4,
            "render": function ( data, type, row, meta ) {
               if (row.status == 0)
                  return '<span class="badge badge-danger">Berakhir</span>';
               else return '<span class="badge badge-success">Aktif</span>';
               
            }
         },
         {
            "targets": 5,
            "render": function ( data, type, row, meta ) {
               var showButton = '<a class="btn btn-info btn-sm" href="pengaliran/'+row.id_pengaliran+'">Show</a>';
               var editButton = '<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit"'
                     +'data-id_pengaliran="'+row.id_pengaliran+'"'+
                     'data-nama="'+row.nama_tanaman+'"'+
                     'data-deskripsi="'+row.deskripsi+'"'+
                     'data-ppm="'+row.min_ppm+' - '+row.max_ppm+' PPM"'+
                     'data-tanggal_awal="'+row.tanggal_tanam+'"'+
                     'data-tanggal_akhir="'+row.tanggal_berakhir+'"'+
                     'data-keterangan="'+row.keterangan+'"'+
                     'data-status="'+row.status+'">Edit </button>';
               var deleteButton = '<a class="btn btn-danger btn-sm" href="deletePengaliran/'+row.id_pengaliran+'">Delete</a>'

               if (row.status == 0)
                  return showButton+' '+editButton+' '+deleteButton;
               else return showButton+' '+editButton;
            }
         }
      ],
	});

   $('#edit').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var idPengaliran = button.data('id_pengaliran');
      var nama = button.data('nama');
      var deskripsi = button.data('deskripsi');
      var ppm = button.data('ppm');
      var tanggalAwal = button.data('tanggal_awal');
      var tanggalAkhir = button.data('tanggal_akhir');
      var keterangan = button.data('keterangan');
      var status = button.data('status');

      console.log(nama);
      
      var modal = $(this);
      modal.find('.modal-title').text('Edit Data Pengaliran ' + nama);
      modal.find('#namaTanaman').val(nama);
      modal.find('#deskripsi').val(deskripsi);
      modal.find('#ppm').val(ppm);
      modal.find('#tanggalAwal').val(tanggalAwal);
      // console.log(tanggalAkhir);
      if(tanggalAkhir==null){
         modal.find('#tanggalAkhir').val("pengaliran masih berjalan");
      }else{
         modal.find('#tanggalAkhir').val(tanggalAkhir);
      }
      modal.find('#keterangan').val(keterangan);
      modal.find('#idPengaliran').val(idPengaliran);

   });

   $('#edit').on('hidden.bs.modal', function (event) {
        $('#form_edit')[0].reset();
    });

} );
</script>
@stop