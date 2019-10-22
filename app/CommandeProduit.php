<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\Pivot;

class CommandeProduit extends Pivot
{
    //récuperer la taille du produit commandé
    public function taille(){
        return $this->belongsTo('App\Taille');
    }
}
