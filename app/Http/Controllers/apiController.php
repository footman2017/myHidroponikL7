<?php

namespace App\Http\Controllers;

use App\PembacaanSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class apiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function requestPPM(){
      $data = PembacaanSensor::orderBy('waktu', 'desc')
         ->take(23)
         ->get();
      return response()->json($data);
   }

   public function requestLastPPM(){
      $data = PembacaanSensor::latest('waktu')->first();
      return response()->json($data);
   }

   public function requestSerapanPPM(){
      $results = DB::select('
         select DATE(waktu) as tanggal, (sum(ppm1) - sum(ppm2)) as selisih
         from pembacaan_sensor
         group by tanggal
         ORDER BY tanggal ASC'
      );

      return response()->json($results);
   }

}
