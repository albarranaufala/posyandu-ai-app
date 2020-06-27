<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['name', 'range', 'curve', 'code', 'variable_id'];

    public function variable(){
        return $this->belongsTo('App\Variable');
    }
}
