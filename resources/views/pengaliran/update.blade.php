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
   <div class="user-image mb-3">
      <div id="imagePrev">

      </div>
  </div>
   <div class="form-group">
      <label for="#">Upload Foto</label>
      <div class="custom-file">
         <input type="file" name="imageFile[]" class="custom-file-input" id="imageFile" multiple="multiple">
         <label class="custom-file-label" for="imageFile">Choose image</label>
      </div>
   </div>
   <div class="form-group">
      <label for="keterangan">Keterangan</label>
      <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
   </div>
   <button type="submit" class="btn btn-primary">Akhiri</button>
</form>

@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
   $('#tanggal_tanam').datepicker({
      // format : 'mm/dd/yyyy',
      format : 'yyyy-mm-dd',
      // format : 'dd-mm-yyyy',
      todayHighlight : true,
      autoclose : true,
      startDate: new Date()
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
</script>
@stop