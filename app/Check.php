<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $fillable = ['body_height', 'body_height', 'nutritional_value', 'user_id', 'baby_id', 'nutritional_status', 'age'];

    public function baby(){
        return $this->belongsTo('App\Baby', 'baby_id');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
    
}
