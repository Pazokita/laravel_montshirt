<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    //recuperer les tailles
    public function tailles(){
        return $this->hasMany('App\Taille');
    }
}
