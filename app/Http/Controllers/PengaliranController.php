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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $thereIsPengaliran = 0;
      $pengaliran = Pengaliran::all();
      
      foreach ($pengaliran as $row) {  
         if ($row->status == 1) {      //pengecekan apakan ada pengaliran yang aktif
            $thereIsPengaliran = 1;
            break;
         }
      }

      // print_r($thereIsPengaliran);die;

      return view('pengaliran.index', ['pengaliran' => $pengaliran, 'thereIsPengaliran' => $thereIsPengaliran]);
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
         'tanggal_berakhir'=>'required',
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
         'tanggal_berakhir' => $request->get('tanggal_berakhir'),
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
    public function show(Pengaliran $pengaliran)
    {
      $totalSerapan = DB::select('
         select sum(ppm1) - sum(ppm2) as total_serapan
         from pembacaan_sensor
         where id_pengaliran = :id
      ', ['id' => $pengaliran->id_pengaliran]);

      $foto = Kondisi::where('id_pengaliran', $pengaliran->id_pengaliran)->get();
      // foreach ($foto as $data) {
      //    print_r($data->image_path);
      // }
      // die;
      // print_r($foto);die;
      return view('pengaliran.detail', [
         'pengaliran' => $pengaliran, 
         'totalSerapan' => $totalSerapan, 
         'foto' => $foto
      ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pengaliran  $pengaliran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pengaliran $pengaliran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pengaliran  $pengaliran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pengaliran $pengaliran)
    {
        //
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
      
      foreach ($dataFoto as $item) {
         if(file_exists($item->image_path)) unlink($item->image_path);
         $item->delete();
      }

      Pengaliran::destroy($id);
      return redirect()->route('pengaliran.index')->with('success','pengaliran deleted successfully');
    }
}
