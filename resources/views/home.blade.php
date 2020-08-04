{{-- @extends('layouts.app') --}}
@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
<div>
   @if(session()->get('success'))
     <div class="alert alert-success" role="alert">
       {{ session()->get('success') }}  
     </div>
   @endif   
</div>
@if (empty($data))
   <div>
      <h1>Tidak ada pengaliran yang sedang berjalan</h1>
   </div>
@else
@foreach ($data as $item)
    {{-- <div class="container"> --}}
   <div id="PPMgraph">
      <div class="card">
        <div class="card-header">
           <h3 class="card-title">
             <i class="fas fa-chart-pie mr-1"></i>
             Kadar PPM
           </h3>
           <div class="card-tools">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" class="custom-control-input" id="realtime">
               <label class="custom-control-label" for="realtime">Realtime</label>
             </div>
           </div>
         </div><!-- /.card-header -->
         <div class="card-body">
           <div class="tab-content p-0">
             <div class="chart tab-pane active" id="PPM-chart" style="position: relative; height: auto;">
                 <canvas id="myChart" height="300" style="height: 1200px;"></canvas>                         
              </div>
           </div>
         </div><!-- /.card-body -->
      </div>
   </div>

   <div id="serapanGraph">
     <div class="card">
       <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            Serapan PPM
          </h3>
          <div class="card-tools">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" class="custom-control-input" id="realtime_serapan">
               <label class="custom-control-label" for="realtime_serapan">Realtime</label>
             </div>
           </div>
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
  
  <div class="row">
     <div class="col-sm-4">
        <div class="card text-center">
           <div class="card-header ">
              Status
           </div>
           <div class="card-body">
              @if ($item->status==1)
                 <button type="button" class="btn btn-success">Connected</button>
              @else
                 <button type="button" class="btn btn-danger">Disconected</button>
              @endif
           </div>
        </div>
     </div>
     <div class="col-sm-4">
        <div class="card text-center">
           <div class="card-header">
              PPM
           </div>
           <div class="card-body">
              <p class="card-text">{{$item->min_ppm}}-{{$item->max_ppm}}</p>
           </div>
        </div>
     </div>
     <div class="col-sm-4">
        <div class="card text-center">
           <div class="card-header">
              Action
           </div>
           <div class="card-body">
           <a id="end_button"  href="/stop/{{$item->id_pengaliran}}" class="btn btn-primary" role="button">Akhiri Pengaliran</a>
           </div>
        </div>
     </div>
  </div>
  <div class="card text-center">
     <div class="card-header">
        Deskripsi
     </div>
     <div class="card-body">
     <p class="card-text">{{$item->deskripsi}}</p>
     </div>
  </div>
  <div class="card border-primary">
    <img class="card-img-top" src="holder.js/100px180/" alt="">
    <div class="card-body">
      <div class="table-responsive">
         <table id="table_tanaman" class="table table-striped" data-page-length='5'>
            <thead style="text-align: center">
            <tr>
               <th colspan="3">Riwayat Pengaliran</th>
            </tr>
            <tr>
               <th scope="col">Deskripsi</th>
               <th scope="col">PPM</th>
               <th scope="col">Aksi</th>
            </tr>
            </thead>
            <tbody>
               @php
                  $i = 1;
               @endphp
               @foreach ($dataPengaliran as $tanaman)
               <tr>
                  <td>{{$tanaman->deskripsi}}</td>
                  <td align="center">{{$tanaman->min_ppm}} - {{$tanaman->max_ppm}}</td>
                  <td align="center">
                     <a class="btn btn-info btn-sm" href="{{route('pengaliran.show', $tanaman)}}">Show</a>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
    </div>
  </div>
{{-- </div> --}}
@endforeach
@endif

<br>

@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA==" crossorigin="anonymous" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha512-G8JE1Xbr0egZE5gNGyUm1fF764iHVfRXshIoUWCTPAbKkkItp/6qal5YAHXrxEu4HNfPTQs6HOu3D5vCGS1j3w==" crossorigin="anonymous"></script>
<script>
$('#end_button').click(function (e) { 
   return confirm("Yakin akan diakhiri ?");
});

