<?php

namespace App\Http\Controllers;

use App\Check;
use App\Baby;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $babies = Baby::all();
        return view('checks.index', compact('babies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nama_anak' => 'required',
            'berat_badan' => 'required',
            'tinggi_badan' => 'required'
        ]);
        
        $id = $request->nama_anak;
        $baby = Baby::find($id);
        $tanggalLahir = Carbon::createFromFormat('Y-m-d', $baby->baby_birthday);
        $tanggalSekarang = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString());
        $umur = $tanggalSekarang->diffInMonths($tanggalLahir);
        $beratBadan = $request->berat_badan;
        $tinggiBadan = $request->tinggi_badan;

        if($umur >=0 and $umur <=6){
            $miu_umur_fase1 = 1;
        }
        if($umur >= 6 and $umur <=12){
            $miu_umur_fase1 = (12-$umur)/6;
            $miu_umur_fase2 = ($umur-6)/6;
        }
        if($umur >= 12 and $umur <= 24){
            $miu_umur_fase2 = (24-$umur)/12;
            $miu_umur_fase3 = ($umur-12)/12;
        }
        if($umur >= 24 and $umur <= 36){
            $miu_umur_fase3 = (36-$umur)/12;
            $miu_umur_fase4 = ($umur-24)/12;
        }
        if($umur >= 38 and $umur <= 48){
            $miu_umur_fase4 = (48-$umur)/12;
            $miu_umur_fase5 = ($umur-36)/12;
        }
        if($umur >= 48){
            $miu_umur_fase5 = 1;
        }

        if($baby->gender == 'L'){
            if($beratBadan >= 0 and $beratBadan <=7){
                $miu_bb_ringan = 1;
            }
            if($beratBadan >= 7 and $beratBadan <=13){
                $miu_bb_ringan = (13-$beratBadan)/6;
                $miu_bb_sedang = ($beratBadan-7)/6;
            }
            if($beratBadan >= 13 and $beratBadan <= 19){
                $miu_bb_sedang = (19-$beratBadan)/6;
                $miu_bb_berat = ($beratBadan-13)/6;
            }
            if($beratBadan >= 19){
                $miu_bb_berat = 1;
            }

            if($tinggiBadan >= 0 and $tinggiBadan <=49){
                $miu_tb_rendah = 1;
            }
            if($tinggiBadan >=49 and $tinggiBadan <=75){
                $miu_tb_rendah = (75-$tinggiBadan)/26;
                $miu_tb_sedang = ($tinggiBadan-49)/26;
            }
            if($tinggiBadan >=75 and $tinggiBadan <=101){
                $miu_tb_sedang = (101-$tinggiBadan)/26;
                $miu_tb_tinggi = ($tinggiBadan-75)/26;
            }
            if($tinggiBadan >= 101){
                $miu_tb_tinggi = 1;
            }

            $alpha = array();
            $z = array();
            //Aturan fase I
            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 13) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase II
            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 13) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase III
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 
            
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 13 ) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }
            
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 13 ) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase IV
            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 13 ) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 13 ) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 13 ) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            //Aturan Fase V
            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            }
            
            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=13) and ($tinggiBadan >= 75)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=19) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 13) and ($tinggiBadan >= 0 and $tinggiBadan <=75)){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 13 ) and ($tinggiBadan >= 49 and $tinggiBadan <=101)){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 13 ) and ($tinggiBadan >= 75 )){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            $size = count($alpha);
            $total =0;
            $pembagi =0;
            for($i=0; $i<$size; $i++){
                $total += $alpha[$i]*$z[$i];
                $pembagi += $alpha[$i];
            }
            $nilaiGizi = $total/$pembagi;
        }    
        else if($baby->gender == 'P'){
            if($beratBadan > 0 and $beratBadan <=7){
                $miu_bb_ringan = 1;
            }
            if($beratBadan > 7 and $beratBadan <=12){
                $miu_bb_ringan = (12-$beratBadan)/5;
                $miu_bb_sedang = ($beratBadan-7)/5;
            }
            if($beratBadan > 12 and $beratBadan <= 18){
                $miu_bb_sedang = (18-$beratBadan)/6;
                $miu_bb_berat = ($beratBadan-12)/6;
            }
            if($beratBadan > 18){
                $miu_bb_berat = 1;
            }

            if($tinggiBadan > 0 and $tinggiBadan <=48){
                $miu_tb_rendah = 1;
            }
            if($tinggiBadan >48 and $tinggiBadan <=74){
                $miu_tb_rendah = (75-$tinggiBadan)/26;
                $miu_tb_sedang = ($tinggiBadan-49)/26;
            }
            if($tinggiBadan >74 and $tinggiBadan <=100){
                $miu_tb_sedang = (101-$tinggiBadan)/26;
                $miu_tb_tinggi = ($tinggiBadan-75)/26;
            }
            if($tinggiBadan > 100){
                $miu_tb_tinggi = 1;
            }

            $alpha = array();
            $z = array();
            //Aturan fase I
            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase1, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase1, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 0 and $umur <=12) and ($beratBadan >= 12) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase1, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase II
            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase2, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase2, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 12 ) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 12 ) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 6 and $umur <=24) and ($beratBadan >= 12 ) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase2, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase III
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase3, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 
            
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase3, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 12 ) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }
            
            if(($umur >= 12 and $umur <=36) and ($beratBadan >= 12) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase3, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (12*$minimum)+70;
                array_push($z, $hitung);
            }

            //Aturan Fase IV
            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase4, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase4, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            } 

            if(($umur >= 24 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase4, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            } 

            //Aturan Fase V
            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            }
            
            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 0 and $beratBadan <=12) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase5, $miu_bb_ringan, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = 49-(6*$minimum);
                array_push($z, $hitung);
            } 

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 7 and $beratBadan <=18) and ($tinggiBadan >= 74 )){
                $minimum = min($miu_umur_fase5, $miu_bb_sedang, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (6*$minimum)+43;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 0 and $tinggiBadan <=74)){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_rendah);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 48 and $tinggiBadan <=100)){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_sedang);
                array_push($alpha, $minimum);
                $hitung = (29*$minimum)+53;
                array_push($z, $hitung);
            }

            if(($umur >= 36 and $umur <=48) and ($beratBadan >= 12 ) and ($tinggiBadan >= 74)){
                $minimum = min($miu_umur_fase5, $miu_bb_berat, $miu_tb_tinggi);
                array_push($alpha, $minimum);
                $hitung = (5*$minimum)+48;
                array_push($z, $hitung);
            }
            $size = count($alpha);
            $total =0;
            $pembagi =0;
            for($i=0; $i<$size; $i++){
                $total += $alpha[$i]*$z[$i];
                $pembagi += $alpha[$i];
            }

            $nilaiGizi = $total/$pembagi;
        }

        if($nilaiGizi > 0){
            if($nilaiGizi < 45.5 ){
                $statusGizi = 'Gizi Buruk';
            } else if($nilaiGizi < 50.5){
                $statusGizi = 'Gizi Kurang';
            } else if ($nilaiGizi < 61.5){
                $statusGizi = 'Gizi Normal';
            } else if($nilaiGizi <76.5){
                $statusGizi = 'Gizi Lebih';
            } else{
                $statusGizi = 'Obesitas';
            }
        }

        $check = new Check();
        $check->body_weight = $request->berat_badan;
        $check->body_height = $request->tinggi_badan;
        $check->nutritional_value = $nilaiGizi;
        $check->nutritional_status = $statusGizi;
        $check->age = $umur;
        $check->baby_id = $baby->id;
        $check->user_id = Auth::user()->id;
        $check->save();
        
        $check = Check::with('baby')->find($check->id);
        return response()->json([
            'status' => 200,
            'data' => [
                'checkResult' => $check,
                'bodyWeight' => $beratBadan,
                'bodyHeight' => $tinggiBadan,
                'ageMonth' => $umur,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function show(Check $check)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function edit(Check $check)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Check $check)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Check  $check
     * @return \Illuminate\Http\Response
     */
    public function destroy(Check $check)
    {
        //
    }
}
