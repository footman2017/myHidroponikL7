<?php

namespace App\Http\Controllers;

use App\PembacaanSensor;
use Illuminate\Http\Request;


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
         ->take(20)
         ->get();
      return response()->json($data);
   }

   public function requestLastPPM(){
      $data = PembacaanSensor::latest('waktu')->first();
      return response()->json($data);
   }

}
