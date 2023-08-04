<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Transactions;
use Locomotif\Shop\Models\Orders;

class TransactionsProvider extends Model{
    
    protected $table = 'transactions_providers';

    public function transactions(){
        return $this->hasMany(Transactions::class, 'provider_id');
    }
    
    public function order(){
        return $this->belongsTo(Orders::class);
    }

}
