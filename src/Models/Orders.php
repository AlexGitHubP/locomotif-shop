<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model{
    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
