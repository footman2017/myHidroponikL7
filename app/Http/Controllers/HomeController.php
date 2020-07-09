<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengaliran;
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
      // $data = Pengaliran::where([
      //    ['email', $user->email],
      //    ['status', 1]
      // ])->get();

      if(Pengaliran::where([
         ['email', $user->email],
         ['status', 1]
      ])->exists()) {
          $data = Pengaliran::where([
            ['email', $user->email],
            ['status', 1]
         ])->get();
      }else $data = 0;
      // print_r($data);die;

      return view('home', compact('data'));
   }
}
