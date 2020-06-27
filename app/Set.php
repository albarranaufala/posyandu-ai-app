<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $fillable = ['name', 'range', 'curve', 'code', 'variable_id'];

    public function variable(){
        return $this->belongsTo('App\Variable');
    }

    public function input_rules(){
        return $this->belongsToMany('App\Rule', 'rule_set');
    }
    public function output_rules(){
        return $this->hasMany('App\Rule', 'output_set_id');
    }
}
