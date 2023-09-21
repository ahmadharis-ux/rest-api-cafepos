<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeveragesCategory extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function beverage(){
        return $this->hasMany(Beverage::class, 'beverage_category_id');
    }
}
