<?php

namespace App\Http\Controllers;

use App\Tanaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TanamanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $plans = Tanaman::all();
      return view('tanaman.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tanaman.create');
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
      ]);
      
      $tanaman = new Tanaman([
         'id_tanaman' => uniqid(),
         'nama_tanaman' => $request->get('nama_tanaman'),
         'tanggal_tanam' => date('d-m-yy'),
         'keterangan' => $request->get('keterangan'),
         'min_ppm' => $request->get('min_ppm'),
         'max_ppm' => $request->get('max_ppm'),
         'email' => Auth::user()->email,
         'status' => 0
      ]);

      $tanaman->save();
      
      return redirect('/plans')->with('success', 'Tanaman saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tanaman  $tanaman
     * @return \Illuminate\Http\Response
     */
    public function show(Tanaman $tanaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tanaman  $tanaman
     * @return \Illuminate\Http\Response
     */
    public function edit(Tanaman $tanaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tanaman  $tanaman
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tanaman $tanaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tanaman  $tanaman
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
      // echo $tanaman;die;
      $tanaman = Tanaman::find($id);
      $tanaman->delete();
      return redirect()->route('plans.index')->with('success','Tanaman deleted successfully');
    }
}
