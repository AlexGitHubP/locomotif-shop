<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\TransactionsProvider;
use Locomotif\Shop\Models\Orders;


class Transactions extends Model{
    
    protected $table = 'transactions';

    public function provider(){
        return $this->belongsTo(TransactionsProvider::class, 'provider_id');
    }

    public function order(){
        return $this->belongsTo(Orders::class, 'id', 'order_id');
    }
}
