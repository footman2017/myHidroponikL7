@extends('adminlte::page')
@section('title', 'Akhiri pengairan')
@section('content')
<h3>Akhiri Pengaliran {{$pengaliran->nama_tanaman}}</h3>
<form action="{{ route('imageUpload') }}" method="post" name="form1" enctype="multipart/form-data">
   {{ csrf_field() }}
   @if ($message = Session::get('success'))
         <div class="alert alert-success">
            <strong>{{ $message }}</strong>
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
   <input class="form-control" type="hidden" id="id_pengaliran" name="id_pengaliran"  value="{{$pengaliran->id_pengaliran}}">
   <div class="form-group">
      <div class="custom-file">
         <input type="file" name="imageFile[]" class="custom-file-input" id="uploadFile" multiple>
         <label class="custom-file-label" for="uploadFile">Choose file</label>
       </div>
   </div>
   <div class="form-group">
      <label for="keterangan">Keterangan</label>
      <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
   </div>
   <button type="submit" class="btn btn-primary">Akhiri</button>
</form>

@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

<script>
   $(document).ready(function () {
      bsCustomFileInput.init();
   });
</script>
@stop