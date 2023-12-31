<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;

class Invoices extends Model{
    protected $table = 'orders_invoices';

    public function order(){
        return $this->belongsTo(Orders::class);
    }
}
