<?php

namespace App\Http\Controllers;

use App\Pengaliran;
use App\PembacaanSensor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatasensorController extends Controller
{
   public function insert($ppm1, $ppm2){
      $pengaliran = Pengaliran::firstWhere('status', 1);
      $dataSensor = new PembacaanSensor([
         'id_pengaliran' => $pengaliran->id_pengaliran,
         'ppm1' => $ppm1,
         'ppm2' => $ppm2
      ]);

      try{
         if($dataSensor->save()) echo "success";
      }
      catch(\Exception $e){
         echo $e->getMessage();
      }
   }

   public function getDeskripsi(){
      $pengaliran = Pengaliran::where('status', 1)->firstOrFail();
      echo $pengaliran->deskripsi;
   }
   
   public function getMinPPM(){
      $pengaliran = Pengaliran::where('status', 1)->firstOrFail();
      echo $pengaliran->min_ppm;
   }
}
