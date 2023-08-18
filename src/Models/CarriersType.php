<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;

class CarriersType extends Model{
    protected $table = 'carriers_type';

    public function order(){
        return $this->belongsTo(Orders::class);
    }
}
