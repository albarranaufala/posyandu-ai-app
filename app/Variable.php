<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variable extends Model
{
    public function sets(){
        return $this->hasMany('App\Set');
    }
}
