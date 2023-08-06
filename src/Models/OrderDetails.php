<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;


class OrderDetails extends Model{
    
    protected $table = 'orders_details';

    public function order(){
        return $this->belongsTo(Orders::class);
    }
}
