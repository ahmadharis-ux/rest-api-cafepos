<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|confirmed',
            'role_id' => 'required|integer',
        ]);
        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'role_id' => $fields['role_id'],
        ]);
        $token = $user->createToken('mypptoken')->plainTextToken;
        
        $response = [
            'user' => $user,
            'toke' => $token,
        ];

        return response($response, 201);
    }
    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        //cek email
        $user = User::where('email', $fields['email'])->first();

        //cek password
        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Bad Creds'
            ], 401);
        }

        $token = $user->createToken('mypptoken')->plainTextToken;
        
        $response = [
            'user' => $user,
            'toke' => $token,
        ];

        return response($response, 201);
    }
    function logout(Request $request){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Loged out'
        ]; 
    }
}
