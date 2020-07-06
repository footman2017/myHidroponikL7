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
<div class="container">
    <div>
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
              <div class="chart tab-pane active" id="revenue-chart"
                   style="position: relative; height: auto;">
                  <canvas id="myChart" height="300" style="height: 1200px;"></canvas>                         
               </div>
            </div>
          </div><!-- /.card-body -->
       </div>
    </div>
</div>
@endsection
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA==" crossorigin="anonymous" />
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" integrity="sha512-G8JE1Xbr0egZE5gNGyUm1fF764iHVfRXshIoUWCTPAbKkkItp/6qal5YAHXrxEu4HNfPTQs6HOu3D5vCGS1j3w==" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
      var tanggal = new Array();
      var nilai = new Array();
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
               tanggal.push(data.waktu);
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
                     borderWidth: 3,
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
                        url: "{{url('/diagram/getdatalastph')}}",
                        type: 'get',
                        // data: {ggwp:2},
                        dataType: 'json',
                        success:
                        function(response){
                           if(tanggal[tanggal.length-1] != response.waktu){
                              addData(myChart, response.waktu, response.ppm1);
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
   
   });
</script>
@endsection
