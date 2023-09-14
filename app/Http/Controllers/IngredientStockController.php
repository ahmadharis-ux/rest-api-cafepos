<?php

namespace App\Http\Controllers;

use App\Models\IngredientStock;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class IngredientStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $IngredientStock = IngredientStock::count();
        if($IngredientStock == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return response()->json([
                'AllDataIngredientStock' => IngredientStock::all()
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
            'ingredients_id' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        IngredientStock::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(IngredientStock $ingredientStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IngredientStock $ingredientStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'ingredients_id' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        IngredientStock::findOrFail($id)->update([
            'stock' => $request->stock,
            'ingredients_id' => $request->ingredients_id,
        ]);
        return response()->json([
            'message' => 'Update IngredientStock berhasil'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IngredientStock $ingredientStock)
    {
        //
    }
}
