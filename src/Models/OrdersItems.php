<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;

class OrdersItems extends Model{
    
    public function orders(){
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }
}
