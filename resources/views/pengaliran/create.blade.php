@extends('adminlte::page')
@section('title', 'Tambah Tanaman')
@section('content')
<h3>Mulai Pengaliran Baru</h3>
<form action="{{ route('pengaliran.store') }}" method="post" name="form1">
   {{ csrf_field() }}
   <div class="form-group">
      <label for="nama_tanaman">Nama Tanaman</label>
      {{-- <input pattern="[a-zA-Z\s]+$" type="text" class="form-control" id="nama_tanaman" name="nama_tanaman" placeholder="Nama Tanaman" required> --}}
      <input type="text" class="form-control" id="nama_tanaman" name="nama_tanaman" placeholder="Nama Tanaman" required>
   </div>
   <div class="form-group row">
      <div class="col">
         <label for="min_ppm">Min PPM</label>
         <input class="form-control" type="text" id="min_ppm" name="min_ppm"  placeholder="800" required>
      </div>
      <div class="col">
         <div class="col">
            <label for="max_ppm">Max PPM</label>
            <input class="form-control" type="text" id="max_ppm" name="max_ppm"  placeholder="1000" required>
         </div>
      </div>
   </div>
   {{-- <div class="form-group">
      <label for="tanggal_tanam">Tanggal Berakhir</label>
      <input class="form-control" type="text" id="tanggal_tanam" name="tanggal_berakhir"  placeholder="08/04/2020" required>
   </div> --}}
   <div class="form-group">
      <label for="deskripsi">Deskripsi Pengaliran</label>
      <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
   </div>
   <button type="submit" class="btn btn-primary">Submit</button>
</form>

@stop
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"/>
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
   // bootstrapValidate('#nim', 'required:Please fill out this field!')
   // bootstrapValidate('#nama', 'regex:^[a-zA-Z\s ]+$:You must fill this with alphabet !')
   // bootstrapValidate('#email', 'email:Format email salah !')
   // bootstrapValidate('#email', 'required:Please fill out this field!')
   // bootstrapValidate('#nohp', 'regex:^[0-9+]{0,15}$:Format invalid')
   // bootstrapValidate('#nohp', 'required:Please fill out this field!')

    
   $('#tanggal_tanam').datepicker({
      // format : 'mm/dd/yyyy',
      format : 'yyyy-mm-dd',
      // format : 'dd-mm-yyyy',
      todayHighlight : true,
      autoclose : true,
      startDate: new Date()
   });
</script>
@stop