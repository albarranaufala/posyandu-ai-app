<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Baby extends Model
{
    protected $table = 'babies';

    public function checks(){
        return $this->hasMany('App\Check', 'baby_id');
    }
}
