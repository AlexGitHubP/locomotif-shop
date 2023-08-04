<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;

use Locomotif\Admin\Models\Users;
use Locomotif\Shop\Models\OrdersTracking;
use Locomotif\Shop\Models\Carriers;
use Locomotif\Shop\Models\TransactionsProvider;


class Orders extends Model{
    
    public function user(){
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }
    public function trackingHistory(){
        return $this->hasMany(OrdersTracking::class, 'order_id', 'id');
    }

    public function currentStatus(){
        return $this->hasOne(OrdersTracking::class, 'order_id', 'id')->latest();
    }

    public function carrier(){
        return $this->belongsTo(Carriers::class, 'carrier_id', 'id');
    }

    public function transactionProvider(){
        return $this->belongsTo(TransactionsProvider::class, 'paymethod_id', 'id');
    }

}
