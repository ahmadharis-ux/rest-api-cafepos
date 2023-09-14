<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    function getAllData(){
        $user = User::count();
        if($user == 0){
            return response()->json([
                'message' => 'Not Found'
            ]);
        }else{
            return response()->json([
                'AllDataUsers' => DB::table('users')->select('name', 'role_id', 'created_at')->get()
            ]);
        }
    }
    function registration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
            'role_id' => 'required|numeric',
        ]);
        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) ,
            'role_id' => $request->role_id,
        ]);
        return response()->json([
            'message' => 'selamat anda berhasil daftar'
        ]);
    }
    //function login
    //function update profile
}
