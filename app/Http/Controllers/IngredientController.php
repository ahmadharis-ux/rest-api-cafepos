<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Ingredient = Ingredient::count();
        if($Ingredient == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return response()->json([
                'AllDataIngredient' => Ingredient::all()
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
            'name' => 'required',
        ]);
        Ingredient::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingredient $ingredient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
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
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        Ingredient::findOrFail($id)->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Update Ingredient berhasil'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
