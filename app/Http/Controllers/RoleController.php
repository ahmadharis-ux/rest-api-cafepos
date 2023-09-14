<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'DataRoles' => Role::all()
        ]);
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
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }
        Role::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Role berhasil di tambahkan'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json([
            'role' => Role::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors(),
            ]);
        }
        Role::findOrFail($id)->update([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Role berhasil di update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Role::destroy($id);
        return response()->json([
            'message' => 'Role Berhasil Di Hapus'
        ]);
    }

    function getUserRole($id){
        $role =  Role::findOrFail($id);
        $users = User::where('role_id', $id)->get();
        $data = [$role, $users];
        return response()->json([
            'UserRole' => $data
        ]);  
    }
}
