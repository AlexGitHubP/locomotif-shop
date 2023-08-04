<?php

namespace Locomotif\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Locomotif\Shop\Models\Orders;

class Carriers extends Model{
    protected $table = 'carriers';

    public function order(){
        return $this->belongsTo(Orders::class);
    }
}
