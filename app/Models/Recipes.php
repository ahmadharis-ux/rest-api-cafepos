<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function ris(){
        return $this->hasMany(RecipeIngridientStock::class,'recipe_id');
    }
    function ingredient_stock(){
        return $this->hasMany(IngredientStock::class,'ingredient_stock_id');
    }
}
