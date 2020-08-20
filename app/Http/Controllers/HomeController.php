<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengaliran;
use App\Kondisi;
use Illuminate\Support\Facades\Auth;

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
      if(Pengaliran::where([
         ['email', $user->email],
         ['status', 1]
      ])->exists()) {
          $data = Pengaliran::where([
            ['email', $user->email],
            ['status', 1]
         ])->get();
      }else $data = 0;

      $pengaliran = Pengaliran::where('email', $user->email)->get();

      return view('home', 
      [
         'data' => $data,
         'dataPengaliran' => $pengaliran
      ]);
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
            $filePath = $file->move(public_path().'/files/', $fileName);
            
            $fileModel = new Kondisi;
            $fileModel->id = uniqid();
            $fileModel->id_pengaliran = $request->get('id_pengaliran');
            $fileModel->nama_foto = $fileName;
            $fileModel->image_path = $filePath;
            $fileModel->save();
         }

         $pengaliran = Pengaliran::find($request->get('id_pengaliran'));
         $pengaliran->keterangan = $request->get('keterangan');
         $pengaliran->status = 0;
         $pengaliran->save();

         return redirect()->route('home')->with('success','Pengaliran Berhasil Dihentikan');

      }
   }

}
