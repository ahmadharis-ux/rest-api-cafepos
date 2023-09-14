<?php

namespace App\Http\Controllers;

use App\Models\RecipeIngridientStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeIngridientStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $RecipeIngredientStock = RecipeIngridientStock::count();
        if($RecipeIngredientStock == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return response()->json([
                'AllDataRecipeIngredientStock' => RecipeIngridientStock::all()
            ]);
        }
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
            'recipe_id' => 'required|numeric',
            'ingredient_stock_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        RecipeIngridientStock::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipeIngridientStock $recipeIngridientStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RecipeIngridientStock $recipeIngridientStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(),[
            'recipe_id' => 'required|numeric',
            'ingredient_stock_id' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        RecipeIngridientStock::findOrFail($id)->update([
            'recipe_id' => $request->recipe_id,
            'ingredient_stock_id' => $request->ingredients_stock_id,
            'amount' => $request->amount,
        ]);
        return response()->json([
            'message' => 'Update RecipeIngridientStock berhasil'
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipeIngridientStock $recipeIngridientStock)
    {
        //
    }
}
