<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BabyController extends Controller
{
    public function index(){
        return view('babies.index');
    }

    public function store(Request $request){
        $huruf = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 
        'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $uniqueCode = '';
        for($i=0; $i<10; $i++){
            $angka = rand(0, 25);
            $uniqueCode += $huruf[$angka];
        }

        $this->validate($request,[
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
            'unique_code' => $uniqueCode
        ]);
    }
}
