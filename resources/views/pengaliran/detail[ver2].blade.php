@extends('adminlte::page')
@section('title', 'Detail')
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
      <label for="totalSerapan" class="col-sm-2 col-form-label">Rata - Rata Serapan Nutrisi</label>
      <div class="col-sm-10">
         <input type="text" readonly class="form-control-plaintext" id="totalSerapan" value="{{$totalSerapan[0]->total_serapan}} PPM">
      </div>
   </div>
   <div class="row">
      <div class="col-4">
         <div id="serapanGraph">
            <div class="card">
              <div class="card-header">
                 <h3 class="card-title">
                   <i class="fas fa-chart-pie mr-1"></i>
                   Selisih PPM pada bak dan ujung pipa
                 </h3>
               </div><!-- /.card-header -->
               <div class="card-body">
                  <canvas id="serapanChart" height="300" width="auto" style=""></canvas>                         
               </div><!-- /.card-body -->
            </div>
         </div>
      </div>

      <div class="col-4">
         <div id="ppm1Graph">
            <div class="card">
              <div class="card-header">
                 <h3 class="card-title">
                   <i class="fas fa-chart-pie mr-1"></i>
                   PPM Pada Bak Penampungan
                 </h3>
               </div><!-- /.card-header -->
               <div class="card-body">
                  <canvas id="ppm1Chart" height="300" width="auto" style=""></canvas>                         
               </div><!-- /.card-body -->
            </div>
         </div>
      </div>

      <div class="col-4">
         <div id="serapanGraph">
            <div class="card">
              <div class="card-header">
                 <h3 class="card-title">
                   <i class="fas fa-chart-pie mr-1"></i>
                   PPM pada Ujung Pipa
                 </h3>
               </div><!-- /.card-header -->
               <div class="card-body">
                  <canvas id="ppm2Chart" height="300" width="auto" style=""></canvas>                         
               </div><!-- /.card-body -->
            </div>
         </div>
      </div>
   </div>

   {{-- //********************//
   //KALO MAU YG SEMUANYA//
   //********************// --}}
   
   {{-- <div>
      <div class="card">
         <div class="card-header">
            <h3 class="card-title">
               <i class="fas fa-chart-pie mr-1"></i>
               Selisih PPM pada bak dan ujung pipa
            </h3>
         </div><!-- /.card-header -->
         <div class="card-body">
            <canvas id="AllserapanChart" height="auto" width="auto" style=""></canvas>                         
         </div><!-- /.card-body -->
      </div>
   </div> --}}

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
      var serapanPPM = {!! json_encode($serapanPPM) !!};
      var tanggal_serapan = new Array();
      var selisih = new Array();
      var tanggal_serapanAll = new Array();
      var selisihAll = new Array();
      var ppm1 = new Array();
      var ppm2 = new Array();
      var idPengaliran = $('#idPengaliran').val();
      
      serapanPPM.forEach(function(data){
         tanggal_serapan.push(data.tanggal);
         selisih.push(data.selisih);
         ppm1.push(data.ppm1);
         ppm2.push(data.ppm2);
      });
      var ctx_serapan = document.getElementById("serapanChart").getContext('2d');
      var serapanChart = new Chart(ctx_serapan, {
         type: 'line',
         data: {
            labels:tanggal_serapan,
            datasets: [{
               label: 'Selisih',
               data: selisih,
               borderWidth: 1,
               fill : false,
               backgroundColor : 'black',
               borderColor : 'black',
               // pointBackgroundColor : '#00ffff',
               // pointBorderWidth : 1,
               pointRadius : 1,
               borderCapStyle : 'square'
            }]
         },
         options: {
            layout : {
               padding: {
                  left: 0,
                  right: 0,
                  top: 0,
                  bottom: 0
               }
            },
            scales: {
               yAxes: [{
                  ticks: {
                     beginAtZero:true,
                  }
               }]
            }
         }
      });

      var ctx_ppm1 = document.getElementById("ppm1Chart").getContext('2d');
      var ppm1Chart = new Chart(ctx_ppm1, {
         type: 'line',
         data: {
            labels:tanggal_serapan,
            datasets: [{
               label: 'PPM Pada Bak Penampungan',
               data: ppm1,
               borderWidth: 1,
               fill : false,
               backgroundColor : 'black',
               borderColor : 'black',
               // pointBackgroundColor : '#00ffff',
               // pointBorderWidth : 1,
               pointRadius : 1,
               borderCapStyle : 'square'
            }]
         },
         options: {
            layout : {
               padding: {
                  left: 0,
                  right: 0,
                  top: 0,
                  bottom: 0
               }
            },
            scales: {
               yAxes: [{
                  ticks: {
                     beginAtZero:true,
                  }
               }]
            }
         }
      });

      var ctx_ppm2 = document.getElementById("ppm2Chart").getContext('2d');
      var ppm2Chart = new Chart(ctx_ppm2, {
         type: 'line',
         data: {
            labels:tanggal_serapan,
            datasets: [{
               label: 'PPM Pada Ujung Pipa',
               data: ppm2,
               borderWidth: 1,
               fill : false,
               backgroundColor : 'black',
               borderColor : 'black',
               // pointBackgroundColor : '#00ffff',
               // pointBorderWidth : 1,
               pointRadius : 1,
               borderCapStyle : 'square'
            }]
         },
         options: {
            layout : {
               padding: {
                  left: 0,
                  right: 0,
                  top: 0,
                  bottom: 0
               }
            },
            scales: {
               yAxes: [{
                  ticks: {
                     beginAtZero:true,
                  }
               }]
            }
         }
      });


      //********************//
      //KALO MAU YG SEMUANYA//
      //********************//


      // $.ajax({
      //    url: "{{url('/getAllSerapanPPMbyId')}}",
      //    type: 'get',
      //    data: {id:idPengaliran},
      //    dataType: 'json',
      //    success: function (response) {
      //       console.log(response);
      //       response.forEach(function(data){
      //          tanggal_serapanAll.push(data.tanggal);
      //          selisihAll.push(data.selisih);
      //       });
      //       var ctx_serapan = document.getElementById("AllserapanChart").getContext('2d');
      //       var serapanChart = new Chart(ctx_serapan, {
      //          type: 'line',
      //          data: {
      //             labels:tanggal_serapanAll,
      //             datasets: [{
      //                label: 'Selisih',
      //                data: selisihAll,
      //                borderWidth: 1,
      //                fill : false,
      //                backgroundColor : 'black',
      //                borderColor : 'black',
      //                // pointBackgroundColor : '#00ffff',
      //                // pointBorderWidth : 1,
      //                pointRadius : 1,
      //                borderCapStyle : 'square'
      //             }]
      //          },
      //          options: {
      //             layout : {
      //                padding: {
      //                   left: 0,
      //                   right: 0,
      //                   top: 0,
      //                   bottom: 0
      //                }
      //             },
      //             scales: {
      //                yAxes: [{
      //                   ticks: {
      //                      beginAtZero:true,
      //                   }
      //                }]
      //             }
      //          }
      //       });
      //    }
      // });
          
      $('.img-thumbnail').on('click', function () { 
         var image = $(this).attr('src');
         window.open(image);
      });
   });
</script>
@stop