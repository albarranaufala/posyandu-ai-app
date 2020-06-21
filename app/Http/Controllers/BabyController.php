<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BabyController extends Controller
{
    public function index(){
        return view('babies.index');
    }
}
