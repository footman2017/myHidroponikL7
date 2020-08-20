<?php

namespace App\Http\Controllers;

use App\PembacaanSensor;
use App\Pengaliran;
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

      $data = PembacaanSensor::join('pengaliran', 'pembacaan_sensor.id_pengaliran', '=', 'pengaliran.id_pengaliran')
      ->select('ppm1', 'ppm2', 'waktu')
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
      $user = Auth::user();
      $data = PembacaanSensor::join('pengaliran', 'pembacaan_sensor.id_pengaliran', '=', 'pengaliran.id_pengaliran')
      ->latest('waktu')
      ->where([
         ['email', $user->email],
         ['status', 1]
      ])
      ->first();
      return response()->json($data);
   }

   public function requestLastSerapan(){
      // $data = PembacaanSensor::latest('waktu')->selectRaw('ppm1-ppm2 as selisih, waktu')->first();
      // return response()->json($data);
      $user = Auth::user();
      $results = DB::select('
         select waktu, (ppm1 - ppm2) as selisih
         from pembacaan_sensor
         left join pengaliran on pengaliran.id_pengaliran = pembacaan_sensor.id_pengaliran
         where email = :emailUser and status = 1
         order by waktu desc
         limit 1
      ', ['emailUser' => $user->email]);

      return response()->json($results);
   }

   public function requestSerapanPPM(){
      $user = Auth::user();
      $results = DB::select('
         select waktu as tanggal, (ppm1 - ppm2) as selisih
         from pembacaan_sensor
         left join pengaliran on pengaliran.id_pengaliran = pembacaan_sensor.id_pengaliran
         where email = :emailUser and status = 1
         order by tanggal desc
         limit 10
      ', ['emailUser' => $user->email]);

      return response()->json($results);
   }

   public function requestSerapanPPMbyId(Request $request){
      $results = DB::select('
         select date(waktu) as tanggal, (sum(ppm1) - sum(ppm2))/count(*) as selisih, sum(ppm1)/count(*) as "ppm1", sum(ppm2)/count(*) as "ppm2"
         from pembacaan_sensor
         where id_pengaliran = :id
         group by tanggal
         order by tanggal asc
      ', ['id' => $request->get('id')]);

      return response()->json($results);
   }

   public function getAllPengaliran(){
      $user = Auth::user();
      return datatables(Pengaliran::where('email', $user->email)->get())->toJson();
   }

   //********************//
   //KALO MAU YG SEMUANYA//
   //********************//
   
   // public function requestAllSerapanPPMbyId(Request $request){
   //    $results = DB::select('
   //       select waktu as tanggal, (ppm1 - ppm2) as selisih
   //       from pembacaan_sensor
   //       where id_pengaliran = :id
   //       order by tanggal asc
   //    ', ['id' => $request->get('id')]);

   //    return response()->json($results);
   // }

}
