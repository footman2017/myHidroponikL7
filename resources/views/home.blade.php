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
              <div class="chart tab-pane active" id="PPM-chart"
                   style="position: relative; height: auto;">
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
               <p class="card-text">{{$data[0]['status']}}</p>
            </div>
         </div>
      </div>
      <div class="col-sm-4">
         <div class="card text-center">
            <div class="card-header">
               PPM
            </div>
            <div class="card-body">
               <p class="card-text">{{$data[0]['min_ppm']}}-{{$data[0]['max_ppm']}}</p>
            </div>
         </div>
      </div>
      <div class="col-sm-4">
         <div class="card text-center">
            <div class="card-header">
               Action
            </div>
            <div class="card-body">
               <button type="button" class="btn btn-primary">Akhiri Pengaliran</button>
            </div>
         </div>
      </div>
   </div>
   <div class="card text-center">
      <div class="card-header">
         Deskripsi
      </div>
      <div class="card-body">
      <p class="card-text">{{$data[0]['keterangan']}}</p>
      </div>
   </div>
{{-- </div> --}}
<br>

@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA==" crossorigin="anonymous" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha512-G8JE1Xbr0egZE5gNGyUm1fF764iHVfRXshIoUWCTPAbKkkItp/6qal5YAHXrxEu4HNfPTQs6HOu3D5vCGS1j3w==" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
      var tanggal = new Array();
      var tanggal_serapan = new Array();
      var time;
      var nilai = new Array();
      var selisih = new Array();
      var refreshIntervalId;
      
      function addData(chart, label, data) {
         chart.data.labels.push(label);
         chart.data.datasets.forEach((dataset) => {
            dataset.data.push(data);
         });
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
            console.log(response);
            response.forEach(function(data){
               time = data.waktu.split(' ');
               // console.log(time[0]);
               tanggal.push(time[1]);
               nilai.push(data.ppm1);
            });
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
               type: 'line',
               data: {
                  labels:tanggal.reverse(),
                  datasets: [{
                     label: 'PPM',
                     data: nilai.reverse(),
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
                           console.log(time[1]);
                           if(tanggal[tanggal.length-1] != time[1]){
                              addData(myChart, time[1], response.ppm1);
                              removeData(myChart);
                           }
                        }
                     });

                     // var waktu = new Date();
                     // var tahun = waktu.getFullYear();
                     // var bulan = waktu.getMonth();
                     // var tanggal = waktu.getDate();
                     // var jam = waktu.getHours();
                     // var menit = waktu.getMinutes();
                     // var detik = waktu.getSeconds();
                     // addData(myChart, ""+tahun+"-"+bulan+"-"+tanggal+" "+jam+":"+menit+":"+detik+"", (Math.floor(Math.random() * 14) + 0));
                     // removeData(myChart);
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
   
   });
</script>
@endsection
