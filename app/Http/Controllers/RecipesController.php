<?php

namespace App\Http\Controllers;

use App\Models\IngredientStock;
use App\Models\RecipeIngridientStock;
use App\Models\Recipes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(Recipes::with('ris.ingredient_stock.ingredient')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'isAvailable' => 'required',
        ]);
        Recipes::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipes $recipes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipes $recipes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'isAvailable' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        Recipes::findOrFail($id)->update([
            'name' => $request->name,
            'isAvailable' => $request->isAvailable,
        ]);
        return response()->json([
            'message' => 'Update Recipes berhasil'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipes $recipes)
    {
        //
    }


    public function search($name) {
        return Recipes::where('name', 'like', '%'.$name.'%')->get();
    }

    public function recipeSekalianIngredient(Request $request){
        $validatedData = $request->validate([
            'recipe.name' => 'required',
            'recipe.isAvailable' => 'required',
            'ris.*.ingredient_stock_id' => 'required|integer',
            'ris.*.amount' => 'required|integer',
        ]);
        $recipe = Recipes::create([
            'name' => $validatedData['recipe']['name'],
            'isAvailable' => $validatedData['recipe']['isAvailable'],
        ]);
        $recipe_id  = $recipe->id;
        $recipeIngredientStock = [];
        foreach($validatedData['ris'] as $data){
            $recipeIngredientStock[] = [
                'recipe_id' => $recipe_id,
                'ingredient_stock_id' => $data['ingredient_stock_id'],
                'amount' => $data['amount'],
            ];
        }

        RecipeIngridientStock::insert($recipeIngredientStock);
        return response($recipeIngredientStock);
    }
}
