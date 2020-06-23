<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Baby;

class BabySearchController extends Controller
{
    public function search($code){
        $baby = Baby::where('unique_code', $code)->with('checks')->first();
        return response()->json([
            'status' => 200,
            'data' => [
                'baby' => $baby
            ]
        ]);
    }
}
