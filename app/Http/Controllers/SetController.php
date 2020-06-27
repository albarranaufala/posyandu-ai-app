<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Set;
use App\Variable;

class SetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $sets = Set::all();
        $variables = Variable::all();
        return view('sets.index', [
            "sets" => $sets,
            "variables" => $variables
        ]);
    }

    public function store(Request $request){
        $set = new Set();
        $set->name = $request->nama_himpunan;
        $set->range = $request->range;
        $set->curve = $request->kurva;
        $set->code = $request->kode_himpunan;
        $set->variable_id = $request->variabel;
        $set->save();

        return redirect()->back()->with('status', 'Menambah himpunan berhasil!');
    }

    public function update($id, Request $request){
        $set = Set::find($id);
        $set->name = $request->nama_himpunan;
        $set->range = $request->range;
        $set->curve = $request->kurva;
        $set->code = $request->kode_himpunan;
        $set->variable_id = $request->variabel;
        $set->save();

        return redirect()->back()->with('status', 'Mengubah himpunan berhasil!');
    }

    public function destroy($id){
        $set = Set::find($id);
        if(count($set->input_rules) != 0 || count($set->output_rules) != 0) {
            return redirect()->back()->with('status', 'Gagal menghapus himpunan! Himpunan masih memiliki aturan yang terkait!');
        }
        $set->delete();
        return redirect()->back()->with('status', 'Berhasil menghapus himpunan!');
    }
}
