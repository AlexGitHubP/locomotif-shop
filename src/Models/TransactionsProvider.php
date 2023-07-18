<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Transactons;

class TransactionsProvider extends Model{
    
    protected $table = 'transactions_providers';

    public function transactions(){
        return $this->hasMany(Transactons::class, 'provider_id');
    }

}
