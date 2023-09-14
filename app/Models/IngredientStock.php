<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientStock extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function ingredient(){
        return $this->belongsTo(Ingredient::class,'ingredients_id');
    }
}
