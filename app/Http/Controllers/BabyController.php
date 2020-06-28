<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Baby;

class BabyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('babies.index');
    }

    public function getAllBabies(){
        $babies = Baby::with('checks')->orderBy('baby_name')->get();

        return response()->json([
            'status' => 200,
            'data' => [
                'babies' => $babies
            ]
        ]);
    }

    public function store(Request $request){

        $this->validate($request,[
            'nik_anak' => 'required',
            'nama_anak' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'nama_ibu' => 'required',
            'kontak' => 'required',
            'alamat' => 'required'
        ]);

        Baby::create([
            'baby_name'=> $request->nama_anak,
            'mother_name' => $request->nama_ibu,
            'baby_birthday' => $request->tanggal_lahir,
            'address' => $request->alamat,
            'gender' => $request->jenis_kelamin,
            'contact' => $request->kontak,
            'unique_code' => $request->nik_anak
        ]);
        $babies = Baby::with('checks')->orderBy('baby_name')->get();
        return response()->json([
            'status' => 200,
            'data' => [
                'babies' => $babies
            ]
        ]);
    }
    
}
