<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;


class OrdersTracking extends Model{
    protected $table = 'orders_tracking';
    
    public function order(){
        return $this->belongsTo(Orders::class, 'order_id');
    }

}
