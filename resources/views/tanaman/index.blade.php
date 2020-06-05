@extends('adminlte::page')
@section('title', 'Tanaman')
@section('plugins.Datatables', true)
@section('content')

@if(session()->get('success'))
   <div class="alert alert-success" role="alert">
      {{ session()->get('success') }}  
   </div>
@endif
<div>
   <a class="btn btn-primary" href="{{ route('plans.create') }}" role="button">Tambah Pengaliran</a>
</div>
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
          <th scope="col">Keterangan</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
          test
        </tr>
      </thead>
      <tbody>
         @php
             $i = 1;
         @endphp
         @foreach ($plans as $tanaman)
         <tr>
            <th scope="row">{{ $i++ }}</th>
            <td>{{$tanaman->nama_tanaman}}</td>
            <td>{{$tanaman->tanggal_tanam}}</td>
            <td>{{$tanaman->min_ppm}}</td>
            <td>{{$tanaman->max_ppm}}</td>
            <td>{{$tanaman->keterangan}}</td>
            <td style="text-align: center">
               @if ($tanaman->status == 0)
                  <span class="badge badge-primary">Aktif</span>
               @endif
            </td>
            <td style="text-align: center">
               @if ($tanaman->status != 0)
               <form action="{{route('plans.destroy',$tanaman->id_tanaman)}}" method="POST">
                  <a class="btn btn-info btn-sm" href="{{route('plans.show',$tanaman)}}">Show</a>
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
               </form>
               @else
               <a class="btn btn-info btn-sm" href="{{route('plans.show',$tanaman)}}">Show</a>
               <button type="submit" class="btn btn-danger btn-sm" disabled>Delete</button>
               @endif
               
            </td>
          </tr>
         @endforeach
      </tbody>
   </table>
</div>
@stop

@section('css')
@stop

@section('js')
<script>
   $(document).ready(function() {
    $('#table_tanaman').DataTable({
      responsive: true,
		"dom": '<"row"<"col-sm" i><"col-sm" f>>t<"row"<"col-sm" p>>',
      "columnDefs": [
         { "width": "200px", "targets": 7 }
      ]
	});
} );
</script>
@stop