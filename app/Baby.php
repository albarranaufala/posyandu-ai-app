<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baby extends Model
{
    protected $table = 'babies';
    protected $fillable = ['baby_name', 'unique_code', 'baby_birthday', 'mother_name', 'address', 'gender', 'contact'];

    public function checks(){
        return $this->hasMany('App\Check', 'baby_id');
    }
}
