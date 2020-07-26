@extends('adminlte::page')
@section('title', 'Tanaman')
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
   <table id="table_tanaman" class="table table-striped" data-page-length='5'>
      <thead style="text-align: center">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Tanaman</th>
          <th scope="col">Tanggal Tanam</th>
          <th scope="col">Min PPM</th>
          <th scope="col">Max PPM</th>
          {{-- <th scope="col">Keterangan</th> --}}
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
         @php
             $i = 1;
         @endphp
         @foreach ($pengaliran as $tanaman)
         <tr>
            <td scope="row">{{ $i++ }}</td>
            <td>{{$tanaman->nama_tanaman}}</td>
            <td>{{$tanaman->tanggal_tanam}}</td>
            <td>{{$tanaman->min_ppm}}</td>
            <td>{{$tanaman->max_ppm}}</td>
            {{-- <td>{{$tanaman->keterangan}}</td> --}}
            <td style="text-align: center">
               @if ($tanaman->status == 1)
                  <span class="badge badge-success">Aktif</span>
               @else
                  <span class="badge badge-danger">Berakhir</span>
               @endif
            </td>
            <td style="text-align: center">
               @if ($tanaman->status != 1)
               <form action="{{route('pengaliran.destroy',$tanaman->id_pengaliran)}}" method="POST">
                  <a class="btn btn-info btn-sm" href="{{route('pengaliran.show',$tanaman)}}">Show</a>
                  <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit" 
                     data-id_pengaliran="{{$tanaman->id_pengaliran}}"
                     data-nama="{{$tanaman->nama_tanaman}}"
                     data-deskripsi="{{$tanaman->deskripsi}}"
                     data-ppm="{{$tanaman->min_ppm}} - {{$tanaman->max_ppm}} PPM"
                     data-tanggal_awal="{{$tanaman->tanggal_tanam}}"
                     data-tanggal_akhir="{{$tanaman->tanggal_berakhir}}"
                     data-keterangan="{{$tanaman->keterangan}}"
                     data-status="{{$tanaman->status}}"
                  >
                     Edit
                   </button>
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
               </form>
               @else
               <a class="btn btn-info btn-sm" href="{{route('pengaliran.show', $tanaman)}}">Show</a>
               <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit" 
                  data-id_pengaliran="{{$tanaman->id_pengaliran}}"
                  data-nama="{{$tanaman->nama_tanaman}}"
                  data-deskripsi="{{$tanaman->deskripsi}}"
                  data-ppm="{{$tanaman->min_ppm}} - {{$tanaman->max_ppm}} PPM"
                  data-tanggal_awal="{{$tanaman->tanggal_tanam}}"
                  data-tanggal_akhir="{{$tanaman->tanggal_berakhir}}"
                  data-keterangan="{{$tanaman->keterangan}}"
                  data-status="{{$tanaman->status}}"
               >
                  Edit
               </button>
               <button type="submit" class="btn btn-danger btn-sm" disabled>Delete</button>
               @endif
            </td>
          </tr>
         @endforeach
      </tbody>
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
                     <input type="text" name="nama_tanaman" class="form-control" id="namaTanaman">
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
                     <input type="date" readonly class="form-control-plaintext" id="tanggalAwal" value="">
                  </div>
               </div>
               <div class="form-group row">
                  <label for="tanggalAkhir" class="col-sm-3 col-form-label">Tanggal Akhir Pengaliran</label>
                  <div class="col-sm-9">
                     <input type="date" readonly class="form-control-plaintext" id="tanggalAkhir" value="">
                  </div>
               </div>
               <div class="form-group">
                  <label for="keterangan">Keterangan</label>
                  <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
               </div>
               <div class="user-image mb-3">
                  <div id="imagePrev">
            
                  </div>
              </div>
               <div class="form-group">
                  <label for="#">Tambah Foto</label>
                  <div class="custom-file">
                     <input type="file" name="imageFile[]" class="custom-file-input" id="imageFile" multiple="multiple">
                     <label class="custom-file-label" for="imageFile">Choose image</label>
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
{{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css"> --}}
@stop

@section('js')
{{-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script> --}}
<script>
   $(document).ready(function() {
    $('#table_tanaman').DataTable({
      responsive: true,
		"dom": '<"row"<"col-sm" i><"col-sm" f>>t<"row"<"col-sm" p>>',
      "columnDefs": [
         // { "width": "200px", "targets": 6 }
      ]
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
      modal.find('.modal-title').text('Edit Data Pengaliran' + nama);
      modal.find('#namaTanaman').val(nama);
      modal.find('#deskripsi').val(deskripsi);
      modal.find('#ppm').val(ppm);
      modal.find('#tanggalAwal').val(tanggalAwal);
      modal.find('#tanggalAkhir').val(tanggalAkhir);
      modal.find('#keterangan').val(keterangan);
      modal.find('#idPengaliran').val(idPengaliran);

   });

   $(function () {
      // Multiple images preview with JavaScript
      var multiImgPreview = function(input, imgPreviewPlaceholder) {

      if (input.files) {
         var filesAmount = input.files.length;

         for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                  $($.parseHTML('<img class="" style="max-width:100px; padding:8px;">')).attr('src', event.target.result).appendTo(imgPreviewPlaceholder);
            }

            reader.readAsDataURL(input.files[i]);
         }
      }

      };

      $('#imageFile').on('change', function() {
         multiImgPreview(this, '#imagePrev');
      });
   });
} );
</script>
@stop