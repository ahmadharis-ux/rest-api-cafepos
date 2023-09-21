<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    function beverage(){
        return $this->belongsTo(Beverage::class, 'beverage_id');
    }
}
