<?php

namespace App\Http\Controllers;

use App\Pengaliran;
use App\PembacaanSensor;
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
      $pengaliran = Pengaliran::all();
      return view('pengaliran.index', compact('pengaliran'));
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
         'keterangan' => $request->get('keterangan'),
         'min_ppm' => $request->get('min_ppm'),
         'max_ppm' => $request->get('max_ppm'),
         'email' => Auth::user()->email,
         'status' => 1,
         'tanggal_berakhir' => $request->get('tanggal_berakhir')
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
        //
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
      // echo $pengaliran;die;
      $dataSensor = PembacaanSensor::where('id_pengaliran', $id);
      $dataSensor->delete();
      Pengaliran::destroy($id);
      // $pengaliran->delete();
      return redirect()->route('pengaliran.index')->with('success','pengaliran deleted successfully');
    }
}
