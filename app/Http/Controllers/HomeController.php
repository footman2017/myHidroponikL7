<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengaliran;
use App\PembacaanSensor;
use App\Kondisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   public function index()
   {
      $user = Auth::user();
      if(Pengaliran::where([['email', $user->email], ['status', 1]])->exists()) {
          $data = Pengaliran::firstWhere([
            ['email', $user->email],
            ['status', 1]
         ]);

         // $pengaliran = Pengaliran::where('email', $user->email)->limit(5)->get();
         
         $pengaliran = DB::select("
            select *
            from pengaliran
            where lower(nama_tanaman) like lower('%'||:searchTerm||'%')
            order by tanggal_tanam desc
         ", ['searchTerm' => $data->nama_tanaman]);
         // foreach($pengaliran as $row){
         //    $dataPengaliran[] = (array)$row;   
         // }
         // print_r($pengaliran->toArray());die;
      }else{
         $data = 0;
         $pengaliran = 0;
      }
         
      
      $data_ppm = PembacaanSensor::join('pengaliran', 'pembacaan_sensor.id_pengaliran', '=', 'pengaliran.id_pengaliran')
         ->select('ppm1', 'ppm2', 'waktu')
         ->where([
            ['email', $user->email],
            ['status', 1],
         ])
         ->orderBy('pembacaan_sensor.waktu', 'desc')
         ->take(10)
      ->get();
      
      $data_serapan = DB::select('
         select waktu as tanggal, (ppm1 - ppm2) as selisih
         from pembacaan_sensor
         left join pengaliran on pengaliran.id_pengaliran = pembacaan_sensor.id_pengaliran
         where email = :emailUser and status = 1
         order by tanggal desc
         limit 10
      ', ['emailUser' => $user->email]);


      return view('home[ver2]', 
      [
         'data' => $data,
         // 'dataPengaliran' => $dataPengaliran,
         'dataPengaliran' => $pengaliran,
         'dataPPM' => $data_ppm,
         'dataSerapan' => $data_serapan
      ]);
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
   public function akhiriPengaliran($id){
      $pengaliran = Pengaliran::find($id);
      return view('pengaliran.update', ['pengaliran' => $pengaliran]);
   }

   public function update(Request $request){
      $request->validate([
         'imageFile' => 'required',
         'imageFile.*' => 'mimes:jpg,png,jpeg,gif'
      ]);
 
      if($request->hasfile('imageFile')) {
         foreach ($request->file('imageFile') as $file) {
            $fileName = time().'_'.$file->getClientOriginalName();
            $file->move(public_path().'/files/', $fileName);
            
            $fileModel = new Kondisi;
            $fileModel->id = uniqid();
            $fileModel->id_pengaliran = $request->get('id_pengaliran');
            $fileModel->nama_foto = $fileName;
            // $fileModel->image_path = $filePath;
            $fileModel->save();
         }

         $pengaliran = Pengaliran::find($request->get('id_pengaliran'));
         $pengaliran->keterangan = $request->get('keterangan');
         $pengaliran->tanggal_berakhir = date("d-m-Y");
         $pengaliran->status = 0;
         $pengaliran->save();

         return redirect()->route('home')->with('success','Pengaliran Berhasil Dihentikan');

      }
   }

}
