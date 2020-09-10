<?php

namespace App\Http\Controllers;

use App\Pengaliran;
use App\PembacaanSensor;
use App\Kondisi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengaliranController extends Controller
{
      public function __construct()
      {
         $this->middleware('auth');
      }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $user = Auth::user();
      // $thereIsPengaliran = 0;
      // $pengaliran = Pengaliran::where('email', $user->email)->get();
      
      if(Pengaliran::where([
         ['status', 1],
         ['email', $user->email]
      ])->exists()) {
         $thereIsPengaliran = 1;
      }else $thereIsPengaliran = 0;
      
      $pengaliran = datatables(Pengaliran::where('email', $user->email)->get())->toJson();

      // return view('pengaliran.index', ['pengaliran' => $pengaliran, 'thereIsPengaliran' => $thereIsPengaliran]);
      return view('pengaliran.indexVer2', [
         'thereIsPengaliran' => $thereIsPengaliran,
         'listPengaliran' => $pengaliran
      ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pengaliran.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
         'nama_tanaman'=>'required',
         'min_ppm'=>'required',
         'max_ppm'=>'required',
         // 'tanggal_berakhir'=>'required',
      ]);
      
      $pengaliran = new Pengaliran([
         'id_pengaliran' => uniqid(),
         'nama_tanaman' => $request->get('nama_tanaman'),
         'tanggal_tanam' => date('d-m-yy'),
         'deskripsi' => $request->get('deskripsi'),
         'min_ppm' => $request->get('min_ppm'),
         'max_ppm' => $request->get('max_ppm'),
         'email' => Auth::user()->email,
         'status' => 1,
         // 'tanggal_berakhir' => $request->get('tanggal_berakhir'),
      ]);

      $pengaliran->save();
      
      return redirect('/pengaliran')->with('success', 'Pengaliran saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pengaliran  $pengaliran
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $pengaliran = Pengaliran::find($id);
      $totalSerapan = DB::select('
         select (sum(ppm1) - sum(ppm2))/count(*) as total_serapan
         from pembacaan_sensor
         where id_pengaliran = :id
      ', ['id' => $id]);

      $foto = Kondisi::where('id_pengaliran', $id)->get();

      $serapanPPM = DB::select('
         select date(waktu) as tanggal, (sum(ppm1) - sum(ppm2))/count(*) as selisih, sum(ppm1)/count(*) as "ppm1", sum(ppm2)/count(*) as "ppm2"
         from pembacaan_sensor
         where id_pengaliran = :id
         group by tanggal
         order by tanggal asc
      ', ['id' => $id]);
      
      return view('pengaliran.detail[ver2]', [
         'pengaliran' => $pengaliran, 
         'totalSerapan' => $totalSerapan, 
         'foto' => $foto,
         'serapanPPM' => $serapanPPM
      ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengaliran  $pengaliran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
      $request->validate([
         'nama_tanaman'=>'required',
         'imageFile.*' => 'mimes:jpg,png,jpeg,gif'
      ]);
      
      $pengaliran = Pengaliran::find($request->get('id_pengaliran'));
      $pengaliran->nama_tanaman = $request->get('nama_tanaman');
      $pengaliran->deskripsi = $request->get('deskripsi');
      $pengaliran->keterangan = $request->get('keterangan');
      $pengaliran->save();

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
      }
      return redirect('/pengaliran')->with('success', 'Pengaliran saved!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pengaliran  $pengaliran
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
      $dataSensor = PembacaanSensor::where('id_pengaliran', $id);
      $dataSensor->delete();

      $dataFoto = Kondisi::where('id_pengaliran', $id)->get();
      $path = public_path().'/files/';
      // print_r($path);
      
      foreach ($dataFoto as $item) {
         // if(file_exists($item->image_path)) unlink($item->image_path);
         if(file_exists($path.$item->nama_foto)) unlink($path.$item->nama_foto);
         $item->delete();
      }

      Pengaliran::destroy($id);
      return redirect()->route('pengaliran.index')->with('success','pengaliran deleted successfully');
    }

    public function getAllPengaliran(){
      $user = Auth::user();
      return datatables(Pengaliran::where('email', $user->email)->get())->toJson();
   }
}
