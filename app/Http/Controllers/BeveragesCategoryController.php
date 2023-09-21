<?php

namespace App\Http\Controllers;

use App\Models\BeveragesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BeveragesCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $BeveragesCategory = BeveragesCategory::count();
        if($BeveragesCategory == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return  BeveragesCategory::all();

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
        BeveragesCategory::create($validatedData);
        return response()->json([
            'message' => 'Berhasil Ditambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BeveragesCategory $beveragesCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BeveragesCategory $beveragesCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        BeveragesCategory::findOrFail($id)->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Update Beverage Category berhasil'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BeveragesCategory $beveragesCategory)
    {
        //
    }
    public function search(Request $request)
    {
        $query = $request->input('name');

        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }


        return BeveragesCategory::with('beverage')->where('name', 'like', '%'.$query.'%')->get();
    }
}
