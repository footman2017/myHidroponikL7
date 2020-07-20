<?php

namespace App\Http\Controllers;

use App\PembacaanSensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
      $user = Auth::user();
      // $data = PembacaanSensor::orderBy('waktu', 'desc')
      //    ->take(10)
      //    ->get();

      $data = PembacaanSensor::join('pengaliran', 'pembacaan_sensor.id_pengaliran', '=', 'pengaliran.id_pengaliran')
      ->select('pembacaan_sensor.id_pengaliran', 'ppm1', 'ppm2', 'waktu')
      ->where([
         ['email', $user->email],
         ['status', 1]
      ])
      ->orderBy('pembacaan_sensor.waktu', 'desc')
      ->take(10)
      ->get();

      // print_r(response()->json($data));die;
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
