<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function ingredient_stock(){
        return $this->hasMany(IngredientStock::class, 'ingredients_id');
    }
}
