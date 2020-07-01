<?php

namespace App\Http\Controllers;

use App\Check;
use App\Baby;
use App\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CheckController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $babies = Baby::all();
        return view('checks.index', compact('babies'));
    }

    public function store($id, Request $request)
    {   
        //Menyiapkan Data yang Diperlukan untuk Fuzzifikasi
        $baby = Baby::find($id);
        $tanggalLahir = Carbon::createFromFormat('Y-m-d', $baby->baby_birthday);
        // $tanggalSekarang = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString());
        $tanggalSekarang = Carbon::createFromFormat('Y-m-d', '2020-07-13');
        $umur = $tanggalSekarang->diffInMonths($tanggalLahir);

        // //Proses Defuzzifikasi
        $nilaiGizi = $this->hitung([$umur, $request->berat_badan, $request->tinggi_badan], $baby->gender);
        
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

        //Menyimpan Data Pemeriksaan
        $check = new Check();
        $check->body_weight = $request->berat_badan;
        $check->body_height = $request->tinggi_badan;
        $check->nutritional_value = $nilaiGizi;
        $check->nutritional_status = $statusGizi;
        $check->age = $umur;
        $check->baby_id = $baby->id;
        $check->user_id = Auth::user()->id;
        $check->save();
        
        $check = Check::with('baby.checks')->find($check->id);
        return response()->json([
            'status' => 200,
            'data' => [
                'checkResult' => $check,
                'babies' => Baby::with('checks')->orderBy('baby_name')->get()
            ]
        ]);
    }

    //MENGALI ANTAR ELEMEN DI ARRAY
    private function mengaliElemenArray($array1, $array2){
        for($i = 0; $i < count($array1); $i++){
            $arrayBaru[] = $array1[$i] * $array2[$i];
        }
        return $arrayBaru;
    }

    //FUNGSI UMUR
    private function umurFase1($x){
        if($x <= 6){
            return 1;
        } else if($x <= 12){
            return (12-$x)/6;
        } else{
            return 0;
        }
    }
    private function umurFase2($x){
        if($x <= 6 || $x >= 24){
            return 0;
        } else if($x <= 12){
            return ($x-6)/6;
        } else{
            return (24-$x)/12;
        }
    }
    private function umurFase3($x){
        if($x <= 12 || $x >= 36){
            return 0;
        } else if($x <= 24){
            return ($x-12)/12;
        } else{
            return (36-$x)/12;
        }
    }
    private function umurFase4($x){
        if($x <= 24 || $x >= 48){
            return 0;
        } else if($x <= 36){
            return ($x-24)/12;
        } else{
            return (48-$x)/12;
        }
    }
    private function umurFase5($x){
        if($x <= 36){
            return 0;
        } else if($x <= 48){
            return ($x-36)/12;
        } else{
            return 1;
        }
    }

    //FUNGSI BERAT
    private function beratRingan($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 7){
                return 1;
            } else if($x <= 13){
                return (13 - $x)/6;
            } else{
                return 0;
            }
        } else if($kelamin == 'P'){
            if($x <= 7){
                return 1;
            } else if($x <= 12){
                return (12 - $x)/5;
            } else{
                return 0;
            }
        }
    }
    private function beratSedang($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 7 || $x >= 19){
                return 0;
            } else if($x <= 13){
                return ($x - 7)/6;
            } else{
                return (19-$x)/6;
            }
        } else if($kelamin == 'P'){
            if($x <= 7 || $x >= 18){
                return 0;
            } else if($x <= 12){
                return ($x - 7)/5;
            } else{
                return (18-$x)/6;
            }
        }
    }
    private function beratBerat($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 13){
                return 0;
            } else if($x <= 19){
                return ($x - 13)/6;
            } else{
                return 1;
            }
        } else if($kelamin == 'P'){
            if($x <= 12){
                return 0;
            } else if($x <= 12){
                return ($x - 12)/6;
            } else{
                return 1;
            }
        }
    }

    //FUNGSI TINGGI
    private function tinggiRendah($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 49){
                return 1;
            } else if($x <= 75){
                return (75 - $x)/26;
            } else{
                return 0;
            }
        } else if($kelamin == 'P'){
            if($x <= 48){
                return 1;
            } else if($x <= 74){
                return (74 - $x)/26;
            } else{
                return 0;
            }
        }
    }
    private function tinggiSedang($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 49 || $x >= 101){
                return 0;
            } else if($x <= 75){
                return ($x - 49)/26;
            } else{
                return (101-$x)/26;
            }
        } else if($kelamin == 'P'){
            if($x <= 48 || $x >= 100){
                return 0;
            } else if($x <= 74){
                return ($x - 48)/26;
            } else{
                return (100-$x)/26;
            }
        }
    }
    private function tinggiTinggi($x, $kelamin){
        if($kelamin == 'L'){
            if($x <= 75){
                return 0;
            } else if($x <= 101){
                return ($x - 75)/26;
            } else{
                return 1;
            }
        } else if($kelamin == 'P'){
            if($x <= 74){
                return 0;
            } else if($x <= 100){
                return ($x - 74)/26;
            } else{
                return 1;
            }
        }
    }

    //FUNGSI GIZI BURUK
    private function giziBuruk($alfa){
        if($alfa == 1){
            return 43;
        } else if($alfa == 0){
            return 49;
        } else{
            return 49 - (6*$alfa);
        }
    }
    private function giziKurang($alfa){
        if($alfa == 0){
            return 43;
        } else{
            return 43 + (6*$alfa);
        } 
    }
    private function giziNormal($alfa){
        if($alfa == 0){
            return 49;
        } else{
            return 49 + (4*$alfa);
        } 
    }
    private function giziLebih($alfa){
        if($alfa == 0){
            return 53;
        } else{
            return 53 + (29*$alfa);
        } 
    }
    private function giziObesitas($alfa){
        if($alfa == 0){
            return 70;
        } else{
            return 70 + (12*$alfa);
        } 
    }

    //ATURAN
    private function aturan($umur, $beratBadan, $tinggiBadan, $jenisKelamin){
        $alfa = array();
        $z = array();
        //FASE 1
        array_push($alfa, min($this->umurFase1($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[0]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[1]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[2]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[3]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[4]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[5]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[6]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[7]));
        array_push($alfa, min($this->umurFase1($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziObesitas($alfa[8]));

        //FASE 2
        array_push($alfa, min($this->umurFase2($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[9]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[10]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[11]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[12]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[13]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[14]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[15]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[16]));
        array_push($alfa, min($this->umurFase2($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziObesitas($alfa[17]));

        //FASE 3
        array_push($alfa, min($this->umurFase3($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[18]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[19]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[20]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[21]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[22]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[23]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[24]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[25]));
        array_push($alfa, min($this->umurFase3($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziObesitas($alfa[26]));

        //FASE 4
        array_push($alfa, min($this->umurFase4($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[27]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[28]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[29]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[30]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[31]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[32]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[33]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[34]));
        array_push($alfa, min($this->umurFase4($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[35]));

        //FASE 5
        array_push($alfa, min($this->umurFase5($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[36]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[37]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratRingan($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziBuruk($alfa[38]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[39]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[40]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratSedang($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziKurang($alfa[41]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiRendah($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[42]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiSedang($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziLebih($alfa[43]));
        array_push($alfa, min($this->umurFase5($umur), $this->beratBerat($beratBadan, $jenisKelamin), $this->tinggiTinggi($tinggiBadan, $jenisKelamin)));
        array_push($z, $this->giziNormal($alfa[44]));

        return [
            "alfa" => $alfa,
            "z" => $z
        ];
    }

    public function linearTurun($input, $x, $y){
        if($input < $x) return 1;
        else if($input > $y) return 0;
        else return ($y - $input)/($y - $x);
    }

    public function linearNaik($input, $x, $y){
        if($input < $x) return 0;
        else if($input > $y) return 1;
        else return ($input - $x)/($y - $x);
    }

    public function segitiga($input, $x, $y, $z){
        if($input < $x || $input > $z) return 0;
        else if($input < $y) return ($input - $x)/($y - $x);
        else return ($y - $input)/($y - $x);
    }

    public function zLinearTurun($alfa, $x, $y){
        if($alfa == 1) return $x;
        else if($alfa == 0) return $y;
        else return $y - ($alfa * ($y - $x));
    }

    public function zLinearNaik($alfa, $x, $y){
        if($alfa == 1) return $y;
        else if($alfa == 0) return $x;
        else return $x + ($alfa * ($y - $x));
    }

    public function zSegitiga($alfa, $x, $y, $z){
        if($alfa == 1) return $y;
        else if($alfa == 0) return $x;
        else return $x + ($alfa * ($y - $x));
    }

    public function hitung($inputs, $jenisKelamin){
        $rules = Rule::all();
        $alfas = array();
        $zs = array();
        //ulangi setiap atusan
        foreach($rules as $rule){
            //ulangi setiap himpunan output
            $alfasLocal = array();
            $i = 0;
            foreach($rule->input_sets as $inputSet){
                $ranges = explode('|', $inputSet->range);
                //jika rangenya tergantung jenis kelamin
                if(count($ranges) == 2){
                    if($jenisKelamin == 'L'){
                        //jika laki
                        $input = $inputs[$i];
                        $range = explode(',', $ranges[0]);
                        $alfa = $this->cariAlfa($input, $inputSet->curve, $range);
                    } else if($jenisKelamin == 'P'){
                        //jika perempuan
                        $input = $inputs[$i];
                        $range = explode(',', $ranges[1]);
                        $alfa = $this->cariAlfa($input, $inputSet->curve, $range);
                    }
                } else{
                    $input = $inputs[$i];
                    $range = explode(',', $ranges[0]);
                    $alfa = $this->cariAlfa($input, $inputSet->curve, $range);
                }
                array_push($alfasLocal, $alfa);
                $i++;
            }
            $alfaMin = min($alfasLocal);
            array_push($alfas, $alfaMin);
            $outputRanges = explode('|', $rule->output_set->range);
            if(count($outputRanges) == 2){
                if($jenisKelamin == 'L'){
                    $outputRange = explode(',', $outputRanges[0]);
                    $z = $this->cariZ($alfaMin, $rule->output_set->curve, $outputRange);
                } else if($jenisKelamin == 'P'){
                    $outputRange = explode(',', $outputRanges[1]);
                    $z = $this->cariZ($alfaMin, $rule->output_set->curve, $outputRange);
                }
            } else{
                $outputRange = explode(',', $outputRanges[0]);
                $z = $this->cariZ($alfaMin, $rule->output_set->curve, $outputRange);
            }
            array_push($zs, $z);
        }
        $nilaiGizi = array_sum($this->mengaliElemenArray($alfas, $zs))/array_sum($alfas);
        return $nilaiGizi;
    }

    public function cariAlfa($input, $curve, $range){
        if($curve == 'linear turun'){
            return $this->linearTurun($input, $range[0], $range[1]);
        } else if($curve == 'linear naik'){
            return $this->linearNaik($input, $range[0], $range[1]);
        } else if($curve == 'segitiga'){
            return $this->segitiga($input, $range[0], $range[1], $range[2]);
        }
    }

    public function cariZ($alfa, $curve, $range){
        if($curve == 'linear turun'){
            return $this->zLinearTurun($alfa, $range[0], $range[1]);
        } else if($curve == 'linear naik'){
            return $this->zLinearNaik($alfa, $range[0], $range[1]);
        } else if($curve == 'segitiga'){
            return $this->zSegitiga($alfa, $range[0], $range[1], $range[2]);
        }
    }
}
