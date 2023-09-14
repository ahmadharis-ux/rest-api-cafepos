<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngridientStock extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function recipes(){
        return $this->hasMany(Recipes::class);
    }
    function ing(){
        return $this->hasMany(Ingredient::class);
    }
    function ingredient_stock(){
        return $this->belongsTo(IngredientStock::class);
    }
}
