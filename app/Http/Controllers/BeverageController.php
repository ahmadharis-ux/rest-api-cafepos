<?php

namespace App\Http\Controllers;

use App\Models\Beverage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeverageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Beverages = Beverage::count();
        if($Beverages == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return  Beverage::with('beverageCategory')->get();
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
            'beverage_category_id' => 'required',
            'recipe_id' => 'required',
            'price' => 'required|numeric',
            'netto' => 'nullable|numeric',
        ]);
        Beverage::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Beverage $beverage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beverage $beverage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
		$beverage = Beverage::find($id); 

		$request->validate([
			'name' => 'required',
			'beverage_category_id' => 'required',
			'recipe_id' => 'required',
			'price' => 'required|numeric',
			'netto' => 'nullable|numeric',
		]);

		$beverage->name = $request->name; 
		$beverage->beverage_category_id = $request->beverage_category_id;
		$beverage->recipe_id = $request->recipe_id;
		$beverage->price = $request->price;
		$beverage->netto = $request->netto;
		$beverage->save();

		return $beverage;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beverage $beverage)
    {
        //
    }
}