window.setTimeout(function() {
        $(".alert").fadeTo(300, 0).slideUp(300, function(){
            $(this).remove(); 
        });
}, 2500);

$(document).ready(function() {
      var tanggal = new Array();
      var tanggal_serapan = new Array();
      var time;
      var nilai = new Array();
      var nilai2 = new Array();
      var selisih = new Array();
      var refreshIntervalId;
      var arrPPMupdate = new Array();
      
      function addData(chart, label, data) {
         var i = 0;
         chart.data.labels.push(label);
         chart.data.datasets.forEach((dataset) => {
            if(Array.isArray(data)){
               dataset.data.push(data[i]);
               i++;
            }else{
               dataset.data.push(data);
            }
            
         });
         // chart.data.datasets[0].data.push(data[0]);
         // chart.data.datasets[1].data.push(data[1]);
         chart.update();
      }

      function removeData(chart) {
         chart.data.labels.shift();
         chart.data.datasets.forEach((dataset) => {
            dataset.data.shift();
         });
         chart.update();
      }

      $.ajax({
         url: "{{url('/getDataPPM')}}",
         type: 'get',
         // data: {ggwp:2},
         dataType: 'json',
         success:function(response){
            // console.log(response);
            response.forEach(function(data){
               time = data.waktu.split(' ');
               tanggal.push(time[1]);
               nilai.push(data.ppm1);
               nilai2.push(data.ppm2);
            });
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
               type: 'line',
               data: {
                  labels:tanggal.reverse(),
                  datasets: [
                  {
                     label: 'PPM Pada Bak penampung',
                     data: nilai.reverse(),
                     borderWidth: 2,
                     fill : false,
                     backgroundColor : '##00cccc',
                     borderColor : '#00ffff'
                  },
                  {
                     label: 'PPM Pada Ujung Pipa',
                     data: nilai2.reverse(),
                     borderWidth: 2,
                     fill : false,
                     backgroundColor : 'black',
                     borderColor : 'black'
                  },
                  ]
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

            $('#realtime').change(function() {
               if(this.checked) {
                  refreshIntervalId = setInterval(function(){
                     $.ajax({
                        url: "{{url('/getLastPPM')}}",
                        type: 'get',
                        // data: {ggwp:2},
                        dataType: 'json',
                        success:
                        function(response){
                           time = response.waktu.split(' ');
                           arrPPMupdate = [response.ppm1, response.ppm2];
                           if(tanggal[tanggal.length-1] != time[1]){
                              addData(myChart, time[1], arrPPMupdate);
                              removeData(myChart);
                           }
                        }
                     });

                  }, 1000);      
               }else{
                  console.log(refreshIntervalId);
                  clearInterval(refreshIntervalId);
               }
            });
         }
      });

      $.ajax({
         url: "{{url('/getSerapanPPM')}}",
         type: 'get',
         // data: {ggwp:2},
         dataType: 'json',
         success:function(response){
            response.forEach(function(data){
               time = data.tanggal.split(' ');
               tanggal_serapan.push(time[1]);
               selisih.push(data.selisih);
            });
            var ctx_serapan = document.getElementById("serapanChart").getContext('2d');
            var serapanChart = new Chart(ctx_serapan, {
               type: 'line',
               data: {
                  labels:tanggal_serapan.reverse(),
                  datasets: [{
                     label: 'Serapan PPM',
                     data: selisih.reverse(),
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
                        }
                     }]
                  }
               }
            });

            $('#realtime_serapan').change(function() {
               if(this.checked) {
                  refreshIntervalIdSerapan = setInterval(function(){
                     $.ajax({
                        url: "{{url('/getLastSerapan')}}",
                        type: 'get',
                        // data: {ggwp:2},
                        dataType: 'json',
                        success:
                        function(response){
                           time = response[0].waktu.split(' ');
                           // console.log(response[0]);
                           if(tanggal_serapan[tanggal_serapan.length-1] != time[1]){
                              addData(serapanChart, time[1], response[0].selisih);
                              removeData(serapanChart);
                           }
                        }
                     });

                  }, 1000);      
               }else{
                  console.log(refreshIntervalIdSerapan);
                  clearInterval(refreshIntervalIdSerapan);
               }
            });
         }
      });

      
   
   });
</script>
@endsection
