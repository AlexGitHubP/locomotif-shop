<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\TransactionsProvider;

class Transactions extends Model{
    
    public function provider(){
        return $this->belongsTo(TransactionsProvider::class, 'provider_id');
    }
}
