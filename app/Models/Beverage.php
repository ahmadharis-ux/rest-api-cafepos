<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beverage extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    function recipe()
	{
		return  $this->belongsTo(Recipes::class, 'recipe_id');
	}

	function beverageCategory()
	{
		return  $this->belongsTo(BeveragesCategory::class, 'beverage_category_id');
	}
}
