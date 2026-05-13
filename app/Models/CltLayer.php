<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CltLayer extends Model
{
    
    public function layup() { 
        return $this->belongsTo(CltLayup::class); 
    }

}
