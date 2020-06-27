<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = ['output_set_id'];

    public function output_set(){
        return $this->belongsTo('App\Set', 'output_set_id');
    }

    public function input_sets(){
        return $this->belongsToMany('App\Set', 'rule_set');
    }
}
