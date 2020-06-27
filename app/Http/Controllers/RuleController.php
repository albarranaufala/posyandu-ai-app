<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rule;
use App\Variable;
use App\Set;

class RuleController extends Controller
{
    public function index(){
        $rules = Rule::with('output_set')->with('input_sets')->get();
        $variables = Variable::with('sets')->get();
        return view('rules.index', 
            [
                'rules' => $rules,
                'variables' => $variables
            ]);
    }

    public function update($id, Request $request){
        $rule = Rule::find($id);
        $rule->input_sets()->sync([$request->values[0], $request->values[1], $request->values[2]]);
        $rule->update([
            'output_set_id' => $request->values[3]
        ]);

        return response()->json([
            'status' => 200,
            'data' => [
                'rules' => Rule::with('output_set')->with('input_sets')->get(),
            ]
        ]);
    }
    
    public function store(Request $request){
        $rule = new Rule();
        $rule->code = $request->kode;
        $rule->output_set_id = $request->nilai_gizi;
        $rule->save();
        $rule->input_sets()->sync([$request->umur, $request->berat_badan, $request->tinggi_badan]);

        return response()->json([
            'status' => 200,
            'data' => [
                'rules' => Rule::with('output_set')->with('input_sets')->get(),
            ]
        ]);
    }

    public function destroy($id){
        $rule = Rule::find($id);
        $rule->input_sets()->detach();
        $rule->delete();

        return response()->json([
            'status' => 200,
            'data' => [
                'rules' => Rule::with('output_set')->with('input_sets')->get(),
            ]
        ]);
    }
}
