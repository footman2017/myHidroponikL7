@extends('adminlte::page')
@section('title', 'Tambah Tanaman')
@section('content')
<h3>Detail pengaliran {{$pengaliran->nama_tanaman}}</h3>
<form>
   <input type="hidden" id="idPengaliran" value="{{$pengaliran->id_pengaliran}}">
   <div class="form-group row">
      <label for="namaTanaman" class="col-sm-2 col-form-label">Nama Pengaliran</label>
      <div class="col-sm-10">
         <input type="text" readonly class="form-control-plaintext" id="namaTanaman" value="{{$pengaliran->nama_tanaman}}">
      </div>
   </div>
   <div class="form-group row">
      <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi Pengaliran</label>
      <div class="col-sm-10">
         <input type="text" readonly class="form-control-plaintext" id="deskripsi" value="{{$pengaliran->deskripsi}}">
      </div>
   </div>
   <div class="form-group row">
      <label for="ppm" class="col-sm-2 col-form-label">PPM</label>
      <div class="col-sm-10">
         <input type="text" readonly class="form-control-plaintext" id="ppm" value="{{$pengaliran->min_ppm}} - {{$pengaliran->max_ppm}} PPM">
      </div>
   </div>
   <div class="form-group row">
      <label for="tanggalAwal" class="col-sm-2 col-form-label">Tanggal Awal Pengaliran</label>
      <div class="col-sm-10">
         <input type="date" readonly class="form-control-plaintext" id="tanggalAwal" value="{{$pengaliran->tanggal_tanam}}">
      </div>
   </div>
   <div class="form-group row">
      <label for="tanggalAkhir" class="col-sm-2 col-form-label">Tanggal Akhir Pengaliran</label>
      <div class="col-sm-10">
         <input type="date" readonly class="form-control-plaintext" id="tanggalAkhir" value="{{$pengaliran->tanggal_berakhir}}">
      </div>
   </div>
   <div class="form-group row">
      <label for="totalSerapan" class="col-sm-2 col-form-label">Total Serapan Nutrisi</label>
      <div class="col-sm-10">
         <input type="text" readonly class="form-control-plaintext" id="totalSerapan" value="{{$totalSerapan[0]->total_serapan}} PPM">
      </div>
   </div>
   <div>
      <div id="serapanGraph">
         <div class="card">
           <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i>
                Serapan PPM
              </h3>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content p-0">
                <div class="chart tab-pane active" id="serapans-chart"
                     style="position: relative; height: auto;">
                    <canvas id="serapanChart" height="300" style="height: 1200px;"></canvas>                         
                 </div>
              </div>
            </div><!-- /.card-body -->
         </div>
      </div>
   </div>
   <div class="form-group">
      <label for="keterangan">Keterangan</label>
      <textarea class="form-control-plaintext" readonly id="keterangan" name="keterangan" rows="3">{{$pengaliran->keterangan}}</textarea>
   </div>
   <label for="">Foto</label>
   <div class="row-sm">
      @foreach ($foto as $item)
         <img src="{{ url('files/'.$item->nama_foto) }}" class="img-thumbnail" alt="{{$item->nama_foto}}" style="height:200px">
      @endforeach
   </div>
</form>
<br>
@stop
@section('css')
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA==" crossorigin="anonymous" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha512-G8JE1Xbr0egZE5gNGyUm1fF764iHVfRXshIoUWCTPAbKkkItp/6qal5YAHXrxEu4HNfPTQs6HOu3D5vCGS1j3w==" crossorigin="anonymous"></script>
<script>
   $(document).ready(function () {
      var tanggal_serapan = new Array();
      var selisih = new Array();
      var idPengaliran = $('#idPengaliran').val();
      
      $.ajax({
         url: "{{url('/getSerapanPPMbyId')}}",
         type: 'get',
         data: {id:idPengaliran},
         dataType: 'json',
         success:function(response){
            console.log(response);
            response.forEach(function(data){
               tanggal_serapan.push(data.tanggal);
               selisih.push(data.selisih);
            });
            var ctx_serapan = document.getElementById("serapanChart").getContext('2d');
            var serapanChart = new Chart(ctx_serapan, {
               type: 'line',
               data: {
                  labels:tanggal_serapan,
                  datasets: [{
                     label: 'Serapan PPM',
                     data: selisih,
                     borderWidth: 2,
                     fill : false,
                     backgroundColor : '##00cccc',
                     borderColor : '#00ffff'
                  }]
               },
               options: {
                  scales: {
                     yAxes: [{
                        ticks: {
                           beginAtZero:true,
                           // suggestedMin: 0,
                           // suggestedMax: 14,
                           // stepSize: 2
                        }
                     }]
                  }
               }
            });
         }
      });
          
      $('.img-thumbnail').on('click', function () { 
         var image = $(this).attr('src');
         window.open(image);
      });
   });
</script>
@stop