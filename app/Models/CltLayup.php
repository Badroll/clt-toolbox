<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CltLayup extends Model
{
    
    public function supplier() { 
        return $this->belongsTo(Supplier::class); 
    }

    public function layers() { 
        return $this->hasMany(CltLayer::class); 
    }

}
